<?php
require_once __DIR__ . '/../models/members.php';
class MembersController
{
    private $model;
    public function __construct()
    {
        $this->model = new Members();
    }

    public function members()
    {
        // 1. Settings
        $limit = 8; // How many rows per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // 2. Get Data
        $members = $this->model->getPaginated($limit, $offset);
        $membersNameASC = $this->model->sortByNameASC($limit, $offset);
        $membersNameDESC = $this->model->sortByNameDESC($limit, $offset);
        $totalRows = $this->model->countAll();
        $totalPages = ceil($totalRows / $limit);

        // 3. Send to View
        include __DIR__ . '/../views/members.php';
    }

    public function form($id = null)
    {
        $data = $id ? $this->model->find($id) : null;
        $studyBackground = $this->model->getStudyBackground($data['id'] ?? null);
        include __DIR__ . '/../views/members_form.php';
    }
    public function save()
    {
        $id = $_POST['id'] ?? '';

        // 0. Check for "Add Field" (Row Logic) - KEEP THIS AS IS
        if (isset($_POST['add_row'])) {
            $data = [
                'id' => $id,
                'full_name' => $_POST['full_name'] ?? '',
                'role' => $_POST['role'] ?? '',
                // Keep the existing photo if editing, otherwise blank
                'photo' => $id ? ($this->model->find($id)['photo'] ?? '') : '', 
                'expertise' => $_POST['expertise'] ?? '',
                'description' => $_POST['description'] ?? '',
                'linkedin' => $_POST['linkedin'] ?? '',
                'scholar' => $_POST['scholar'] ?? '',
                'researchgate' => $_POST['researchgate'] ?? '',
                'orcid' => $_POST['orcid'] ?? '',
                'status' => $_POST['status'] ?? 'Active'
            ];

            // Get existing backgrounds from the form (so we don't lose typed rows)
            $studyBackground = $_POST['backgrounds'] ?? [];

            // ADD AN EMPTY ROW TO THE LIST
            $studyBackground[] = [
                'id' => 'new',
                'institute' => '',
                'academic_title' => '',
                'year' => '',
                'degree' => ''
            ];

            // Reload the view with the extra row
            include __DIR__ . '/../views/members_form.php';
            return; // STOP here. Do not save to DB yet.
        }

        // 1. Prepare Data (10 Fields)
        $data = [
            $_POST['full_name'] ?? '',
            $_POST['role'] ?? '',
            null, // Placeholder for Photo
            $_POST['expertise'] ?? '',
            $_POST['description'] ?? '',
            $_POST['linkedin'] ?? '',
            $_POST['scholar'] ?? '',
            $_POST['researchgate'] ?? '',
            $_POST['orcid'] ?? '',
            $_POST['status'] ?? 'Active'
        ];

        // 2. LOGIC SPLIT: CREATE vs UPDATE
        if ($id === "") {
            // ========================
            // CASE A: CREATE NEW
            // ========================
            $memberId = $this->model->create($data); // Returns new ID

            // Handle Photo Upload (New Member)
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $photoName = $this->handleFileUpload($memberId, $_FILES['photo']);
                if ($photoName) {
                    $this->model->updatePhoto($memberId, $photoName);
                }
            }

        } else { // <--- THIS ELSE MATCHES "if ($id === "")"
            // ========================
            // CASE B: UPDATE EXISTING
            // ========================
            $memberId = $id;

            // Handle Photo Upload (Existing Member)
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                // Upload New & Overwrite in Data Array
                $data[2] = $this->handleFileUpload($id, $_FILES['photo']);
            } else {
                // Keep Old Photo
                $oldData = $this->model->find($id);
                $data[2] = $oldData['photo'];
            }

            // Update Database
            $this->model->update($id, $data);
        }

        // 3. Save Backgrounds
        if (isset($_POST['backgrounds'])) {
            foreach ($_POST['backgrounds'] as $bgData) {
                $bgFields = [
                    'institute'      => $bgData['institute'],
                    'academic_title' => $bgData['academic_title'],
                    'year'           => $bgData['year'],
                    'degree'         => $bgData['degree']
                ];

                if (!empty($bgData['id']) && is_numeric($bgData['id'])) {
                    $this->model->updateBackground($bgData['id'], $bgFields);
                } else {
                    $this->model->createBackground($memberId, $bgFields);
                }
            }
        }

        header("Location: index.php?action=members_form&id=" . $memberId);
        exit;
    }

    private function handleFileUpload($id, $file)
    {
        // 1. UPDATE TARGET DIRECTORY
        // Points to: frontend/img/profile-photos/
        $targetDir = __DIR__ . '/../../img/profile-photos/';

        // Create folder if missing
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // 2. Delete Existing Files
        // glob() finds all files matching the pattern (e.g. "15.*")
        $existingFiles = glob($targetDir . $id . '.*'); 
        foreach ($existingFiles as $oldFile) {
            if (is_file($oldFile)) {
                unlink($oldFile); // Delete the file
            }
        }

        // 3. Get Extension
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // 4. Create Name (Just the ID + Ext)
        // Example: "15.png"
        $newFileName = $id . '.' . $imageFileType;
        $targetPath = $targetDir . $newFileName;

        // 5. Validate
        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowed)) {
            return null;
        }

        // 6. Move File
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // RETURN ONLY THE FILENAME (e.g., "15.png")
            // The view handles the directory path
            return $newFileName;
        }

        return null;
    }

    public function delete($id)
    {
        // 2. Delete Photos
        // glob() finds all files matching the pattern (e.g. "15.*")
        $targetDir = __DIR__ . '/../../img/profile-photos/';
        $existingFiles = glob($targetDir . $id . '.*'); 
        foreach ($existingFiles as $oldFile) {
            if (is_file($oldFile)) {
                unlink($oldFile); // Delete the file
            }
        }

        $this->model->delete($id);
        // $this->model->resetID();
        header("Location: index.php?action=members");
    }

    public function deleteBackground($id)
    {
        // 1. Call the correct model function name
        // Was: $this->model->delete($id); -> WRONG
        $this->model->deleteBackground($id);

        // 2. Get the member_id from URL so we go back to the correct form
        $memberId = $_GET['member_id'] ?? '';

        // 3. Redirect back to the Edit Form
        header("Location: index.php?action=members_form&id=" . $memberId);
        exit;
    }
}

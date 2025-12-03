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

        // 1. Prepare Data (10 Fields)
        $data = [
            $_POST['full_name'] ?? '',
            $_POST['role'] ?? '',
            null, // Photo logic goes here later
            $_POST['expertise'] ?? '',
            $_POST['description'] ?? '',
            $_POST['linkedin'] ?? '',
            $_POST['scholar'] ?? '',
            $_POST['researchgate'] ?? '',
            $_POST['orcid'] ?? '',
            $_POST['status'] ?? 'Active'
        ];

        // 2. Save Main Data & Get ID
        if ($id === "") {
            // Create returns the NEW ID
            $memberId = $this->model->create($data);
        } else {
            $this->model->update($id, $data);
            $memberId = $id;
        }

        // Check if the user just clicked "Add Field"
        if (isset($_POST['add_row'])) {
            // Get the current data from the form so we don't lose what they typed
            $data = [
                'id' => $_POST['id'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'role' => $_POST['role'] ?? '',
                'expertise' => $_POST['expertise'] ?? '',
                'description' => $_POST['description'] ?? '',
                'linkedin' => $_POST['linkedin'] ?? '',
                'scholar' => $_POST['scholar'] ?? '',
                'researchgate' => $_POST['researchgate'] ?? '',
                'orcid' => $_POST['orcid'] ?? '',
                'status' => $_POST['status'] ?? 'Active'
            ];

            // Get existing backgrounds from POST
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


        // 3. Save Backgrounds
        if (isset($_POST['backgrounds'])) {
            foreach ($_POST['backgrounds'] as $bgData) {

                $bgFields = [
                    'institute'      => $bgData['institute'],
                    'academic_title' => $bgData['academic_title'],
                    'year'           => $bgData['year'],
                    'degree'         => $bgData['degree']
                ];

                // CHECK: Is the ID numeric? (Existing) OR is it 'new'? (Insert)
                if (!empty($bgData['id']) && is_numeric($bgData['id'])) {
                    // UPDATE Existing Row
                    $this->model->updateBackground($bgData['id'], $bgFields);
                } else {
                    // INSERT New Row
                    // Crucial: $memberId comes from the logic above (either existing ID or new ID)
                    $this->model->createBackground($memberId, $bgFields);
                }
            }
        }

        header("Location: index.php?action=members_form&id=" . $memberId);
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        $this->model->deleteBackgroundFromMember($id);
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

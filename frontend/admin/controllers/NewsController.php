<?php
require_once __DIR__ . '/../models/News.php';

class NewsController {
    private $model;
    public function __construct() {
        $this->model = new News();
    }

public function index() {
    // pagination
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = 8;
    $offset = ($page - 1) * $limit;

    $total = $this->model->countAll();
    $news = $this->model->getPaginated($limit, $offset);

    $totalPages = ceil($total / $limit);

    include __DIR__ . '/../views/news/news-list.php';
}

public function delete() {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'msg' => 'Invalid ID']);
        return;
    }

    $ok = $this->model->delete($id);
    echo json_encode([
        'success' => $ok,
        'msg' => $ok ? 'News deleted' : 'Delete failed'
    ]);
}

    // show add form
    public function create() {
        $categories = $this->model->getUniqueCategories();
        $news = null;
        $this->renderForm($news, $categories);
    }

    // show edit form
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $news = $this->model->getById($id);
        if (!$news) {
            header("Location: index.php?action=news&msg=notfound");
            exit;
        }
        $categories = $this->model->getUniqueCategories();
        $this->renderForm($news, $categories);
    }

    private function renderForm($news, $categories) {
        // pass $news and $categories to view
        include __DIR__ . '/../views/news/news-form.php';
    }

    // handle store
    public function store() {
        $this->handleSave();
    }

    // handle update
    public function update() {
        $this->handleSave(true);
    }

    private function handleSave($isUpdate = false) {
        // simple validation
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $created_at = trim($_POST['created_at'] ?? date('Y-m-d'));
        $quote = trim($_POST['quote'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['main','none']) ? $_POST['status'] : 'none';

        $errors = [];
        if ($title === '') $errors[] = 'Title is required';
        if ($category === '') $errors[] = 'Category is required';
        if ($excerpt === '') $errors[] = 'Brief description is required';

        // image validations: image_detail required; thumbnail required
        $maxBytes = 5 * 1024 * 1024;
        $allowed = ['image/jpeg','image/png'];

        // prepare uploads dir
        $uploadDir = __DIR__ . '/../uploads/news/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        // helper to validate and move & resize image
        $processImage = function($fileField, $targetWidth, $required = false) use ($uploadDir, $maxBytes, $allowed, &$errors) {
            if (empty($_FILES[$fileField]) || $_FILES[$fileField]['error'] === UPLOAD_ERR_NO_FILE) {
                if ($required) $errors[] = ucfirst($fileField) . ' is required';
                return null;
            }
            $f = $_FILES[$fileField];
            if ($f['error'] !== UPLOAD_ERR_OK) { $errors[] = 'Upload error for ' . $fileField; return null; }
            if ($f['size'] > $maxBytes) { $errors[] = 'File ' . $fileField . ' exceeds 5MB'; return null; }
            if (!in_array(mime_content_type($f['tmp_name']), $allowed)) { $errors[] = 'Invalid file type for ' . $fileField; return null; }

            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $basename = $fileField . '_' . time() . '_' . rand(100,999) . '.' . $ext;
            $dest = $uploadDir . $basename;

            // resize with GD
            $srcMime = mime_content_type($f['tmp_name']);
            if ($srcMime === 'image/jpeg') $srcImg = imagecreatefromjpeg($f['tmp_name']);
            else $srcImg = imagecreatefrompng($f['tmp_name']);

            if (!$srcImg) { $errors[] = 'Unable to process image ' . $fileField; return null; }

            $origW = imagesx($srcImg);
            $origH = imagesy($srcImg);
            $ratio = $origW > 0 ? $origH / $origW : 1;
            $newW = $targetWidth;
            $newH = (int) round($newW * $ratio);

            $dst = imagecreatetruecolor($newW, $newH);
            // preserve PNG transparency
            if ($srcMime === 'image/png') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }
            imagecopyresampled($dst, $srcImg, 0,0,0,0, $newW, $newH, $origW, $origH);
            // save
            if ($srcMime === 'image/jpeg') imagejpeg($dst, $dest, 85);
            else imagepng($dst, $dest);

            imagedestroy($srcImg);
            imagedestroy($dst);

            // return web path relative to admin root (adjust as needed)
            return 'uploads/news/' . $basename;
        };

        // If update, existing images may remain if no file uploaded
        $thumbPath = $processImage('image_thumb', 600, !$isUpdate); // required only for new entry, but for safety we recommend required
        $detailPath = $processImage('image_detail', 1200, !$isUpdate);

        if ($isUpdate && empty($errors)) {
            $id = intval($_POST['id'] ?? 0);
            $existing = $this->model->getById($id);
            if (!$existing) { $errors[] = 'Record not found'; }
            // fallback to existing image path if not uploaded
            if (!$thumbPath) $thumbPath = $existing['image_thumb'] ?? null;
            if (!$detailPath) $detailPath = $existing['image_detail'] ?? null;
        }

        if (!empty($errors)) {
            // pass variables back to form
            $news = [
                'id' => $_POST['id'] ?? null,
                'title' => $title,
                'category' => $category,
                'excerpt' => $excerpt,
                'content' => $content,
                'created_at' => $created_at,
                'quote' => $quote,
                'status' => $status,
                'image_thumb' => $thumbPath,
                'image_detail' => $detailPath
            ];
            $categories = $this->model->getUniqueCategories();
            include __DIR__ . '/../views/news/news-form.php';
            return;
        }

        // build payload
        $payload = [
            'title' => $title,
            'category' => $category,
            'excerpt' => $excerpt,
            'content' => $content,
            'created_at' => $created_at,
            'quote' => $quote,
            'status' => $status,
            'image_thumb' => $thumbPath,
            'image_detail' => $detailPath,
        ];

        if ($isUpdate) {
            $ok = $this->model->update(intval($_POST['id']), $payload);
            if ($ok) {
                header("Location: index.php?action=news&msg=updated");
                exit;
            } else {
                $errors[] = 'Update failed';
            }
        } else {
            $id = $this->model->insert($payload);
            if ($id) {
                header("Location: index.php?action=news&msg=created");
                exit;
            } else {
                $errors[] = 'Insert failed';
            }
        }

        // on error, show form with errors
        $news = array_merge($payload, ['id' => $_POST['id'] ?? null]);
        $categories = $this->model->getUniqueCategories();
        include __DIR__ . '/../views/news/news-form.php';
    }
}

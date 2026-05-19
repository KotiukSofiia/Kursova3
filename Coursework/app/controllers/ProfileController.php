<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Admin.php';

class ProfileController extends BaseController
{
    public function index()
    {
        $this->redirect('profile', 'edit');
    }
    /**
     * Сторінка “My Profile” (GET)
     * URL: index.php?controller=profile&action=edit
     */
    public function edit()
    {
        $this->requireLogin();

        $adminId = $_SESSION['UserId'];
        $admin   = Admin::findById($adminId);

        if (!$admin) {
            $this->setErrorMessage("User not found!");
            $this->redirect('dashboard', 'index');
        }

        // Ми НЕ читаємо флеш-повідомлення тут, залишаємо це на header.php
        $this->render('profile/edit', [
            'admin' => $admin
        ]);
    }

    /**
     * Обробка Submit для оновлення профілю (POST)
     * URL: index.php?controller=profile&action=update
     */
    public function update()
    {
        $this->requireLogin();

        $adminId  = $_SESSION['UserId'];
        $name     = trim($_POST['Name'] ?? '');
        $headline = trim($_POST['Headline'] ?? '');
        $bio      = trim($_POST['Bio'] ?? '');
        $imageName = '';  // якщо файл не завантажений — залишимо порожнім

        // Отримуємо дані з БД, щоб дізнатися старе ім'я картинки
        $admin    = Admin::findById($adminId);
        if (!$admin) {
            $this->setErrorMessage("User not found!");
            $this->redirect('profile', 'edit');
        }
        $oldImage = $admin['aimage'];

        // Валідація
        if (mb_strlen($headline) > 30) {
            $this->setErrorMessage("Заголовок має бути менше 30 символів");
            $this->redirect('profile', 'edit');
        }
        if (mb_strlen($bio) > 500) {
            $this->setErrorMessage("Біографія має містити менше 500 символів");
            $this->redirect('profile', 'edit');
        }

        // Якщо завантажили нове зображення
        if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
            $imageName = $_FILES['Image']['name'];
            $tempPath  = $_FILES['Image']['tmp_name'];

            // папка для зображень профілю — public/uploads/
            $targetDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $targetFile = $targetDir . basename($imageName);

            // Перевіримо розширення
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes  = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                $this->setErrorMessage("Дозволено лише JPG/JPEG/PNG/GIF");
                $this->redirect('profile', 'edit');
            }
            // Спроба перемістити у uploads
            if (move_uploaded_file($tempPath, $targetFile)) {
                // Видалення старого, якщо є
                if ($oldImage) {
                    $oldFile = $targetDir . $oldImage;
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            } else {
                $this->setErrorMessage("Не вдається завантажити нове зображення.");
                $this->redirect('profile', 'edit');
            }
        }

        // Оновлення в БД
        $updated = Admin::updateProfile($adminId, $name, $headline, $bio, $imageName);

        if ($updated) {
            $this->setSuccessMessage("Профіль успішно оновлено");
        } else {
            $this->setErrorMessage("Щось пішло не так. Спробуйте ще раз!");
        }
        $this->redirect('profile', 'edit');
    }

    /**
     * Показ профілю іншого користувача (публічна частина)
     * URL: index.php?controller=profile&action=show&username=admin
     */
    public function show()
    {
        $username = $_GET['username'] ?? '';
        if (!$username) {
            http_response_code(400);
            echo "<h1>400 Bad Request</h1>";
            exit;
        }

        $admin = Admin::findByUsername($username);
        if (!$admin) {
            http_response_code(404);
            echo "<h1>404 Not Found</h1><p>User not found.</p>";
            exit;
        }

        // На сторінці show ми також НЕ читаємо флеш-повідомлення
        $this->render('profile/show', [
            'admin' => $admin
        ]);
    }
}

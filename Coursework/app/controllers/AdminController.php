<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Admin.php';

class AdminController extends BaseController
{
    /**
     * Сторінка “Manage Admins”
     * URL: index.php?controller=admin&action=index
     */
    public function index()
    {
        $this->requireLogin();

        $admins = Admin::getAll();

        $this->render('admins/index', [
            'admins' => $admins
        ]);
    }

    /**
     * Обробка Submit із форми “Add New Admin”
     * URL: index.php?controller=admin&action=create
     */
    public function create()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username        = trim($_POST['Username'] ?? '');
            $name            = trim($_POST['Name'] ?? '');
            $password        = trim($_POST['Password'] ?? '');
            $confirmPassword = trim($_POST['ConfirmPassword'] ?? '');
            $addedBy         = $_SESSION['UserName'] ?? '';

            // Валідація
            if ($username === '' || $password === '' || $confirmPassword === '') {
                $this->setErrorMessage("Усі поля мають бути заповнені");
                $this->redirect('admin', 'index');
            }
            if (mb_strlen($password) < 4) {
                $this->setErrorMessage("Пароль має бути довшим за 3 символи");
                $this->redirect('admin', 'index');
            }
            if ($password !== $confirmPassword) {
                $this->setErrorMessage("Пароль та підтвердження пароля повинні збігатися");
                $this->redirect('admin', 'index');
            }
            if (Admin::existsByUsername($username)) {
                $this->setErrorMessage("Ім'я користувача вже існує. Спробуйте інше!");
                $this->redirect('admin', 'index');
            }

            $success = Admin::create($username, $password, $name, $addedBy);
            if ($success) {
                $this->setSuccessMessage("Нового адміністратора успішно додано");
            } else {
                $this->setErrorMessage("Щось пішло не так. Спробуйте ще раз!");
            }
            $this->redirect('admin', 'index');
        }

        $this->redirect('admin', 'index');
    }

    /**
     * Видалення адміністратора по ID
     * URL: index.php?controller=admin&action=delete&id=123
     */
    public function delete()
    {
        $this->requireLogin();

        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $this->setErrorMessage("Bad Request!");
            $this->redirect('admin', 'index');
        }

        $deleted = Admin::deleteById((int)$id);
        if ($deleted) {
            $this->setSuccessMessage("Адміна успішно видалено.");
        } else {
            $this->setErrorMessage("Не вдалося видалити адміністратора. Спробуйте ще раз!");
        }
        $this->redirect('admin', 'index');
    }

    public function show()
    {
        // Перевіряємо, що користувач залогінений
        $this->requireLogin();

        // 1) Читаємо ID із GET
        $id = isset($_GET['id']) && is_numeric($_GET['id'])
            ? (int)$_GET['id']
            : null;

        if (!$id) {
            // Некоректний запит — редіректимо назад на список
            $this->setErrorMessage("Неправильний запит!");
            $this->redirect('admin', 'index');
        }

        // 2) Дістаємо дані адміністратора
        $admin = Admin::findById($id);
        if (!$admin) {
            $this->setErrorMessage("Адміністратора не знайдено.");
            $this->redirect('admin', 'index');
        }

        // 3) Рендеримо view. Третім параметром 'public',
        //     щоб підключився публічний header_public.php
        $this->render('admins/show', [
            'admin' => $admin
        ], 'public');
    }
}

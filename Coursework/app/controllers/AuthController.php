<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Database.php';

class AuthController extends BaseController
{
    /**
     * Форма логіна + обробка Submit
     * URL: index.php?controller=auth&action=login
     */
    public function login()
    {
        if (isset($_SESSION['UserId'])) {
            $this->redirect('dashboard', 'index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['Username'] ?? '');
            $password = trim($_POST['Password'] ?? '');

            if ($username === '' || $password === '') {
                $this->setErrorMessage("Усі поля мають бути заповнені");
                $this->redirect('auth', 'login');
            }

            $found = Admin::attemptLogin($username, $password);
            if ($found) {
                $_SESSION['UserId']    = $found['id'];
                $_SESSION['UserName']  = $found['username'];
                $_SESSION['AdminName'] = $found['aname'];

                $this->setSuccessMessage("Ласкаво просимо, {$_SESSION['AdminName']}!");

                $backTo = $_SESSION['TrackingURL'] 
                          ?? 'index.php?controller=dashboard&action=index';
                unset($_SESSION['TrackingURL']);
                header("Location: {$backTo}");
                exit;
            } else {
                $this->setErrorMessage("Неправильне ім'я користувача/пароль");
                $this->redirect('auth', 'login');
            }
        }

        // GET: просто показуємо форму login
        $errorMsg   = $this->getErrorMessage();
        $successMsg = $this->getSuccessMessage();
        // наприкінці login()
$this->render('auth/login', [
    'errorMsg'   => $errorMsg,
    'successMsg' => $successMsg
], 'empty');    // ось тут ключове — 'empty' замість 'public'


    }

    /**
     * Logout — чистимо сесію та перекидаємо на login
     * URL: index.php?controller=auth&action=logout
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}

<?php
class BaseController
{
    protected function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }


    // app/controllers/BaseController.php
    protected function render(string $viewPath, array $params = [], string $layout = 'admin')
    {
        extract($params);

        // HEADER
        if ($layout === 'public') {
            require_once __DIR__ . '/../views/layouts/header_public.php';
        } elseif ($layout === 'admin') {
            require_once __DIR__ . '/../views/layouts/header.php';
        } elseif ($layout === 'empty') {
            // якщо треба можна підключити фактично порожній файл,
            // але можна й нічого не робити
            require_once __DIR__ . '/../views/layouts/empty.php';
        }

        // власне контент
        require_once __DIR__ . '/../views/' . $viewPath . '.php';

        // FOOTER
        if ($layout === 'empty') {
            require_once __DIR__ . '/../views/layouts/empty_footer.php';
        } else {
            require_once __DIR__ . '/../views/layouts/footer.php';
        }
    }


    /**
     * Редірект на інший контролер/дію.
     * @param string $controller
     * @param string $action
     */
    protected function redirect($controller, $action = 'index')
    {
        header("Location: index.php?controller={$controller}&action={$action}");
        exit;
    }

    /**
     * Перевірка, чи користувач авторизований.
     * Якщо ні, робимо редірект на сторінку логіну.
     */
    protected function requireLogin()
    {
        if (!isset($_SESSION['UserId'])) {
            // якщо користувач не залогінений, перенаправляємо на логін (поки припустимо, що буде AdminController->login)
            $this->redirect('admin', 'login');
        }
    }

    protected function setErrorMessage($msg)
    {
        $_SESSION['ErrorMessage'] = $msg;
    }

    protected function setSuccessMessage($msg)
    {
        $_SESSION['SuccessMessage'] = $msg;
    }

    protected function getErrorMessage()
    {
        if (isset($_SESSION['ErrorMessage'])) {
            $m = $_SESSION['ErrorMessage'];
            unset($_SESSION['ErrorMessage']);
            return $m;
        }
        return '';
    }

    protected function getSuccessMessage()
    {
        if (isset($_SESSION['SuccessMessage'])) {
            $m = $_SESSION['SuccessMessage'];
            unset($_SESSION['SuccessMessage']);
            return $m;
        }
        return '';
    }
}

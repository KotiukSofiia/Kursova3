<?php
// public/index.php

// Увімкнення виводу всіх помилок у розробці
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Запускаємо сесію один раз на всіх сторінках
session_start();

// Підключаємо контролери
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';
require_once __DIR__ . '/../app/controllers/PostController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CommentController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/BlogController.php';
// ...інші контролери, якщо є

// Зчитуємо GET-параметри для роутера
$controllerName  = $_GET['controller'] ?? 'blog';
$actionName      = $_GET['action']     ?? 'index';
$controllerClass = ucfirst($controllerName) . 'Controller';

// Починаємо буферизацію виводу
ob_start();

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    if (method_exists($controller, $actionName)) {
        // Виконуємо дію
        $controller->{$actionName}();
    } else {
        // Якщо метод не знайдено — 404
        http_response_code(404);
    }
} else {
    // Якщо контролер не знайдено — 404
    http_response_code(404);
}

// Забираємо увесь згенерований контент
$content = ob_get_clean();

// Визначаємо HTTP-код відповіді
$status = http_response_code();

// Якщо статус — 500 або >=500, показуємо 500-шаблон
if ($status >= 500) {
    require __DIR__ . '/../app/views/errors/500.php';
    exit;
}

// Якщо статус — точно 404, показуємо 404-шаблон
if ($status === 404) {
    require __DIR__ . '/../app/views/errors/404.php';
    exit;
}

// Інакше — виводимо згенерований контент
echo $content;

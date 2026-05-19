<?php
// app/views/errors/404.php
http_response_code(404);
$pageTitle = '404 Not Found';
// Підключаємо публічний хедер (у ньому вже є ваш CSS та Bootstrap)
require_once __DIR__ . '/../layouts/header_public.php';
?>
<!-- Повний екран, контент по центру -->
<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 56px - 56px);">
  <div class="text-center">
    <h1 class="display-1">404</h1>
    <p class="lead">Сторінку не знайдено.</p>
    <a href="index.php?controller=blog&action=index&page=1" class="btn btn-primary">Повернутися додому</a>
  </div>
</div>
<?php
// Підключаємо загальний футер
require_once __DIR__ . '/../layouts/footer.php';

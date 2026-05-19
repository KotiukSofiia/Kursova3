<?php
require_once __DIR__ . '/../../models/Category.php';
$categories = Category::getAll();

// Визначаємо, що зараз активне в меню
$currentController = $_GET['controller'] ?? 'blog';
$currentAction     = $_GET['action']     ?? 'index';
$currentCategory   = $_GET['category']   ?? '';
?>
<!DOCTYPE html>
<html lang="uk">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlentities($pageTitle ?? 'News'); ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a href="index.php?controller=blog&action=index&page=1" class="navbar-brand">NEWS.COM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navPub">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navPub">
          <ul class="navbar-nav mr-auto">
            <!-- Home -->
            <li class="nav-item">
              <a
                href="index.php?controller=blog&action=index&page=1"
                class="nav-link <?= ($currentController === 'blog' && $currentCategory === '') ? 'active' : '' ?>">
                Головна
              </a>
            </li>
            <!-- About Us -->

            <!-- Динамічний список категорій -->
            <?php foreach ($categories as $cat): ?>
              <li class="nav-item">
                <a
                  href="index.php?controller=blog&action=index&page=1&category=<?= urlencode($cat['title']); ?>"
                  class="nav-link <?= ($currentController === 'blog' && $currentCategory === $cat['title']) ? 'active' : '' ?>">
                  <?= htmlentities($cat['title']); ?>
                </a>
              </li>
            <?php endforeach; ?>


            <li class="nav-item">
              <a
                href="index.php?controller=site&action=about"
                class="nav-link <?= ($currentController === 'site' && $currentAction === 'about') ? 'active' : '' ?>">
                Про нас
              </a>
            </li>
          </ul>

          <!-- Пошук -->

        </div>
      </div>
    </nav>
    <div style="height:10px; background:#fff;"></div>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інформаційна система новин</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
    <div id="page-wrapper">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a href="index.php" class="navbar-brand">NEWS.COM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCMS">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCMS">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a 
                        href="index.php?controller=profile&action=index" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'profile' ? 'active' : ''); ?>">
                        Мій профіль
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        href="index.php?controller=dashboard&action=index" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'dashboard' ? 'active' : ''); ?>">
                        Панель інструментів
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        href="index.php?controller=post&action=index" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'post' ? 'active' : ''); ?>">
                        Дописи
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        href="index.php?controller=category&action=index" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'category' ? 'active' : ''); ?>">
                        Категорії
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        href="index.php?controller=admin&action=index" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'admin' ? 'active' : ''); ?>">
                        Адміністратори
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        href="index.php?controller=comment&action=index" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'comment' ? 'active' : ''); ?>">
                        Коментарі
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        href="index.php?controller=blog&action=index&page=1" 
                        class="nav-link <?php echo (($_GET['controller'] ?? '') === 'blog' ? 'active' : ''); ?>" 
                        target="_blank">
                        Блог
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="index.php?controller=auth&action=logout" class="nav-link text-danger">
                        <i class="fas fa-user-times"></i> Вийти
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="height:10px; background:#fff;"></div>

<?php
// Виводимо flash-повідомлення з сесії лише один раз — саме тут, у header
$errorMsg   = $this->getErrorMessage();
$successMsg = $this->getSuccessMessage();
?>

<?php if ($errorMsg): ?>
    <div class="container">
        <div class="alert alert-danger text-center mb-0">
            <?php echo htmlentities($errorMsg); ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($successMsg): ?>
    <div class="container">
        <div class="alert alert-success text-center mb-0">
            <?php echo htmlentities($successMsg); ?>
        </div>
    </div>
<?php endif; ?>

<!-- Тепер починається вміст конкретної сторінки -->

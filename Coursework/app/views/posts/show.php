<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlentities($post['title']); ?></title>
    <link 
        rel="stylesheet" 
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" 
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/Styles.css">
</head>
<body>
    <div class="container py-4">
        <h1><?php echo htmlentities($post['title']); ?></h1>
        <p class="text-muted">
            <strong>Дата:</strong> <?php echo htmlentities($post['datetime']); ?>
            &nbsp;|&nbsp;
            <strong>Категорія:</strong> <?php echo htmlentities($post['category']); ?>
            &nbsp;|&nbsp;
            <strong>Автор:</strong> <?php echo htmlentities($post['author']); ?>
        </p>
        <?php if (!empty($post['image'])): ?>
            <div class="mb-4">
                <img 
                    src="uploads/<?php echo htmlentities($post['image']); ?>" 
                    alt="Banner Image" 
                    class="img-fluid">
            </div>
        <?php endif; ?>
        <div class="post-content">
            <?php echo nl2br(htmlentities($post['post'])); ?>
        </div>
    </div>
</body>
</html>

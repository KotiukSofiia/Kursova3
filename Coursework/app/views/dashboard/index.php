<div class="container py-2 mb-4 d-flex flex-column min-vh-100">
    <div class="row">
        <div class="col-lg-12">
            <h1>Панель інструментів</h1>

            <!-- Кнопки зверху -->
            <div class="mb-3">
                <a href="index.php?controller=post&action=create" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Додати нову публікацію
                </a>
                <a href="index.php?controller=category&action=index" class="btn btn-info">
                    <i class="fas fa-folder-plus"></i> Додати нову категорію
                </a>
                <a href="index.php?controller=admin&action=index" class="btn btn-warning">
                    <i class="fas fa-user-plus"></i> Додати нового адміністратора
                </a>
                <a href="index.php?controller=comment&action=index" class="btn btn-success">
                    <i class="fas fa-check"></i> Коментарі
                </a>
            </div>

            <!-- Flash-повідомлення -->
            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger"><?php echo htmlentities($errorMsg); ?></div>
            <?php endif; ?>
            <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success"><?php echo htmlentities($successMsg); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Ліва панель: кількості -->
        <div class="col-lg-2 d-none d-md-block">
            <div class="card text-center bg-dark text-white mb-3">
                <div class="card-body">
                    <h1 class="lead">Дописи</h1>
                    <h4 class="display-5">
                        <i class="fas fa-file-alt"></i>
                        <?php echo $totalPosts; ?>
                    </h4>
                </div>
            </div>
            <div class="card text-center bg-dark text-white mb-3">
                <div class="card-body">
                    <h1 class="lead">Категорії</h1>
                    <h4 class="display-5">
                        <i class="fas fa-folder"></i>
                        <?php echo $totalCategories; ?>
                    </h4>
                </div>
            </div>
            <div class="card text-center bg-dark text-white mb-3">
                <div class="card-body">
                    <h1 class="lead">Адміністратори</h1>
                    <h4 class="display-5">
                        <i class="fas fa-users"></i>
                        <?php echo $totalAdmins; ?>
                    </h4>
                </div>
            </div>
            <div class="card text-center bg-dark text-white mb-3">
                <div class="card-body">
                    <h1 class="lead">Коментарі</h1>
                    <h4 class="display-5">
                        <i class="fas fa-comments"></i>
                        <?php echo $totalComments; ?>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Права панель: топ-пости -->
        <div class="col-lg-10">
            <h1>Дописи</h1>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Титул</th>
                        <th>Дата &amp; Час</th>
                        <th>Автор</th>
                        <th>Коментарі</th>
                        <th>Деталі</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $srNo = 0;
                    foreach ($topPosts as $post):
                        $srNo++;
                        $postId   = $post['id'];
                        $title    = $post['title'];
                        $datetime = $post['datetime'];
                        $author   = $post['author'];
                        // Ось тут ми підраховуємо обидва бейджі:
                        $approved   = Post::countApprovedComments($postId);
                        $unapproved = Post::countUnapprovedComments($postId);
                    ?>
                    <tr>
                        <td><?php echo $srNo; ?></td>
                        <td><?php echo htmlentities($title); ?></td>
                        <td><?php echo htmlentities($datetime); ?></td>
                        <td><?php echo htmlentities($author); ?></td>
                        <td>
                            <?php if ($approved > 0): ?>
                                <span class="badge badge-success"><?php echo $approved; ?></span>
                            <?php endif; ?>
                            <?php if ($unapproved > 0): ?>
                                <span class="badge badge-danger"><?php echo $unapproved; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=post&action=show&id=<?php echo $postId; ?>"
                               class="btn btn-info btn-sm" target="_blank">
                               Попередній перегляд
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($topPosts)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

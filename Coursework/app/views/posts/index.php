<div class="container py-2 mb-4">
    <div class="row">
        <div class="col-lg-12">
            <h1>Редагувати дописи</h1>

    
            <!-- Таблиця постів -->
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Титул</th>
                        <th>Категорія</th>
                        <th>Дата &amp; Час</th>
                        <th>Автор</th>
                        <th>Банер</th>
                        <th>Коментарі</th>
                        <th>Дія</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sr = 0;
                    foreach ($posts as $post): 
                        $sr++;
                        $pid  = $post['id'];
                        $pt   = $post['title'];
                        $cat  = $post['category_name'] ?: '—';
                        $dt   = $post['datetime'];
                        $auth = $post['author'];
                        $img  = $post['image'];
                        // Кількість коментарів
                        $approved   = Post::countApprovedComments($pid);
                        $unapproved = Post::countUnapprovedComments($pid);
                    ?>
                    <tr>
                        <td><?php echo $sr; ?></td>
                        <td>
                            <?php 
                            $short = (mb_strlen($pt) > 20) ? mb_substr($pt, 0, 18) . '…' : $pt;
                            echo htmlentities($short);
                            ?>
                        </td>
                        <td>
                            <?php 
                            $shortC = (mb_strlen($cat) > 8) ? mb_substr($cat, 0, 8) . '…' : $cat;
                            echo htmlentities($shortC);
                            ?>
                        </td>
                        <td>
                            <?php 
                            $shortDt = (mb_strlen($dt) > 11) ? mb_substr($dt, 0, 11) . '…' : $dt;
                            echo htmlentities($shortDt);
                            ?>
                        </td>
                        <td>
                            <?php 
                            $shortA = (mb_strlen($auth) > 6) ? mb_substr($auth, 0, 6) . '…' : $auth;
                            echo htmlentities($shortA);
                            ?>
                        </td>
                        <td>
                            <?php if ($img): ?>
                                <img src="uploads/<?php echo htmlentities($img); ?>" 
                                     width="170" height="50" style="object-fit:cover;" alt="Banner">
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($approved > 0): ?>
                                <span class="badge badge-success"><?php echo $approved; ?></span>
                            <?php endif; ?>
                            <?php if ($unapproved > 0): ?>
                                <span class="badge badge-danger"><?php echo $unapproved; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=post&action=edit&id=<?php echo $pid; ?>" 
                               class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?controller=post&action=delete&id=<?php echo $pid; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Ви дійсно хочете видалити цей пост?');">
                               Delete
                            </a>
                        </td>
                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

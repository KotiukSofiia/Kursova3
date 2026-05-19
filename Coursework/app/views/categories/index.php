<div class="container py-2 mb-4 d-flex flex-column min-vh-100">
    <div class="row">
        <div class="offset-lg-1 col-lg-10" style="min-height:500px;">
            <form action="index.php?controller=category&action=index" method="post">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Додати нову категорію</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="CategoryTitle">Назва категорії:</label>
                            <input class="form-control" type="text" 
                                   name="CategoryTitle" id="CategoryTitle" 
                                   placeholder="Введіть тут заголовок" value="">
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="index.php?controller=dashboard&action=index" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-arow-left"></i> Назад
                                </a>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <button type="submit" name="Submit" 
                                        class="btn btn-success btn-block">
                                    Опублікувати
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <h2>Існуючі категорії</h2>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>№</th>
                        <th>Дата &amp; Час</th>
                        <th>Назва категорії</th>
                        <th>Ім'я адміна</th>
                        <th>Дія</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $srNo = 0;
                    foreach ($categories as $row): 
                        $srNo++;
                        $categoryId   = $row['id'];
                        $categoryDate = $row['datetime'];
                        $categoryName = $row['title'];
                        $creatorName  = $row['author'];
                    ?>
                    <tr>
                        <td><?php echo htmlentities($srNo); ?></td>
                        <td><?php echo htmlentities($categoryDate); ?></td>
                        <td><?php echo htmlentities($categoryName); ?></td>
                        <td><?php echo htmlentities($creatorName); ?></td>
                        <td>
                            <a href="index.php?controller=category&action=delete&id=<?php echo $categoryId; ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Ви впевнені, що хочете видалити цю категорію?');">
                                Видалити
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

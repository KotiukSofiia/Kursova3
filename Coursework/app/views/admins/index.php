
<div class="container py-2 mb-4 ">
    <div class="row">
        <div class="col-lg-12">

            <h1>Керування адміністраторами</h1>

            <!-- Форма для створення нового адміністратора -->
            <form action="index.php?controller=admin&action=create" method="post">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Додати нового адміністратора</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="username">Ім'я користувача:</label>
                            <input 
                              class="form-control" 
                              type="text" 
                              name="Username" 
                              id="username" 
                              placeholder="Введіть ім'я користувача">
                        </div>
                        <div class="form-group">
                            <label for="Name">Ім'я:</label>
                            <input 
                              class="form-control" 
                              type="text" 
                              name="Name" 
                              id="Name" 
                              placeholder="Ім'я">
                        </div>
                        <div class="form-group">
                            <label for="Password">Пароль:</label>
                            <input 
                              class="form-control" 
                              type="password" 
                              name="Password" 
                              id="Password" 
                              placeholder="Введіть пароль">
                        </div>
                        <div class="form-group">
                            <label for="ConfirmPassword">Підтвердіть пароль:</label>
                            <input 
                              class="form-control" 
                              type="password" 
                              name="ConfirmPassword" 
                              id="ConfirmPassword" 
                              placeholder="Підтвердіть пароль">
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="index.php?controller=dashboard&action=index" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-arrow-left"></i> Назад
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

            <!-- Список існуючих адміністраторів -->
            <h2>Існуючі адміністратори</h2>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>№</th>
                        <th>Дата &amp; Час</th>
                        <th>Ім'я користувача</th>
                        <th>Ім'я адміністратора</th>
                        <th>Додано</th>
                        <th>Дія</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sr = 0;
                    foreach ($admins as $admin): 
                        $sr++;
                        $id      = $admin['id'];
                        $dt      = $admin['datetime'];
                        $uname   = $admin['username'];
                        $aname   = $admin['aname'];
                        $addedBy = $admin['addedby'];
                    ?>
                        <tr>
                            <td><?php echo $sr; ?></td>
                            <td><?php echo htmlentities($dt); ?></td>
                            <td><?php echo htmlentities($uname); ?></td>
                            <td><?php echo htmlentities($aname); ?></td>
                            <td><?php echo htmlentities($addedBy); ?></td>
                            <td>
                                <a 
                                  href="index.php?controller=admin&action=delete&id=<?php echo $id; ?>" 
                                  class="btn btn-danger btn-sm"
                                  onclick="return confirm('Ви дійсно хочете видалити цього адміністратора?');">
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

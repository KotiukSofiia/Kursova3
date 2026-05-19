<?php
$pageTitle = 'Blog';
require_once __DIR__ . '/../layouts/header_public.php';
?>

<div class="container py-4 ">
  <h1 class="mb-4">Дописи в блозі</h1>
  <div class="row">
    <div class="col-lg-8">
      <?php if (empty($posts)): ?>
        <p>No posts found.</p>
        <?php else: foreach ($posts as $post): ?>
          <div class="card mb-4">
            <?php if ($post['image']): ?>
              <img src="uploads/<?php echo htmlentities($post['image']); ?>"
                class="card-img-top" alt="">
            <?php endif; ?>
            <div class="card-body">
              <h2 class="card-title"><?php echo htmlentities($post['title']); ?></h2>
              <p class="text-muted">
                <small>
                  Категорія:
                  <a href="?controller=blog&action=index&category=<?php echo urlencode($post['category']); ?>">
                    <?php echo htmlentities($post['category_name'] ?: $post['category']); ?>
                  </a>
                  &bull; Написав
                  <a href="?controller=profile&action=show&username=<?php echo urlencode($post['author']); ?>"
                    class="text-info">
                    <?php echo htmlentities($post['author']); ?>
                  </a>
                  в <?php echo htmlentities($post['datetime']); ?>
                </small>
              </p>
              <p>
                <?php
                echo nl2br(htmlentities(mb_strimwidth($post['post'], 0, 200, '…')));
                ?>
              </p>
              <a href="?controller=blog&action=show&id=<?php echo $post['id']; ?>"
                class="btn btn-primary">
                Детальніше →
              </a>
            </div>
          </div>
      <?php endforeach;
      endif; ?>

      <!-- Пагінація -->
      <?php if (isset($perPage, $totalPosts, $currentPage)): ?>
        <nav>
          <ul class="pagination">
            <?php
            $pages = (int)ceil($totalPosts / $perPage);
            for ($i = 1; $i <= $pages; $i++):
            ?>
              <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                <a class="page-link"
                  href="?controller=blog&action=index&page=<?php echo $i; ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>
    </div>

    <!-- Бічна панель -->
    <div class="col-lg-4">
      <!-- Пошук -->
      <form class="form-inline mb-4" method="get" action="">
        <input type="hidden" name="controller" value="blog">
        <input type="hidden" name="action" value="index">
        <div class="input-group w-100">
          <input type="text"
            name="search"
            class="form-control"
            placeholder="Шукати..."
            value="<?php echo htmlentities($_GET['search'] ?? ''); ?>">
          <button class="btn btn-primary" type="submit">Шукати</button>
        </div>
      </form>

      <div class="card mb-4">
        <div class="card-body ">
          <img src="images/startblog.png" class="d-block img-fluid mb-3" alt="">
          <div class="text-center">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat…
          </div>
        </div>
      </div>

      <!-- Список категорій -->
      <div class="card mb-4">
        <div class="card-header bg-dark text-light">Категорії</div>
        <ul class="list-group list-group-flush">
          <?php foreach ($categories as $cat): ?>
            <li class="list-group-item">
              <a href="?controller=blog&action=index&category=<?php echo urlencode($cat['title']); ?>">
                <?php echo htmlentities($cat['title']); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Останні пости -->
      <div class="card mb-4">
                <div class="card-header  bg-dark text-light">Останні дописи</div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($recentPosts as $rp): ?>
                        <li class="list-group-item d-flex align-items-center">
                            <?php if (!empty($rp['image'])): ?>
                                <img
                                    src="uploads/<?php echo htmlentities($rp['image']); ?>"
                                    alt=""
                                    class="img-thumbnail mr-3"
                                    style="width:60px; height:60px; object-fit:cover;">
                            <?php endif; ?>
                            <div>
                                <a
                                    href="?controller=blog&action=show&id=<?php echo $rp['id']; ?>"
                                    class="font-weight-bold">
                                    <?php echo htmlentities($rp['title']); ?>
                                </a>
                                <br>
                                <small class="text-muted">
                                    <?php echo htmlentities($rp['datetime']); ?>
                                </small>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
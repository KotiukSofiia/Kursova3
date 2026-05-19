<?php
// app/views/blog/show.php
$pageTitle = $post['title'];
require_once __DIR__ . '/../layouts/header_public.php';
?>
<div class="container py-4 ">
  <div class="row">
    <!-- Ліва колонка: пост + коментарі + форма -->
    <div class="col-lg-8">
      <!-- сам пост -->
      <div class="card mb-4">
        <?php if ($post['image']): ?>
          <img src="uploads/<?php echo htmlentities($post['image']); ?>"
            class="card-img-top" alt="">
        <?php endif; ?>
        <div class="card-body">
          <h2 class="card-title"><?php echo htmlentities($post['title']); ?></h2>
          <p class="text-muted">
            <small>

              Написав
              <a href="?controller=profile&action=show&username=<?php echo urlencode($post['author']); ?>"
                class="text-info">
                <?php echo htmlentities($post['author']); ?>
              </a>
              <?php echo htmlentities($post['datetime']); ?>
            </small>
          </p>
          <p><?php echo nl2br(htmlentities($post['post'])); ?></p>
        </div>
      </div>

      <!-- Повідомлення помилки/успіху -->
      <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo htmlentities($errorMsg); ?></div>
      <?php endif; ?>
      <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo htmlentities($successMsg); ?></div>
      <?php endif; ?>

      <!-- Схвалені коментарі -->
      <h3>Коментарі</h3>
      <div id="commentsList">
        <?php if (empty($comments)): ?>
          <p class="text-muted">Поки що немає коментарів.</p>
        <?php else: ?>
          <?php foreach ($comments as $c): ?>
            <div class="media mb-3 p-3 bg-light rounded">
              <img src="images/comment.png"
                class="mr-3 rounded-circle"
                style="width:60px;height:60px;" alt="">
              <div class="media-body">
                <h5 class="mt-0"><?php echo htmlentities($c['name']); ?></h5>
                <small class="text-muted"><?php echo htmlentities($c['datetime']); ?></small>
                <p><?php echo nl2br(htmlentities($c['comment'])); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Форма асинхронного додавання коментаря -->
      <div class="card mt-4">
        <div class="card-header bg-dark text-light">
          Поділіться своїми думками щодо цієї публікації
        </div>
        <div class="card-body">
          <form id="commentForm">
            <input type="hidden" name="postId" value="<?php echo $post['id']; ?>">
            <div class="form-group">
              <input type="text" name="name" class="form-control" placeholder="Ім'я">
            </div>
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="Пошта">
            </div>
            <div class="form-group">
              <textarea name="comment" class="form-control" rows="5" placeholder="Твій коментар"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Опублікувати</button>
            <div id="commentFeedback" class="mt-3"></div>
          </form>
        </div>
      </div>
    </div>

    <!-- Права колонка: бічна панель -->
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<script>
  document.getElementById('commentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const postId = form.postId.value;
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const comment = form.comment.value.trim();
    const feedback = document.getElementById('commentFeedback');
    const commentsList = document.getElementById('commentsList');

    feedback.innerHTML = '';
    feedback.className = '';

    try {
      const res = await fetch(
        `index.php?controller=blog&action=commentAsync&id=${postId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            name,
            email,
            comment
          })
        });

      const data = await res.json();
      if (!data.success) {
        const msgs = data.errors || [data.message];
        feedback.className = 'alert alert-danger';
        feedback.innerHTML = msgs.map(m => `<div>${m}</div>`).join('');
        return;
      }

      // Видаляємо placeholder, якщо він ще є
      const emptyMsg = commentsList.querySelector('p.text-muted');
      if (emptyMsg) {
        emptyMsg.remove();
      }
      const c = data.comment;
      // Додаємо новий коментар угору
      const tpl = `
      <div class="media mb-3 p-3 bg-light rounded">
        <img src="images/comment.png"
            class="mr-3 rounded-circle"
            style="width:60px;height:60px;" alt="">
        <div class="media-body">
          <h5 class="mt-0">${c.name}</h5>
          <small class="text-muted">${c.datetime}</small>
          <p>${c.comment}</p>
        </div>
      </div>`;
      commentsList.insertAdjacentHTML('afterbegin', tpl);


      feedback.className = 'alert alert-success';
      feedback.textContent = 'Дякуємо! Коментар додано.';
      form.reset();

    } catch (err) {
      feedback.className = 'alert alert-danger';
      feedback.textContent = 'Сталася помилка, спробуйте ще раз.';
    }
  });
</script>
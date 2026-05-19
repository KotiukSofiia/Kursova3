<div class="container py-2 mb-4">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header bg-dark text-light">
          <h3><?php echo htmlentities($admin['aname']); ?></h3>
        </div>
        <div class="card-body text-center">
          <?php if ($admin['aimage']): ?>
            <img
              src="uploads/<?php echo htmlentities($admin['aimage']); ?>"
              class="img-fluid rounded mb-3"
              alt="Profile Image">
          <?php endif; ?>
          <p class="lead"><?php echo nl2br(htmlentities($admin['abio'])); ?></p>
        </div>
      </div>
    </div>



    <div class="col-md-9" style="min-height:400px;">
      <form action="index.php?controller=profile&action=update" method="post" enctype="multipart/form-data">
        <div class="card">
          <div class="card-header">
            <h4>Редагувати профіль</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <input
                class="form-control"
                type="text"
                name="Name"
                placeholder="Ваше ім'я"
                value="<?php echo htmlentities($admin['aname']); ?>">
            </div>

            <div class="form-group">
              <input
                class="form-control"
                type="text"
                name="Headline"
                placeholder="Заголовок (до 30 символів)"
                value="<?php echo htmlentities($admin['aheadline']); ?>">
            </div>

            <div class="form-group">
              <textarea
                class="form-control"
                name="Bio"
                rows="8"
                placeholder="Бio (до 500 символів)"><?php
                                                    echo htmlentities($admin['abio']); ?></textarea>
            </div>

            <div class="form-group">
              <div class="custom-file">
                <input
                  class="custom-file-input"
                  type="file"
                  name="Image"
                  id="imageSelect">
                <label class="custom-file-label" for="imageSelect">
                  Виберіть нове зображення
                </label>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 mb-2">
                <a href="index.php?controller=dashboard&action=index"
                  class="btn btn-warning btn-block">
                  <i class="fas fa-arrow-left"></i> Назад до панелі інструментів
                </a>
              </div>
              <div class="col-lg-6 mb-2">
                <button type="submit" name="Submit"
                  class="btn btn-success btn-block">
                  Зберегти
                </button>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('imageSelect');
    const fileLabel = fileInput.nextElementSibling;
    fileInput.addEventListener('change', function(e) {
      const fileName = e.target.files[0]?.name || 'Select New Image';
      fileLabel.textContent = fileName;
    });
  });
</script>
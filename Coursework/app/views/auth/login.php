<div class="container vh-100 d-flex align-items-center justify-content-center">
  <div class="w-50" style="max-width: 550px;">
    <?php if (!empty($errorMsg)): ?>
      <div class="alert alert-danger"><?php echo htmlentities($errorMsg); ?></div>
    <?php endif; ?>
    <?php if (!empty($successMsg)): ?>
      <div class="alert alert-success"><?php echo htmlentities($successMsg); ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header">
        <h4 class="text-center mb-0">Вхід</h4>
      </div>
      <div class="card-body">
        <form action="index.php?controller=auth&action=login" method="post">
          <div class="form-group mb-3">
            <label for="username">Ім'я користувача:</label>
            <div class="input-group">
              <span class="input-group-text bg-primary text-white">
                <i class="fas fa-user"></i>
              </span>
              <input 
                type="text" 
                class="form-control" 
                name="Username" 
                id="username" 
                placeholder="Enter username"
                value=""
              >
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="password">Пароль:</label>
            <div class="input-group">
              <span class="input-group-text bg-primary text-white">
                <i class="fas fa-lock"></i>
              </span>
              <input 
                type="password" 
                class="form-control" 
                name="Password" 
                id="password" 
                placeholder="Enter password" 
                value=""
              >
            </div>
          </div>

          <button type="submit" name="Submit" class="btn btn-primary btn-block w-100">
            Увійти
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

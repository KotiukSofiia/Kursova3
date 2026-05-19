<?php
$pageTitle = htmlentities($admin['aname']);
?>
<div class="container py-4">
  <h1><?php echo $pageTitle; ?></h1>
  <small><?php echo htmlentities($admin['aheadline']); ?></small>
  <hr>
  <div class="row mb-4">
    <?php if ($admin['aimage']): ?>
      <div class="col-sm-3 text-center">
        <img
          src="uploads/<?php echo htmlentities($admin['aimage']); ?>"
          alt=""
          class="img-fluid rounded mb-3"
          style="max-width:280px;"
        >
      </div>
    <?php endif; ?>
    <div class="<?php echo $admin['aimage'] ? 'col-sm-9' : 'col-12'; ?>">
      <div class="post-content">
        <?php echo nl2br(htmlentities($admin['abio'])); ?>
      </div>
    </div>
  </div>
</div>

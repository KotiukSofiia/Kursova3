<div class="container py-2 mb-4 d-flex flex-column min-vh-100">
  <h1>Коментарі</h1>

  <table class="table table-striped table-hover mt-4">
    <thead class="thead-dark">
      <tr>
        <th>No.</th>
        <th>Дата &amp; Час</th>
        <th>Назва</th>
        <th>Коментувати</th>
        <th>Дія</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($comments)): ?>
        <tr>
          <td colspan="5" class="text-center">Ніяких коментарів не знайдено.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($comments as $idx => $c): ?>
          <tr>
            <td><?php echo $idx + 1; ?></td>
            <td><?php echo htmlentities($c['datetime']); ?></td>
            <td><?php echo htmlentities($c['name']); ?></td>
            <td><?php echo htmlentities($c['comment']); ?></td>
            <td>
              <a href="index.php?controller=comment&action=delete&id=<?php echo $c['id']; ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Ви впевнені, що хочете видалити цей коментар?');">
                Видалити
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
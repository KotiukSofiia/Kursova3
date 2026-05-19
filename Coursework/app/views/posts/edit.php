<?php
// app/views/posts/edit.php
/**
 * Змінні, що приходять із контролера:
 *   $post — асоціативний масив поточного посту
 *   $categories — масив усіх категорій
 *
 * Flash-повідомлення показує header.php, тому тут їх НЕ дублюємо.
 */
?>

<div class="container py-2 mb-4">
    <div class="row">
        <div class="offset-lg-1 col-lg-10" style="min-height:400px;">

            <form action="index.php?controller=post&action=edit&id=<?php echo $post['id']; ?>"
                  method="post" enctype="multipart/form-data">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Редагувати публікацію</h2>
                    </div>
                    <div class="card-body">
                        <!-- Post Title -->
                        <div class="form-group">
                            <label for="PostTitle">Назва публікації:</label>
                            <input class="form-control" type="text" name="PostTitle" id="PostTitle"
                                   value="<?php echo htmlentities($post['title']); ?>">
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <span>Існуюча категорія: 
                                <strong><?php echo htmlentities($post['category']); ?></strong>
                            </span>
                            <br>
                            <label for="CategorySelect">Виберіть категорію</label>
                            <select class="form-control" id="CategorySelect" name="Category">
                                <option value="">-- Виберіть категорію --</option>
                                <?php foreach ($categories as $cat): 
                                    $selected = ($cat['title'] === $post['category']) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo htmlentities($cat['title']); ?>" <?php echo $selected; ?>>
                                        <?php echo htmlentities($cat['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Existing Image -->
                        <?php if ($post['image']): ?>
                        <div class="form-group">
                            <span>Існуюче зображення:</span><br>
                            <img src="uploads/<?php echo htmlentities($post['image']); ?>" 
                                 width="100" height="70" style="object-fit:cover;" alt="Banner">
                        </div>
                        <?php endif; ?>

                        <!-- New Image -->
                        <div class="form-group">
                            <label for="ImageSelect">Виберіть нове зображення (якщо змінюєте):</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="Image" id="ImageSelect">
                                <label class="custom-file-label" for="ImageSelect">Виберіть зображення</label>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group">
                            <label for="PostDescription">Допис:</label>
                            <textarea class="form-control" id="PostDescription" name="PostDescription" rows="8"><?php
                                echo htmlentities($post['post']);
                            ?></textarea>
                        </div>

                        <!-- Кнопки Back / Publish -->
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="index.php?controller=post&action=index" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-arrow-left"></i> Назад до публікацій
                                </a>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <button type="submit" name="Submit" 
                                        class="btn btn-success btn-block">Опублікувати</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
// JS для показу імені вибраного файлу
document.addEventListener('DOMContentLoaded', function(){
    const fileInput = document.getElementById('ImageSelect');
    const fileLabel = fileInput.nextElementSibling;
    fileInput.addEventListener('change', function(e){
        const fileName = e.target.files[0]?.name || 'Select image';
        fileLabel.textContent = fileName;
    });
});
</script>

<div class="container py-2 mb-4">
    <div class="row">
        <div class="offset-lg-1 col-lg-10" style="min-height:400px;">

            <form action="index.php?controller=post&action=create" method="post" enctype="multipart/form-data">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Додати новий допис</h2>
                    </div>
                    <div class="card-body">
                        <!-- Post Title -->
                        <div class="form-group">
                            <label for="PostTitle">Назва допису:</label>
                            <input class="form-control" type="text" name="PostTitle" id="PostTitle" 
                                   placeholder="Введіть заголовок" value="">
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="CategorySelect">Виберіть категорію:</label>
                            <select class="form-control" id="CategorySelect" name="Category">
                                <option value="">-- Виберіть категорію --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlentities($cat['title']); ?>">
                                        <?php echo htmlentities($cat['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Image -->
                        <div class="form-group">
                            <label for="ImageSelect">Виберіть Зображення :</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="Image" 
                                       id="ImageSelect" value="">
                                <label class="custom-file-label" for="ImageSelect">Виберіть зображення</label>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group">
                            <label for="PostDescription">Допис:</label>
                            <textarea class="form-control" id="PostDescription" name="PostDescription" 
                                      rows="8" placeholder="Введіть тут вміст публікації..."></textarea>
                        </div>

                        <!-- Кнопки Back / Publish -->
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="index.php?controller=dashboard&action=index" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-arrow-left"></i> Назад
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
// JS для відображення імені вибраного файлу у custom-file-label
document.addEventListener('DOMContentLoaded', function(){
    const fileInput = document.getElementById('ImageSelect');
    const fileLabel = fileInput.nextElementSibling;
    fileInput.addEventListener('change', function(e){
        const fileName = e.target.files[0]?.name || 'Select image';
        fileLabel.textContent = fileName;
    });
});
</script>

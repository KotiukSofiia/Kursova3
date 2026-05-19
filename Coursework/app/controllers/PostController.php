<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Database.php';

class PostController extends BaseController
{
    /**
     * Список постів (адмінська частина)
     * URL: index.php?controller=post&action=index
     */
    public function index()
    {
        $this->requireLogin();

        $posts = Post::getAll();

        $this->render('posts/index', [
            'posts' => $posts
        ]);
    }

    /**
     * Форма створення нового посту (GET) та обробка Submit (POST)
     * URL: index.php?controller=post&action=create
     */
    public function create()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Зчитуємо дані з форми
            $title     = trim($_POST['PostTitle'] ?? '');
            $category  = trim($_POST['Category'] ?? '');
            $content   = trim($_POST['PostDescription'] ?? '');
            $author    = $_SESSION['UserName'] ?? 'Unknown';
            $imageName = '';
            $dateTime  = strftime('%B-%d-%Y %H:%M:%S', time());

            // Якщо юзер завантажив файл
            if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
                $imageName = $_FILES['Image']['name'];
                $tempPath  = $_FILES['Image']['tmp_name'];

                // Шлях до папки public/uploads
                $targetDir  = __DIR__ . '/../../public/uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetFile = $targetDir . basename($imageName);

                // Перевірка розширення файлу
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes  = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($imageFileType, $allowedTypes)) {
                    $this->setErrorMessage('Дозволено лише файли JPG, JPEG, PNG, GIF.');
                    $this->redirect('post', 'create');
                }

                // Спроба перемістити файл у папку uploads
                if (!move_uploaded_file($tempPath, $targetFile)) {
                    $this->setErrorMessage('Не вдалося завантажити зображення.');
                    $this->redirect('post', 'create');
                }
            }

            // Валідація полів
            if ($title === '') {
                $this->setErrorMessage('Title не може бути порожнім.');
                $this->redirect('post', 'create');
            }
            if (mb_strlen($title) < 5) {
                $this->setErrorMessage('Title повинен мати щонайменше 5 символів.');
                $this->redirect('post', 'create');
            }
            if (mb_strlen($content) > 10000) {
                $this->setErrorMessage('Content повинен бути меншим за 10000 символів.');
                $this->redirect('post', 'create');
            }
            if ($category === '') {
                $this->setErrorMessage('Оберіть категорію.');
                $this->redirect('post', 'create');
            }

            // Створюємо новий пост
            $success = Post::create($title, $category, $author, $imageName, $content, $dateTime);
            if ($success) {
                $lastId = Database::connect()->lastInsertId();
                $this->setSuccessMessage("Post з id: {$lastId} успішно додано.");
                $this->redirect('post', 'index');
            } else {
                $this->setErrorMessage('Сталася помилка при створенні посту.');
                $this->redirect('post', 'create');
            }
        }

        // GET-запит: показуємо форму
        // Дістаємо категорії
        $categories = Category::getAll();
        // Flash зчитувати НЕ потрібно – header сам виведе, якщо є
        $this->render('posts/create', [
            'categories' => $categories
        ]);
    }

    /**
     * Форма редагування посту (GET) та обробка Submit (POST)
     * URL: index.php?controller=post&action=edit&id=123
     */
    public function edit()
    {
        $this->requireLogin();

        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $this->setErrorMessage('Некоректний ID посту.');
            $this->redirect('post', 'index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Зчитуємо нові значення з форми
            $title       = trim($_POST['PostTitle'] ?? '');
            $category    = trim($_POST['Category'] ?? '');
            $content     = trim($_POST['PostDescription'] ?? '');
            $newImageName = null;

            // Якщо обрали новий файл
            if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
                $newImageName = $_FILES['Image']['name'];
                $tempPath     = $_FILES['Image']['tmp_name'];

                // Шлях до папки public/uploads
                $targetDir  = __DIR__ . '/../../public/uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetFile = $targetDir . basename($newImageName);

                // Перевірка розширення
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes  = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($imageFileType, $allowedTypes)) {
                    $this->setErrorMessage('Дозволено лише файли JPG, JPEG, PNG, GIF.');
                    $this->redirect('post', 'edit&id=' . $id);
                }

                // Переміщуємо новий файл
                if (!move_uploaded_file($tempPath, $targetFile)) {
                    $this->setErrorMessage('Не вдалося завантажити нове зображення.');
                    $this->redirect('post', 'edit&id=' . $id);
                }

                // Видаляємо старе зображення з диска
                $oldPost = Post::getById($id);
                if ($oldPost && $oldPost['image']) {
                    $oldFile = __DIR__ . '/../../public/uploads/' . $oldPost['image'];
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }

            // Валідація полів
            if ($title === '') {
                $this->setErrorMessage('Title не може бути порожнім.');
                $this->redirect('post', 'edit&id=' . $id);
            }
            if (mb_strlen($title) < 5) {
                $this->setErrorMessage('Title повинен мати щонайменше 5 символів.');
                $this->redirect('post', 'edit&id=' . $id);
            }
            if (mb_strlen($content) > 10000) {
                $this->setErrorMessage('Content повинен бути меншим за 10000 символів.');
                $this->redirect('post', 'edit&id=' . $id);
            }
            if ($category === '') {
                $this->setErrorMessage('Оберіть категорію.');
                $this->redirect('post', 'edit&id=' . $id);
            }

            // Оновлюємо пост
            $success = Post::update($id, $title, $category, $content, $newImageName);
            if ($success) {
                $this->setSuccessMessage('Post успішно оновлено.');
                $this->redirect('post', 'index');
            } else {
                $this->setErrorMessage('Сталася помилка при оновленні post.');
                $this->redirect('post', 'edit&id=' . $id);
            }
        }

        // GET-запит: отримуємо поточні дані посту
        $post = Post::getById($id);
        if (!$post) {
            $this->setErrorMessage('Post не знайдено.');
            $this->redirect('post', 'index');
        }

        // Дістаємо всі категорії для select
        $categories = Category::getAll();

        // Не читаємо flash тут – header покаже їх автоматично
        $this->render('posts/edit', [
            'post'       => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Видалення посту (GET-параметр id)
     * URL: index.php?controller=post&action=delete&id=123
     */
    public function delete()
    {
        $this->requireLogin();

        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $this->setErrorMessage('Некоректний ID посту.');
            $this->redirect('post', 'index');
        }

        // Видаляємо пост (отримуємо назву зображення, щоб потім unlink)
        $deletedImage = Post::delete($id);
        if ($deletedImage !== false) {
            // Видаляємо файл-зображення з диска
            $filePath = __DIR__ . '/../../public/uploads/' . $deletedImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $this->setSuccessMessage('Post успішно видалено.');
        } else {
            $this->setErrorMessage('Сталася помилка при видаленні post.');
        }

        $this->redirect('post', 'index');
    }

    /**
     * Live-preview посту (публічна частина)
     * URL: index.php?controller=post&action=show&id=123
     */
    public function show()
    {
        // Публічна сторінка — не вимагаємо логіну
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo "<h1>400 Bad Request</h1>";
            exit;
        }

        $post = Post::getById($id);
        if (!$post) {
            http_response_code(404);
            echo "<h1>404 Not Found</h1><p>Post не знайдено.</p>";
            exit;
        }

        // Вивід публічної частини без адмінської шапки
?>
        <!DOCTYPE html>
        <html lang="uk">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlentities($post['title']); ?></title>
            <link rel="stylesheet"
                href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
                crossorigin="anonymous">
            <link rel="stylesheet" href="css/Styles.css">
        </head>

        <body>
            <div class="container py-4">
                <h1><?php echo htmlentities($post['title']); ?></h1>
                <p class="text-muted">
                    <strong>Дата:</strong> <?php echo htmlentities($post['datetime']); ?>
                    &nbsp;|&nbsp;
                    <strong>Категорія:</strong> <?php echo htmlentities($post['category']); ?>
                    &nbsp;|&nbsp;
                    <strong>Автор:</strong> <?php echo htmlentities($post['author']); ?>
                </p>
                <?php if ($post['image']): ?>
                    <div class="mb-4">
                        <img src="uploads/<?php echo htmlentities($post['image']); ?>"
                            alt="Banner Image" class="img-fluid">
                    </div>
                <?php endif; ?>
                <div class="post-content">
                    <?php echo nl2br(htmlentities($post['post'])); ?>
                </div>
            </div>
        </body>

        </html>
<?php
    }
}

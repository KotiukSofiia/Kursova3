<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Category.php';

class BlogController extends BaseController
{
    /**
     * Сторінка списку постів
     * URL: index.php?controller=blog&action=index
     */
    public function index()
    {
        $perPage = 5;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;

        $search    = trim($_GET['search'] ?? '');
        $category  = trim($_GET['category'] ?? '');
        if ($search !== '') {
            $posts = Post::search($search);
            $total = count($posts);
        } elseif ($category !== '') {
            $posts = Post::getByCategory($category);
            $total = count($posts);
        } else {
            $posts = Post::getPaged($offset, $perPage);
            $total = Post::countAll();
        }

        // категорії та останні пости
        $categories  = Category::getAll();
        $recentPosts = Post::getRecent(5);

        // рендеримо public‐шаблон
        $this->render('blog/index', [
            'posts'       => $posts,
            'categories'  => $categories,
            'recentPosts' => $recentPosts,
            'perPage'     => $perPage,
            'totalPosts'  => $total,
            'currentPage' => $page
        ], 'public');
    }

    /**
     * Сторінка одного поста з формою коментарів
     * URL: index.php?controller=blog&action=show&id=123
     */
    public function show()
    {
        // 1) Отримуємо ID
        $postId = isset($_GET['id']) && is_numeric($_GET['id'])
            ? (int)$_GET['id']
            : null;

        if (!$postId) {
            http_response_code(400);
            echo "Bad Request";
            exit;
        }

        // 2) Сесія для контролю швидкості коментування (ваш код)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['guestId'])) {
            $_SESSION['guestId'] = session_id();
        }
        $lastTime = $_SESSION['lastCommentTime'][$postId] ?? 0;
        $now      = time();

        // 3) Обробка POST (ваш код) …
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // … валідація, запис коментара, редірект назад …
            header("Location: index.php?controller=blog&action=show&id={$postId}");
            exit;
        }

        // 4) GET: дістаємо публікацію та додаткові дані
        $post        = Post::getById($postId) ?: die("Публікацію не знайдено");
        $comments    = Comment::getApprovedByPost($postId);
        $categories  = Category::getAll();       // Ось тут уже всі категорії
        $recentPosts = Post::getRecent(5);

        // ————— тепер шукаємо назву категорії в $categories —————
        // Припустимо, у кожному елементі $categories є ['id'] та ['name']
        $catMap = array_column($categories, 'name', 'id');
        $post['category_name'] = $catMap[$post['category']] ?? 'Без категорії';

        // далі передаємо все в шаблон
        $this->render('blog/show', [
            'post'        => $post,
            'comments'    => $comments,
            'categories'  => $categories,
            'recentPosts' => $recentPosts,
            'errorMsg'    => $this->getErrorMessage(),
            'successMsg'  => $this->getSuccessMessage()
        ], 'public');
    }

    /**
     * Асинхронна кінцева точка для JSON-коментарів
     * URL: index.php?controller=blog&action=commentAsync&id=123
     */
    public function commentAsync()
    {
        if (
            ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'XMLHttpRequest'
            || $_SERVER['REQUEST_METHOD'] !== 'POST'
        ) {
            http_response_code(400);
            exit;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['guestId'])) {
            $_SESSION['guestId'] = session_id();
        }

        $postId   = (int)($_GET['id'] ?? 0);
        $lastTime = $_SESSION['lastCommentTime'][$postId] ?? 0;
        $now      = time();
        header('Content-Type: application/json; charset=utf-8');

        if ($now - $lastTime < 120) {
            echo json_encode([
                'success' => false,
                'message' => 'Будь ласка, зачекайте кілька хвилин перед наступним коментарем.'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $name    = trim($data['name']    ?? '');
        $email   = trim($data['email']   ?? '');
        $comment = trim($data['comment'] ?? '');

        $errors = [];
        if ($name === '')    $errors[] = 'Name is required';
        if ($email === '')   $errors[] = 'Email is required';
        if ($comment === '') $errors[] = 'Comment is required';
        if (mb_strlen($comment) > 500) {
            $errors[] = 'Коментар має бути ≤ 500 символів';
        }

        if ($errors) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        $created = Comment::create($postId, $name, $email, $comment);
        if (!$created) {
            echo json_encode([
                'success' => false,
                'message' => 'Не вдалося зберегти коментар. Спробуйте ще раз.'
            ]);
            exit;
        }

        $_SESSION['lastCommentTime'][$postId] = $now;

        echo json_encode([
            'success' => true,
            'comment' => [
                'name'     => htmlentities($name),
                'datetime' => date('F-d-Y H:i:s', $now),
                'comment'  => nl2br(htmlentities($comment))
            ]
        ]);
    }
}

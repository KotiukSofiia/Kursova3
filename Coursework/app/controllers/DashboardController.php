<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Comment.php';

class DashboardController extends BaseController
{
    /**
     * Сторінка «Dashboard»
     * URL: index.php?controller=dashboard&action=index
     */
    public function index()
    {
        $this->requireLogin();

        $errorMsg   = $this->getErrorMessage();
        $successMsg = $this->getSuccessMessage();

        // Дані для лівої панелі (кількості)
        $totalPosts      = Post::countAll();
        $totalCategories = Category::countAll();
        $totalAdmins     = Admin::countAll();
        $totalComments   = Comment::countAll();

        // Дані для «Топ-постів» (останні 6 постів)
        $topPosts = Post::getRecent(6);

        // Передаємо у view
        $this->render('dashboard/index', [
            'errorMsg'        => $errorMsg,
            'successMsg'      => $successMsg,
            'totalPosts'      => $totalPosts,
            'totalCategories' => $totalCategories,
            'totalAdmins'     => $totalAdmins,
            'totalComments'   => $totalComments,
            'topPosts'        => $topPosts
        ]);
    }
}

<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Comment.php';

class CommentController extends BaseController
{
    /**
     * Сторінка «Manage Comments»
     * URL: index.php?controller=comment&action=index
     */
    public function index()
    {
        $this->requireLogin();

        // Отримуємо всі (автоматично схвалені) коментарі
        $comments = Comment::getApproved();

        // Передаємо у view лише масив коментарів
        $this->render('comments/index', [
            'comments' => $comments
        ]);
    }

    /**
     * Дія: Видалити коментар
     * URL: index.php?controller=comment&action=delete&id=123
     */
    public function delete()
    {
        $this->requireLogin();

        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $this->setErrorMessage("Invalid Comment ID");
            return $this->redirect('comment', 'index');
        }

        if (Comment::delete((int)$id)) {
            $this->setSuccessMessage("Коментар успішно видалено!");
        } else {
            $this->setErrorMessage("Не вдалося видалити коментар. Спробуйте ще раз.");
        }
        $this->redirect('comment', 'index');
    }
}
<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Database.php';

class CategoryController extends BaseController
{

    /**
     * Список категорій + обробка POST (додавання нової)
     */
    public function index()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title  = trim($_POST['CategoryTitle'] ?? '');
            $author = $_SESSION['UserName'] ?? 'Unknown';
            date_default_timezone_set('Europe/Kyiv');
            $dateTime = strftime('%B-%d-%Y %H:%M:%S', time());

            // Валідація
            if ($title === '') {
                $this->setErrorMessage('Вкажіть назву категорії.');
                $this->redirect('category');
            }
            if (mb_strlen($title) < 3) {
                $this->setErrorMessage('Назва категорії повинна містити принаймні 3 символи.');
                $this->redirect('category');
            }
            if (mb_strlen($title) > 49) {
                $this->setErrorMessage('Назва категорії повинна бути меншою за 50 символів.');
                $this->redirect('category');
            }

            // Перевірка дубліката
            if (Category::exists($title)) {
                $this->setErrorMessage("Категорія «{$title}» вже існує");
                $this->redirect('category');
            }

            // Додаємо
            $success = Category::create($title, $author, $dateTime);
            if ($success) {
                $lastId = Database::connect()->lastInsertId();
                $this->setSuccessMessage("Категорія з id: «{$title}» успішно додано");
            } else {
                $this->setErrorMessage('Сталася помилка при додаванні категорії. Спробуйте знову.');
            }
            $this->redirect('category');
        }

        // GET-запит: просто виводимо список
        $categories = Category::getAll();
        $this->render('categories/index', [
            'categories' => $categories
        ]);
    }

    /**
     * Видалення категорії (GET-param id)
     */
    public function delete()
    {
        $this->requireLogin();

        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $this->setErrorMessage('Некоректний ID категорії.');
            $this->redirect('category');
        }

        $success = Category::delete(intval($id));
        if ($success) {
            $this->setSuccessMessage('Категорію успішно видалено.');
        } else {
            $this->setErrorMessage('Сталася помилка при видаленні категорії. Спробуйте знову.');
        }
        $this->redirect('category');
    }
}

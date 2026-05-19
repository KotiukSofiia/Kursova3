<?php
// app/models/Category.php

require_once __DIR__ . '/Database.php';

class Category {
    /**
     * Отримати всі категорії у порядку від найновіших до найстаріших
     * @return array
     */
    public static function getAll(): array {
    $pdo = Database::connect();
    $rows = $pdo->query("SELECT * FROM category ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect();
    return $rows;
  }

    /**
     * Перевіряє, чи категорія з таким заголовком уже існує
     * @param string $title
     * @return bool — true, якщо знайдено хоча б один запис із цим title
     */
    public static function exists($title) {
        $pdo = Database::connect();
        $sql = "SELECT COUNT(*) AS cnt FROM category WHERE title = :title";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->execute();
        $result = $stmt->fetch();
        Database::disconnect();
        return ($result && intval($result['cnt']) > 0);
    }

    /**
     * Додати нову категорію
     * @param string $title
     * @param string $author
     * @param string $dateTime
     * @return bool
     */
    public static function create($title, $author, $dateTime) {
        $pdo = Database::connect();
        $sql = "INSERT INTO category (title, author, datetime) 
                VALUES (:title, :author, :datetime)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':author', $author);
        $stmt->bindValue(':datetime', $dateTime);
        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }

    /**
     * Видалити категорію за її ID
     * @param int $id
     * @return bool
     */
    public static function delete($id) {
        $pdo = Database::connect();
        $sql = "DELETE FROM category WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }


    public static function countAll(): int
    {
        $pdo = Database::connect();
        $sql = "SELECT COUNT(*) AS cnt FROM category";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return intval($row['cnt'] ?? 0);
    }
}

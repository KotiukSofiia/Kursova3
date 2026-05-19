<?php
require_once __DIR__ . '/Database.php';

class Comment
{
    /**
     * Повертає всі не схвалені коментарі (status = 'OFF'), найновіші зверху
     * @return array
     */
    public static function getUnapproved(): array
    {
        $pdo = Database::connect();
        $sql = "SELECT * FROM comments WHERE status = 'OFF' ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
     * Повертає всі схвалені коментарі (status = 'ON'), найновіші зверху
     * @return array
     */
    public static function getApproved(): array
    {
        $pdo = Database::connect();
        $sql = "SELECT * FROM comments WHERE status = 'ON' ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
     * Видаляє коментар за id
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }

    /**
     * Повернути схвалені коментарі для конкретного поста
     * @param int $postId
     * @return array
     */
    public static function getApprovedByPost(int $postId): array
    {
        $pdo = Database::connect();
        $sql = "SELECT datetime, name, comment
                  FROM comments
                 WHERE post_id = :pid
                   AND status = 'ON'
              ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pid', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
     * Додати новий коментар (завжди status = 'ON')
     * @param int $postId
     * @param string $name
     * @param string $email
     * @param string $text
     * @return bool
     */
    public static function create(int $postId, string $name, string $email, string $text): bool
    {
        $pdo = Database::connect();
        $sql = "INSERT INTO comments
                    (datetime, name, email, comment, approvedby, status, post_id)
                VALUES
                    (:dt, :name, :email, :comment, '', 'ON', :pid)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':dt',      date('F-d-Y H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':name',    $name,               PDO::PARAM_STR);
        $stmt->bindValue(':email',   $email,              PDO::PARAM_STR);
        $stmt->bindValue(':comment', $text,               PDO::PARAM_STR);
        $stmt->bindValue(':pid',     $postId,             PDO::PARAM_INT);
        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }


    public static function countAll(): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM comments");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return intval($row['cnt'] ?? 0);
    }
}

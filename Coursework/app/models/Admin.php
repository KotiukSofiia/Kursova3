<?php

require_once __DIR__ . '/Database.php';

class Admin
{
    /**
     * Отримати всіх адміністраторів (для сторінки “Manage Admins”)
     */
    public static function getAll(): array
    {
        $db   = Database::connect();
        $stmt = $db->query("
            SELECT 
                id, 
                datetime, 
                username, 
                aname, 
                addedby 
            FROM admins 
            ORDER BY id DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
     * Перевіряє, чи існує адміністратор із таким username
     */
    public static function existsByUsername(string $username): bool
    {
        $db   = Database::connect();
        $sql  = "SELECT COUNT(*) FROM admins WHERE username = :uname";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
        $stmt->execute();
        $count = (int)$stmt->fetchColumn();
        Database::disconnect();
        return $count > 0;
    }

    /**
     * Створює нового адміністратора
     */
    public static function create(string $username, string $password, ?string $name, string $addedBy): bool
    {
        $db = Database::connect();

        // Вставляємо значення для всіх NOT NULL-полів: datetime, username, password, aname, aheadline, abio, aimage, addedby
        $sql = "
            INSERT INTO admins
                (datetime, username, password, aname, aheadline, abio, aimage, addedby)
            VALUES
                (:dt, :username, :pw, :aname, :aheadline, :abio, :aimage, :addedby)
        ";
        $stmt = $db->prepare($sql);

        // Замінюємо strftime() на date(), бо strftime() застаріла
        $dt = date("F-d-Y H:i:s"); // наприклад: «June-06-2025 12:34:56»

        $stmt->bindValue(':dt',       $dt,       PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':pw',       $password, PDO::PARAM_STR);
        $stmt->bindValue(':aname',    $name ?: '',     PDO::PARAM_STR);

        // Оскільки aheadline, abio і aimage у таблиці не мають DEFAULT, просто передаємо ''
        $stmt->bindValue(':aheadline', '', PDO::PARAM_STR);
        $stmt->bindValue(':abio',      '', PDO::PARAM_STR);
        $stmt->bindValue(':aimage',    '', PDO::PARAM_STR);

        $stmt->bindValue(':addedby', $addedBy, PDO::PARAM_STR);

        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }

    /**
     * Видаляє адміністратора за ідентифікатором
     * @param int $id
     * @return bool
     */
    public static function deleteById(int $id): bool
    {
        $db = Database::connect();
        $sql = "DELETE FROM admins WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }

    /**
     * Знаходить адміністратора за username (для показу профілю)
     */
    public static function findByUsername(string $username): ?array
    {
        $db  = Database::connect();
        $sql = "
            SELECT 
                id, username, aname, aheadline, abio, aimage 
            FROM admins 
            WHERE username = :uname 
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $row ?: null;
    }

    /**
     * Знаходить адміністратора за ID (для редагування профілю)
     */
    public static function findById(int $id): ?array
    {
        $db  = Database::connect();
        $sql = "SELECT * FROM admins WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $row ?: null;
    }

    /**
     * Оновлює профіль адміністратора 
     * (якщо $imageName = null, то не змінюємо поле aimage)
     */
    public static function updateProfile(
        int $id,
        string $name,
        string $headline,
        string $bio,
        ?string $imageName
    ): bool {
        $db = Database::connect();

        if ($imageName) {
            $sql = "
                UPDATE admins
                SET aname     = :aname,
                    aheadline = :aheadline,
                    abio      = :abio,
                    aimage    = :aimage
                WHERE id = :id
            ";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':aimage', $imageName, PDO::PARAM_STR);
        } else {
            $sql = "
                UPDATE admins
                SET aname     = :aname,
                    aheadline = :aheadline,
                    abio      = :abio
                WHERE id = :id
            ";
            $stmt = $db->prepare($sql);
        }

        $stmt->bindValue(':aname',     $name,     PDO::PARAM_STR);
        $stmt->bindValue(':aheadline', $headline, PDO::PARAM_STR);
        $stmt->bindValue(':abio',      $bio,      PDO::PARAM_STR);
        $stmt->bindValue(':id',        $id,       PDO::PARAM_INT);

        $success = $stmt->execute();
        Database::disconnect();
        return $success;
    }

    /**
     * Перевіряє комбінацію username+password
     * Повертає масив даних адміністратора або null
     */
    public static function attemptLogin(string $username, string $password): ?array
    {
        $db = Database::connect();
        $sql = "
            SELECT * 
            FROM admins 
            WHERE username = :uname 
              AND password = :pwd 
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
        $stmt->bindValue(':pwd',   $password, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $row ?: null;
    }

     public static function countAll(): int
    {
        $db = Database::connect();
        $sql = "SELECT COUNT(*) AS cnt FROM admins";
        $stmt = $db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return intval($row['cnt'] ?? 0);
    }
}
<?php
// app/models/Post.php

require_once __DIR__ . '/Database.php';

class Post
{
    /**
     * Повертає всі пости у порядку від найновіших до найстаріших
     * @return array
     */
    public static function getAll()
    {
        $pdo = Database::connect();
        $sql = "
            SELECT
                p.id,
                p.title,
                c.title AS category_name,
                p.category,
                p.datetime,
                p.author,
                p.image,
                p.post
            FROM posts AS p
            LEFT JOIN category AS c ON p.category = c.title
            ORDER BY p.id DESC
        ";
        $stmt = $pdo->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $posts;
    }

    /**
     * Повернути пост за id
     * @param int $id
     * @return array|false
     */
     public static function getById(int $id): ?array {
    $pdo = Database::connect();
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
    $stmt->bindValue(':id',$id,PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
    return $row ?: null;
  }

    /**
     * Повернути пости з врахуванням пагінації: OFFSET та LIMIT
     * @param int $offset  — з якого запису починати
     * @param int $limit   — скільки записів вибрати
     * @return array
     */
    public static function getPaged(int $offset, int $limit): array
    {
        $pdo = Database::connect();
        $sql = "
            SELECT
                p.id,
                p.title,
                c.title AS category_name,
                p.category,
                p.datetime,
                p.author,
                p.image,
                p.post
            FROM posts AS p
            LEFT JOIN category AS c ON p.category = c.title
            ORDER BY p.id DESC
            LIMIT :offset, :limit
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
 * Пошук постів за текстом (в заголовку, категорії або самому тексті)
 */
public static function search(string $term): array {
    $pdo = Database::connect();
    $sql = "
        SELECT
            p.id,
            p.title,
            c.title AS category_name,
            p.category,
            p.datetime,
            p.author,
            p.image,
            p.post
        FROM posts AS p
        LEFT JOIN category AS c ON p.category = c.title
        WHERE p.title   LIKE :t
           OR p.category LIKE :t
           OR p.post    LIKE :t
        ORDER BY p.id DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':t', "%{$term}%", PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect();
    return $rows;
}



    /**
     * Повернути всі пости в межах однієї категорії
     * @param string $category
     * @return array
     */
    public static function getByCategory(string $category): array
    {
        $pdo = Database::connect();
        $sql = "
            SELECT
                p.id,
                p.title,
                c.title AS category_name,
                p.category,
                p.datetime,
                p.author,
                p.image,
                p.post
            FROM posts AS p
            LEFT JOIN category AS c ON p.category = c.title
            WHERE p.category = :cat
            ORDER BY p.id DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cat', $category, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
     * Повернути останні N постів у порядку від найновіших до старіших
     * @param int $limit
     * @return array
     */
    public static function getRecent(int $limit = 5): array
    {
        $pdo = Database::connect();
        $sql = "
            SELECT 
                p.id,
                p.title,
                c.title AS category_name,
                p.datetime,
                p.author,
                p.image,
                p.post
            FROM posts AS p
            LEFT JOIN category AS c ON p.category = c.title
            ORDER BY p.id DESC
            LIMIT :limit
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    /**
     * Повернути загальну кількість постів
     * @return int
     */
     public static function countAll(): int {
    $pdo = Database::connect();
    // fetchColumn() поверне перший стовпець першого рядка — а це і є COUNT(*)
    $count = (int)$pdo
      ->query("SELECT COUNT(*) FROM posts")
      ->fetchColumn();
    Database::disconnect();
    return $count;
}

    /**
     * Повертає кількість схвалених коментарів для даного поста
     * @param int $postId
     * @return int
     */
    public static function countApprovedComments(int $postId): int
    {
        $pdo = Database::connect();
        $sql = "SELECT COUNT(*) AS cnt 
                  FROM comments 
                 WHERE post_id = :pid 
                   AND status = 'ON'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pid', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return intval($row['cnt'] ?? 0);
    }

    /**
     * Повертає кількість не схвалених коментарів (status = 'OFF') для даного поста
     * @param int $postId
     * @return int
     */
    public static function countUnapprovedComments(int $postId): int
    {
        $pdo = Database::connect();
        $sql = "SELECT COUNT(*) AS cnt 
                  FROM comments 
                 WHERE post_id = :pid 
                   AND status = 'OFF'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pid', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        return intval($row['cnt'] ?? 0);
    }

    public static function paginate(int $offset, int $limit): array
    {
        $pdo = Database::connect();
        $sql = "SELECT p.*, c.title AS category_name 
            FROM posts p 
            LEFT JOIN category c ON p.category = c.title
            ORDER BY p.id DESC
            LIMIT :offset, :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Database::disconnect();
        return $rows;
    }

    public static function byCategory(string $category): array {
    $pdo  = Database::connect();
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE category = :cat ORDER BY id DESC");
    $stmt->bindValue(':cat', $category, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect();
    return $rows;
  }


  public static function create(
        string $title,
        string $category,
        string $author,
        string $imageName,
        string $content,
        string $dateTime
    ): bool {
        $pdo = Database::connect();
        $sql = "INSERT INTO posts
                  (title, category, author, image, post, datetime)
                VALUES
                  (:title, :category, :author, :image, :post, :dt)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title',    $title);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':author',   $author);
        $stmt->bindValue(':image',    $imageName);
        $stmt->bindValue(':post',     $content);
        $stmt->bindValue(':dt',       $dateTime);
        $ok = $stmt->execute();
        Database::disconnect();
        return $ok;
    }

    public static function update(
        int $id,
        string $title,
        string $category,
        string $content,
        ?string $newImage = null
    ): bool {
        $pdo = Database::connect();
        if ($newImage !== null) {
            $sql = "UPDATE posts
                       SET title    = :title,
                           category = :category,
                           post     = :post,
                           image    = :image
                     WHERE id = :id";
        } else {
            $sql = "UPDATE posts
                       SET title    = :title,
                           category = :category,
                           post     = :post
                     WHERE id = :id";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title',    $title);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':post',     $content);
        $stmt->bindValue(':id',       $id, PDO::PARAM_INT);
        if ($newImage !== null) {
            $stmt->bindValue(':image', $newImage);
        }
        $ok = $stmt->execute();
        Database::disconnect();
        return $ok;
    }

     public static function delete(int $id)
    {
        // спершу дістанемо назву файлу
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            Database::disconnect();
            return false;
        }
        $imageName = $row['image'];

        // потім сам delete
        $del = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $del->bindValue(':id', $id, PDO::PARAM_INT);
        $ok = $del->execute();
        Database::disconnect();

        return $ok ? $imageName : false;
    }

}

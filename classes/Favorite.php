<?php
require_once __DIR__ . '/../core/Database.php';

class Favorite
{
    public static function add(int $userId, int $rentalId): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "INSERT IGNORE INTO favorites (user_id, rental_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$userId, $rentalId]);
    }

    public static function remove(int $userId, int $rentalId): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "DELETE FROM favorites WHERE user_id = ? AND rental_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$userId, $rentalId]);
    }

    public static function isFavorite(int $userId, int $rentalId): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT 1 FROM favorites WHERE user_id = ? AND rental_id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $rentalId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function findUserFavorites(int $userId): array
    {
        $pdo = Database::getInstance()->getConnection();

       $sql = "SELECT r.*
                FROM favorites f
                INNER JOIN rentals r ON r.id = f.rental_id
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

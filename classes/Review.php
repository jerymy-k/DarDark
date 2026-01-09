<?php
require_once __DIR__ . '/../core/Database.php';

class Review
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): int
    {
        $rentalId = (int)($data['rental_id'] ?? 0);
        $userId   = (int)($data['user_id'] ?? 0);
        $rating   = (int)($data['rating'] ?? 0);
        $comment  = trim((string)($data['comment'] ?? ''));

        if ($comment === '' || strlen($comment) < 3) {
            throw new Exception("Comment is too short.");
        }

        $sql = "INSERT INTO reviews (rental_id, user_id, rating, comment)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rentalId, $userId, $rating, $comment]);

        return (int)$this->db->lastInsertId();
    }

    public static function findByRental(int $rentalId): array
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT r.id, r.rating, r.comment, r.created_at,
                       u.name AS user_name
                FROM reviews r
                INNER JOIN users u ON u.id = r.user_id
                WHERE r.rental_id = ?
                ORDER BY r.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$rentalId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAverageRating(int $rentalId): float
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT AVG(rating) FROM reviews WHERE rental_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$rentalId]);

        $avg = $stmt->fetchColumn();
        return $avg ? (float)$avg : 0.0;
    }

    public static function canReview(int $userId, int $rentalId): bool
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT 1
                FROM bookings
                WHERE user_id = ?
                  AND rental_id = ?
                  AND status = 'CONFIRMED'
                  AND end_date < CURDATE()
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $rentalId]);

        return (bool)$stmt->fetchColumn();
    }

    public static function alreadyReviewed(int $userId, int $rentalId): bool
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "SELECT 1 FROM reviews WHERE user_id = ? AND rental_id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $rentalId]);

        return (bool)$stmt->fetchColumn();
    }
}

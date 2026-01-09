<?php
require_once __DIR__ . '/../core/Database.php';

class Statistics
{
    public static function stats(): array
    {
        $pdo = Database::getInstance()->getConnection();

        $users = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $activeUsers = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE is_active = 1")->fetchColumn();

        $rentals = (int) $pdo->query("SELECT COUNT(*) FROM rentals")->fetchColumn();
        $activeRentals = (int) $pdo->query("SELECT COUNT(*) FROM rentals WHERE is_active = 1")->fetchColumn();

        $bookings = (int) $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

        $revenue = (float) $pdo->query("
            SELECT COALESCE(SUM(total_price),0)
            FROM bookings
            WHERE status='CONFIRMED'
        ")->fetchColumn();

        return [
            'users' => $users,
            'activeUsers' => $activeUsers,
            'rentals' => $rentals,
            'activeRentals' => $activeRentals,
            'bookings' => $bookings,
            'revenue' => $revenue,
        ];
    }
    public static function getTopRentals(int $limit = 10): array
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "
          SELECT r.title, SUM(b.total_price) AS revenue
          FROM bookings b
          INNER JOIN rentals r ON b.rental_id = r.id
          WHERE b.status = 'CONFIRMED'
          GROUP BY r.id
          ORDER BY revenue DESC
          LIMIT ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

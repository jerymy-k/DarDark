<?php
require_once __DIR__ . '/../core/Database.php';

class Statistics
{
    public static function getTotalUsers(): int
    {
        $pdo = Database::getInstance()->getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public static function getTotalRentals(): int
    {
        $pdo = Database::getInstance()->getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM rentals")->fetchColumn();
    }

    public static function getTotalBookings(): int
    {
        $pdo = Database::getInstance()->getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    }

    public static function getTotalRevenue(): float
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT COALESCE(SUM(total_price),0)
                FROM bookings
                WHERE status = 'CONFIRMED'";
        return (float)$pdo->query($sql)->fetchColumn();
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

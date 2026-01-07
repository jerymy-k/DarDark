<?php
require_once __DIR__ . '/../core/Database.php';

class AdminManager
{
    public static function allUsers(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("SELECT id, name, email, role, is_active, created_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function setUserActive(int $userId, int $isActive): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        return $stmt->execute([$isActive, $userId]);
    }

    public static function changeUserRole(int $userId, string $role): bool
    {
        $allowed = ['ADMIN', 'HOST', 'TRAVELER'];
        if (!in_array($role, $allowed, true))
            return false;

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $userId]);
    }

    public static function allRentals(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT r.id, r.title, r.city, r.price_per_night, r.status, r.is_active, r.created_at,
                       u.name AS host_name, u.email AS host_email
                FROM rentals r
                INNER JOIN users u ON r.host_id = u.id
                ORDER BY r.id DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function setRentalActive(int $rentalId, int $isActive): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("UPDATE rentals SET is_active = ? WHERE id = ?");
        return $stmt->execute([$isActive, $rentalId]);
    }

   
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
}

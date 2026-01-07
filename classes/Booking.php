<?php
declare(strict_types=1);

require_once __DIR__ . "/../core/Database.php";

class BookingException extends Exception
{
}

interface CancelPolicy
{
    public function canCancel(array $booking, int $actorId): bool;
}

class AdminCancelPolicy implements CancelPolicy
{
    public function canCancel(array $booking, int $actorId): bool
    {
        return true; // admin can cancel any booking
    }
}

class UserCancelPolicy implements CancelPolicy
{
    public function canCancel(array $booking, int $actorId): bool
    {
        return (int) $booking['user_id'] === $actorId; // only own booking
    }
}

class Booking
{
    private PDO $db;

    // attributes (as requested)
    private ?int $id = null;
    private int $rental_id;
    private int $user_id;
    private string $start_date;
    private string $end_date;
    private float $total_price;
    private string $status;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ✅ create booking
    public function create(array $data): int
    {
        $rentalId = (int) ($data['rental_id'] ?? 0);
        $userId = (int) ($data['user_id'] ?? 0);
        $start = (string) ($data['start_date'] ?? '');
        $end = (string) ($data['end_date'] ?? '');
        $pricePerNight = (float) ($data['price_per_night'] ?? 0);

        if ($rentalId <= 0 || $userId <= 0) {
            throw new BookingException("Invalid booking data.");
        }

        if (!$start || !$end) {
            throw new BookingException("Please choose start and end dates.");
        }

        $startDT = new DateTime($start);
        $endDT = new DateTime($end);

        if ($endDT <= $startDT) {
            throw new BookingException("End date must be after start date.");
        }

        // ✅ conflict check
        self::checkAvailability($rentalId, $start, $end);

        // ✅ total price = nights * price_per_night
        $nights = (int) $startDT->diff($endDT)->days;
        $total = $nights * $pricePerNight;

        $sql = "INSERT INTO bookings (rental_id, user_id, start_date, end_date, total_price, status)
                VALUES (?, ?, ?, ?, ?, 'CONFIRMED')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rentalId, $userId, $start, $end, $total]);

        return (int) $this->db->lastInsertId();
    }

    // ✅ polymorphism cancel (Admin vs User)
    public static function cancel(int $bookingId, CancelPolicy $policy, int $actorId): bool
    {
        $conn = Database::getInstance()->getConnection();

        $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            throw new BookingException("Booking not found.");
        }

        if ($booking['status'] === 'CANCELLED') {
            throw new BookingException("This booking is already cancelled.");
        }

        if (!$policy->canCancel($booking, $actorId)) {
            throw new BookingException("You are not allowed to cancel this booking.");
        }

        $up = $conn->prepare("UPDATE bookings SET status = 'CANCELLED' WHERE id = ?");
        return $up->execute([$bookingId]);
    }

    // ✅ list traveler bookings
    public static function findUserBookings(int $userId): array
    {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT b.*, r.title, r.city, r.cover_path
                FROM bookings b
                JOIN rentals r ON r.id = b.rental_id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ list rental bookings
    public static function findRentalBookings(int $rentalId): array
    {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM bookings
                WHERE rental_id = ?
                ORDER BY start_date ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rentalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ check no overlap (conflict logic)
    public static function checkAvailability(int $rentalId, string $startDate, string $endDate): bool
    {
        $conn = Database::getInstance()->getConnection();

        // overlap rule:
        // conflict exists if NOT (existing_end <= new_start OR existing_start >= new_end)
        $sql = "SELECT COUNT(*) FROM bookings
                WHERE rental_id = ?
                  AND status = 'CONFIRMED'
                  AND NOT (end_date <= ? OR start_date >= ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rentalId, $startDate, $endDate]);

        $count = (int) $stmt->fetchColumn();
        if ($count > 0) {
            throw new BookingException("This rental is already booked for these dates.");
        }

        return true;
    }
    public static function getById(int $id): ?array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public static function findAll(): array
    {
        $pdo = Database::getInstance()->getConnection();

        $sql = "
      SELECT b.*, r.title, u.email AS user_email
      FROM bookings b
      INNER JOIN rentals r ON b.rental_id = r.id
      INNER JOIN users u ON b.user_id = u.id
      ORDER BY b.id DESC
    ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

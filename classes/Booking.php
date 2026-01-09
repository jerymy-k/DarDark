<?php
declare(strict_types=1);

require_once __DIR__ . "/../core/Database.php";

class BookingException extends Exception
{
}
class Booking
{
    private PDO $db;

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

    public function create(array $data): int
    {
        $rentalId = (int) ($data['rental_id']);
        $userId = (int) ($data['user_id']);
        $start = (string) ($data['start_date']);
        $end = (string) ($data['end_date']);
        $pricePerNight = (float) ($data['price_per_night']);

        $startDT = new DateTime($start);
        $endDT = new DateTime($end);

        if ($endDT <= $startDT) {
            throw new BookingException("End date must be after start date.");
        }

        self::checkAvailability($rentalId, $start, $end);

        $nights = (int) $startDT->diff($endDT)->days;
        $total = $nights * $pricePerNight;

        $sql = "INSERT INTO bookings (rental_id, user_id, start_date, end_date, total_price, status)
                VALUES (?, ?, ?, ?, ?, 'CONFIRMED')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rentalId, $userId, $start, $end, $total]);

        return (int) $this->db->lastInsertId();
    }

    public static function cancel(int $bookingId): bool
    {
        $conn = Database::getInstance()->getConnection();

        $up = $conn->prepare("UPDATE bookings SET status = 'CANCELLED' WHERE id = ?");
        return $up->execute([$bookingId]);
    }

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

    public static function checkAvailability(int $rentalId, string $startDate, string $endDate): bool
    {
        $conn = Database::getInstance()->getConnection();

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
        return $row;
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

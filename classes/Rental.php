<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Session.php';

class Rental
{
    private PDO $db;
    public int $hostId;
    public string $title;
    public string $city;
    public string $address;
    public float $pricePerNight;
    public int $maxGuests;
    public string $description;
    public string $coverPath;

    public function __construct(int $hostId, string $title, string $city, string $address, float $pricePerNight, int $maxGuests, string $description, string $coverPath)
    {
        $this->db = Database::getInstance()->getConnection();
        $this->hostId = $hostId;
        $this->title = $title;
        $this->city = $city;
        $this->address = $address;
        $this->pricePerNight = $pricePerNight;
        $this->maxGuests = $maxGuests;
        $this->description = $description;
        $this->coverPath = $coverPath;
    }
    public function createRental(): bool
    {
        $sql = "INSERT INTO rentals (
            host_id,
            title,
            city,
            address,
            price_per_night,
            max_guests,
            description,
            cover_path
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $this->hostId,
            $this->title,
            $this->city,
            $this->address,
            $this->pricePerNight,
            $this->maxGuests,
            $this->description,
            $this->coverPath
        ]);
    }
    public function updateRental(int $id, string $status): bool
    {
        $sql = "UPDATE rentals SET
        title = ?,
        city = ?,
        address = ?,
        price_per_night = ?,
        max_guests = ?,
        description = ?,
        status = ?,
        cover_path = ?
        WHERE id = ? AND host_id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $this->title,
            $this->city,
            $this->address,
            $this->pricePerNight,
            $this->maxGuests,
            $this->description,
            $status,
            $this->coverPath,
            $id,
            $this->hostId
        ]);
    }
    public static function deleteByIdAndHost(int $rentalId, int $hostId): bool
    {
        $conn = Database::getInstance()->getConnection();

        $sql = "DELETE FROM rentals WHERE id = ? AND host_id = ?";
        $stmt = $conn->prepare($sql);

        return $stmt->execute([$rentalId, $hostId]);
    }
    public static function getAllByHost(int $hostId): ?array
    {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM rentals WHERE host_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$hostId]);
        return $stmt->fetchAll();
    }
    public static function getAll(): array
    {
        $conn = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM rentals WHERE status = 'ACTIVE' ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

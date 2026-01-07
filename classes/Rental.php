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
    public static function getById(int $rentalId): ?array
    {
        $conn = Database::getInstance()->getConnection();

        $sql = "SELECT rentals.*, users.name 
            FROM rentals
            INNER JOIN users ON rentals.host_id = users.id
            WHERE rentals.id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$rentalId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;  // ila mal9ach => null
    }

    public static function search(array $criteria, int $page = 1): array
    {
        $pdo = Database::getInstance()->getConnection();

        $limit = 9;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM rentals WHERE status = 'ACTIVE' AND is_active = 1";
        $params = [];

        if (!empty($criteria['city'])) {
            $sql .= " AND city LIKE ?";
            $params[] = '%' . $criteria['city'] . '%';
        }

        if (!empty($criteria['min_price'])) {
            $sql .= " AND price_per_night >= ?";
            $params[] = $criteria['min_price'];
        }

        if (!empty($criteria['max_price'])) {
            $sql .= " AND price_per_night <= ?";
            $params[] = $criteria['max_price'];
        }

        if (!empty($criteria['guests'])) {
            $sql .= " AND max_guests >= ?";
            $params[] = $criteria['guests'];
        }

        $sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM rentals
            WHERE status = 'ACTIVE' AND is_active = 1
            ORDER BY created_at DESC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}

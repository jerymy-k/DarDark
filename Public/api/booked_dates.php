<?php
declare(strict_types=1);

require_once __DIR__ . "/../../core/Database.php";

header('Content-Type: application/json; charset=utf-8');

$rentalId = (int)($_GET['rental_id'] ?? 0);
if ($rentalId <= 0) {
    echo json_encode([]);
    exit;
}

$pdo = Database::getInstance()->getConnection();


$stmt = $pdo->prepare("
    SELECT start_date, end_date
    FROM bookings
    WHERE rental_id = ?
      AND status = 'CONFIRMED'
");
$stmt->execute([$rentalId]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


$ranges = [];
foreach ($rows as $r) {
    $ranges[] = [
        'from' => $r['start_date'],
        'to'   => $r['end_date'],
    ];
}

echo json_encode($ranges);

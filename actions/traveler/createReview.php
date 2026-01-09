<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Review.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if (Session::get('role') !== 'TRAVELER') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/traveler/dashboard.php");
    exit();
}

$userId   = (int)Session::get('id');
$rentalId = (int)($_POST['rental_id'] ?? 0);
$rating   = (int)($_POST['rating'] ?? 0);
$comment  = trim($_POST['comment'] ?? '');

if ($rentalId <= 0) {
    Session::set('errer', "Invalid rental.");
    header("Location: /airbnb-php-oop/Public/traveler/dashboard.php");
    exit();
}

try {
    if (!Review::canReview($userId, $rentalId)) {
        throw new Exception("You can review only after finishing your stay.");
    }

    if (Review::alreadyReviewed($userId, $rentalId)) {
        throw new Exception("You already reviewed this rental.");
    }

    $review = new Review();
    $review->create([
        'rental_id' => $rentalId,
        'user_id' => $userId,
        'rating' => $rating,
        'comment' => $comment,
    ]);

    Session::set('succes', "Review added successfully ✅");
    header("Location: /airbnb-php-oop/Public/traveler/rental-details.php?id=" . $rentalId);
    exit();

} catch (Exception $e) {
    Session::set('errer', $e->getMessage());
    header("Location: /airbnb-php-oop/Public/traveler/rental-details.php?id=" . $rentalId);
    exit();
}

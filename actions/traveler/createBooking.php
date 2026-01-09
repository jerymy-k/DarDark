<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Booking.php";
require_once __DIR__ . "/../../classes/Rental.php";
require_once __DIR__ . "/../../classes/Mailer.php";
require_once __DIR__ . "/../../classes/User.php";



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

$userId = (int) Session::get('id');
$rentalId = (int) ($_POST['rental_id'] ?? 0);
$start = trim($_POST['start_date'] ?? '');
$end = trim($_POST['end_date'] ?? '');

$rental = Rental::getById($rentalId);

try {
    $bookingObj = new Booking();

    $newId = $bookingObj->create([
        'rental_id' => $rentalId,
        'user_id' => $userId,
        'start_date' => $start,
        'end_date' => $end,
        'price_per_night' => (float) $rental['price_per_night'],
    ]);

    $booking = Booking::getById($newId);

    $rental = Rental::getById((int) $booking['rental_id']);
    $traveler = User::getUserById((int) $booking['user_id']);
    $host = User::getUserById((int) $rental['host_id']);

    $mailer = new Mailer();
    $mailer->sendBookingConfirmation($booking, $traveler, $host, $rental);

    Session::set('succes', "Booking created successfully.");
    header("Location: /airbnb-php-oop/Public/traveler/my-bookings.php");
    exit();

} catch (BookingException $e) {
    Session::set('errer', $e->getMessage());
    header("Location: /airbnb-php-oop/Public/traveler/rental-details.php?id=" . $rentalId);
    exit();
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../core/Session.php";
require_once __DIR__ . "/../classes/Booking.php";
require_once __DIR__ . "/../classes/Mailer.php";
require_once __DIR__ . "/../classes/User.php";
require_once __DIR__ . "/../classes/Rental.php";
Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/index.php");
    exit();
}

$bookingId = (int) ($_POST['booking_id'] ?? 0);
$actorId = (int) Session::get('id');
$role = (string) Session::get('role');

try {

    Booking::cancel($bookingId);
    $booking = Booking::getById($bookingId);
    $rental = Rental::getById((int) $booking['rental_id']);

    $traveler = User::getUserById((int) $booking['user_id']);
    $host = User::getUserById((int) $rental['host_id']);


    $mailer = new Mailer();
    $mailer->sendBookingCancellation($booking, $traveler, $host, $rental);
    Session::set('succes', "Booking cancelled successfully.");
    header("Location: /airbnb-php-oop/Public/traveler/my-bookings.php");
    exit();

} catch (BookingException $e) {
    Session::set('errer', $e->getMessage());
    header("Location: /airbnb-php-oop/Public/traveler/my-bookings.php");
    exit();
}

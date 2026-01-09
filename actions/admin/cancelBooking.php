<?php
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Booking.php";

Session::start();

if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$bookingId = (int)($_POST['booking_id'] ?? 0);

Booking::cancel($bookingId);

header("Location: /airbnb-php-oop/Public/admin/bookings.php");
exit();

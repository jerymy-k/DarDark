<?php
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/AdminManager.php";

Session::start();
if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$rentalId = (int)($_POST['rental_id'] ?? 0);
$isActive = (int)($_POST['is_active'] ?? 0);

if ($rentalId <= 0) {
    Session::set('errer', 'Invalid rental.');
    header("Location: /airbnb-php-oop/Public/admin/rentals.php");
    exit();
}

AdminManager::setRentalActive($rentalId, $isActive);
Session::set('succes', 'Rental updated.');
header("Location: /airbnb-php-oop/Public/admin/rentals.php");

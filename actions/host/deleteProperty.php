<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Rental.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if (Session::get('role') !== 'HOST') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/host/my-properties.php");
    exit();
}

$rentalId = (int)($_POST['id']);
$hostId = (int)Session::get('id');

$deleted = Rental::deleteByIdAndHost($rentalId, $hostId);

if ($deleted) {
    Session::set('succes', 'Property deleted successfully.');
} else {
    Session::set('errer', 'Delete failed or you are not allowed to delete this property.');
}

header("Location: /airbnb-php-oop/Public/host/my-properties.php");
exit();

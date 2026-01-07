<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Favorite.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/traveler/dashboard.php");
    exit();
}

$userId = (int) Session::get('id');
$rentalId = (int) ($_POST['rental_id'] ?? 0);
$back = $_POST['back'] ?? '/airbnb-php-oop/Public/traveler/dashboard.php';



$isFav = Favorite::isFavorite($userId, $rentalId);

if ($isFav) {
    Favorite::remove($userId, $rentalId);
    Session::set('succes', 'Removed from favorites.');
} else {
    Favorite::add($userId, $rentalId);
    Session::set('succes', 'Added to favorites.');
}

header("Location: $back");
exit();

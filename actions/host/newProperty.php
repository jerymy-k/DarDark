<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Rental.php";

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/host/add-property.php");
    exit();
}
$hostId = Session::get('id');
$title = $_POST['title'];
$city = $_POST['city'];
$maxGuests = $_POST['guests'];
$address = $_POST['address'];
$pricePerNight = $_POST['price_per_night'];
$coverPAth = $_POST['cover_image'];
$description = $_POST['description'];


$rentalObj = new Rental($hostId , $title , $city , $address , $pricePerNight , $maxGuests , $description , $coverPAth);
$isCreate = $rentalObj->createRental();
if($isCreate){
    Session::set('succes' , 'Your property is added with succes');
    
}else{
    Session::set('errer' , 'Something went wrong');
}
header("Location: /airbnb-php-oop/Public/host/add-property.php");
    exit();
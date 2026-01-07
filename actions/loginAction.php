<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../core/Session.php";
require_once __DIR__ . "/../classes/User.php";

Session::start();
$redirectLogin = "/airbnb-php-oop/Public/login.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: {$redirectLogin}");
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];


$userObj = new User(null, $email, $password, null);
$isLogedIn = $userObj->logIn();
if (!$isLogedIn) {
    header("Location: {$redirectLogin}");
    exit();
} else {
    $role = Session::get('role');

    if ($role === 'TRAVELER') {
        header('Location: /airbnb-php-oop/Public/traveler/dashboard.php');
        exit();
    }

    if ($role === 'HOST') {
        header('Location: /airbnb-php-oop/Public/host/dashboard.php');
        exit();
    }
    
    if ($role === 'ADMIN') {
        header('Location: /airbnb-php-oop/Public/admin/dashboard.php');
        exit();
    }
    
}
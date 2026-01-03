<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../core/Session.php";
require_once __DIR__ . "/../classes/User.php";

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/signup.php");
    exit();
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];
$role = $_POST['role'];

$redirectSignup = "/airbnb-php-oop/Public/signup.php";
$redirectLogin = "/airbnb-php-oop/Public/login.php";

if (strlen($name) < 4) {
    Session::set("errer", "Name must contain at least 4 characters.");
    header("Location: {$redirectSignup}");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Session::set("errer", "Please enter a valid email address.");
    header("Location: {$redirectSignup}");
    exit();
}

if (strlen($password) < 6) {
    Session::set("errer", "Password must contain at least 6 characters.");
    header("Location: {$redirectSignup}");
    exit();
}

if ($password !== $confirmPassword) {
    Session::set("errer", "The passwords you entered do not match. Please try again.");
    header("Location: {$redirectSignup}");
    exit();
}


$userObj = new User($name, $email, $password, $role);
$isSignUp = $userObj->signUp();

if ($isSignUp) {
    Session::set("succes", "Your account has been created successfully. You can now log in.");
    header("Location: {$redirectLogin}");
    exit();
}

Session::set("errer", "An account with this email already exists.");
header("Location: {$redirectSignup}");
exit();

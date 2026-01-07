<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../core/Session.php";
require_once __DIR__ . "/../classes/User.php";

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /airbnb-php-oop/Public/index.php");
    exit();
}
$redirectPath = '';
$role = Session::get('role');
$id = Session::get('id');
$user;
if ($role === 'HOST') {
    $redirectPath = '/airbnb-php-oop/Public/host/host-profile.php';
} else if ($role === 'TRAVELER') {
    $redirectPath = '/airbnb-php-oop/Public/traveler/traveler-profile.php';
} else if ($role === 'ADMIN') {
    $redirectPath = '/airbnb-php-oop/Public/admin/admin-profile.php';
}
$name = $_POST['name'];
$email = $_POST['email'];
$oldPassword = $_POST['old_password'] ?? "";
$newPassword = $_POST['new_password'] ?? "";
$confirmNewPassword = $_POST['confirm_new_password'] ?? "";
if (strlen($name) < 4) {
    Session::set("errer", "Name must contain at least 4 characters.");
    header("Location: {$redirectPath}");
    exit();

} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Session::set("errer", "Please enter a valid email address.");
    header("Location: {$redirectPath}");
    exit();

}
if ($oldPassword !== "") {
    if (User::passwordCheaker($email, $oldPassword)) {
        if (strlen($newPassword) < 6) {
            Session::set("errer", "Password must contain at least 6 characters.");
            header("Location: {$redirectPath}");
            exit();
        } else if ($newPassword !== $confirmNewPassword) {
            Session::set("errer", "The passwords you entered do not match. Please try again.");
            header("Location: {$redirectPath}");
            exit();
        }
        $user = new User($name, $email, $newPassword, null);
        $user->editProfile($id);
        Session::set('succes', 'Your information updated with succes');
        header("Location: {$redirectPath}");
        exit();
    } else {
        Session::set('errer', 'Please cheak your old password and try again.');
        header("Location: {$redirectPath}");
        exit();
    }
}
if ($oldPassword === "") {
    $user = new User($name, $email, null, null);
    $user->editProfile($id);
    Session::set('succes', 'Your Name||Email updated with succes');
    header("Location: {$redirectPath}");
    exit();
}
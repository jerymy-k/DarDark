<?php
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/AdminManager.php";

Session::start();
if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$userId = (int)($_POST['user_id'] ?? 0);
$isActive = (int)($_POST['is_active'] ?? 0);

if ($userId <= 0) {
    Session::set('errer', 'Invalid user.');
    header("Location: /airbnb-php-oop/Public/admin/users.php");
    exit();
}

AdminManager::setUserActive($userId, $isActive);
Session::set('succes', 'User updated.');
header("Location: /airbnb-php-oop/Public/admin/users.php");

<?php
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/AdminManager.php";

Session::start();
if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$userId = (int)($_POST['user_id'] ?? 0);
$role = trim($_POST['role'] ?? '');

if ($userId <= 0 || $role === '') {
    Session::set('errer', 'Invalid data.');
    header("Location: /airbnb-php-oop/Public/admin/users.php");
    exit();
}

if (!AdminManager::changeUserRole($userId, $role)) {
    Session::set('errer', 'Role not allowed.');
} else {
    Session::set('succes', 'Role updated.');
}

header("Location: /airbnb-php-oop/Public/admin/users.php");

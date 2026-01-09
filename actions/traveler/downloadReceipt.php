<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Booking.php";
require_once __DIR__ . "/../../classes/Rental.php";
require_once __DIR__ . "/../../classes/User.php";

require_once __DIR__ . "/../../vendor/autoload.php";

use Dompdf\Dompdf;

Session::start();
if (!Session::get('id')) { header("Location: /airbnb-php-oop/Public/login.php"); exit(); }

$userId = (int)Session::get('id');
$bookingId = (int)($_GET['id'] ?? 0);

$booking = Booking::getById($bookingId);

$rental = Rental::getById((int)$booking['rental_id']);
$traveler = User::getUserById((int)$booking['user_id']);
$host = User::getUserById((int)$rental['host_id']);

function h($v){ return htmlspecialchars((string)$v); }

$html = '
<!doctype html>
<html><head><meta charset="utf-8">
<style>
body{font-family: DejaVu Sans, Arial, sans-serif; font-size:12px;}
.card{border:1px solid #ddd; padding:16px; border-radius:10px;}
h1{margin:0 0 10px;}
.small{color:#666;}
table{width:100%; border-collapse: collapse; margin-top:10px;}
td{padding:8px; border-bottom:1px solid #eee;}
</style>
</head><body>
  <div class="card">
    <h1>Receipt • DarDark</h1>
    <div class="small">Booking ID: #'.(int)$booking['id'].' • Status: '.h($booking['status']).'</div>

    <table>
      <tr><td><b>Traveler</b></td><td>'.h($traveler['name']).' ('.h($traveler['email']).')</td></tr>
      <tr><td><b>Host</b></td><td>'.h($host['name']).' ('.h($host['email']).')</td></tr>
      <tr><td><b>Rental</b></td><td>'.h($rental['title']).' — '.h($rental['city']).'</td></tr>
      <tr><td><b>Dates</b></td><td>'.h($booking['start_date']).' → '.h($booking['end_date']).'</td></tr>
      <tr><td><b>Total price</b></td><td>'.h($booking['total_price']).'</td></tr>
    </table>

    <p class="small" style="margin-top:12px;">Thank you for using DarDark.</p>
  </div>
</body></html>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = "receipt_booking_" . (int)$booking['id'] . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit();

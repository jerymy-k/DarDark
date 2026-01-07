<?php
// src/Mailer.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer
{
    private array $cfg;

    public function __construct()
    {
        $this->cfg = require __DIR__ . '/../config/mail.php';
    }

    private function baseMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $this->cfg['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $this->cfg['username'];
        $mail->Password   = $this->cfg['password'];
        $mail->SMTPSecure = $this->cfg['encryption']; // 'tls'
        $mail->Port       = (int)$this->cfg['port'];

        $mail->setFrom($this->cfg['from_email'], $this->cfg['from_name']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        return $mail;
    }

    public function sendBookingConfirmation(array $booking, array $traveler, array $host, array $rental): bool
    {
        $subject = "Booking confirmed — " . ($rental['title'] ?? 'Rental');

        // Traveler email
        $travelerHtml = $this->renderTemplate('booking_confirm_traveler.php', compact('booking', 'traveler', 'host', 'rental'));
        $ok1 = $this->send($traveler['email'], $subject, $travelerHtml);

        // Host email
        $hostSubject = "New booking received — " . ($rental['title'] ?? 'Rental');
        $hostHtml = $this->renderTemplate('booking_confirm_host.php', compact('booking', 'traveler', 'host', 'rental'));
        $ok2 = $this->send($host['email'], $hostSubject, $hostHtml);

        return $ok1 && $ok2;
    }

    public function sendBookingCancellation(array $booking, array $traveler, array $host, array $rental): bool
    {
        $subject = "Booking cancelled — " . ($rental['title'] ?? 'Rental');

        $travelerHtml = $this->renderTemplate('booking_cancel_traveler.php', compact('booking', 'traveler', 'host', 'rental'));
        $ok1 = $this->send($traveler['email'], $subject, $travelerHtml);

        $hostHtml = $this->renderTemplate('booking_cancel_host.php', compact('booking', 'traveler', 'host', 'rental'));
        $ok2 = $this->send($host['email'], $subject, $hostHtml);

        return $ok1 && $ok2;
    }

    private function renderTemplate(string $file, array $vars): string
    {
        $path = __DIR__ . '/../templates/emails/' . $file;
        if (!file_exists($path)) return "<p>Email template missing: {$file}</p>";

        extract($vars);
        ob_start();
        require $path;
        return (string)ob_get_clean();
    }

    public function send(string $toEmail, string $subject, string $html): bool
    {
        try {
            $mail = $this->baseMailer();
            $mail->addAddress($toEmail);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = strip_tags($html);
            return $mail->send();
        } catch (Exception $e) {
            // تقدر تخزن error فـ logs
            return false;
        }
    }
}

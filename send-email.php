<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars(trim($_POST['subject'] ?? 'Contact Form Message'));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ashapulokesh32@gmail.com';
    $mail->Password   = 'hcxt lkob rire elwu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom($email, $name);
    $mail->addAddress('ashapulokesh32@gmail.com');
    $mail->Subject = $subject;
    $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $mail->send();
    echo "<script>alert('Message sent successfully!'); window.location.href='contact.html';</script>";
} catch (Exception $e) {
    echo "<script>alert('Mailer Error: {$mail->ErrorInfo}'); window.location.href='contact.html';</script>";
}

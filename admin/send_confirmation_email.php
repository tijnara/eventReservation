<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\SMTP.php';

function sendConfirmationEmail($email, $name, $schedule, $timeofevent, $payment) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                        // Set the SMTP server to Gmail (or use your SMTP)
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'tijnara0430@gmail.com';             // SMTP username
        $mail->Password = 'pxsebypureoysvby';              // SMTP password
        $mail->SMTPSecure = 'ssl';   // Enable TLS encryption
        $mail->Port = 465;                                    // TCP port for TLS

        //Recipients
        $mail->setFrom('tijnara0430@gmail.com', 'Event Management System');
        $mail->addAddress($email, $name);                     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your Booking has been Confirmed!';
        $mail->Body    = "
            <p>Dear $name,</p>
            <p>Your booking has been confirmed! Below are the details:</p>
            <ul>
                <li><strong>Name:</strong> $name</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Event Date:</strong> " . date("F j, Y", strtotime($schedule)) . "</li>
                <li><strong>Time Slot:</strong> " . ($timeofevent == 0 ? '8 AM - 12 PM' : ($timeofevent == 1 ? '12 PM - 1 PM' : ($timeofevent == 2 ? '4 PM - 8 PM' : '8 PM - 12 AM'))) . "</li>
                <li><strong>Payment Method:</strong> " . ($payment == 0 ? 'Cash' : 'GCash') . "</li>
            </ul>
            <p>Thank you for booking with us!</p>
        ";

        // Send email
        $mail->send();
        return true;  // Email sent successfully
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

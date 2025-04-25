<!-- audience registration email for client and admin -->
<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\SMTP.php';

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $event_id = $_POST['event_id']; 
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $payment_status = $_POST['payment_status'] == 1 ? "Paid" : "Pending";
    $status = $_POST['status'] == 1 ? "Confirmed" : ($_POST['status'] == 0 ? "For Verification" : "Cancelled");

    // Fetch the event name based on event_id
    $event_query = $conn->query("SELECT event FROM events WHERE id = $event_id");
    $event_row = $event_query->fetch_assoc();
    $event_name = $event_row['event']; // Event name

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tijnara0430@gmail.com';
        $mail->Password = 'pxsebypureoysvby'; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port = 465; 

        // First: Send email to the user
        $mail->setFrom('tijnara0430@gmail.com', 'Regina Garden and Restaurant');
        $mail->addAddress($email); // User's email

        // Email content for the user
        $mail->isHTML(true);
        $mail->Subject = 'Audience Registration Confirmation';
        $mail->Body = "
            Hello $name,<br><br>
            We appreciate your registration for the event: <strong>$event_name</strong>.<br>
            We are excited to welcome you.<br><br>
            <strong>Event Details:</strong><br>
            Event: $event_name<br>
            Contact: $contact<br>
            Payment Status: $payment_status<br><br>
            Best Regards,<br>
            Regina's Garden and Restaurant - Event Team
        ";

        $mail->send(); 

        // Second: Send email to the admin
        $mail->clearAddresses(); 
        $mail->addAddress('tijnara0430@gmail.com'); // Admin's email

        // Email content for the admin
        $mail->Subject = 'New Audience Registration';
        $mail->Body = "
            Admin,<br><br>
            A new audience has been registered for the event: <strong>$event_name</strong>.<br><br>
            <strong>Details:</strong><br>
            Name: $name<br>
            Email: $email<br>
            Contact: $contact<br>
            Address: $address<br>
            Payment Status: $payment_status<br>
            Status: $status<br><br>
        ";

        $mail->send(); 

        echo 'Emails sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo 'Invalid request.';
}
?>

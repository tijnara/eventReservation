<!-- for sending email to client and admin for Audience Registration -->
<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $event_name = $_POST['event_name'];    

    $mail = new PHPMailer(true);  

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tijnara0430@gmail.com';
        $mail->Password = 'pxsebypureoysvby'; // App Password 
        $mail->SMTPSecure = 'ssl';  
        $mail->Port = 465; 

        // Send email to user
        $mail->setFrom('tijnara0430@gmail.com', 'Regina Garden and Restaurant');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Audience Registration Successful - ' . $event_name;
        $mail->Body    = "
            <html>
            <head>
                <title>Thank you for registering!</title>
            </head>
            <body>
                <h2>Thank you, $name!</h2>
                <p>You have successfully registered for the event <strong>$event_name</strong>.</p>
                <p>We look forward to seeing you at the event!</p>
                <p>P.S</p>
                <p>To enter the event: Please show this email as your registration proof and make sure to pay the entrance fee upon arrival.</p>
                <br>
                <p>Best regards,</p>
                <p>Regina's Garden and Restaurant - Event Team</p>
            </body>
            </html>
        ";
        $mail->AltBody = "Thank you, $name! You have successfully registered for the event $event_name. We look forward to seeing you at the event.";
        $mail->send();

        // Send notification email to admin
        $mail->clearAddresses();
        $mail->addAddress('tijnara0430@gmail.com');  // Admin email
        $mail->Subject = 'New Audience Registration';
        $mail->Body    = "A new audience event registration:<br><br>" .
                         "Details:<br>" .
                         "Name: $name<br>" .
                         "Email: $email<br>" .
                         "Event: $event_name";
        $mail->send();

       
        echo '1';
    } catch (Exception $e) {
        
        echo '0';
    }

    exit();
}

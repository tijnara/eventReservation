<!-- send an email to admin for the "Send Us a Message" using the PHPMailer -->
<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // retrieve form inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = htmlspecialchars(trim($_POST['address']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tijnara0430@gmail.com';
        $mail->Password = 'pxsebypureoysvby';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Send thank-you email to user
        $mail->setFrom('tijnara0430@gmail.com', 'Regina Garden and Restaurant');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for messaging us!';
        $mail->Body    = "Your message is important to us. We'll be in touch with you very soon.<br><br>" .
                         "Your Details: <br>" .
                         "First Name: $first_name<br>" .
                         "Last Name: $last_name<br>" .
                         "Email: $email<br>" .
                         "Address: $address<br>" .
                         "Phone: $phone<br>" .
                         "Message:<br>$message";
        $mail->send();

        // Send notification email to admin
        $mail->clearAddresses();  
        $mail->addAddress('tijnara0430@gmail.com');  // Admin email
        $mail->Subject = 'New Inquiry/Message from Our Website';
        $mail->Body    = "A new message/inquiry has been submitted on the website.<br><br>" .
                         "Details:<br>" .
                         "First Name: $first_name<br>" .
                         "Last Name: $last_name<br>" .
                         "Email: $email<br>" .
                         "Address: $address<br>" .
                         "Phone: $phone<br>" .
                         "Message:<br>$message";
        $mail->send();

        $_SESSION['message'] = 'Message sent successfully!';
        $_SESSION['alert_type'] = 'success'; 

    } catch (Exception $e) {
        $_SESSION['message'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        $_SESSION['alert_type'] = 'error'; 
    }

    // Redirect back to the contact form
    header('Location: index.php?page=about'); 
    exit();
}
?>

<!-- - captures user input from a contact form and sends an email using PHPMailer.
	 - It performs input sanitization for security and sets up SMTP configurations to send the email.
	 - It provides feedback to the user about the success or failure of the email sending process 
		through session messages, which can be displayed on the redirected page. -->


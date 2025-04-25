<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\SMTP.php';

include 'db_connect.php';

try {
    // Check if the necessary data is received
    if (!isset($_POST['id']) || !isset($_POST['status'])) {
        throw new Exception('Missing required POST data');
    }

    $booking_id = $_POST['id'];
    $status = $_POST['status'];

    // Update the status of the booking
    $query = "UPDATE venue_booking SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("ii", $status, $booking_id);
    if (!$stmt->execute()) {
        throw new Exception('Execution failed: ' . $stmt->error);
    }

    // Fetch customer information for the email
    $customer_query = "SELECT name, email, datetime FROM venue_booking WHERE id = ?";
    $customer_stmt = $conn->prepare($customer_query);
    if (!$customer_stmt) {
        throw new Exception('Customer query prepare failed: ' . $conn->error);
    }

    $customer_stmt->bind_param("i", $booking_id);
    if (!$customer_stmt->execute()) {
        throw new Exception('Customer query execution failed: ' . $customer_stmt->error);
    }

    $customer_result = $customer_stmt->get_result();
    if ($customer_result->num_rows === 0) {
        throw new Exception('No customer found for booking ID: ' . $booking_id);
    }

    $customer = $customer_result->fetch_assoc();
    $customer_name = $customer['name'];
    $customer_email = $customer['email'];
    $booking_date = date("F d, Y", strtotime($customer['datetime']));

    // Prepare PHPMailer instance
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

        $mail->setFrom('tijnara0430@gmail.com', 'Regina Garden and Restaurant');
        $mail->addAddress($customer_email); // Send to the customer

        // Determine email content based on status
        if ($status == 1) { // Confirmed
            $mail->Subject = 'Booking Confirmation';
            $mail->Body = "
                Hello $customer_name,<br><br>
                Your booking has been successfully confirmed!<br><br>
                Booking ID: $booking_id<br>
                Status: Confirmed<br><br>
                Best regards,<br>
                The Regina Garden and Restaurant Team
            ";
        } elseif ($status == 2) { // Canceled
            $mail->Subject = 'Booking Cancellation';
            $mail->Body = "
                Hello $customer_name,<br><br>
                We regret to inform you that your booking for our service on <strong>$booking_date</strong> has been canceled. 
                We understand this may cause inconvenience, and we sincerely apologize for any disruption this may bring to your plans.<br><br>

                <strong>Reason for Cancellation:</strong><br>
                - Unforeseen Circumstances: Due to unexpected events beyond our control, we are unable to proceed with your booking as planned.<br>
                - Maintenance Issues: Our facilities require urgent maintenance to ensure the safety and quality of our services.<br>
                - Event Overbooking: An unexpected surge in bookings resulted in overbooking for the specified date.<br>
                - Health and Safety Concerns: Due to recent health and safety guidelines, we must limit the number of attendees to ensure everyone's well-being.<br><br>

                <strong>We deeply regret any inconvenience caused and are committed to making things right. As a gesture of goodwill, we would like to offer you the following options:</strong><br>
                - Reschedule: We can reschedule your booking to a later date that suits your convenience.<br>
                - Full Refund: We will process a full refund of the amount you paid.<br>
                - Discount Voucher: You will receive a discount voucher for future bookings with us.<br><br>

                Please contact our support team at <a href='mailto:support@example.com'>support@example.com</a> or call us at (123) 456-7890 to discuss your preferred option. 
                We appreciate your understanding and patience in this matter.<br><br>

                Best regards,<br>
                The Regina Garden and Restaurant Team<br>
            ";
        }

        $mail->isHTML(true);
        $mail->send();

        echo 'success'; // Success response for AJAX
    } catch (Exception $e) {
        throw new Exception('Mailer Error: ' . $mail->ErrorInfo);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

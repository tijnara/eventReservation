<?php
// Assuming this file is handling the form submission, you'll need to update the status and then send an email.
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status']; // Get the selected status from the form

    // Query to fetch the current booking details
    $booking = $conn->query("SELECT * FROM venue_booking WHERE id = " . $id);
    $bookingData = $booking->fetch_assoc();

    $email = $bookingData['email']; // Client's email address
    $name = $bookingData['name']; // Client's name

    // Update booking status
    $updateQuery = $conn->prepare("UPDATE venue_booking SET status = ? WHERE id = ?");
    $updateQuery->bind_param("ii", $status, $id);
    $updateQuery->execute();

    // Check if status is Confirm (1)
    if ($status == 1) {
        // Prepare email content
        $subject = "Your Booking has been Confirmed!";
        $body = "
            <p>Dear $name,</p>
            <p>We are happy to inform you that your booking has been confirmed!</p>
            <p>Your booking details are as follows:</p>
            <ul>
                <li><strong>Name:</strong> $name</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Event Date:</strong> " . date("F j, Y", strtotime($bookingData['schedule'])) . "</li>
                <li><strong>Time Slot:</strong> " . $time_slots[$bookingData['timeofevent']] . "</li>
                <li><strong>Payment Method:</strong> " . $payment_methods[$bookingData['payment']] . "</li>
            </ul>
            <p>Thank you for choosing us! We look forward to serving you.</p>
        ";

        // Send email to the client
        $clientResult = sendEmail($email, $name, $subject, $body);
        if ($clientResult !== true) {
            echo "Error: Client email could not be sent.";
        } else {
            echo "Confirmation email successfully sent to the client.";
        }
    }
}
?>

<!-- this script processes event venue bookings, handles payment via GCash or cash, and confirms bookings through modals. 
It also fetches relevant system settings and user information dynamically from the database. -->
<?php 
include 'admin/db_connect.php';

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';
$amount = 0; 
$venue_id = isset($_GET['venue_id']) ? $_GET['venue_id'] : ''; 
$max_capacity = 0; 

// get the event amount
if (!empty($event_id)) {
    $query = $conn->query("SELECT amount FROM events WHERE id = '$event_id'");
    if ($query) {
        $event = $query->fetch_assoc();
        if ($event) {
            $amount = $event['amount'];
        }
    }
}

// get max_capacity
if (!empty($venue_id)) {
    $capacity_query = $conn->query("SELECT max_capacity FROM venue WHERE id = '$venue_id'");
    if ($capacity_query) {
        $capacity_data = $capacity_query->fetch_assoc();
        if ($capacity_data) {
            $max_capacity = $capacity_data['max_capacity'];
        }
    }
}

// get GCash account details from system settings
$gcash_account = "";
$settings_query = $conn->query("SELECT gcash FROM system_settings LIMIT 1");
if ($settings_query) {
    $settings = $settings_query->fetch_assoc();
    if ($settings) {
        $gcash_account = $settings['gcash'];
    }
}

// get GCash name and QR code and fb
$qry = $conn->query("SELECT * FROM system_settings LIMIT 1");
if ($qry->num_rows > 0) {
    $settings = $qry->fetch_assoc();
    $gcash_name_display = $settings['gcash_name'];
    $gcash_account = $settings['gcash'];
    $qr_image = $settings['qr_image'];
    $facebook = $settings['facebook'];
} else {
    $gcash_name_display = '';
    $gcash_account = '';
    $qr_image = '';
    $facebook = '';
}
?>

			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
			<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">  
			
<div class="container-fluid">
    <form action="" id="manage-book">
	
	<!-- get all venues -->
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="venue_id" value="<?php echo $venue_id; ?>">		
        
        <!-- Error Alert for Missing Fields -->
        <div id="alertMessage" class="alert alert-danger" style="display: none;"></div>		
		
		<div class="form-group">
    <label for="" class="control-label">Max Guest Capacity:</label>
    <span style="color: blue; font-weight: bold;">
        <i><?php echo htmlspecialchars($max_capacity); ?></i>
    </span>
</div>

		<!-- User Information Fields -->
		<div class="form-group">
		
			<!-- full name Fields -->
            <label for="" class="control-label">Full Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter your full name" value="<?php echo isset($name) ? $name : '' ?>" required>
        </div>
		
		<!-- address Fields -->
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea cols="30" rows="5" required name="address" class="form-control" placeholder="Please specify the location to cater if you're booking Catering Services."><?php echo isset($address) ? $address : '' ?></textarea>
		</div>
		
		<!-- email Fields -->
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter your email address" value="<?php echo isset($email) ? $email : '' ?>" required>
        </div>
		
		<!-- contact # Fields -->
        <div class="form-group">
            <label for="" class="control-label">Contact #</label>
            <input type="text" class="form-control" name="contact" placeholder="Enter your contact number" value="<?php echo isset($contact) ? $contact : '' ?>" required>
        </div>
		
		<!-- calendar for desired event date -->
        <div class="form-group">
            <label for="" class="control-label">Desired Event Date</label>
            <input type="text" class="form-control datetimepicker" name="schedule" placeholder="Select desired event date" value="<?php echo isset($schedule) ? $schedule : '' ?>" required>
        </div>

        <!-- Desired Time of Event Selection -->
        <div class="form-group">
            <label for="timeofevent" class="control-label">Desired Time of Event</label>
            <select class="form-control" name="timeofevent" id="timeofevent" required>
                <option value="" disabled selected>Select time of event</option>
                <option value="0" <?php echo (isset($timeofevent) && $timeofevent == 0) ? 'selected' : ''; ?>>8 AM - 12 PM</option>
                <option value="1" <?php echo (isset($timeofevent) && $timeofevent == 1) ? 'selected' : ''; ?>>12 PM - 4 PM</option>
                <option value="2" <?php echo (isset($timeofevent) && $timeofevent == 2) ? 'selected' : ''; ?>>4 PM - 8 PM</option>
                <option value="3" <?php echo (isset($timeofevent) && $timeofevent == 3) ? 'selected' : ''; ?>>8 PM - 12 AM</option>
            </select>
        </div>

        <!-- Payment Option Selection -->
        <div class="form-group">
            <label for="payment" class="control-label">Payment Option</label>
            <select class="form-control" name="payment" id="payment" required>
                <option value="" disabled selected>Select payment option</option>
                <option value="0" <?php echo (isset($payment) && $payment == 0) ? 'selected' : ''; ?>>Cash</option>
                <option value="1" <?php echo (isset($payment) && $payment == 1) ? 'selected' : ''; ?>>GCash</option>
            </select>
        </div> 
		
<div id="msg"></div>

    </form>
</div>
<style>
/* for modal content */
    .modal-dialog {
        max-width: 40%; 
        margin: 1.75rem auto; 
    }

    .modal-content {
        height: 90vh; 
        overflow-y: auto; 
    }

    .modal-body {
        padding: 15px; 
    }
</style>	

<!-- Reservation Fee Section -->		
<?php
// get reservation fee from system settings
$reservation_fee_query = $conn->query("SELECT reservation_fee FROM system_settings");
$reservation_fee = $reservation_fee_query->fetch_assoc()['reservation_fee'];
?>	
<div class="reservation-fee" style="padding: 10px; margin-bottom: 15px;">
    <h5>Reservation Fee Details</h5>
    <p>The reservation fee is: <strong style="color: blue;">₱<?php echo number_format($reservation_fee, 2); ?></strong></p>
    <p>
    <i><b style="color: #ffcc00;">Note: Reservation Fee is non-refundable but deductible on total price.</b></i>
</p>
</div>

<!-- GCash Confirmation Modal, 
Retrieve Facebook page URL and GCash name and
Encrypt the Gcash name in random-->
<?php
if (isset($_GET['id'])) {
    $booking = $conn->query("SELECT * FROM venue_booking WHERE id = " . $_GET['id']);    
    if ($booking->num_rows > 0) { 
        $row = $booking->fetch_assoc(); 
        foreach ($row as $k => $v) {
            $$k = $v; 
        }
    } else {
        
        echo "No booking found with the given ID.";
    }
}
// get Facebook from system settings
$facebook_query = $conn->query("SELECT facebook FROM system_settings");
$facebook = $facebook_query->fetch_assoc()['facebook'];
// get GCash name from system settings
$gcash_name_query = $conn->query("SELECT gcash_name FROM system_settings");
$gcash_name_data = $gcash_name_query->fetch_assoc();
$gcash_name = $gcash_name_data['gcash_name'];
// for encryption of account name
$gcash_name_display = '';
if (strlen($gcash_name) > 2) {
    $first_two = substr($gcash_name, 0, 2); // First two letters
    $remaining_chars = substr($gcash_name, 2); // Remaining characters    
    // Randomly replace characters 
    $remaining_chars_array = str_split($remaining_chars);
    $asterisk_count = rand(1, count($remaining_chars_array) - 1); 
    // Randomly select positions to replace with asterisks
    $positions = array_rand($remaining_chars_array, $asterisk_count);
    foreach ((array)$positions as $pos) { 
        $remaining_chars_array[$pos] = '*';
    }
    $gcash_name_display = $first_two . implode('', $remaining_chars_array);
} else {
    $gcash_name_display = $gcash_name; // If name is too short, display as is
}
?>

<!-- GCash instructions modals-->
<div class="modal fade" id="gcashConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="gcashConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 32.5%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gcashConfirmationModalLabel">GCash Payment Instructions</h5>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-info">GCash Acct Name: <br><b style="font-size: 2rem;"><?php echo htmlspecialchars($gcash_name_display); ?></b></span>
                    
                    <?php if (!empty($qr_image) && file_exists('admin/assets/uploads/' . $qr_image)): ?>
                        <img src="admin/assets/uploads/<?php echo htmlspecialchars($qr_image); ?>" alt="GCash Instructions" class="img-fluid" style="max-width: 150px; height: auto;">
                    <?php else: ?>
                        <p>No QR code available.</p>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <p>GCash Acct Number: <br><b style="font-size: 2rem;"><?php echo htmlspecialchars($gcash_account); ?></b></p>
                </div>
                <p>Send us a message of your proof of GCash payment to our <a href="<?php echo htmlspecialchars($facebook); ?>" target="_blank">Facebook Page.</a> Provide us the following: 
                    <ol>
                        <li><b>Proof of Payment:</b> Please send a screenshot of your GCash payment.</li>
                        <li><b>Full Name:</b> The name you provided in the booking form.</li>
                        <li><b>Contact Number:</b> The contact number you provided in the booking form.</li>
                    </ol>
                </p>
                <span class="text-info">Facebook Page: <a href="<?php echo htmlspecialchars($facebook); ?>" target="_blank">Regina's Garden and Restaurant</a></span>
            </div>
            <div class="modal-footer">
                <p>Thank you for choosing us!</p>                
                <button type="button" class="btn btn-primary" id="confirmGcashPayment">OK</button>
            </div>
        </div>
    </div>
</div>
<style>
#gcashConfirmationModal .modal-dialog {
    max-width: 100%;
    width: auto;
    height: auto;
}
#gcashConfirmationModal .modal-content {
    max-height: 65vh; 
    overflow-y: auto;
}
</style>


<!-- GCash Confirmation Alert Modal and FB page -->
<div class="modal fade" id="gcashSuccessModal" tabindex="-1" role="dialog" aria-labelledby="gcashSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gcashSuccessModalLabel">Payment Confirmation</h5>
            </div>
            <div class="modal-body">
                <p><h2><center>We will contact you as soon as we verify your payment.</center></h2></p>
				<p>Send us a message of your proof of GCash payment to our <a href="<?php echo htmlspecialchars($facebook); ?>" target="_blank">Facebook Page.</a> Provide us the following: 
                    <ol>
                        <li><b>Proof of Payment:</b> Please send a screenshot of your GCash payment.</li>
                        <li><b>Full Name:</b> The name you provided in the booking form.</li>
                        <li><b>Contact Number:</b> The contact number you provided in the booking form.</li>
                    </ol>
                </p>                
            </div>
            <div class="modal-footer">
			<p><center>Thank you for choosing us!</center></p>			
                <button type="button" class="btn btn-primary" id="gcashOkayButton">Okay</button>
            </div>
        </div>
    </div>
</div>
<style>
#gcashSuccessModal .modal-dialog {
    max-width: auto;
    width: auto;
    height: auto;
}
#gcashSuccessModal .modal-content {
    max-height: 60vh; 
    overflow-y: auto;
}
</style>

<!-- other alert modals -->

<!-- Success Alert Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Booking Successful</h5>
            </div>
            <div class="modal-body">
                <p><center>Your booking has been successfully submitted!</center></p>
                <p><center>We will contact you as soon as we confirm your booking. Thank you for choosing us!</center></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cashOkayButton">Okay</button>
            </div>
        </div>
    </div>
</div>
<style>
#successModal .modal-dialog {
    max-width: auto;
    width: auto;
    height: auto;
}
#successModal .modal-content {
    max-height: 45vh; 
    overflow-y: auto;
}
</style>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Your Booking</h5>                
            </div>
            <div class="modal-body" id="confirmationMessageBody">
                <!-- details will be dynamically inserted here -->
            </div>
			
            <div class="modal-footer d-flex flex-column align-items-start">			
                <div class="alert alert-warning d-flex align-items-center" role="alert" style="width: 100%; max-width: 100%;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                    <p class="mb-0">&nbsp Warning! By clicking "OK," your booking will proceed to the payment instructions.<br>&nbsp Please ensure that all information provided is correct.</p>
                </div>
                <div class="d-flex justify-content-end" style="width: 100%;">
                    <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancel</button>&nbsp&nbsp
                    <button type="button" class="btn btn-primary" id="confirmBookingButton">OK</button>
                </div>
            </div>
			
        </div>
    </div>
</div>
<style>
#confirmationModal .modal-dialog {
    max-width: auto;
    width: auto;
    height: auto;
}
#confirmationModal .modal-content {
    max-height: 60vh; 
	max-width: 100%;
    overflow-y: auto;
}
#confirmationModal .alert {
    width: 100%; 
    margin-bottom: 1rem; 
}
</style>
<script>
// confirmationModal cancelButton
$('#confirmationModal').on('click', '.btn-secondary', function() {
    $('#confirmationModal').modal('hide'); 
});
</script>
<style>
<!-- form section and fields -->
    .required-field {
        border: 2px solid red; /* Red border for required fields */
    }	
	.form-control,
    .control-label,
    .text-info,
    .modal-title,
    #alertMessage {
        color: black !important; 
    }
	#uni_modal {
        color: black; 
    }
</style>


<script>
//for cancelling booking to refresh page
$(document).ready(function() {
	document.getElementById('cancelBooking').addEventListener('click', function() {
    // Refresh the page when the cancel button is clicked
    location.reload();
});
	
    // Hide alert modal and focus on the first input field when OK is clicked
    $('#okButton').click(function() {
        $('#alertModal').modal('hide');
        $('#manage-book input, #manage-book select, #manage-book textarea').first().focus();
    });
	
    // datetimepicker
    $('.datetimepicker').datepicker({
        format: 'yyyy-mm-dd',
        timepicker: false,
		startDate: '+30d',
		minDate: 0,        
		autoclose: true
    });

    // Remove red mark on input when filled
    $('#manage-book input, #manage-book select, #manage-book textarea').on('input change', function() {
        if ($(this).val()) {
            $(this).removeClass('required-field'); // Remove red mark when input is filled
        }
    });

    // Handle GCash confirmation
    $('#confirmGcashPayment').click(function() {
        $('#gcashConfirmationModal').modal('hide');
        $('#gcashSuccessModal').modal('show');
    });

    // Handle form submission (empty fields, for mailing(PHP Mailer))
    $('#manage-book').submit(function(e) {
        e.preventDefault(); 
        $('#msg').html(''); 
        $('#alertMessage').hide(); 
        let missingFields = [];
        let firstMissingField; 

        // Define variables for form elements (for mailing)
        const nameField = $("input[name='name']");
        const addressField = $("textarea[name='address']");
        const emailField = $("input[name='email']");
        const contactField = $("input[name='contact']");
        const scheduleField = $("input[name='schedule']");
        const timeField = $("#timeofevent");
        const paymentField = $("#payment");

        // Check required fields
        if (!nameField.val()) {
            missingFields.push("Full Name");
            nameField.addClass('required-field');
            if (!firstMissingField) firstMissingField = nameField;
        }
        if (!addressField.val()) {
            missingFields.push("Address");
            addressField.addClass('required-field');
            if (!firstMissingField) firstMissingField = addressField;
        }
        if (!emailField.val()) {
            missingFields.push("Email");
            emailField.addClass('required-field');
            if (!firstMissingField) firstMissingField = emailField;
        }
        if (!contactField.val()) {
            missingFields.push("Contact Number");
            contactField.addClass('required-field');
            if (!firstMissingField) firstMissingField = contactField;
        }
        if (!scheduleField.val()) {
            missingFields.push("Desired Event Date");
            scheduleField.addClass('required-field');
            if (!firstMissingField) firstMissingField = scheduleField;
        }
        if (!timeField.val()) {
            missingFields.push("Desired Time of Event");
            timeField.addClass('required-field');
            if (!firstMissingField) firstMissingField = timeField;
        }
        if (!paymentField.val()) {
            missingFields.push("Payment Option");
            paymentField.addClass('required-field');
            if (!firstMissingField) firstMissingField = paymentField;
        }

        // Show error alert if there are missing fields
        if (missingFields.length > 0) {
            $('#alertMessage').html("Please fill out the following fields before submitting: " + missingFields.join(", "))
                .css({
                    'color': 'red',
                    'font-weight': 'bold',
                    'border': '1px solid red',
                    'padding': '10px',
                    'background-color': 'rgba(255, 0, 0, 0.1)',
                    'border-radius': '5px'
                })
                .show(); // Show the alert message

            if (firstMissingField) {
                firstMissingField.focus(); 
            }
            return;
        }

        // Check if the desired event date is in the past
        const selectedDate = new Date(scheduleField.val());
        const currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0); // Set current time to midnight for comparison

        if (selectedDate < currentDate) {
            $('#alertMessage1').html("⚠️ You cannot book a past date. Please select a valid date.")
                .css({
                    'color': 'red',
                    'font-weight': 'bold',
                    'border': '1px solid red',
                    'padding': '10px',
                    'background-color': 'rgba(255, 0, 0, 0.1)',
                    'border-radius': '5px'
                })
                .show(); // Show the alert message
            scheduleField.addClass('required-field'); // Highlight the field
            
            // Focus the alert message
            $('#alertMessage1').focus();

            return; // Exit the function
        }

        // Check for booking conflicts before form submission
        const venue_id = $("input[name='venue_id']").val();
        const schedule = scheduleField.val();
        const timeofevent = timeField.val();		

        // Map time slots
        const timeMap = {
            '0': '8AM - 12PM',
            '1': '12PM - 4PM',
            '2': '4PM - 8PM',
            '3': '8PM-  12AM'
        };
		// map payment
		

        const mappedTime = timeMap[timeofevent] || 'Not specified'; // Default if not found
		

        // AJAX call to check for conflicts
        $.ajax({
            url: 'admin/ajax.php?action=check_booking_conflict',
            data: { venue_id: venue_id, schedule: schedule, timeofevent: timeofevent },
            method: 'POST',
            success: function(resp) {
                if (resp == 'conflict') {
                    $('#alertMessage1').html("The selected venue is already booked for the chosen date and/or time. Please choose a different date or time.")
                        .show();
                } else {
                    var paymentOption = paymentField.val();
					const paymentMap = {
					'0': 'Cash',
					'1': 'GCash'
};
const mappedPayment = paymentMap[paymentOption] || 'Not specified'; 

                    // Construct a detailed confirmation message
                    var confirmationMessage = `
                        <p>Please review your booking details before proceeding:</p>
                        <ul>
                            <li><strong>Full Name:</strong> ${nameField.val()}</li>
                            <li><strong>Address:</strong> ${addressField.val()}</li>
                            <li><strong>Email:</strong> ${emailField.val()}</li>
                            <li><strong>Contact Number:</strong> ${contactField.val()}</li>
                            <li><strong>Desired Event Date:</strong> ${scheduleField.val()}</li>
                            <li><strong>Desired Time of Event:</strong> ${mappedTime}</li>
							<li><strong>Payment Option:</strong> ${mappedPayment}</li>
                        </ul>
                        <p><strong>Note:</strong> If you click 'OK', you will ${paymentOption === '1' ? 'be directed to GCASH payment instructions.' : 'proceed with the CASH payment.'}</p>
                    `;
                    
                    // Insert the message into the modal body and show the modal
                    $('#confirmationMessageBody').html(confirmationMessage);
                    $('#confirmationModal').modal('show');
					

                    // Handle the booking confirmation
                    $('#confirmBookingButton').off('click').on('click', function() {
                        start_load(); // Start loading only after user confirms
                        
                        // Proceed with mailing if no conflicts, 
                        $.ajax({
                            url: 'admin/ajax.php?action=save_book',
                            data: new FormData($('#manage-book')[0]),
                            cache: false,
                            contentType: false,
                            processData: false,
                            method: 'POST',
                            success: function(resp) {
                                end_load(); // End loading indicator
                                if (resp == 1) {
                                    if (paymentOption === '1') { 
                                        $('#gcashConfirmationModal').modal('show'); 
                                    } else {
                                        $('#successModal').modal('show'); 
                                    }

                                    // AJAX call to send booking email
                                    $.ajax({
                                        url: 'admin/ajax.php?action=send_booking_email',
                                        data: {
                                            name: nameField.val(),
                                            email: emailField.val(),
                                            contact: contactField.val(),
                                            address: addressField.val(),
                                            schedule: scheduleField.val(),
                                            timeofevent: timeField.val(),
                                            payment: paymentField.val(),
											venue_id: $("input[name='venue_id']").val()
                                        },
                                        method: 'POST',
                                        success: function(emailResp) {
                                            console.log(emailResp); 
                                        },
                                        error: function() {
                                            console.error("An error occurred while sending the booking email."); 
                                        }
                                    });
                                } else {
                                    $('#msg').html(resp); 
                                }
                            },
                            error: function() {
                                alert("An error occurred. Please try again.");
                                end_load();
                            }
                        });

                        $('#confirmationModal').modal('hide'); 
                    });
                }
            },
            error: function() {
                alert("An error occurred while checking for booking conflicts. Please try again.");
                end_load();
            }
        });
    }); 

    // Handle Okay button in GCash success modal
    $('#gcashOkayButton').click(function() {
        $('#gcashSuccessModal').modal('hide'); 
        setTimeout(function() {
            location.reload(); 
        }, 1000); 
    });

    // Handle Okay button in success modal for cash payments
    $('#cashOkayButton').click(function() {
        $('#successModal').modal('hide'); 
        setTimeout(function() {
            location.reload(); 
        }, 1000); 
    });
});

</script>
<?php include 'db_connect.php'; ?>

<?php
if (isset($_GET['id'])) {
    $booking = $conn->query("SELECT * FROM venue_booking WHERE id = " . $_GET['id']);
    foreach ($booking->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
?>

<div class="container-fluid">
	<!-- booking form -->
    <form action="" id="manage-book">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
		
		<!-- Venue field -->
        <div class="form-group">
            <label for="" class="control-label">Venue</label>
			<!-- selection of venue -->
            <select name="venue_id" id="" class="custom-select select2">
                <option></option>
                <?php 
                $venue = $conn->query("SELECT * FROM venue ORDER BY venue ASC");
                while ($row = $venue->fetch_assoc()): ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($venue_id) && $venue_id == $row['id'] ? 'selected' : ''; ?>>
                        <?php echo ucwords($row['venue']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

		<!-- name field -->
        <div class="form-group">
            <label for="" class="control-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
        </div>

		<!-- address field -->
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea cols="30" rows="2" required name="address" class="form-control"><?php echo isset($address) ? $address : ''; ?></textarea>
        </div>
		
		<!-- email field -->
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
        </div>

		<!-- mobile number field -->
        <div class="form-group">
            <label for="" class="control-label">Cellphone Mobile Number</label>
            <input type="text" class="form-control" name="contact" id="contact" value="<?php echo isset($contact) ? $contact : ''; ?>" required>
        </div>		
		<!-- error message -->
        <div id="contact-error-message" style="color: red; display: none;">Please input a valid mobile number.</div>

		<!-- date of event field -->
        <div class="form-group">
            <label for="" class="control-label">Desired Date of Event</label>
            <input type="text" class="form-control datetimepicker" name="schedule" value="<?php echo isset($datetime) ? date("Y-m-d", strtotime($datetime)) : ''; ?>" required>
        </div>
			
		<!-- time of event field -->
        <div class="form-group">
            <label for="timeofevent" class="control-label">Desired Time of Event</label>
            <select class="form-control" name="timeofevent" id="timeofevent" required>
                <option value="" disabled selected>Select time of event</option>
                <option value="0" <?php echo (isset($timeofevent) && $timeofevent == 0) ? 'selected' : ''; ?>>8 AM - 12 PM</option>
                <option value="1" <?php echo (isset($timeofevent) && $timeofevent == 1) ? 'selected' : ''; ?>>12 PM - 1 PM</option>
                <option value="2" <?php echo (isset($timeofevent) && $timeofevent == 2) ? 'selected' : ''; ?>>4 PM - 8 PM</option>
                <option value="3" <?php echo (isset($timeofevent) && $timeofevent == 3) ? 'selected' : ''; ?>>8 PM - 12 AM</option>
            </select>
        </div>   
		
		<!-- payment option field -->
        <div class="form-group">
            <label for="payment" class="control-label">Payment Option</label>
			<!-- selection and mapping -->
            <select class="form-control" name="payment" id="payment" required>
                <option value="" disabled selected>Select payment option</option>
                <option value="0" <?php echo (isset($payment) && $payment == 0) ? 'selected' : ''; ?>>Cash</option>
                <option value="1" <?php echo (isset($payment) && $payment == 1) ? 'selected' : ''; ?>>GCash</option>
            </select>
        </div>

        <!-- Display the reservation fee -->
        <?php
        if (isset($_GET['id'])) {
            $booking = $conn->query("SELECT * FROM venue_booking WHERE id = " . $_GET['id']);
            foreach ($booking->fetch_array() as $k => $v) {
                $$k = $v;
            }
        }
        // Retrieve reservation fee from system_settings
        $reservation_fee_query = $conn->query("SELECT reservation_fee FROM system_settings");
        $reservation_fee = $reservation_fee_query->fetch_assoc()['reservation_fee'];
        ?>      
        <p>The reservation fee is: <strong>₱<?php echo number_format($reservation_fee, 2); ?></strong></p>    

		<!-- status and Paid(checkbox) -->
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="payment_status" name="payment_status" <?php echo isset($payment_status) && $payment_status == 1 ? "checked" : ''; ?>>
                <label class="form-check-label" for="payment_status">
                    Paid
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Status</label>
            <select name="status" id="status" class="custom-select">
                <option value="0" <?php echo isset($status) && $status == 0 ? "selected" : ''; ?>>For Verification</option>
                <option value="1" id="confirm-option" <?php echo isset($status) && $status == 1 ? "selected" : ''; ?>>Confirm</option>
                <option value="2" <?php echo isset($status) && $status == 2 ? "selected" : ''; ?>>Cancel</option>
            </select>
        </div>  
		
    </form>
    <div id="error-message" style="color: red; display: none;"></div>
</div>

<script>
	$('.datetimepicker').datepicker({
    format: 'yyyy-mm-dd',
	minDate: 0,
	startDate: '+30d' ,
    autoclose: true
	});

	// validating form inputs before submission (error, empty fields, formats)
    $('#manage-book').submit(function(e) {
        e.preventDefault();

        var emptyFields = []; // Array to hold names of empty fields
		
		// Validate that contact contains only digits
        var contact = $('#contact').val();
        var contactIsValid = /^\d+$/.test(contact); 

        $('#manage-book [required]').removeClass('error-indicator');
        $('.required-label').remove();

        $('#manage-book [required]').each(function() {
            if ($(this).val() === '') {
                emptyFields.push($(this).attr('name')); 
                $(this).addClass('error-indicator'); 
                $(this).after('<span class="required-label" style="color:red;">Required</span>');
            }
        });

        // If contact is not valid, display an error message
        if (!contactIsValid) {
            $('#contact-error-message').show();
            $('#contact').focus();
            return; 
        } else {
            $('#contact-error-message').hide(); 
        }

        // If there are empty fields, show alert message
        if (emptyFields.length > 0) {
            $('#error-message').html("Please fill out all required fields.").show(); 

            // Focus on the first empty required field
            $('#manage-book [required]').filter(function() {
                return $(this).val() === ''; 
            }).first().focus(); 

            return; 
        }
        start_load(); 

        $.ajax({
            url: 'ajax.php?action=save_book',
            method: "POST",
            data: $(this).serialize(),
            success: function(resp) {
                console.log(resp); 
                $('#error-message').hide(); // Hide errors message on success

                if (resp.trim() == "1") {
                    alert_toast("Booking successfully updated", "success");
                    setTimeout(function() {
                        location.reload(); 
                    }, 1000);
                } else if (resp.trim() == "2") {
                    $('#error-message').html("Booking updated, but failed to send email notification to admin.").show();
                } else {
                    $('#error-message').html("The selected venue is already booked for the chosen date and time. Please choose a different date or time.").show();
                }
                end_load(); 
            }
            
        });
    });
	
	// function for Paid checkbox (when to and not to appear in form) 
    $(document).ready(function() {
    // update the form based on the payment status and confirmation
    function updateStatusVisibility() {
        const isPaidChecked = $('#payment_status').is(':checked');
        const statusSelect = $('select[name="status"]');

        if (!isPaidChecked) {
            // If Paid is not checked, hide the Confirm option
            statusSelect.find('option[value="1"]').hide();
        } else {
            // If Paid is checked, show Confirm option
            statusSelect.find('option[value="1"]').show();
        }

        // if status is Confirm, hide "Paid" checkbox and "For Verification" in option
        if (statusSelect.val() == 1) {
            $('#payment_status').closest('.form-check').hide(); // Hide Paid checkbox
            statusSelect.find('option[value="0"]').hide(); // Hide For Verification option
        } else {
            $('#payment_status').closest('.form-check').show(); // Show Paid checkbox
            statusSelect.find('option[value="0"]').show(); // Show For Verification option
        }
    }

    updateStatusVisibility();

    $('#payment_status').change(updateStatusVisibility);
    $('select[name="status"]').change(updateStatusVisibility);
});

</script>

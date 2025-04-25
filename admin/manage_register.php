<?php include 'db_connect.php' ?>

<?php
if(isset($_GET['id'])){
    $booking = $conn->query("SELECT * from audience where id = ".$_GET['id']);
    foreach($booking->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>

<div class="container-fluid">
    <form action="" id="manage-register">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">

        <!-- Event Field/Selection -->
        <div class="form-group">
            <label for="" class="control-label">Event</label>
            <select name="event_id" id="event_id" class="custom-select select2" onchange="checkRegistrationFee()">
                <option></option>
                <?php 
                $event = $conn->query("SELECT * FROM events order by event asc");
                while($row = $event->fetch_assoc()): ?>
                    <option value="<?php echo $row['id'] ?>" data-fee="<?php echo $row['amount'] ?>" <?php echo isset($event_id) && $event_id == $row['id'] ? 'selected' : '' ?>>
                        <?php echo ucwords($row['event']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Full Name Field -->
        <div class="form-group">
            <label for="" class="control-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo isset($name) ? $name : '' ?>" required>
        </div>

        <!-- Address Field -->
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea cols="30" rows="2" required name="address" class="form-control"><?php echo isset($address) ? $address : '' ?></textarea>
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>" required>
        </div>

        <!-- Contact Number Field -->
        <div class="form-group">
            <label for="" class="control-label">Contact #</label>
            <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact : '' ?>" required>
        </div>

        <!-- Checkbox for Paid -->
        <div class="form-group" id="payment_status_container">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="payment_status" name="payment_status" <?php echo isset($payment_status) && $payment_status == 1 ? "checked" : '' ?>>
                <label class="form-check-label" for="payment_status">Paid</label>
            </div>
        </div>

        <!-- Status Selection -->
        <div class="form-group">
            <label for="" class="control-label">Status</label>
            <select name="status" id="status" class="custom-select">
                <option value="0" <?php echo isset($status) && $status == 0 ? "selected" : (!isset($status) ? "selected" : '') ?>>For Verification</option>
                <option value="1" <?php echo isset($status) && $status == 1 ? "selected" : '' ?>>Confirmed</option>
                <option value="2" <?php echo isset($status) && $status == 2 ? "selected" : '' ?>>Canceled</option>
            </select>
        </div>

        <!-- Error Message Container -->
        <div id="error-message" class="text-danger" style="display:none; margin-bottom: 10px;"></div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Check Registration Fee on Load
    checkRegistrationFee();

    // Handle Event Change
    $('#event_id').change(function() {
        const selectedEvent = this.options[this.selectedIndex];
        const registrationFee = selectedEvent.getAttribute('data-fee');

		// Automatically set status to Confirmed and Paid if the event has no reg fee
        if (registrationFee == 0) {
            $('#payment_status').prop('checked', true);
            $('select[name="status"]').val(1); 
            $('#payment_status_container').hide(); 
        } else {
            $('#payment_status_container').show(); 
        }
    });

    $('#manage-register').submit(function(e) {
        e.preventDefault();
        $('#error-message').hide().text('');

        // Validate that all required fields are filled out
        const requiredFields = ['name', 'address', 'email', 'contact', 'event_id'];
        let allFieldsFilled = true;
        let firstEmptyField = null;

        requiredFields.forEach(field => {
            const input = $(`[name="${field}"]`);
            if (!input.val()) {
                allFieldsFilled = false;
                input.addClass('is-invalid'); // Highlight invalid fields
                if (!firstEmptyField) {
                    firstEmptyField = input; 
                }
            } else {
                input.removeClass('is-invalid'); 
            }
        });

        if (!allFieldsFilled) {
            $('#error-message').text("Please fill out all required fields.").show();
            if (firstEmptyField) {
                firstEmptyField.focus(); // Focus on the first empty field
            }
            return; 
        }

        start_load();
        $.ajax({
            url: 'ajax.php?action=save_register',
            method: "POST",
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Audience Registration successfully updated", "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    });
});

function checkRegistrationFee() {
    const selectedEvent = document.querySelector('#event_id').selectedOptions[0];
    const registrationFee = selectedEvent ? selectedEvent.getAttribute('data-fee') : 0;
	
	// Automatically set status to Confirmed and Paid if the event has no reg fee
    if (registrationFee == 0) {
        document.getElementById('payment_status_container').style.display = 'none';
        document.getElementById('payment_status').checked = true; 
        $('#status').val(1); 
    } else {
        document.getElementById('payment_status_container').style.display = 'block';
        document.getElementById('payment_status').checked = false;
    }
}
</script>

<!--  handles the registration process for an event by providing a user 
interface for attendees to fill out their details and submit their registration.
 provides a registration form for an event, fetching event details from a database. -->
<?php
include 'admin/db_connect.php';

// get audience capacity and amount
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';
$event_query = $conn->query("SELECT audience_capacity, amount, event FROM events WHERE id = '$event_id'");
$event = $event_query->fetch_assoc();
$audience_capacity = $event ? $event['audience_capacity'] : 0;
$amount = $event ? $event['amount'] : 0; 
$event_name = $event ? $event['event'] : ''; 
?>

<div class="container-fluid">
    <form action="" id="manage-register">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
        <input type="hidden" name="event_id" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : ''; ?>">
        
        <h4 class="mb-4">Register for Event</h4>

        <div id="registration-error" class="alert alert-danger" style="display: none;" tabindex="0"></div>
		
		<!-- full name field -->
        <div class="form-group">
            <label for="name" class="control-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
        </div>
		
		<!-- address field -->
        <div class="form-group">
            <label for="address" class="control-label">Address</label>
            <textarea id="address" cols="30" rows="2" required name="address" class="form-control"><?php echo isset($address) ? $address : ''; ?></textarea>
        </div>
		
		<!-- email field -->
        <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
            <div id="email-error" class="invalid-feedback" style="display: none;">Please enter a valid email address (e.g., sample@gmail.com).</div>
        </div>

		<!-- contact # field -->
        <div class="form-group">
            <label for="contact" class="control-label">Contact #</label>
            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($contact) ? $contact : ''; ?>" required>
            <div id="contact-error" class="invalid-feedback" style="display: none;">Please enter a valid contact number (e.g., 09123456789).</div>
        </div>

		<!-- audience capacity label -->
        <div class="form-group">
            <label for="audience_capacity" class="control-label">Audience Capacity (Remaining):</label>
            <?php if ($audience_capacity > 0): ?>
                <label for="capacity_counter" style="color: #007bff; font-weight: bold; font-size: 1rem;">
                    <?php echo $audience_capacity; ?>
                </label>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    The event has no audience capacity or limit.
                </div>
            <?php endif; ?>
        </div>
		
		<!-- registration fee field -->
        <div class="form-group">
            <label for="amount" class="control-label">Registration Fee:</label>
            <?php if ($amount > 0): ?>
                <label for="amount_counter" style="color: #007bff; font-weight: bold; font-size: 1.5rem;">
                    <?php echo number_format($amount, 2); ?> PHP
                </label>
                <div class="alert alert-warning" role="alert">
                    Kindly note that there is a registration fee to secure your spot at the event. 
                    We appreciate your prompt payment before entering the event. Thank you!
                </div>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    We are pleased to inform you that there is no registration fee for this event. 
                    We look forward to your participation!
                </div>
            <?php endif; ?>
        </div>
		
    </form>
</div>

<!-- Modal for Appreciation Message -->
<div class="modal fade" id="appreciationModal" tabindex="-1" role="dialog" aria-labelledby="appreciationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appreciationModalLabel">Thank You!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Thank you for registering for <strong><?php echo ucwords($event_name); ?></strong>. We're excited to have you join us.
            </div>
            <div class="modal-footer">
                <button id="okBtn" type="button" class="btn btn-secondary" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#manage-register').submit(function(e) {
    e.preventDefault();     

    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').hide();
    $('#registration-error').hide(); 

    const name = $('#name').val().trim();
    const email = $('#email').val();
    const contact = $('#contact').val();

    const namePattern = /^[a-zA-Z\s]+$/; // Name format (only letters and spaces)
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Email format
    const contactPattern = /^09[0-9]{9}$/; // Contact format 

    if (name === '' || !namePattern.test(name)) {
        $('#name').addClass('is-invalid'); 
        $('#name-error').show(); 
        $('#name').focus(); 
        return; 
    }

    // Validate email
    if (!emailPattern.test(email)) {
        $('#email').addClass('is-invalid'); 
        $('#email-error').show(); 
        $('#email').focus(); 
        return; 
    }

    // Validate contact number
    if (!contactPattern.test(contact)) {
        $('#contact').addClass('is-invalid'); 
        $('#contact-error').show(); 
        $('#contact').focus(); 
        return; 
    }

    // Check audience capacity
    const audienceCapacity = <?php echo $audience_capacity; ?>;
    if (audienceCapacity <= 0) {
        $('#registration-error').text("No more registrations allowed. The event has reached its audience capacity.").show().focus(); 
        $('html, body').animate({
            scrollTop: $('#registration-error').offset().top
        }, 500); 
        return; 
    }

    start_load();
    $('#msg').html('');
	
	// if the process is success it will sent an email and a notification success for sending email
    $.ajax({
        url: 'admin/ajax.php?action=save_register',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
    if (resp == 1) {
        $.ajax({
            url: 'send_email.php',
            method: 'POST',
            data: {
                email: email,  
                name: name,    
                event_name: "<?php echo ucwords($event_name); ?>" 
            },
            success: function(mailResp) {
                if (mailResp == '1') {
                    alert_toast("Registration Request Sent and Email Sent.", 'success');
                } else {
                    alert_toast("Registration Request Sent but Failed to Send Email.", 'warning');
                }
                end_load();
                $('#appreciationModal').modal('show');

                $('#okBtn').click(function() {
                    location.reload();
                });
            }
        });
    } else {
        alert_toast("Registration failed. Please try again.", 'error');
        end_load();
    }
}

		
		
    });
});
</script>
<!-- Bootstrap CSS  -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

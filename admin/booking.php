<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
	
        <div class="row mb-4 mt-4">
			<!-- space between topNav and table -->
        </div>
		
        <div class="row">
            <div class="col-md-12">
                <div class="card">
				
                    <div class="card-header">
                        <b>Venue Booking List</b>
                        <span class="">
                            <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_book">
                                <i class="fa fa-plus"></i> New
                            </button>
                        </span>
                    </div>
					
                    <div class="card-body">
                        <table class="table table-bordered table-condensed table-hover" style="background-color: #f0f0f0;">
						<!-- Column names -->
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Booking Information</th>
                                    <th class="">Customer Information</th>
                                    <th class="">Status</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
							
                            <tbody>
							
                                <?php 
                                $i = 1;
                                $booking = $conn->query("SELECT b.*, v.venue FROM venue_booking b INNER JOIN venue v ON v.id = b.venue_id ORDER BY b.created_at DESC");
                                
                                if ($booking->num_rows > 0): 
                                    while($row = $booking->fetch_assoc()):
                                ?>
								
                                <tr style="background-color: #f0f0f0;">
                                    <td class="text-center"><?php echo $i++ ?></td>
									
									<!-- Booking Information -->
                                    <td class="">
                                        <p>Venue: <b><?php echo htmlspecialchars(ucwords($row['venue'])) ?></b></p>
                                        <?php 
                                            if ($row['timeofevent'] == 0) {
                                                $time = '8am - 12pm';
                                            } elseif ($row['timeofevent'] == 1) {
                                                $time = '12pm - 1pm';
                                            } elseif ($row['timeofevent'] == 2) {
                                                $time = '4pm - 8pm';
                                            } elseif ($row['timeofevent'] == 3) {
                                                $time = '8pm - 12am';
                                            } else {
                                                $time = 'Unknown time slot'; 
                                            }
                                        ?>
                                        <p><small>Schedule: <b><?php echo date("M d, Y", strtotime($row['datetime'])); ?></b>, Time: <b><?php echo $time; ?></b></small></p>
                                    </td>
									
									<!-- Customer Information -->
                                    <td class="">
                                        <p>Booked by: <b><?php echo htmlspecialchars(ucwords($row['name'])) ?></b></p>
                                        <p><small>Email: <b><?php echo htmlspecialchars(($row['email'])) ?></b></small></p>
                                        <p><small>Contact: <b><?php echo htmlspecialchars(ucwords($row['contact'])) ?></b></small></p>
                                        <p><small>Address: <b><?php echo htmlspecialchars(ucwords($row['address'])) ?></b></small></p>
                                        <?php $payment_option = ($row['payment'] == 0) ? 'Cash' : 'GCash';?>
                                        <p><small>Payment Option: <b><?php echo $payment_option; ?></b></small></p>
                                    </td>
									
									<!-- Status -->
                                    <td class="text-center">
                                        <?php if($row['status'] == 0): ?>
                                            <span class="badge badge-secondary">For Verification</span>
                                        <?php elseif($row['status'] == 1): ?>
                                            <span class="badge badge-primary">Confirmed</span>
                                        <?php elseif($row['status'] == 2): ?>
                                            <span class="badge badge-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
									
									<!-- Created At -->
                                    <td class="text-center" style="max-width: 105px;">
                                        <small><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></small>
                                    </td>
									
									<!-- Action -->
                                    <td class="text-center">
    <button class="btn btn-sm btn-outline-primary edit_book" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
    <?php if ($row['status'] == 0): // Only show the confirm button if the status is 'For Verification' ?>
        <button class="btn btn-sm btn-outline-success confirm_booking" type="button" data-id="<?php echo $row['id'] ?>">Confirm</button>
		<button class="btn btn-sm btn-outline-danger cancel_book" type="button" data-id="<?php echo $row['id'] ?>">Cancel</button>
    <?php endif; ?>
</td>

									
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No bookings found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paid (checkbox), table alignment and image size) -->
    <style>
        input[type=checkbox] {
            -ms-transform: scale(1.5);
            -moz-transform: scale(1.5);
            -webkit-transform: scale(1.5);
            -o-transform: scale(1.5);
            transform: scale(1.5);
            padding: 10px;
        }

        td {
            vertical-align: middle !important;
        }

        td p {
            margin: unset;
        }

        img {
            max-width: 100px;
            max-height: 150px;
        }
    </style>

<script>
// Handle Confirm button click
$('.confirm_booking').click(function(){
    var booking_id = $(this).data('id');
    
    // Confirm the booking with an AJAX request
    $.ajax({
        url: 'update_booking_status.php', // This file will handle the database update
        type: 'POST',
        data: {
            id: booking_id,
            status: 1 // 1 means confirmed
        },
        success: function(response) {
            if(response == 'success') {
                // Update the status in the table
                alert("Booking Confirmed!");
                location.reload(); // Reload the page to reflect the changes
            } else {
                alert("Error confirming booking.");
            }
        }
    });
});

$(document).ready(function () {
    // Handle the cancel button click
    $('.cancel_book').click(function () {
        const bookingId = $(this).data('id');

        // Confirm with the user
        if (confirm("Are you sure you want to cancel this booking?")) {
            // Send AJAX request to update the status
            $.ajax({
                url: 'update_booking_status.php',
                method: 'POST',
                data: { id: bookingId, status: 2 }, // 2 = Canceled
                success: function (response) {
                    if (response.trim() === 'success') {
                        alert('Booking canceled successfully!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error canceling booking: ' + response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});


	//  all <table> elements enhancing functionality and usability by adding features like sorting and pagination.
    $(document).ready(function(){
        $('table').dataTable();
    });
	
	// when clicked, a modal window is open "New Entry" and loads the manage_booking.php to create new entries in database or to manage bookings 
    $('#new_book').click(function(){
        uni_modal("New Entry","manage_booking.php");
    });
	
	// to edit or manage specific records
    $('.edit_book').click(function(){
        uni_modal("Manage Book Details","manage_booking.php?id="+$(this).attr('data-id'));
    });
</script>

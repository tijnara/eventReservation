<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<!-- Paid (checkbox) -->
    <style>
        input[type=checkbox] {
            /* Double-sized Checkboxes */
            -ms-transform: scale(1.5); /* IE */
            -moz-transform: scale(1.5); /* FF */
            -webkit-transform: scale(1.5); /* Safari and Chrome */
            -o-transform: scale(1.5); /* Opera */
            transform: scale(1.5);
            padding: 10px;
        }
    </style>

    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>

        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Event Audience List</b>
                        <span>
                            <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_register">
                                <i class="fa fa-plus"></i> New
                            </button>
                        </span>
                    </div>
                    <div class="card-body">
						<!-- Table for Aaudience List -->
                        <table class="table table-bordered table-condensed table-hover" style="background-color: #f0f0f0;">
						
                            <thead>
								<!-- Column names -->
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Event Information</th>
                                    <th>Audience Information</th>
                                    <th>Date Created</th> 
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
							
                            <tbody>
                                <?php 
                                $i = 1;
                                // query to order by date_created descending
                                $registering = $conn->query("SELECT a.*, e.event, e.payment_type, e.type, e.amount, e.schedule 
                                                              FROM audience a 
                                                              INNER JOIN events e ON e.id = a.event_id 
                                                              ORDER BY a.date_created DESC");
                                while ($row = $registering->fetch_assoc()): 
								?>
                                    <tr style="background-color: #f0f0f0;">
                                        <td class="text-center"><?php echo $i++ ?></td>
										
										<!-- Event Information -->
                                        <td>
                                            <p>Event: <b><?php echo ucwords($row['event']) ?></b></p>
                                            <p><small>Schedule: <b><?php echo date("M d, Y", strtotime($row['schedule'])) ?></b></small></p>
                                            <p><small>Type: <b><?php echo $row['type'] == 1 ? "Public Event" : "Private Event" ?></b></small></p>
                                            <p><small>Fee: <b><?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2) ?></b></small></p>
                                        </td>
										
										<!-- Audience Information -->
                                        <td>
                                            <p>Name: <b><?php echo ucwords($row['name']) ?></b></p>
                                            <p><small>Email: <b><?php echo ($row['email']) ?></b></small></p>
                                            <p><small>Contact: <b><?php echo ucwords($row['contact']) ?></b></small></p>
                                            <p><small>Address: <b><?php echo ucwords($row['address']) ?></b></small></p>
                                            <p><small>Payment Status: <b><?php echo $row['payment_type'] == 1 ? "N/A" : ($row['payment_status'] == 1 ? "Paid" : "Unpaid") ?></b></small></p>
                                        </td>
										
										<!-- Date Created -->
                                        <td>
                                            <p><b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></b></p> 
                                        </td>
										
										<!-- Status -->
                                        <td class="text-center">
                                            <?php if ($row['status'] == 0): ?>
                                                <span class="badge badge-secondary">For Verification</span>
                                            <?php elseif ($row['status'] == 1): ?>
                                                <span class="badge badge-primary">Confirmed</span>
                                            <?php elseif ($row['status'] == 2): ?>
                                                <span class="badge badge-danger">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
										
										<!-- Action -->
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary edit_register" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
                                            <?php if (in_array($row['status'], array(0, 2))): ?>
                                                <!--<button class="btn btn-sm btn-outline-danger delete_register" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>-->
                                            <?php endif; ?>
                                        </td>
										
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>
<!-- Styles for tables -->
<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset
    }
    img {
        max-width: 100px;
        max-height: 150px;
    }
</style>

<script>
	//all <table> elements on the page as DataTables for improved user interaction and experience with table data.
    $(document).ready(function() {
        $('table').dataTable();
    });
	// opens a modal displaying the form for creating a new entry.
    $('#new_register').click(function() {
        uni_modal("New Entry", "manage_register.php");
    });
	// When an edit button is clicked, it open a modal "Manage register Details" and edit the details for that ID
    $('.edit_register').click(function() {
        uni_modal("Manage register Details", "manage_register.php?id=" + $(this).attr('data-id'));
    });
	// delete the selected ID
    /* $('.delete_register').click(function() {
        _conf("Are you sure to delete this Person?", "delete_register", [$(this).attr('data-id')]);
    });
	// delete a specific record from the database
    function delete_register($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_register',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    } */
</script>

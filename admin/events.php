<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
			<!-- space between topNav and table -->
            <div class="col-md-12"></div>
        </div>
		
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
				
                    <div class="card-header">
                        <b>List of Events</b>
                        <span class="float-right">
                            <a class="btn btn-primary btn-block btn-sm col-sm-15 float-right" href="index.php?page=manage_event" id="new_event">
                                <i class="fa fa-plus"></i> New Entry
                            </a>
                        </span>
                    </div>
					
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover" style="width: 100%; table-layout: auto;">
						
                            <colgroup>
                                <col>  <!-- # column -->
                                <col style="width: 15%;">  <!-- Schedule column -->
                                <col style="width: 15%;">  <!-- Venue column -->
                                <col style="width: 20%;">  <!-- Event Info column -->
                                <col style="width: 15%;">  <!-- Date Created column -->
                                <col style="width: 20%;">  <!-- Description column -->
                                <col style="width: 10%;">  <!-- Action column -->
                            </colgroup>
							
							<!-- Column Names -->
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Schedule</th>
                                    <th>Venue</th>
                                    <th>Event Info.</th>
                                    <th>Date Created</th>
                                    <th>Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
							
                            <tbody>
							
                                <!-- get events -->
                                <?php 
                                $i = 1;
                                $events = $conn->query("SELECT e.*, v.venue FROM events e INNER JOIN venue v ON v.id = e.venue_id ORDER BY e.date_created DESC");

                                while ($row = $events->fetch_assoc()):
                                    $event_time = $row['event_time'];

                                    // Map the event_time
                                    switch ($event_time) {
                                        case 0:
                                            $time_slot = "8AM-12PM";
                                            break;
                                        case 1:
                                            $time_slot = "12PM-1PM";
                                            break;
                                        case 2:
                                            $time_slot = "4PM-8PM";
                                            break;
                                        case 3:
                                            $time_slot = "8PM-12AM";
                                            break;
                                        default:
                                            $time_slot = "Not Specified";
                                    }
                                ?>								
								
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
									
									<!-- Schedule -->
                                    <td>
                                        <p><b><?php echo date("M d, Y", strtotime($row['schedule'])); ?></b></p>
                                        <p><small>Time of Event: <b><?php echo $time_slot; ?></b></small></p>
                                    </td>
									
									<!-- Venue -->
                                    <td>
                                        <p><b><?php echo ucwords($row['venue']); ?></b></p>
                                    </td>
									
									<!-- Event Info -->
                                    <td>
                                        <p>Event: <b><?php echo ucwords($row['event']); ?></b></p>
                                        <p><small>Fee: <b><?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2); ?></b></small></p>
                                    </td>
									
									<!-- Date Created -->
                                    <td>
                                        <p><b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])); ?></b></p>
                                    </td>
									
									<!-- Description -->
                                    <td>
                                        <p class="truncate"><?php echo nl2br(htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                                    </td>
									
									<!-- Action -->
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary view_event" type="button" data-id="<?php echo $row['id']; ?>">View</button>
                                        <button class="btn btn-sm btn-outline-primary edit_event" type="button" data-id="<?php echo $row['id']; ?>">Edit</button>
                                      <!--<button class="btn btn-sm btn-outline-danger delete_event" type="button" data-id="<?php echo $row['id']; ?>">Delete</button>-->
                                    </td>
									
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>	
</div>

<style>
    /* action column */
    td.text-center {
        white-space: nowrap; 
    }
    td.text-center button {
        margin-right: 5px; 
    }
    td.text-center button:last-child {
        margin-right: 0; 
    }

    /* full table */
    td {
        vertical-align: middle !important;
    }
    
    td p {
        margin: unset;
    }
</style>

<script>
	// allows you to create a more dynamic and manageable table
    $(document).ready(function(){
        $('table').dataTable();
    });
	
    // redirects to a new page (index.php?page=view_event) 
    $('.view_event').click(function(){
        location.href = "index.php?page=view_event&id=" + $(this).attr('data-id');
    });
    
	// When Edit is clicked, the user is redirected to the "index.php?page=manage_event&id=" page
    $('.edit_event').click(function(){
        location.href = "index.php?page=manage_event&id=" + $(this).attr('data-id');
    });
    
	// delete the selected event
    /* $('.delete_event').click(function(){
        _conf("Are you sure to delete this event?", "delete_event", [$(this).attr('data-id')]);
    }); */
    
	// function to delete the event to database
/*     function delete_event($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_event',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    } */
</script>

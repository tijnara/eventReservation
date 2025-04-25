<?php
include('db_connect.php');
?>

<style>
    .review-text {
        max-height: 100px;
        overflow-y: auto;
        white-space: pre-wrap;
    }
</style>

<!-- Container for content -->
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
				
                    <div class="card-header">
                        <b>Manage Reviews</b>
                    </div>
					
                    <div class="card-body">
                        <!-- Reviews table -->
                        <table class="table table-condensed table-bordered" style="background-color: #f0f0f0;">
							<!-- coulmn names -->
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" style="min-width: 100px; max-width: 100px;">Reviewer Name</th>
                                    <th class="text-center" style="min-width: 120px; max-width: 100px;">Email</th>
                                    <th class="text-center" style="min-width: 20px; max-width: 80px;">Category</th>
                                    <th class="text-center" style="min-width: 15px; max-width: 65px;">Rating</th>
                                    <th class="text-center" style="max-width: 250px;">Review Content</th>
                                    <th class="text-center" style="min-width: 60px; max-width: 115px;">Created At</th>
                                    <th class="text-center" style="min-width: 75px; max-width: 77px;">Status</th>
                                    <th class="text-center" style="min-width: 130px; max-width: 130px;">Action</th>
                                </tr>
                            </thead>
							
                            <tbody>
                                <?php 
                                $i = 1; 
                                $reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
                                while ($row = $reviews->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
									
									<!-- Reviewer name -->
                                    <td style="min-width: 100px; max-width: 100px;"><?php echo htmlspecialchars($row['name']); ?></td>
									
									<!-- email -->
                                    <td style="min-width: 100px; max-width: 120px;"><?php echo htmlspecialchars($row['email']); ?></td>
									
									<!-- category -->
                                    <td style="min-width: 15px; max-width: 15px;"><?php echo htmlspecialchars($row['category']); ?></td>
									
									<!-- rating -->
                                    <td style="min-width: 15px; max-width: 55px;"><?php echo htmlspecialchars($row['rating']); ?>/5</td>
									
									<!-- Review content -->
                                    <td class="review-text" style="max-width: 250px; word-wrap: break-word; white-space: normal;"><?php echo htmlspecialchars($row['review_text']); ?></td>
									
									<!-- created at -->
                                    <td style="word-wrap: break-word; white-space: normal; min-width: 60px; max-width: 115px;"><?php echo htmlspecialchars(date('Y-m-d h:i A', strtotime($row['created_at']))); ?></td>
                                    
									<!-- status -->
									<td style="min-width: 60px; max-width: 60px;" class="text-center">
                                        <?php if ($row['is_displayed'] == 1): ?>
                                            <span class="badge bg-success">Displayed</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Hidden</span>
                                        <?php endif; ?>
                                    </td>
									
									<!-- action -->
                                    <td style="min-width: 60px; max-width: 90px;" class="text-center">
                                        <button class="btn btn-danger btn-sm delete-review" data-id="<?php echo $row['id']; ?>" style="margin-right: 10px;">Delete</button>
                                        <?php if ($row['is_displayed'] == 1): ?>
                                            <button class="btn btn-warning btn-sm hide-review" data-id="<?php echo $row['id']; ?>">Hide</button>
                                        <?php else: ?>
                                            <button class="btn btn-success btn-sm show-review" data-id="<?php echo $row['id']; ?>">Show</button>
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

<script>
$(document).ready(function(){
        $('table').dataTable();
    });
	
	/* ask for confirmation before deleting a review upon confirmation, it will execute the delete_review function using the review's ID. */
    $('.delete-review').click(function() {
        _conf("Are you sure to delete this Review?", "delete_review", [$(this).attr('data-id')]);
    });

	// function to delete review
    function delete_review(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_review',
            method: 'POST',
            data: {id: id},
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }

	// confirmation if hide or not
    $('.hide-review').click(function() {
        const reviewId = $(this).attr('data-id');
        _conf("Are you sure to hide this Review?", "hide_review", [reviewId]);
    });
	
	// function to hide review in client side
    function hide_review(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=hide_review',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Review successfully hidden", 'success');
                    $(`.hide-review[data-id="${id}"]`).removeClass('btn-warning').addClass('btn-success').text('Show').attr('class', 'btn btn-success btn-sm show-review').attr('data-id', id);
                    $(`.badge[data-id="${id}"]`).removeClass('bg-success').addClass('bg-danger').text('Hidden');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert_toast("Failed to hide review", 'danger');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert_toast("Error: " + textStatus, 'danger');
                console.error('Error Details:', errorThrown);
            },
            complete: function() {
                end_load();
            }
        });
    }
	
	// confirmation to show review in client side
    $('.show-review').click(function() {
        const reviewId = $(this).attr('data-id');
        _conf("Are you sure to show this Review?", "show_review", [reviewId]);
    });

	// function to show review
    function show_review(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=show_review',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Review successfully made visible", 'success');
                    $(`.show-review[data-id="${id}"]`).removeClass('btn-success').addClass('btn-warning').text('Hide').attr('class', 'btn btn-warning btn-sm hide-review').attr('data-id', id);
                    $(`.badge[data-id="${id}"]`).removeClass('bg-danger').addClass('bg-success').text('Displayed');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert_toast("Failed to show review!!", 'danger');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert_toast("Error: " + textStatus, 'danger');
                console.error('Error Details:', errorThrown);
            },
            complete: function() {
                end_load();
            }
        });
    }
</script>

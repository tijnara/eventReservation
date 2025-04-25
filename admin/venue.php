<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
				
                    <div class="card-header">
                        <b>List of Venue</b>
                        <span class="float-right">
                            <a class="btn btn-primary btn-sm" href="index.php?page=manage_venue" id="new_venue">
                                <i class="fa fa-plus"></i> New Entry
                            </a>
                        </span>
                    </div>
					
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover" style="background-color: #f0f0f0;">
						
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="15%">
                                <col width="50%">
                                <col width="7.5%">
                                <col width="7.5%">
                                <col width="10%">
                            </colgroup>
							
							<!-- column names -->
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Venue</th>
                                    <th>Address</th>
                                    <th>Description</th>
                                    <th>Rate</th>
                                    <th>Max Capacity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
							
                            <tbody>
                                <?php 
                                $i = 1;
                                $venue = $conn->query("SELECT * FROM venue");
                                while($row = $venue->fetch_assoc()):
                                    $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                                    unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                                ?>
                                <tr style="background-color: #f0f0f0;">
                                    <td class="text-center"><?php echo $i++ ?></td>
									
									<!-- venue -->
                                    <td><b><?php echo ucwords($row['venue']) ?></b></td>
									
									<!-- address -->
                                    <td><?php echo $row['address'] ?></td>
									
									<!-- description -->
                                    <td><?php echo $row['description'] ?></td>
									
									<!-- rate -->
                                    <td class="text-right"><?php echo number_format($row['rate'], 2) ?></td>
									
									<!-- max capacity -->
                                    <td class="text-right"><?php echo $row['max_capacity'] ?></td>
									
									<!-- action -->
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary edit_venue" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
                                            &nbsp
                                            <button class="btn btn-sm btn-outline-danger delete_venue" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                        </div>
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

<style>
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
// <table> elements on the page and enhance them with the interactive functionality
    $(document).ready(function() {
        $('table').dataTable();
    });
	// redirect to a venue management page for editing purposes.
    $('.edit_venue').click(function() {
        location.href = "index.php?page=manage_venue&id=" + $(this).attr('data-id');
    });
	// delete the specified venue.
    $('.delete_venue').click(function() {
        _conf("Are you sure to delete this venue?", "delete_venue", [$(this).attr('data-id')]);
    });

	// delete a venue record from the database
    function delete_venue($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_venue',
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
    }
</script>

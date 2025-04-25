<?php 
include 'db_connect.php';
?>

<div class="container-fluid">
    <div class="row">
	
        <div class="col-lg-12">
            <button class="btn btn-primary float-right btn-sm" id="new_user">
                <i class="fa fa-plus"></i> New User
            </button>
        </div>
		
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table-bordered col-md-12">
				
					<!-- column names -->
                    <thead>
                        <tr style="background-color: #f0f0f0;">
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
					
                    <tbody>
                        <?php
                            $type = array("","Admin","Staff");
                            $users = $conn->query("SELECT * FROM users ORDER BY name ASC");
                            $i = 1;
                            while($row = $users->fetch_assoc()):
                        ?>
                        <tr style="background-color: #f0f0f0;">
							
                            <td class="text-center"><?php echo $i++ ?></td>
							
							<!-- name -->
                            <td><?php echo ucwords($row['name']) ?></td>
							
							<!-- username -->
                            <td><?php echo $row['username'] ?></td>
							
							<!--type -->
                            <td><?php echo $type[$row['type']] ?></td>
							
							<!-- action -->
                            <td>
                                <center>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary">Action</button>
                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item edit_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item delete_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                                        </div>
                                    </div>
                                </center>
                            </td>
							
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // DataTable
$('table').dataTable();

// for the "New User" button
$('#new_user').click(function() {
    // upon clicking it will direct to manage_user, a blank form to create a new user
    uni_modal('New User', 'manage_user.php?create=1'); 
});

// "Edit User" upon clicking it will direct to manage_user that edit the user depends on id
$('.edit_user').click(function() {
        uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id')); // will open/load the manager_user.php
});

// for "Delete User"
$('.delete_user').click(function() {    
    _conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')]); 
});

// to delete a user from the database
function delete_user(id) {
    start_load(); 
    
    $.ajax({
        url: 'ajax.php?action=delete_user', 
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

</script>

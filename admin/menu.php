<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">

        <!-- Header Row -->
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>

        <!-- Food Menu Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
				
                    <div class="card-header">
                        <b>Food Menu</b>
                        <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_menu">
                            <i class="fa fa-plus"></i> New
                        </button>
                    </div>
					
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
							<!-- column names -->
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Package</th>
                                    <th>Food List</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
							
                            <tbody>
                                <?php 
                                $i = 1;
                                $menu = $conn->query("SELECT * from menu");
                                while($row = $menu->fetch_assoc()): 
                                ?>
                                    <tr style="background-color: #f0f0f0;">
                                        <td class="text-center"><?php echo $i++; ?></td>
										<!-- price per pax -->
                                        <td>
                                            <p><b><?php 
                                                $perPax = explode(',', $row['perPax']);
                                                foreach ($perPax as $perPax) {
                                                    echo "<br>" . trim($perPax) . "</br>";
                                                }
                                                ?></b></p>
                                        </td>
										
										<!-- Foods List -->
                                        <td><p><ul>
                                                <?php 
                                                $foods = explode(',', $row['foods']);
                                                foreach ($foods as $food) {
                                                    echo "<li>" . trim($food) . "</li>";
                                                }
                                                ?>
                                            </ul></p></td>
											
										<!-- Action -->	
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary edit_menu" type="button" data-id="<?php echo $row['id']; ?>">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger delete_menu" type="button" data-id="<?php echo $row['id']; ?>">Delete</button>
                                        </td>
										
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Inclusion and Additional Charges Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
				
                    <div class="card-header">
                        <b>Package Inclusion and Additional Charges</b>
                        <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_package">
                            <i class="fa fa-plus"></i> New
                        </button>
                    </div>
					
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover" style="background-color: #f0f0f0;">
						
                            <thead>
							<!-- content -->
                                <tr>
                                    <th class="text-center"></th>
                                    <?php 
                                    $i = 1;
                                    $packages = $conn->query("SELECT * FROM package");
                                    $package_data = [];
                                    while ($row = $packages->fetch_assoc()):
                                        $package_data[] = $row;
                                    ?>
                                    <th>Content</th>
                                    <?php endwhile; ?>
                                </tr>
                            </thead>
							
                            <tbody>
								<!-- package and inclusions -->
                                <tr>
                                    <td><b>Package Inclusion</b></td>
                                    <?php foreach ($package_data as $package): ?>
                                    <td><?php echo nl2br(ucwords($package['inclusion'])); ?></td>
                                    <?php endforeach; ?>
                                </tr>
								
								<!-- aditional charges -->
                                <tr>
                                    <td><b>Additional Charges</b></td>
                                    <?php foreach ($package_data as $package): ?>
                                    <td><?php echo nl2br(ucwords($package['addcharges'])); ?></td>
                                    <?php endforeach; ?>
                                </tr>
								
								<!-- actions -->
                                <tr>
                                    <td class="text-center"><b>Actions</b></td>
                                    <?php foreach ($package_data as $package): ?>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary edit_package" type="button" data-id="<?php echo $package['id']; ?>">Edit</button>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
								
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
    $(document).ready(function() {
		// add a new package
        $('#new_package').click(function() {
            uni_modal("New Package", "manage_package.php");
        });
		
		//edit an existing package
        $('.edit_package').click(function() {
            uni_modal("Edit Package", "manage_package.php?id=" + $(this).attr('data-id'));
        });
		
		//  deleting a package
        $('.delete_package').click(function() {
            _conf("Are you sure to delete this package?", "delete_package", [$(this).attr('data-id')]);
        });
		
		// add a new menu entry
        $('#new_menu').click(function() {
            uni_modal("New Menu Entry", "manage_menu.php");
        });
		
		//  edit a menu item
        $('.edit_menu').click(function() {
            uni_modal("Edit Menu", "manage_menu.php?id=" + $(this).attr('data-id'));
        });
		
		// delete a menu item
        $('.delete_menu').click(function() {
            _conf("Are you sure to delete this menu?", "delete_menu", [$(this).attr('data-id')]);
        });
		
		// handler for submission
        $('#manage-menu').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_menu',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Successfully updated", "success");
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });

		// handler for submission
        $('#manage-package').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_package',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Successfully updated", "success");
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });
    });

	// function to request from admin_class to delete package 
    function delete_package($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_package',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Package successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }

	// function to request from admin_class to delete menu
    function delete_menu($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_menu',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Menu successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>

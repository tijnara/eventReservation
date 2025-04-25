<?php

// editing an existing user, values will be replaced with the user data
$meta = array(// used to store default empty values for a new user
    'id' => '',         
    'name' => '',       
    'contact' => '',    
    'address' => '',    
    'username' => '',   
    'password' => '',   
    'type' => ''        
);

// indicates that we are editing an existing user
if (isset($_GET['id'])) {// check the id
    include 'db_connect.php'; 
    
    $qry = $conn->query("SELECT * FROM users WHERE id=" . $_GET['id']);// get the user's data based on the ID

    // get the data and replace default values in the $meta array
    if ($qry->num_rows > 0) { // if the user is found with the ID
        $meta = $qry->fetch_assoc(); 
    }
}
?>

<div class="container-fluid">
    <div id="msg"></div>

    <form action="" id="manage-user">
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : ''; ?>">

		<!-- name field -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : ''; ?>" required>
        </div>
        
		<!-- contact field -->
        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="tel" name="contact" id="contact" class="form-control" pattern="^09\d{9}$" value="<?php echo isset($meta['contact']) ? $meta['contact'] : ''; ?>" required>
            <small class="form-text text-muted">Format: 09123456789</small>
        </div>

		<!-- address field -->
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" class="form-control" value="<?php echo isset($meta['address']) ? $meta['address'] : ''; ?>" required>
        </div>

		<!-- username field -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : ''; ?>" required autocomplete="off">
        </div>

		<!-- password field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
            <?php if (isset($meta['id']) && !empty($meta['id'])): ?>
                <small><i>Leave this blank if you don't want to change the password.</i></small>
            <?php endif; ?>
        </div>
		
		<!-- user type selection -->
        <div class="form-group">
            <label for="type">User Type</label>
            <select name="type" id="type" class="custom-select" required>
                <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : ''; ?>>Staff</option>
                <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : ''; ?>>Admin</option>
            </select>
			
        </div>
    </form>
</div>

<script>
    // jQuery event listener
$('#manage-user').submit(function(e) {
    e.preventDefault(); 

    start_load();

    // save the user data
    $.ajax({
        url: 'ajax.php?action=save_user', 
        method: 'POST', 
        data: $(this).serialize(), 

        success: function(resp) {
            if (resp == 1) {
                // will show a success message
                alert_toast("Data successfully saved", 'success'); 

                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                // If the server responds other than 1, assume it Cannot be save.
                $('#msg').html('<div class="alert alert-danger">Cannot be save.</div>'); 
                end_load(); 
            }
        }
    });
});

</script>

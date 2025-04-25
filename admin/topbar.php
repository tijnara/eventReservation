<style>
    .logo {
        margin: auto;
        font-size: 20px;
        background: white;
        padding: 7px 11px;
        border-radius: 50%;
        color: #000000b3;
    }

    .navbar {
        background-color: rgba(255, 255, 255, 0.5); 
        max-height: 5rem; 
    }

    .navbar .logo-container {
        display: flex;
        align-items: center; 
    }

    .navbar img {
        margin-right: 10px; 
        height: 32px; 
    }

    .navbar .system-name {
        font-size: 20px; 
        color: #000; 
    }

    /* styles for positioning the dropdown */
    .account-settings {
        position: absolute; 
        right: 0; 
        top: 10px; 
    }
</style>

<nav class="navbar navbar-light fixed-top" style="padding: 0;">
    <div class="container-fluid mt-2 mb-2">
        <div class="col-lg-12">
		
            <!-- System name and logo -->
            <div class="logo-container">
                <img src="favicon-32x32.png" alt="System Logo">
                <div class="system-name">
                    <strong><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></strong>
                </div>
            </div>
			
            <!-- Dropdown menu for account settings -->
            <div class="account-settings float-right"> 
                <div class="dropdown mr-4">
                    <a href="#" class="text-dark dropdown-toggle" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['login_name'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
                        <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account">
                            <i class="fa fa-cog"></i> Manage Account
                        </a>
                        <a class="dropdown-item" href="ajax.php?action=logout">
                            <i class="fa fa-power-off"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Handler that allows users to manage their account settings
    $('#manage_my_account').click(function() {
        uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own");
    });
</script>

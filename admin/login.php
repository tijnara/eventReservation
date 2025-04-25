<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if (!isset($_SESSION['system'])) {
    $system = $conn->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
    foreach ($system as $k => $v) {
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo $_SESSION['system']['name']; ?> - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
	
    <style>
        body {
    background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img']; ?>) no-repeat center center fixed; 
    background-size: cover; 
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px; 
}

.container-fluid {
    flex-shrink: 0; 
}

.rainbow-text {
    font-size: 8vw; 
    color: white; 
    font-family: 'Great Vibes', cursive;
    text-align: center;
    line-height: 1.2;
    margin-bottom: 20px; 
}

.login-container {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    width: 100%; 
    max-width: 400px;
    z-index: 1; 
}

@media (min-width: 768px) {
    .rainbow-text {
        font-size: 5rem; 
    }
}
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-md-10 col-lg-8">
                <h2 class="rainbow-text"><?php echo $_SESSION['system']['name']; ?></h2>
            </div>
        </div>
    </div>

    <div class="login-container">
        <h2 class="text-center">Login</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message text-danger text-center">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="button" class="btn btn-primary btn-block" id="login-btn">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $('#login-btn').click(function() {
            $('#login-btn').attr('disabled', true).html('Logging in...');
            $.ajax({
                url: 'ajax.php?action=login',
                method: 'POST',
                data: {
                    username: $('#username').val(),
                    password: $('#password').val()
                },
                error: function(err) {
                    console.log(err);
                    $('#login-btn').removeAttr('disabled').html('Login');
                },
                success: function(resp) {
                    if (resp == 1) {
                        location.href = 'index.php?page=home';
                    } else {
                        $('.error-message').remove();
                        $('#login-form').prepend('<div class="error-message">Username or password is incorrect.</div>');
                        $('#login-btn').removeAttr('disabled').html('Login');
                    }
                }
            });
        });
    </script>
</body>
</html>

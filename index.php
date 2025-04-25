<!-- integrates backend PHP logic with front-end HTML,enabling the display of customizable content 
(such as system settings, navigation, and contact information) from a database. It uses PHP sessions 
to manage and display system-wide data, and it supports dynamic page inclusion based on user navigation.
 The code also includes modals for interaction, a navigation bar, and a footer with contact details,
 all styled with custom CSS and Font Awesome icons. It provides a modular, maintainable structure for the web. -->
<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    include('admin/db_connect.php');
    ob_start();
    $query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    foreach ($query as $key => $value) {
    if(!is_numeric($key))
    $_SESSION['system'][$key] = $value;
    }
    ob_end_flush();
    include('header.php');	
    ?>
	
<!-- for facebook icon-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<body id="page-top">

<!-- Floater Button -->
<div class="floater">
    <a href="#top" class="float-btn" title="Back to Top">
        <i class="fa fa-arrow-up"></i>
    </a>
</div>

<!-- CSS for Floater -->
<style>
    /* Floater Container */
    .floater {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    /* Floater Button Styling */
    .float-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        background-color: #007bff;
        color: #fff;
        border-radius: 50%;
        text-decoration: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        font-size: 20px;
        transition: background-color 0.3s;
    }

    /* Hover Effect */
    .float-btn:hover {
        background-color: #0056b3; 
    }
</style>
<!-- for Smooth Scrolling -->
<script>
    var floatBtn = document.querySelector('.float-btn');
    if (floatBtn !== null) { 
        floatBtn.addEventListener('click', function(event) {
            event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
</script>

<!-- Link to Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-jLKHWMZzXbbbwh5Dh5dXEXeArwC0dd8nYgXnMJt54vv4RjDN6Yd7/FTEYPudS3xk" crossorigin="anonymous">


<!-- Navigation-->
        <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
      </div>
<!-- for System Name, Home, Venues, Packages and about navigator -->	  
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="./"><?php echo $_SESSION['system']['name'] ?></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto my-2 my-lg-0">
			
				<!-- Home-->
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?php echo isset($_GET['page']) && $_GET['page'] == 'home' ? 'active' : '' ?>" href="index.php?page=home">Home</a>
                </li>
				
				<!-- Venues -->
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?php echo isset($_GET['page']) && $_GET['page'] == 'venue' ? 'active' : '' ?>" href="index.php?page=venue">Venues</a>
                </li>
				
				<!--Food Packages-->
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?php echo isset($_GET['page']) && $_GET['page'] == 'menu' ? 'active' : '' ?>" href="index.php?page=menu">Food Packages</a>
                </li>
				
				<!-- About -->
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger <?php echo isset($_GET['page']) && $_GET['page'] == 'about' ? 'active' : '' ?>" href="index.php?page=about">About</a>
                </li>
				
            </ul>
        </div>
    </div>
</nav>
       
        <?php 
        $page = isset($_GET['page']) ?$_GET['page'] : "home";
        include $page.'.php';
        ?>
       

<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  
  
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" id="cancelBooking" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  
  
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-righ t"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  
  
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
  
  
  <div id="preloader"></div>
  
  
<!-- footer ------------------------------------------------->  
<footer class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mt-0 text-white">Contact us</h2>
                <hr class="custom-divider2 my-4" />
            </div>
        </div>
		
        <div class="row text-center">
			<!-- Phone Number -->
            <div class="col-lg-4 mb-5 mb-lg-0">
                <i class="fas fa-phone fa-3x mb-3 text-muted"></i>
                <div class="text-white"><?php echo $_SESSION['system']['contact'] ?></div>
            </div>
			
			<!-- Facebook -->
            <div class="col-lg-4 mb-5 mb-lg-0">
                <a href="https://www.facebook.com/ReginasGardenandRestaurant" target="_blank" class="custom-link" style="color: #3b5998;"> 
                    <i class="fab fa-facebook fa-3x mb-3"></i>
                </a>
                <div class="text-white">Follow us on Facebook</div>
            </div>
			
			<!-- Email -->
            <div class="col-lg-4">
                <i class="fas fa-envelope fa-3x mb-3" style="color: #f39c12;"></i> 
                <a class="custom-link d-block" href="mailto:<?php echo $_SESSION['system']['email'] ?>" style="color: #f39c12;"> 
                    <?php echo $_SESSION['system']['email'] ?>
                </a>
            </div>
			
        </div>
    </div>
    <br>
    <div class="container">
        <div class="small text-center text-muted">Copyright © 2024 - <?php echo $_SESSION['system']['name'] ?></div>
    </div>
</footer>


<style>

header.masthead {
    background: url(admin/assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) no-repeat center center;
    background-size: contain; 
	background-size: cover;
    height: 100vh;
	
}

		header.masthead::before {
    display: none; 
}
    
  #viewer_modal .btn-close {
    position: absolute;
    z-index: 999999;
    /*right: -4.5em;*/
    background: unset;
    color: white;
    border: unset;
    font-size: 27px;
    top: 0;
}
#viewer_modal .modal-dialog {
        width: 80%;
    max-width: unset;
    height: calc(90%);
    max-height: unset;
}
  #viewer_modal .modal-content {
       background: black;
    border: unset;
    height: calc(100%);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  #viewer_modal img,#viewer_modal video{
    max-height: calc(100%);
    max-width: calc(100%);
  }
  body, footer {
    background: #000000e6 !important;
}

	/* -------------------for footer(facebook)---------------------- */
    /* Custom CSS for hover effects */
    .custom-link {
        text-decoration: none; 
    }

    /* Change font color to blue on hover */
    .custom-link:hover {
        color: blue !important;
    }
	
	/* -------------------for navigation---------------------- */
	
	.nav-link {
        color: #333; 
    }
    .nav-link.active {
        color: blue !important; /* Change color to blue for the active link */
    }
	/* -------------------divider---------------------- */
	
	.custom-divider2 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 10%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
	
</style>

        
       <?php include('footer.php') ?>
    </body>

    <?php $conn->close() ?>

</html>



<!-- sidebar navigator -->
<nav id="sidebar" class='mx-lt-5 bg-dark'>
    <!-- Sidebar list container -->
    <div class="sidebar-list">
        <!-- home -->
        <a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
        
		<!-- Venue Booking -->
        <a href="index.php?page=booking" class="nav-item nav-booking"><span class='icon-field'><i class="fa fa-th-list"></i></span> Venue Book List</a>
        
		<!-- audience/guest -->
        <a href="index.php?page=audience" class="nav-item nav-audience"><span class='icon-field'><i class="fa fa-th-list"></i></span> Event Audience List</a>
       
	   <!-- food menu -->
        <a href="index.php?page=menu" class="nav-item nav-menu"><span class='icon-field'><i class="fa fa-th-list"></i></span> Food Menu</a>
        
		<!-- venues -->
        <a href="index.php?page=venue" class="nav-item nav-venue"><span class='icon-field'><i class="fa fa-map-marked-alt"></i></span> Venues</a>
        
		<!-- evennts -->
        <a href="index.php?page=events" class="nav-item nav-events"><span class='icon-field'><i class="fa fa-calendar"></i></span> Events</a>
        
		<!-- Reports Dropdown -->
		<a class="nav-item nav-reports" data-toggle="collapse" href="#reportCollapse" role="button" aria-expanded="false" aria-controls="reportCollapse">
		<span class='icon-field'><i class="fa fa-file"></i></span> Reports <i class="fa fa-angle-down float-right"></i></a>
		
	<!-- Dropdown Items -->
	<div class="collapse" id="reportCollapse">
    <!-- Audience Report -->
    <a href="index.php?page=audience_report" class="nav-item nav-audience_report <?php echo isset($_GET['page']) && $_GET['page'] === 'audience_report' ? 'active' : '' ?>">
        <span class='icon-field'><i class="fa fa-users"></i></span> Audience Report
    </a>
    <!-- Venue Report -->
    <a href="index.php?page=venue_report" class="nav-item nav-venue_report <?php echo isset($_GET['page']) && $_GET['page'] === 'venue_report' ? 'active' : '' ?>">
        <span class='icon-field'><i class="fa fa-map-marker-alt"></i></span> Events Report
    </a>
    <!-- Booking Venue Report -->
    <a href="index.php?page=booking_venue_report" class="nav-item nav-booking_venue_report nav-booking-link <?php echo isset($_GET['page']) && $_GET['page'] === 'booking_venue_report' ? 'active' : '' ?>">
    <span class='icon-field' style="font-size: 13px;"><i class="fa fa-book-open"></i> Booking Venue Report</span></a>

<!-- for admin users (login_type = 1) -->
</div>
		
        <?php if($_SESSION['login_type'] == 1): ?>
        
		<!-- users -->
        <a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
        
		<!-- manage reviews -->
        <a href="index.php?page=manage_reviews" class="nav-item nav-reviews <?php echo isset($_GET['page']) && $_GET['page'] === 'manage_reviews' ? 'active' : '' ?>">
		<span class='icon-field'><i class="fa fa-comments"></i></span> Manage Reviews</a>
        
		<!-- system settings -->
        <a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i class="fa fa-cogs"></i></span> System Settings</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    // collapse behavior in reports section
    $('.nav-item[data-toggle="collapse"]').click(function(e) {
        e.preventDefault(); 
        var targetCollapse = $($(this).attr('href'));
        
        // Toggle the collapse only when clicking on the report heading
        if (!targetCollapse.hasClass('show')) {
            targetCollapse.collapse('toggle');  
        }
    });

    // 'active' item to the side nav corresponding navigation item
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>';
    $('.nav-' + page).addClass('active');

    // expand the 'Reports' collapse if the current page is a report
    if (page.includes('report')) {
        $('#reportCollapse').collapse('show');
    }

    // Prevent collapse from closing 
    $('#reportCollapse .nav-item').click(function(e) {
        e.stopPropagation(); 
    });	

    // Set the 'active' class to the side nav item corresponding to the current page
	var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>';
	$('.nav-' + page).addClass('active');

    // expand the 'Reports' collapse if the current page is a report
    if (page.includes('report')) {
        $('#reportCollpase').collapse('show');  // Show the Reports section if a report page is active
    }
</script>

<style>
    /* for links inside collapsible sections */
    .collapse a {
        text-indent: 10px;
    }
	
    /* background with image and optional blur effect for sidebar*/
    nav#sidebar {
        background: rgba(0, 0, 0, 0.5) url(assets/img/admin4.jpg) no-repeat center center; 
        background-size: cover; /*image covers the entire nav area */
        backdrop-filter: blur(10px); /* adds a blur effect to the background */
    }
</style>

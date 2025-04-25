<!-- sets up the essential metadata for the webpage, links to external resources (like stylesheets and JavaScript libraries), 
and includes a script to change the navbar's appearance based on user scrolling. This setup is crucial for creating a visually 
appealing and functional web application that is responsive and user-friendly.-->


<!-- includes basic HTML metadata, custom page title generation, and links to external resources like Font Awesome, 
Google Fonts, and plugins like Select2, jQuery Datetime Picker, and Bootstrap Datepicker.-->
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $_SESSION['system']['name'] ?></title>
		
		
        <!-- Favicon for icons -->
        <link rel="icon" type="image/x-icon"  />
		
		<!-- external resources -->
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
		
        <!-- Third party plugin CSS-->
		<!-- These are third-party CSS files for jQuery's datetime picker and Magnific Popup. 
		They style the date/time picker input fields and the image/video popup respectively. -->		
        <link href="admin/assets/css/jquery.datetimepicker.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
		
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="admin/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" /> <!-- Bootstrap Datepicker: Provides styles for datepicker elements -->
        <link href="css/styles.css" rel="stylesheet" /> <!-- website overall appearance -->
         <link href="admin/assets/css/select2.min.css" rel="stylesheet"><!--  plugin that enhances the standard HTML select boxes with features like search and multi-selection -->

		<!--  jQuery library and the Bootstrap Datepicker plugin -->
        <script src="admin/assets/vendor/jquery/jquery.min.js"></script>
        <script src="admin/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

	
		<script type="text/javascript" src="admin/assets/js/select2.min.js"></script>
		<script type="text/javascript" src="admin/assets/js/jquery.datetimepicker.full.min.js"></script> 
	


	<script>
/* <!-- A scroll event script dynamically changes the navbar's background color based on the scroll position, 
improving the user interface's visual feedback.--> */
	$(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
        $('.navbar').css('background-color', 'rgba(0, 0, 0, 0.25)'); 
    } else {
        $('.navbar').css('background-color', 'rgba(0, 0, 0, 0.5)'); 
    }
});

	</script>
	
	





<!-- a customer reviews section for submitting and displaying feedback, and a contact section with contact details and a message form. Overall, 
it effectively showcases services, encourages customer engagement, and provides essential information. -->
<?php
include 'admin/db_connect.php';
include 'admin/admin_class.php';

// Create a new Action with the database connection
$action = new Action($conn);

// get reviews using the get_reviews method from the Action class
$reviews = $action->get_reviews();

// get email and contact
$sql = "SELECT email, contact FROM system_settings LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $contact = $row['contact'];
} else {
    $email = "Email not found";
    $contact = "Contact not found";
}
?>

<header class="masthead">
	
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">

	<!-- Google Fonts for custom fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">	
	
	<!-- Toastr JS and Toastr notification -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	
	<!-- Link to the Leaflet CSS file for map styling-->
	<link rel="stylesheet" href="css/leaflet.css" />
	
	<!-- Link to the Leaflet JavaScript file for map functionality -->
	<script src="js/leaflet.js"></script>
	
<!-- Display alert message for successful email sent -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'info'; ?> text-center" role="alert" style="margin-top: 20px; border-radius: 10px; padding: 15px; transition: opacity 1s; background-color: <?php echo $_SESSION['alert_type'] === 'success' ? '#28a745' : ($_SESSION['alert_type'] === 'error' ? '#dc3545' : '#17a2b8'); ?>; color: white;">
        <div style="font-weight: bold; font-size: 1.5em;">
            <i class="bi bi-check-circle" style="font-size: 1.2em;"></i> 
            <?php echo $_SESSION['alert_type'] === 'success' ? 'Success!' : ($_SESSION['alert_type'] === 'error' ? 'Error!' : 'Notice!'); ?>
        </div>
        <div style="margin-top: 10px; font-size: 1.2em;">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); 
                unset($_SESSION['alert_type']); 
            ?>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 2500); 
    </script>
<?php endif; ?>

	<!-- Container for the header content (displaying client's Business name) -->
    
   <div class="container-fluid h-75">
    <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-8 align-self-end mb-4 page-title welcome-section" 
             style="background: rgba(0, 0, 0, 0.5); max-width: 1700px; margin: 0 auto; border: 0px solid white; border-radius: 0px; padding: 5px;">
            <h3 style="color: white;"style="font-size: 7rem;"><?php echo $_SESSION['system']['name']; ?></h3>
            <hr class="divider1"/>
            <div class="col-md-12 mb-2 justify-content-center">
            </div>                        
        </div>
    </div>
</div>
    
	
</header>

<!-- Main section of the page and
Title of the section -->
<main class="page-section" style="background-image: url('assets/img/reginastaff_enhanced2.jpg'); background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat; padding: 70px 0; min-height: 100vh; max-height: auto;">
    <div class="row align-items-start justify-content-center text-center"> 
        <div class="col-lg-10 align-self-start mb-5"> 
		
            <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                <h1 style="font-family: 'Playball', cursive; color: white; font-size: 5rem;">About Us</h1>
                <div class="divider"></div>
            </div>

            <!-- Display the 'about' content from the system settings -->
            <h4>&nbsp;</h4>			
            <div class="container" style="background-color: rgba(0, 0, 0, 0.25); padding: 30px; border-radius: 8px; font-family: 'Playball'; font-size: 30px; color: #e8c1e6; max-width: 1300px; margin: auto;">
                <?php echo html_entity_decode($_SESSION['system']['about_content']); ?>
            </div>	
        </div>
		
    </div>	
<!-- message 1 -->	
<div class="container mt-4" style="background-color: rgba(0, 0, 0, 0); padding: 20px; border-radius: 8px; color: #f8f9fa; max-width: 1200px; margin: auto;">
    <center>
        <p style="font-size: 3.2rem; max-width: 620px;">We Bring Your Dreams and Aspirations to Life.</p>
        <p style="font-style: italic; font-size: 1.5rem;">Our bespoke spaces and superior services are designed to cater precisely to your event’s needs.</p>
		
        <!-- Learn More Button -->
        <a href="index.php?page=venue#venue-lists" class="btn btn-primary" style="font-size: 1.2rem; padding: 10px 20px; margin-top: 20px; background-color: #007bff; border: none; border-radius: 5px;">Learn More</a>
    </center>
</div>
		
		<!-- message 2 -->
		<div class="container mt-4" style="background-color: rgba(0, 0, 0, 0); padding: 20px; border-radius: 8px; color: #f8f9fa; max-width: 1200px; margin: auto;">
    <center>        
        <p style="font-style: italic; font-size: 1.5rem; max-width: 1050px;">
		Established in 1999, Regina's Garden and Restaurant has emerged as a distinguished venue in Lingayen, Pangasinan. 
		Our perfect blend of sophistication, cutting-edge amenities, and meticulous service ensures an unrivaled event experience, with every detail contributing to creating cherished memories.
		</p>       
    </center>
</div>

<!-- message 3 -->
<div class="container mt-4" style="background-color: rgba(0, 0, 0, 0); padding: 20px; border-radius: 8px; color: #f8f9fa; max-width: 1200px; margin: auto;">
    <center>
        <p style="font-size: 2.5rem; max-width: 620px;">Event Venue Services</p>
        <p style="font-style: italic; font-size: 1.5rem; max-width: 1050px;">We specialize in creating unforgettable events in exquisite spaces, harmonizing chic banquets with custom-tailored service.</p>
                    </center>
</div>

<!-- message 4 -->
<div class="container mt-4" style="background-color: rgba(0, 0, 0, 0); padding: 20px; border-radius: 8px; color: #f8f9fa; max-width: 1200px; margin: auto;">
    <center>
        <p style="font-size: 2.5rem; max-width: 620px;">Catering Services</p>
        <p style="font-style: italic; font-size: 1.5rem; max-width: 1050px;">We are dedicated to presenting menus that span a variety of global cuisines, including Filipino, Asian, and Continental delights,
		along with innovative fusion creations.</p>        
            </center>
</div>

<!-- message 5 -->
<div class="container mt-4" style="background-color: rgba(0, 0, 0, 0); padding: 20px; border-radius: 8px; color: #f8f9fa; max-width: 1200px; margin: auto;">
    <center>
        <p style="font-size: 2.5rem; max-width: 620px;">Clientele</p>
        <p style="font-style: italic; font-size: 1.5rem; max-width: 1050px;">We serve esteemed corporate entities, renowned event planners, expert stylists, and individuals with refined tastes. 
		Regina's Garden and Restaurant is celebrated for its exceptional delivery of events and celebrations.</p>        
            </center>
</div>

</main>

<!-- Section for displaying customer reviews -->
<section class="page-section" id="reviews" style="background-image: url('assets/img/reginareview_enhanced.jpg'); background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat; padding: 70px 0; height: auto">

    <!-- Container for the section title and description -->
    <div class="container">    
    
        <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 7px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
		
            <!-- Title of the section -->
            <h1 style="font-family: 'Playball', cursive; color: white; font-size: 5rem;">Customer Reviews</h1>
            <div class="divider3"></div>
			
            <!-- Description of the section -->
            <p style="color: white; font-size: 1.5rem;">We value your feedback! Please take a moment to review our food menu, venue, events and customer service.</p>
			
        </div>

        <!-- review form and other content -->
        <div class="row align-items-start">
            <div class="col-md-6">
                <h4>&nbsp;</h4>
                
                <div class="review-form-container">
                    <form id="reviewForm" action="admin/ajax.php?action=save_review" method="POST" style="color: white;">	
					
                        <!-- user's name -->
                        <div class="form-group">
                            <label for="name">Your Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        
                        <!-- user's email (optional) -->
                        <div class="form-group">
                            <label for="email">Your Email <i>(Optional)</i></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                        </div>
                        
                        <!-- for selecting the review services (radio button) -->
                        <div class="form-group">
                            <label>Service:</label>
                            <div>
                                <label><input type="radio" name="category" value="food_menu" required> Food Menu</label>
                            </div>
                            <div>
                                <label><input type="radio" name="category" value="venue" required> Venue</label>
                            </div>
                            <div>
                                <label><input type="radio" name="category" value="event" required> Event</label>
                            </div>
							 <div>
                                <label><input type="radio" name="category" value="cutomer_service" required> Customer Service</label>
                            </div>
                        </div>
                        
                        <!-- for the rating(stars) -->
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" />
                                <label for="star5" title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star">★</label>
                            </div>
                        </div>
                        
                        <!-- review textarea -->
                        <div class="form-group">
                            <label for="comment">Your Review</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Write your review here..." required></textarea>
                        </div>
                        
                        <input type="hidden" name="user_id" value="1">
                        <input type="hidden" name="event_id" value="1">
                        
                        <!-- Submit button for the review form -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Review</button>
                        </div>
						
                    </form>
                </div>
            </div>

            <!-- reviews that are approved by admin -->
            <div class="col-md-6">
                <h4>&nbsp;</h4>
				
                <center>
                    <h4 class="text-center" style="color: white; background-color: rgba(0, 0, 0, 0.5); padding: 5px 15px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); display: inline-block;">
                        What Our Customers Say
                        <div class="divider4"></div>
                    </h4>
                </center>

                <div class="review-list" style="color: white; max-height: 580px; overflow-y: auto;">
				
                    <?php
					
                    // encrypt the user's name for privacy
                    function encryptName($fullName) {
						
                        // Get the first two letters of the name in uppercase and append **** for privacy
                        $firstTwoLetters = strtoupper(substr(trim($fullName), 0, 2));
                        return $firstTwoLetters . '****';
                    }

                    // get only the reviews that are being allowed/showed by admin
                    $reviews = $conn->query("SELECT * FROM reviews WHERE is_displayed = 1 ORDER BY created_at DESC");

                    if ($reviews->num_rows > 0): ?>
                        <?php while ($review = $reviews->fetch_assoc()): ?>
						
							<!-- container for each reviews -->
                            <div class="review-item" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px; margin-bottom: 10px; word-wrap: break-word; font-size: 14px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                                
                                <!-- Display the encrypted name of the reviewer -->
                                <h5 style="color: #ffcc00; font-size: 16px;">                
                                    <?php echo encryptName($review['name']); ?>
                                </h5>
                                
                                <!-- Display the category of the review based on its value -->                    
                                <p style="margin: 0;"><strong>Service:</strong>
                                    <?php 
                                    // mapping
                                    switch ($review['category']) {
                                        case 'food_menu':
                                            echo 'Food Menu'; // will Display 'Food Menu' instead of food_menu(database)
                                            break;
                                        case 'event':
                                            echo 'Event'; // will Display 'Event' instead of event(database)
                                            break;
                                        case 'venue':
                                            echo 'Venue'; // will Display 'Venue' instead of venue(database )
                                            break;
										case 'customer_service':
											echo 'Customer Service';
											break;
											
                                        default:
                                            echo htmlspecialchars($review['category']);
                                    }
                                    ?>
                                </p>
                                
                                <p style="margin: 0;">
                                    <strong>Rating:</strong>
                                    <?php 
                                    // Display the rating as stars
                                    $rating = htmlspecialchars($review['rating']);
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($i < $rating) {
                                            echo '<span style="color: #FFD700;">&#9733;</span>'; // Filled star
                                        } else {
                                            echo '<span style="color: #FFD700;">&#9734;</span>'; // Empty star
                                        }
                                    }
                                    ?>
                                </p>
                                
                                <!-- Display the review text safely -->                   
                                <p style="margin: 0; padding: 0; padding-top: 5px; word-wrap: break-word;">
                                    "<?php echo htmlspecialchars($review['review_text']); ?>"
                                </p>
                                
                            </div>
							
                            <hr class="divider5"> 
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: white; font-size: 14px;">No reviews available.</p> 
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section for displaying the map and Send Us a Message -->
<section id="contact-section" style="padding: 70px 0; min-height: 800px; background-color: transparent;">
    <div class="container">
        <div class="row">		
            
            <div class="col-md-6">
			
				<!-- Left Side: Contact Details of Regina's Garden and Restaurant -->
                <h3 class="text-center" style="color: white; font-size: 2.5rem;">Contact Us</h3>
				
                <div class="contact-info" style="margin-top: 40px; background-color: rgba(0, 0, 0, 0.6); padding: 30px; border-radius: 10px;">
				
					<!-- email -->
                    <div class="info-item">
                        <h5 style="color: #f0c94a; font-size: 1.2rem;">Email:</h5>
                        <p style="color: white; font-size: 1rem;"><?php echo $email; ?></p>
                    </div>
					
					<!-- phone -->
                    <div class="info-item">
                        <h5 style="color: #f0c94a; font-size: 1.2rem;">Phone:</h5>
                        <p style="color: white; font-size: 1rem;"><?php echo $contact; ?></p>
                    </div>
					
					<!-- location -->
                    <div class="info-item">
                        <h5 style="color: #f0c94a; font-size: 1.2rem;">Location:</h5>
                        <p style="color: white; font-size: 1rem;">Maramba Blvd., Lingayen, Pangasinan</p>
                    </div>
					
                </div>
				
                <!-- Map -->
                <div id="map-container" style="height: 350px; width: 100%; margin-top: 30px;">
                    <div id="map" style="height: 100%; width: 100%;"></div>
                </div>
				
            </div>

            <!-- Right Side: Contact Form -->
            <div class="col-md-6">			
                <h3 class="text-center" style="color: white; font-size: 2.5rem;">Send Us a Message</h3>				

                <form id="contactForm" action="send_message.php" method="POST" style="margin-top: 30px; background-color: rgba(0, 0, 0, 0.6); padding: 30px; border-radius: 10px;">
				
					<!-- first name field -->
                    <div class="form-group">
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                    </div>
					
					<!-- last name field -->
                    <div class="form-group">
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                    </div>
					
					<!-- address field -->
                    <div class="form-group">
                        <input type="text" class="form-control" name="address" placeholder="Address" required>
                    </div>
					
					<!-- email field -->
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>					
					
					<!-- phone field -->
                    <div class="form-group">
                        <input type="tel" class="form-control" name="phone" placeholder="Phone" required>
                    </div>
					
					<!-- message text area -->
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="6" placeholder="Message " required></textarea>
                    </div>
					
					<!-- submit button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="submitButton" style="background-color: #f0c94a; border: none; font-size: 1.2rem;">Submit</button>
                    </div>
					
                </form>				
            </div>			
			
        </div>
    </div>	
</section>

<!-- styles for maps and Send Us Message -->
<style>
    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.8); 
    }

    #map-container {
        background-color: transparent;
        border-radius: 5px;
        overflow: hidden; 
    }

    .btn-primary:hover {
        background-color: #e6b937;
    }

    h3 {
        margin-bottom: 15px;
        
    }

    p {
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .info-item {
        margin-bottom: 15px;
    }
</style>

<!-- styles for other page sections styles -->
<style>

    /* <!-- Styles for star rating -->  */
    .star-rating {
        direction: rtl; 
        font-size: 2rem; 
		}
		
    .star-rating input[type="radio"] {
        display: none; 
    }
	
    .star-rating label {
        color: #ddd; 
        cursor: pointer; 
		}
		
    .star-rating input[type="radio"]:checked ~ label {
        color: #f7c502; 
    }
	
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f7c502; 
    }

    /* <!-- Container for the map --> */
    .map-container {
        height: 350px; 
        width: 45%; 
        position: center; 
        left: 75%; 
        transform: translateX(-50%); 
    }

    /* <!-- Title styles --> */
    .page-title h3 {
        font-family: 'Great Vibes'; 
        font-size: 8rem; 
    }

    /* <!-- Custom divider styles --> */
	
    .divider1 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 90%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
/* <!-- aboutus divider -->  */
.divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 15%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
/* <!-- ourlocation divider --> */
.divider2 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 17%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
/* <!-- customerreviews divider --> */
.divider3 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 27%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
.divider4 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 90%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
.divider5 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 95%; 
    margin: 20px auto; 
    border-radius: 5px; 
}    
    .page-section {
        position: relative; 
    }	
	.review-form-container {
    background-color: rgba(0, 0, 0, 0.5); 
    padding: 20px; 
    border-radius: 10px;
    max-width: 600px; 
    margin: auto; 
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); 
}

body {
        overflow-x: hidden;
    }   
    .page-section {
        height: auto; 
    }    
    .container {
        max-width: 100%; 
        padding: 0 15px; 
    }
</style>

<script>
    // Leaflet map initialization
    L.Icon.Default.imagePath = 'js/images/'; // Image path for Leaflet icon
    var map = L.map('map').setView([16.025793, 120.234724], 15); // Map and view to specified coordinates
    
    // Add tile layer to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors' 
    }).addTo(map);

    // Add a marker to the map
    L.marker([16.025793, 120.23472]).addTo(map)
        .bindPopup('Our Location: Maramba Blvd., Lingayen, Pangasinan') // Popup with location info
        .openPopup();
	
    //////////////////// Toastr Notifications ////////////////////////////
    function showToast(message, type = 'success') {
        toastr[type](message); 
    }

    // Configure Toastr notifications for better visibility and user experience (Send Us a Message)
    toastr.options = {
        closeButton: true,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-center",
        preventDuplicates: true,
        timeOut: "5000",
        extendedTimeOut: "2000",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };

    $(document).ready(function() {
        $('#reviewForm').submit(function(e) {
            e.preventDefault(); 

            const submitButton = $(this).find('button[type="submit"]'); 
            submitButton.prop('disabled', true).text('Submitting...'); 

            // Validate required fields
            const requiredFields = $(this).find('[required]');
            let allFilled = true; 

            requiredFields.each(function() {
                if ($(this).val() === '') {
                    allFilled = false; 
                    showToast('Please fill out all required fields.', 'error'); // Show error notification
                    return false; 
                }
            });

            // Set default rating to 5 if not selected
            const selectedRating = $(this).find('input[name="rating"]:checked').val();
            if (!selectedRating) {
                $(this).find('input[name="rating"][value="5"]').prop('checked', true); 
            }

            // If not all fields are filled, reset the button and exit
            if (!allFilled) {
                submitButton.prop('disabled', false).text('Submit Review'); 
                return; 
            }

            // Save the review using AJAX(admin_class)
            $.ajax({
                url: 'admin/ajax.php?action=save_review', 
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (typeof response === "string") {
                        try {
                            response = JSON.parse(response); 
                        } catch (e) {
                            showToast('Error parsing response: ' + e.message, 'error');
                            return;
                        }
                    }

                    // Process successful response
                    if (response.success) {
                        let newReview = `
                            <div class="review-item">
                                <h5>${response.data.name || 'Anonymous'}</h5>
                                <p><strong>Service:</strong> ${response.data.category || 'General'}</p>
                                <p><strong>Rating:</strong> ${response.data.rating}/5</p>
                                <p>"${response.data.comment || 'No comments provided.'}"</p>
                            </div>
                            <hr>`;

                        $('#reviewList').prepend(newReview); // Add new review to the top of the list
                        $('#reviewForm')[0].reset(); 
                        showToast('Review submitted successfully!', 'success');

                        setTimeout(function() {
                            location.reload(); 
                        }, 2500);
                    } else {
                        showToast('Error: ' + response.message, 'error');
                    }

                    submitButton.prop('disabled', false).text('Submit Review');
                },
                error: function(xhr) {
                    console.error("AJAX Error Response:", xhr.responseText); 
                    showToast('Error submitting review. Please try again.', 'error');
                    submitButton.prop('disabled', false).text('Submit Review');
                }
            });
        });
    });
</script>




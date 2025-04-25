<!-- serves as a promotional and informational platform for "Regina's Garden and Restaurant," showcasing its offerings, 
upcoming events, and a user-friendly inquiry system. It combines PHP for backend data handling and HTML/CSS for frontend 
presentation, ensuring a dynamic and engaging user experience. -->
<?php 
include 'admin/db_connect.php'; 
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regina's Garden and Restaurant</title>
	
    <!-- Include Canvas Confetti library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti"></script>
	<!-- font styles -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
	
</head>
<body>

<header class="masthead">
<div class="container-fluid h-75">
    <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-8 align-self-end mb-4 page-title welcome-section" 
             style="background: rgba(0, 0, 0, 0.5); max-width: 1700px; margin: 0 auto; border: 0px solid white; border-radius: 0px; padding: 5px;">
            <h3 class="welcome-text" style="font-size: 7rem;">
                Welcome to <?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : 'Our System'; ?>
            </h3>
            <hr class="custom-divider my-4" />
            <div class="col-md-12 mb-2 justify-content-center">
            </div>
        </div>
    </div>
</div>


<!-- styles for Title -->
<style>
.welcome-section {
    background: rgba(0, 0, 0, .5);
}
.welcome-text {
    color: white;
}

/* Fade and slide in for the title */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Fade in for the divider */
@keyframes fadeInDivider {
    0% {
        opacity: 0;
        width: 0;
    }
    100% {
        opacity: 1;
        width: 100%;
    }
}

.page-title h3 {
    animation: fadeInUp 1s ease-in-out forwards;
    opacity: 0;
}

.custom-divider {
    animation: fadeInDivider 5s ease-in-out forwards;
    opacity: 0;
    width: 0;
    transition: width 2.5s ease-in-out;
}
</style>
<!-- Confetti Effect Script -->
<script>    
    function launchConfetti() {
        setInterval(() => {
            confetti({
                particleCount: 10,
                angle: 60,
                spread: 55,
                origin: { x: 0, y: 0 }, // Start from the left
                zIndex: 9999
            });
            confetti({
                particleCount: 10,
                angle: 120,
                spread: 55,
                origin: { x: 1, y: 0 }, // Start from the right
                zIndex: 9999
            });
			confetti({
            particleCount: 10,
            angle: 90,
            spread: 55,
            origin: { x: 0.5, y: 0 }, // Start from the center
            zIndex: 9999
        });
        }, 500); // Adjust the interval time (in milliseconds) for confetti bursts
    }

    // Trigger confetti effect when the page loads
    window.onload = launchConfetti;
</script>
</header>

<!-- venue-intro Section -->
<section class="venue-intro mt-4">
    <div class="background-animation"></div>
    <div class="overlay"></div>
    <h2>Welcome to Our Refined Venue: <br>Perfect for Every Occasion.</h2>
    <br>
    <p>
        <i>Experience the perfect blend of elegance and modernity in our thoughtfully designed spaces, perfect for weddings, corporate gatherings, debut parties, 
        or any social occasion. Our venue in Lingayen, Pangasinan, delivers the exquisite charm of affordable luxury.</i>
    </p>    
    <!-- Learn More Button -->
    <div class="text-center mt-3">
    <button type="button" class="btn btn-primary" onclick="window.location.href='index.php?page=about#contact-section'">
        INQUIRE NOW
    </button>
</div>
</section>
<!-- styles for venue-intro Section -->
<style>
    .venue-intro {
        position: relative;
        padding: 200px;
        color: #333; 
        text-align: center;
        overflow: hidden; 
    }
    .background-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #ff6b6b, #ffd700, #1e90ff, #32cd32);
        background-size: 300% 300%;
        animation: gradientShift 10s ease infinite;
        z-index: 0; 
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8); 
        z-index: 1; 
    }
    h2 {
        font-family: 'Playball', cursive;
        font-size: 3rem;
        z-index: 2; 
        position: relative;
		color: #b59b67;
    }
    p {
        font-size: 1.2rem;
        line-height: 2;
        z-index: 2; 
        position: relative; 
    }
    .btn-primary {
        margin-top: 20px; 
        z-index: 2; 
        position: relative; 
    }
</style>

<!-- occasions Section -->
<div class="container mt-3 pt-2 venue-section" style="background-image: url('assets/img/occasionbg_regina1.jpg'); background-size: cover; background-position: center; padding: 30px;">
<h4 class="text-center custom-heading" style="font-family: 'Playball', cursive; font-size: 3rem;">Customizable Event Venues for Every Occasion</h4>
<hr class="custom-divider1 my-4" />
<!-- styles for title of occassion section -->
<style>
.custom-heading {
    color: #f9f5e8;
	
}
</style>
<!-- images from different occasions -->    
<div class="row text-center justify-content-center">
    <?php
    // Get the images from the database
    $venue_imgs = $conn->query("SELECT wedding_img, debut_img, intimateparty_img, childrenparty_img, bdayparty_img, seminar_img FROM system_settings");

    if ($venue_imgs && $venue_imgs->num_rows > 0) {
        $row = $venue_imgs->fetch_assoc();
    ?>
        <!-- Row 1: Wedding, Debut, Intimate Party -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="venue-img-container">
                <img src="admin/assets/uploads/<?php echo $row['wedding_img']; ?>" id="wedding_img_preview" class="img-fluid rounded classy-image" alt="Wedding">
                <h6 class="mt-3 venue-title">Wedding</h6>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="venue-img-container">
                <img src="admin/assets/uploads/<?php echo $row['debut_img']; ?>" id="debut_img_preview" class="img-fluid rounded classy-image" alt="Debut">
                <h6 class="mt-3 venue-title">Debut</h6>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="venue-img-container">
                <img src="admin/assets/uploads/<?php echo $row['intimateparty_img']; ?>" id="intimateparty_img_preview" class="img-fluid rounded classy-image" alt="Intimate Party">
                <h6 class="mt-3 venue-title">Intimate Party</h6>
            </div>
        </div>

        <!-- Row 2: Children's Party, Birthday Party, Seminar -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="venue-img-container">
                <img src="admin/assets/uploads/<?php echo $row['childrenparty_img']; ?>" id="childrenparty_img_preview" class="img-fluid rounded classy-image" alt="Children's Party">
                <h6 class="mt-3 venue-title">Children's Party</h6>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="venue-img-container">
                <img src="admin/assets/uploads/<?php echo $row['bdayparty_img']; ?>" id="bdayparty_img_preview" class="img-fluid rounded classy-image" alt="Birthday Party">
                <h6 class="mt-3 venue-title">Birthday Party</h6>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="venue-img-container">
                <img src="admin/assets/uploads/<?php echo $row['seminar_img']; ?>" id="seminar_img_preview" class="img-fluid rounded classy-image" alt="Corporate Meeting, Event & Conference">
                <h6 class="mt-3 venue-title">Corporate Meeting, Event & Conference</h6>
            </div>
        </div>
    <?php
    } else {
        echo "<p class='text-center text-dark'>No images found for the venues.</p>";
    }
    ?>
</div>   
    <!-- Learn More Button -->
   <div class="text-center mt-4">
    <button type="button" class="btn btn-primary btn-lg" style="font-size: 1rem;" onclick="window.location.href='index.php?page=about#contact-section'">
        INQUIRE NOW
    </button>
</div>
</div>
<!-- styles for occasions section -->
<style>
    .venue-section {
        padding: 40px 0;
        background-color: rgba(0, 0, 0, 0.5); 
        background-blend-mode: overlay;
    }

    .venue-img-container {
        transition: transform 0.3s ease;
        overflow: hidden;
    }

    .venue-img-container:hover {
        transform: scale(1.05); 
    }

    .classy-image {
        width: 100%;
        height: 200px; 
        object-fit: cover; 
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer; 
    }

    .venue-title {
        font-family: 'Playball', cursive;
        font-size: 1.2rem;
        color: #fff; 
    }

    .custom-divider1 {
        width: 50px;
        height: 3px;
        background-color: #fff; 
        margin: 0 auto; 
    }
	<!-- for Image Modal(viewer) in occasion images -->
	.modal-content {
    border-radius: 15px;
}

.modal-header {
    border-bottom: none; 
}
.close {
    font-size: 3rem; 
    color: #FFFFFF; 
}
</style>
<!-- Image Modal in occasion images -->
<div class="modal" id="imageModal" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.7);">
    <div class="modal-dialog" role="document" style="max-width: 50%; width: auto;">
        <div class="modal-content" style="background: rgba(255, 255, 255, 0); border: none;">
            <div class="modal-header">
               
                <button type="button" class="close" id="closeModal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" alt="Enlarged Image" style="max-width: 100%; max-height: 80vh; height: auto; width: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            </div>
        </div>
    </div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- scripts Image Modal in occasion images -->
<script>
    // Open modal and show the enlarged image when an image is clicked
    $('.classy-image').click(function() {
        const imgSrc = $(this).attr('src');
        $('#modalImage').attr('src', imgSrc); 
        $('#imageModal').fadeIn(); 
    });

    // Close the modal when the close button is clicked
    $('#closeModal').click(function() {
        $('#imageModal').fadeOut(); 
    });

    // Close the modal when clicking outside of the modal image
    $('#imageModal').click(function(event) {
        if (event.target === this) {
            $(this).fadeOut(); // Hide the modal
        }
    });
</script>

<!-- event-section (text services for events) ------------------>
<section class="event-section">
    <div class="background-animation"></div>
    <div class="overlay"></div>
    <h2>Inspire Your Events with Elegance</h2>
    <p><i>
        Discover affordable luxury at our premier venue in Lingayen, Pangasinan. Our exceptional services and great ambiance to suit every detail of your celebration.
    </i></p>
    <div class="content-container">
        <div class="origami">
            <img src="assets/img/origane_trans-removebg-preview.png" alt="Origami Crane Design">
        </div>
        <div class="content">
            <ul class="services-list">
                <li><span class="checkmark">&#10003;</span> Customizable Event Packages</li>
                <li><span class="checkmark">&#10003;</span> Stylish Banquets</li>
                <li><span class="checkmark">&#10003;</span> Professional Culinary Team</li>
                <li><span class="checkmark">&#10003;</span> Bespoke Service</li>
            </ul>
        </div>
    </div>
</section>
<style>
    .event-section {
        position: relative;
        display: flex;
        justify-content: center; 
        align-items: center; 
        flex-direction: column; 
        padding: 50px;
        text-align: center; 
        min-height: 100vh; 
        width: 100%; 
        box-sizing: border-box; 
        overflow: hidden; 
    }

    .background-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #ff6b6b, #ffd700, #1e90ff, #32cd32);
        background-size: 300% 300%;
        animation: gradientShift 10s ease infinite;
        z-index: 0; 
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8); 
        z-index: 1; 
    }

    .event-section h2,
    .event-section p {
        margin: 0; 
        text-align: center; 
        z-index: 2; 
        position: relative;
    }

    .event-section h2 {
        font-size: 55px;
        color: #b59b67; 
        margin-bottom: 20px; 
    }

    .event-section p {
        font-size: 20px;
        color: #444; 
        line-height: 2;
        margin-bottom: 30px; 
    }

    .content-container {
        display: flex; 
        justify-content: center; 
        align-items: flex-start; 
        margin-top: 30px; 
        max-width: 1200px; 
        width: 100%; 
        padding: 0 20px; 
        z-index: 2; 
        position: relative; 
    }

    .origami {
        margin-right: 20px; 
    }

    .origami img {
        max-width: 300px; 
        width: 100%; 
        height: auto; 
    }

    .content {
        max-width: 600px; 
        text-align: left; 
    }

    .services-list {
        list-style-type: none;
        padding-left: 0;
    }

    .services-list li {
        font-size: 20px;
        color: #444; 
        display: flex;
        align-items: center;
        justify-content: flex-start; 
        margin-bottom: 10px;
    }

    .checkmark {
        color: #b59b67;
        font-size: 24px;
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .content-container {
            flex-direction: column; 
            align-items: center; 
        }

        .origami {
            margin-right: 0; 
            margin-bottom: 20px; 
        }
    }
</style>

<!-- Upcoming Events ------------------>
<div class="container mt-3 pt-2">
    <h4 class="text-center text-white" style="font-family: 'Playball', cursive; font-size: 2.5rem;">Upcoming Events</h4>
    <hr class="custom-divider1 my-4" />

    <?php
    // get all upcoming events excluding those with venues that are catering services
    $event = $conn->query("
        SELECT e.*, v.venue, e.audience_capacity, e.amount 
        FROM events e 
        INNER JOIN venue v ON v.id = e.venue_id 
        WHERE e.type = 1 
        AND v.venue != 'catering services'  -- Exclude catering services
        AND date_format(e.schedule, '%Y-%m-%d') > '" . date('Y-m-d') . "'  -- Events scheduled for future dates
        ORDER BY unix_timestamp(e.schedule) ASC
    ");

    if (!$event) {
        echo "Error fetching events: " . $conn->error; 
    }

    // Check if there are any events
    if ($event->num_rows > 0) {
        $count = 0; // Counter for displayed events
        while ($row = $event->fetch_assoc()):
            $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
            unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]); 
            $desc = strtr(html_entity_decode($row['description']), $trans); 
            $desc = str_replace(array("<li>","</li>"), array("",","), $desc); 

            // Map event_time values to corresponding time slots
            $time_slots = [
                0 => "8 AM - 12 PM",
                1 => "12 PM - 4 PM",
                2 => "4 PM - 8 PM",
                3 => "8 PM - 12 AM"
            ];
            $event_time_display = isset($time_slots[$row['event_time']]) ? $time_slots[$row['event_time']] : 'Unknown time';
    ?>
	
    <!-- Displaying the upcoming event -->
    <div class="card event-list <?php echo $count < 2 ? '' : 'hidden'; ?>" data-id="<?php echo $row['id'] ?>">	
		<!-- banner image of event -->
        <div class='banner'>
            <?php if (!empty($row['banner'])): ?>
                <img src="admin/assets/uploads/<?php echo($row['banner']) ?>" alt="">
            <?php endif; ?>
        </div>		
        <div class="card-body">
            <div class="row align-items-center justify-content-center text-center h-100">
                <div class="">				
					<!-- event name -->
                    <h3><b class="filter-txt"><?php echo ucwords($row['event']) ?></b></h3>
                    <hr class="divider" style="max-width: calc(80%)">					
					<!-- date of event -->
                    <div><small><p><b><i class="fa fa-calendar"></i> <?php echo date("F d, Y", strtotime($row['schedule'])) ?></b></p></small></div>					
					<!-- time of event -->
                    <div><small><p><b><i class="fa fa-clock"></i> Time: <?php echo $event_time_display; ?></b></p></small></div>					
					<!-- description of event -->
                    <hr>
                    <larger class="truncate filter-txt"><?php echo strip_tags($desc) ?></larger> 
                    <br>
                    <hr class="divider" style="max-width: calc(80%)">					
					<!-- audience capacity -->
                    <div><small><p><b><i class="fa fa-users"></i> Audience Capacity (Remaining): <?php echo $row['audience_capacity']; ?></b></p></small></div>					
					<!-- registration fee -->
                    <?php if (!empty($row['amount']) && $row['amount'] > 0): ?>
                        <div><small><p><b><i class="fa fa-money"></i> Registration Fee: ₱<?php echo number_format($row['amount'], 2); ?></b></p></small></div>
                    <?php endif; ?>					
					<!-- read more button -->
                    <button class="btn btn-primary read_more" data-id="<?php echo $row['id'] ?>">Read More</button>
                </div>
            </div>
        </div>
    </div>
    <br>
	
    <?php 
            $count++; 
        endwhile; 
    } else {
        echo "<p class='text-center text-white'>No upcoming events available at the moment.</p>";
    }
    ?> 
	
    <!-- Button to show more events if more than 3 events -->
    <?php if ($event->num_rows > 2): 
	?>
	
        <div class="text-center">
            <button id="more-events" class="btn btn-secondary">Show More Events</button>
        </div>
		
    <?php endif; 
	?>

</div>
<script>
    // to toggle the visibility of more events
    document.getElementById('more-events').addEventListener('click', function() {
        
        const hiddenEvents = document.querySelectorAll('.event-list.hidden');

        // Show hidden events
        hiddenEvents.forEach(function(event) {
            event.classList.remove('hidden');
        });

        // Hide the button after showing more events
        this.style.display = 'none';
    });
</script>
<!-- styles for upcoming events and the whole page -->
<style>
    .event-list {
        overflow: hidden; 
        max-height: 650px; 
		max-width: 1300px; 
		margin: 20px auto;
        transition: max-height 0.5s ease; 
    }    
    .event-list.hidden {
        display: none;
    }
	
/* occasions section */
#portfolio .img-fluid {
    width: calc(100%);
    height: 30vh;
    z-index: -1;
    position: relative;
    padding: 1em;
}

/* for banner images and layout (occasions images) */
.banner {
    display: flex; 
    justify-content: center; 
    align-items: center; 
    min-height: 26vh; 
    width: calc(30%); 
}
/* to fit inside container the images (occasions images)*/
.banner img {
    width: calc(100%); 
    height: calc(100%); 
    cursor: pointer; 
}

/* for upcoming events */
.event-list {
    cursor: pointer; 
    border: unset; 
    flex-direction: inherit; 
}

/* width of banner and cardbody (upcoming events)*/
.event-list .banner {
    width: calc(40%); 
}
/* for upcoming events */
.event-list .card-body {
    width: calc(60%); 
}

/* additionals/costumized (upcoming events)*/
.event-list .banner img {
    border-top-left-radius: 5px; 
    border-bottom-left-radius: 5px; 
    min-height: 50vh;
}

/* Set minimum height of the parent container (occasions images)*/
.banner {
    min-height: calc(100%);
}

body {
        overflow-x: hidden; 
    }
	
 /* container doesn't exceed viewport (all containers) */    
    .container {
        max-width: 100%;
        padding: 0 15px; 
    }
	
	/* system name */    
	.page-title h3 {
        font-family: 'Great Vibes', cursive;
        font-size: 6rem; 
    }	
	
/* divider styles */	
	.custom-divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 75%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
	.custom-divider1 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 15%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
	.divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    
    margin: 20px auto; 
    border-radius: 5px; 
}
</style>

<!-- js function for 'Read More' button and after clicking the image -->
<script>
// function for READ MORE button: event details	and audience/guest registration    
    $('.read_more').click(function(){
        location.href = "index.php?page=view_event&id=" + $(this).attr('data-id'); 
    });
    
// image viewer
    $('.banner img').click(function(){
        viewer_modal($(this).attr('src'));
    });
	
// works on admin side that can allow you to search for events based on your inputs
// filter events
    $('#filter').keyup(function(e){
        var filter = $(this).val();

        // filtering text of event(s) and display all that matches
		
        $('.card.event-list .filter-txt').each(function(){
            var txto = $(this).html(); 
            txt = txto;
            
            if((txt.toLowerCase()).includes((filter.toLowerCase())) == true){ 
                $(this).closest('.card').toggle(true); 
            } else {
                $(this).closest('.card').toggle(false); 
            }
        });
    });
</script>


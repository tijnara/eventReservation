<!-- retrieves and displays event venue information from a database, with features for image carousels and booking functionalities. 
The use of Bootstrap classes (e.g., for carousels and modals) suggests it's designed to be responsive and user-friendly. -->
<?php 
include 'admin/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Venues</title>
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

</head>
<body>

<!-- page cover title -->
<header class="masthead">
    <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
            <h1 style="font-family: 'Great Vibes'; font-size: 6rem; color: white;">Our Event Venues</h1>
            <hr class="custom-divider my-4" />
        </div>
    </div>
</header>

<!-- venues -->
<section id = "venue-lists" class="venue-background">    
    <div class="venue-list-container">        
            <div id="venues" class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); width: 100%; min-width: 500px; margin: 0 auto;">    
                <h1 style="font-family: 'Playball', cursive; color: white; font-size: 3.5rem;">Inspired Spaces for Unforgettable Moments</h1>
                <div class="custom-divider1"></div> 
            </div>            
        
        <p>&nbsp;</p>
        
        <?php
        $venue = $conn->query("SELECT * FROM venue");
        while ($row = $venue->fetch_assoc()): ?>
        <section id="venue_<?php echo $row['id']; ?>" class="venue-section" style="background-color: rgba(0, 0, 0, .1); margin: 0; padding: 0;">
            <div class="card venue-list d-flex flex-column" data-id="<?php echo htmlspecialchars($row['id']); ?>" style="background-color: rgba(0, 0, 0, 0.3); margin: 20px auto; border: none; width: 1200px;">
			
				<!-- venues: main hall, conference hall and catering -->
                <div id="imagesCarousel_<?php echo $row['id']; ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php 
                        $fpath = 'admin/assets/uploads/venue_' . $row['id'];
                        if (is_dir($fpath)) {
                            $images = array_diff(scandir($fpath), array('.', '..'));
                            if (!empty($images)) {
                                $i = 1;
                                foreach ($images as $image):
                                    $active = ($i == 1) ? 'active' : '';
                                ?>
                                <div class="carousel-item <?php echo $active; ?>">
                                    <img class="d-block" src="<?php echo $fpath . '/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($row['venue']); ?> image" style="width: 100%; height: 400px; object-fit: cover;">
                                </div>
                                <?php
                                    $i++;
                                endforeach;
                            } else {
                                
                                ?>
                                <div class="carousel-item active">
                                    <img class="d-block" src="path/to/default-image.jpg" alt="No images available" style="width: 100%; height: 400px; object-fit: cover;">
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#imagesCarousel_<?php echo $row['id']; ?>" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#imagesCarousel_<?php echo $row['id']; ?>" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
				
				<!-- details and description -->
                <div class="card-body d-flex flex-column" style="background-color: rgba(0, 0, 0, 0.7); padding: 30px; text-align: center; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); flex: 1;">
                    <div class="venue-content" style="flex-grow: 1; width: 100%; max-width: 1000px; margin: 0 auto;">
                        <h3 style="color: #fff; font-weight: bold; margin-bottom: 15px; font-size: 24px;">
                            <b class="filter-txt"><?php echo htmlspecialchars(ucwords($row['venue'])); ?></b>
                        </h3>
                        <div class="custom-divider3" style="height: 2px; width: 60px; background-color: #fff; margin: 10px auto;"></div>
                        <small style="color: #ddd; font-style: italic; display: block; margin-bottom: 10px;">
                            <i><?php echo htmlspecialchars($row['address']); ?></i>
                        </small>
                        <span style="font-size: 16px; display: block; margin: 15px 0; color: #ddd;">
                            <?php echo htmlspecialchars(ucwords($row['description'])); ?>
                        </span>

                        <!-- Display Max Guest Capacity -->
                        <p style="color: blue;">Max Guest Capacity: 
                            <strong>
                                <?php echo htmlspecialchars($row['max_capacity'] > 0 ? $row['max_capacity'] : ''); ?>
                            </strong>
                        </p>
						
						<!-- book now button -->
                        <button class="btn btn-primary book-venue" type="button" data-id='<?php echo htmlspecialchars($row['id']); ?>' 
                                style="background-color: #007bff; border-color: #0056b3; font-size: 16px; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s, transform 0.3s;">
                            Book Now
                        </button>
						
                    </div>
                    <div class="venue-divider" style="height: 3px; background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 50%, rgba(255,255,255,0) 100%); margin: 20px 0 0; width: 100%; border-radius: 5px;"></div>
                </div>

            </div>
        </section>	
        <?php endwhile; ?>
    </div>
	
<!-- Image Viewer Modal -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" role="dialog" aria-labelledby="imageViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 63.5%; max-height: 90%;">
        <div class="modal-content" style="background-color: rgba(0, 0, 0, 0);">
            <div class="modal-body text-center" style="overflow: hidden; padding: 0;">
                <img id="modalImage" src="" alt="Image Viewer" style="width: auto; height: auto; max-width: 100%; max-height: 85vh;">
            </div>
            <div class="modal-footer d-flex justify-content-center" style="background-color: rgba(0, 0, 0, 0);">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</section>

<script>
	// opens a booking request form
    $('.book-venue').click(function(){
        uni_modal("Submit Booking Request", "booking.php?venue_id=" + $(this).attr('data-id'));
    });
	
	// opens an image viewer modal when a venue image is clicked
    $('.venue-list .carousel img').click(function(){
        var imgSrc = $(this).attr('src');  
        $('#modalImage').attr('src', imgSrc);  
        $('#imageViewerModal').modal('show'); 
    });
</script>

</body>

<style>
/* Body styles */
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        color: white; /* Set default text color */
        }

/* Full-page background image for venue section */
    .venue-background {
        background-image: url('assets/img/image.jpg');
         background-size: cover; 
        background-position: center; 
        background-attachment: fixed; 
        display: flex;
        flex-direction: column; 
            align-items: center; 
            padding: 3rem 0; 
        position: relative; 
        }

/* Custom divider styles */
    .custom-divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 85%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
.custom-divider1 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 55%; 
    margin: 20px auto; 
    border-radius: 5px; 
}
.custom-divider3 {
    border: 0;
    height: 2px;
    background: linear-gradient(90deg, #f39c12, #3498db); 
    width: 40%; 
    margin: 20px auto; 
    border-radius: 5px; 
}

/* Carousel controls */

 /* Hover effect for carousel controls */
    .carousel-control-prev:hover .carousel-control-prev-icon,
    .carousel-control-next:hover .carousel-control-next-icon {
            transform: scale(1.2); 
        }

    .carousel-control-prev, 
    .carousel-control-next {
            width: 5%;
        }
	body {
        overflow-x: hidden; 
    }

/* Adjust height and ensure no overflow */
    .page-section {
        height: auto; 
    }
    
    .container {
        max-width: 100%; 
        padding: 0 15px; 
    }
</style>	
</html>

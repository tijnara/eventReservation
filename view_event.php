<?php include 'admin/db_connect.php'; ?>

<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT e.*, v.venue FROM events e INNER JOIN venue v ON v.id = e.venue_id WHERE e.id = " . $_GET['id']);
    if ($qry) {
        foreach ($qry->fetch_array() as $k => $val) {
            $$k = $val;
        }
    } else {
        // Handle query error
        echo "Error fetching event details.";
        exit;
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 

</head>
<body>
<header class="masthead" style="background-image: url('<?php echo !empty($banner) ? "admin/assets/uploads/$banner" : 'admin/assets/uploads/' . $_SESSION['system']['cover_img']; ?>');">
    <div class="container-fluid h-75">
        <div class="row h-75 align-items-center justify-content-center text-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 align-self-end mb-4 pt-2 page-title" style="background-color: rgba(0, 0, 0, 0.3); padding: 10px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
               
			   <!-- event name -->
                <h4 class="text-center text-white" style="word-wrap: break-word;"><?= ucwords($event); ?></h4>
                <hr class="divider" style="max-width: 100%; margin: 0 auto;"/>
                
				<!-- venue name -->
                <p class="text-center text-white"><small><b><i>Venue: <?= ucwords($venue); ?></i></b></small></p>
            </div>
        </div>
    </div>
</header>

<style>
    .masthead {
        height: 100vh;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .page-title h4 {
        font-family: 'Playball', cursive;
        font-size: 4rem;
        color: white;
    }

    .page-title p {
        font-size: 1.5rem;
        color: white;
    }
</style>

<div class="container" style="background-color: rgba(255, 255, 255, 0);">
    <div class="col-lg-12">
        <div class="card mt-4 mb-4" style="background-color: rgba(255, 255, 255, 0);">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="content">
					
                        <!-- images provided by client -->
                        <div id="imagesCarousel" class="carousel slide col-sm-4 float-left ml-0 mx-4" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php 
                                $images = array();
                                if (isset($id)) {
                                    $fpath = 'admin/assets/uploads/event_' . $id;
                                    $images = scandir($fpath);
                                }
                                $i = 1;
                                foreach ($images as $k => $v):
                                    if (!in_array($v, array('.', '..'))):
                                        $active = $i == 1 ? 'active' : '';
                                ?>
                                        <div class="carousel-item <?= $active ?>">
                                            <img class="img-fluid" src="<?= $fpath . '/' . $v ?>" alt="">
                                        </div>
                                <?php
                                        $i++;
                                    endif;
                                endforeach;
                                ?>
                                <a class="carousel-control-prev" href="#imagesCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#imagesCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <ol class="carousel-indicators">
                                <?php for ($v = 0; $v < ($i - 1); $v++): ?>
                                    <li data-target="#imagesCarousel" data-slide-to="<?= $v ?>" class="<?= ($v == 0) ? 'active' : '' ?>"></li>
                                <?php endfor; ?>
                            </ol>
                        </div>						
                        
                        <?php
                        // Map the event_time values to time slots
                        $time_slots = [
                            0 => "8 AM - 12 PM",
                            1 => "12 PM - 4 PM",
                            2 => "4 PM - 8 PM",
                            3 => "8 PM - 12 AM"
                        ];

                        $mapped_time = isset($time_slots[$event_time]) ? $time_slots[$event_time] : "Time Not Specified";
                        ?>
						
						<!-- date of event -->
                        <p style="color: #FFD700;">
                            <b><i class="fa fa-calendar"></i> <?= date("F d, Y", strtotime($schedule)); ?></b>
                        </p>
						
						<!-- time of event -->
                        <p style="color: #FFD700;">
                            <b><i class="fa fa-clock"></i> <?= $mapped_time; ?></b> 
                        </p>
						
						<!-- description of event -->
                        <p style="color: #FFD700;">
                            <?= html_entity_decode($description); ?>
                        </p>

                        <!-- Display the remaining audience capacity -->
                        <p style="color: #FFD700;">
                            <b><i class="fa fa-users"></i> Remaining Capacity: <?= $audience_capacity; ?></b>
                        </p>

                    </div>
                </div>

                <!-- Section for Attendance Confirmation -->
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <p style="color: #FFD700;">
                            <strong>Are you attending the event? We hope to see you there.</strong>
                        </p>
                        <button class="btn btn-success mr-2" id="attendYes" type="button">Yes</button>
                        <button class="btn btn-danger" id="attendNo" type="button">No</button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- for "No" Response -->
<div class="modal fade" id="noAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="noAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noAttendanceModalLabel">Thank You for Your Response</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                We're sorry to hear you can't make it. We hope to see you at future events!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="redirectHome">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.carousel').carousel();

        // Handle attendance buttons
        $('#attendYes').click(function() {
            // when 'Yes' is clicked
            if (typeof uni_modal === 'function') {
                uni_modal("Submit Registration Request", "registration.php?event_id=<?= $id ?>");
            } else {
                alert("Error: Modal function not available.");
            }
        });

        $('#attendNo').click(function() {
            $('#noAttendanceModal').modal('show');
        });

        $('#redirectHome').click(function() {
            // Redirect to the home page when OK is clicked in noAttendanceModal
            window.location.href = "index.php?page=home";
        });
    });
</script>

<style type="text/css">
<?php if(!empty($banner)): ?>
header.masthead {
    background: url(admin/assets/uploads/<?php echo $banner ?>);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover; 
}
<?php endif; ?>

.imgs {
    margin: .5em;
    max-width: calc(100%);
    max-height: calc(100%);
}
.imgs img {
    max-width: calc(100%);
    max-height: calc(100%);
    cursor: pointer;
}
#imagesCarousel, #imagesCarousel .carousel-inner, #imagesCarousel .carousel-item {
    height: 40vh !important;
    background: black;
}
#imagesCarousel {
    margin-left: unset !important;
}
#imagesCarousel .carousel-item.active {
    display: flex !important;
}
#imagesCarousel .carousel-item-next {
    display: flex !important;
}
#imagesCarousel .carousel-item img {
    margin: auto;
}
#imagesCarousel img {
    width: calc(100%) !important;
    height: auto !important;
}
#banner {
    display: flex;
    justify-content: center;
}
#banner img {
    max-width: calc(100%);
    max-height: 50vh;
    cursor: pointer;
}

.divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db);
    width: 100%;
    margin: 20px auto;
    border-radius: 5px;
}
</style>

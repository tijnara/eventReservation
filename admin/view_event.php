<?php include 'db_connect.php'; ?>
<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT e.*, v.venue, e.amount AS registration_fee FROM events e INNER JOIN venue v ON v.id = e.venue_id WHERE e.id = " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>
<style type="text/css">
    /* images styles for event */
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
        margin-top: unset;
        margin-bottom: unset;
    }
    #imagesCarousel img {
        width: calc(100%) !important;
        height: auto !important;
        max-width: calc(100%) !important;
        cursor: pointer;
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
</style>

<div class="container-field">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- uploaded banner image for event -->
                    <div class="col-md-12">
                        <div id="banner" class='mx-2'>
                            <?php if (!empty($banner)): ?>
                                <img src="assets/uploads/<?php echo($banner) ?>" alt="">
                            <?php endif; ?>
                        </div>
                        <br>
                    </div>

                    <!-- event name and venue -->
                    <div class="col-md-12">
                        <h4 class="text-center"><b><?php echo ucwords($event) ?></b></h4>
                        <p class="text-center"><small><b><i>Venue: <?php echo ucwords($venue) ?></small></i></b></p>
                        <hr class="divider" style="max-width: calc(100%)">
                    </div>

                    <div class="col-md-12" id="content">
                        <!-- uploaded images display in carousel effect -->
                        <div id="imagesCarousel" class="carousel slide col-sm-4 float-left ml-0 mx-4" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php 
                                $images = array();
                                if (isset($id)) {
                                    $fpath = 'assets/uploads/event_' . $id;
                                    $images = scandir($fpath);
                                }
                                $i = 1;
                                foreach ($images as $k => $v):
                                    if (!in_array($v, array('.', '..'))):
                                        $active = $i == 1 ? 'active' : '';
                                ?>
                                    <div class="carousel-item <?php echo $active ?>">
                                        <img class="img-fluid" src="<?php echo $fpath . '/' . $v ?>" alt="">
                                    </div>
                                <?php
                                        $i++;
                                    else:
                                        unset($images[$v]);
                                    endif;
                                endforeach;
                                ?>
                                <!-- previous and next icons -->
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
                                    <li data-target="#imagesCarousel" data-slide-to="<?php echo $v ?>" class="<?php echo ($v == 0) ? 'active' : '' ?>"></li>
                                <?php endfor; ?>
                            </ol>
                        </div>
						
						<!-- schedule -->
                        <p>                            
                            <b><i class="fa fa-calendar"></i> <?php echo date("F d, Y", strtotime($schedule)); ?></b>
                        </p>

                        <!-- time of event -->
                        <?php
                        $event_time_display = "";
                        switch ($event_time) {
                            case 0:
                                $event_time_display = "8 AM - 12 PM";
                                break;
                            case 1:
                                $event_time_display = "12 PM - 4 PM";
                                break;
                            case 2:
                                $event_time_display = "4 PM - 8 PM";
                                break;
                            case 3:
                                $event_time_display = "8 PM - 12 AM";
                                break;
                            default:
                                $event_time_display = "Time Not Specified";
                                break;
                        }
                        ?>
                        <p>
                            <b><i class="fa fa-clock"></i> 
                                <?php echo $event_time_display; ?>
                            </b>
                        </p>
						
                        <!-- registration fee -->
                        <p>
                            <b>Registration Fee: </b>
                            <?php if (!empty($registration_fee) && $registration_fee > 0): ?>
                                <?php echo '₱' . number_format($registration_fee, 2); ?>
                            <?php else: ?>
                                <i>No registration fee required for this event.</i>
                            <?php endif; ?>
                        </p>

                        <!-- description -->
                        <?php echo html_entity_decode($description); ?>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#imagesCarousel img,#banner img').click(function() {
        viewer_modal($(this).attr('src'))
    });
    $('.carousel').carousel();
</script>

<?php include 'db_connect.php' ?>
<!-- for tables -->
<style>    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .table th {
        background-color: #f2f2f2;
    }
	
</style>

<div class="containe-fluid">	
        <div class="col-lg-12">            
                <div class="card-body">
                    <h2><?php echo "Welcome back ". $_SESSION['login_name']."!"  ?></h2>
                    <hr>
					</div>                  			
        </div>    
</div>

<h4>Venue Reservation Fees Not Yet Paid (7 Days or More)</h4>

<table class="table table-bordered table-condensed table-hover" style="background-color: transparent; font-size: 16px; table-layout: fixed;">
    <thead>
        <tr>
            <th>Booking Information</th>
            <th>Customer Information</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // bookings not paid and lasting 7 or more days
        $stmt = $conn->prepare("SELECT b.*, v.venue 
                                FROM venue_booking b 
                                INNER JOIN venue v ON v.id = b.venue_id 
                                WHERE b.status = 0 
                                AND DATEDIFF(CURDATE(), b.created_at) >= 7 
                                AND b.payment_status = 0 
                                ORDER BY b.created_at DESC 
                                LIMIT 3");
        $stmt->execute();
        $bookings = $stmt->get_result();

        // unpaid bookings that are 7 or more days old
        $countStmt = $conn->prepare("SELECT COUNT(*) AS total 
                                     FROM venue_booking b 
                                     WHERE b.status = 0 
                                     AND DATEDIFF(CURDATE(), b.created_at) >= 7
                                     AND b.payment_status = 0");
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalPendingBookings = $countResult->fetch_assoc()['total'];

        // Display the bookings
        if ($bookings->num_rows > 0): 
            while ($row = $bookings->fetch_assoc()):
        ?>
        <tr onclick="window.location='index.php?page=booking';" style="cursor: pointer;">
		
            <!-- Booking Information -->
            <td style="padding: 10px;">
                <div style="padding: 10px;">
                    <p style="margin: 2px 0;">Venue: <b><?php echo htmlspecialchars(ucwords($row['venue'])) ?></b></p>
                    <p style="margin: 2px 0;"><small>Schedule: <b><?php echo date("M d, Y", strtotime($row['datetime'])) . ', Time: <b>' . ['8am - 12pm', '12pm - 4pm', '4pm - 8pm', '8pm - 12am'][$row['timeofevent']] ?? 'Unknown time'; ?></b></small></p>
                </div>
            </td>
            
            <!-- Customer Information -->
            <td style="padding: 10px;">
                <div style="padding: 10px;">
                    <small>
                        <p style="margin: 1px 0;">Booked by: <b><?php echo htmlspecialchars(ucwords($row['name'])) ?></b></p>
                        <p style="margin: 1px 0;">Email: <b><?php echo htmlspecialchars($row['email']) ?></b></p>
                        <p style="margin: 1px 0;">Contact: <b><?php echo htmlspecialchars($row['contact']) ?></b></p>
                        <p style="margin: 1px 0;">Address: <b><?php echo htmlspecialchars(ucwords($row['address'])) ?></b></p>
                        <p style="margin: 1px 0;">Payment Option: <b><?php echo $row['payment'] == 0 ? 'Cash' : 'GCash'; ?></b></p>
                    </small>
                </div>
            </td>

            <!-- Status -->
            <td class="text-center"><span class="badge badge-warning">Not Paid</span></td>

            <!-- Created At -->
            <td class="text-center"><b><?php echo date("M d, Y h:i A", strtotime($row['created_at'])) ?></b></td>
        </tr>
        <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">No bookings found that are not paid and last 3 or more days.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Step 4: Indicate if there are more unpaid bookings -->
<?php if ($totalPendingBookings > 3): ?>
    <div class="text-center">
        <p><a href="index.php?page=booking&status=pending_payment" style="color: blue;">
            More <?php echo $totalPendingBookings - 3; ?> unpaid booking(s) found.
        </a></p>
    </div>
<?php endif; ?>



<div class="container-fluid">
	<p></p>
    <center><h3>Summary Table of Latest Activities</h3></center>
	
<h4>Latest Venue Bookings (Latest and In Verification)</h4>
<?php include 'db_connect.php'; ?>

<table class="table table-bordered table-condensed table-hover" style="background-color: transparent; font-size: 16px; table-layout: fixed;">
    <!-- column names -->
    <thead>
        <tr>
            <th>Booking Information</th>
            <th>Customer Information</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Fetch latest booking
        $stmt = $conn->prepare("SELECT b.*, v.venue FROM venue_booking b INNER JOIN venue v ON v.id = b.venue_id WHERE b.status = 0 ORDER BY b.created_at DESC LIMIT 1");
        $stmt->execute();
        $latestBooking = $stmt->get_result();
        $latestBookingId = null;

        if ($latestBooking->num_rows > 0): 
            $row = $latestBooking->fetch_assoc();
            $latestBookingId = $row['id'];
        ?>
        <tr onclick="window.location='index.php?page=booking';" style="cursor: pointer;">
		
            <!-- Booking Information -->
            <td style="padding: 10px;">
                <div style="padding: 10px;">
                    <p style="margin: 2px 0;">Venue: <b><?php echo htmlspecialchars(ucwords($row['venue'])) ?></b></p>
                    <p style="margin: 2px 0;"><small>Schedule: <b><?php echo date("M d, Y", strtotime($row['datetime'])) . ', Time: <b>' . ['8am - 12pm', '12pm - 4pm', '4pm - 8pm', '8pm - 12am'][$row['timeofevent']] ?? 'Unknown time'; ?></b></small></p>
                </div>
            </td>
			
            <!-- Customer Information -->
            <td style="padding: 10px;">
                <div style="padding: 10px;">
                    <small>
                        <p style="margin: 1px 0;">Booked by: <b><?php echo htmlspecialchars(ucwords($row['name'])) ?></b></p>
                        <p style="margin: 1px 0;">Email: <b><?php echo htmlspecialchars($row['email']) ?></b></p>
                        <p style="margin: 1px 0;">Contact: <b><?php echo htmlspecialchars($row['contact']) ?></b></p>
                        <p style="margin: 1px 0;">Address: <b><?php echo htmlspecialchars(ucwords($row['address'])) ?></b></p>
                        <p style="margin: 1px 0;">Payment Option: <b><?php echo $row['payment'] == 0 ? 'Cash' : 'GCash'; ?></b></p>
                    </small>
                </div>
            </td>
			
            <!-- Status -->
            <td class="text-center"><span class="badge badge-secondary">For Verification</span></td>
			
            <!-- Created At -->
            <td class="text-center"><b><?php echo date("M d, Y h:i A", strtotime($row['created_at'])) ?></b></td>
			
        </tr>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">No latest booking found.</td></tr>
        <?php endif; ?>

        <?php 
        // Fetch additional bookings (in verification)
        if ($latestBookingId !== null) {
            $stmt = $conn->prepare("SELECT b.*, v.venue FROM venue_booking b INNER JOIN venue v ON v.id = b.venue_id WHERE b.status = 0 AND b.id != ? ORDER BY b.created_at DESC LIMIT 2");
            $stmt->bind_param("i", $latestBookingId);
            $stmt->execute();
            $additionalBookings = $stmt->get_result();

            if ($additionalBookings->num_rows > 0): 
                while ($row = $additionalBookings->fetch_assoc()): ?>
                <tr onclick="window.location='index.php?page=booking';" style="cursor: pointer;">
				
					<!-- booking information -->
                    <td style="padding: 10px;">
                        <div style="padding: 10px;">
                            <p style="margin: 2px 0;">Venue: <b><?php echo htmlspecialchars(ucwords($row['venue'])) ?></b></p>
                            <p style="margin: 2px 0;"><small>Schedule: <b><?php echo date("M d, Y", strtotime($row['datetime'])) . ', Time: <b>' . ['8am - 12pm', '12pm - 4pm', '4pm - 8pm', '8pm - 12am'][$row['timeofevent']] ?? 'Unknown time'; ?></b></small></p>
                        </div>
						
                    </td>
					
					<!-- customer information -->
                    <td style="padding: 10px;">
                        <div style="padding: 10px;">
                            <small>
                                <p style="margin: 1px 0;">Booked by: <b><?php echo htmlspecialchars(ucwords($row['name'])) ?></b></p>
                                <p style="margin: 1px 0;">Email: <b><?php echo htmlspecialchars($row['email']) ?></b></p>
                                <p style="margin: 1px 0;">Contact: <b><?php echo htmlspecialchars($row['contact']) ?></b></p>
                                <p style="margin: 1px 0;">Address: <b><?php echo htmlspecialchars(ucwords($row['address'])) ?></b></p>
                                <p style="margin: 1px 0;">Payment Option: <b><?php echo $row['payment'] == 0 ? 'Cash' : 'GCash'; ?></b></p>
                            </small>
                        </div>
                    </td>	

					<!-- status -->	
                    <td class="text-center"><span class="badge badge-secondary">For Verification</span></td>
					
					<!-- created at -->
                    <td class="text-center"><b><?php echo date("M d, Y h:i A", strtotime($row['created_at'])) ?></b></td>
					
                </tr>
                <?php endwhile; 
            else: ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="index.php?page=booking" style="text-decoration: none; color: blue;">View All Bookings</a>
                    </td>
                </tr>
            <?php endif; 
        } ?>

        <?php 
        // Count total bookings
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM venue_booking WHERE status = 0");
        $stmt->execute();
        $totalCount = $stmt->get_result()->fetch_assoc()['total'];

        if ($totalCount > 3): ?>
            <tr><td colspan="4" class="text-center"><a href="index.php?page=booking" style="text-decoration: none; color: blue;">More <b><?php echo $totalCount - 3; ?>+</b></a></td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Event Audiences Summary -->
    <h4>Latest Audiences/Guests</h4>
<?php include 'db_connect.php'; ?>
<table class="table table-bordered table-condensed table-hover" style="background-color: transparent; font-size: 16px;">
    <!-- column names -->
    <thead>
        <tr>
            <th>Event Info</th>
            <th>Audience Info</th>
            <th>Status</th>
            <th>Date Created</th>
        </tr>
    </thead>

    <tbody>
	
        <?php 
        // Get total count of 'For Verification' audience entries
        $totalCountResult = $conn->query("SELECT COUNT(*) as count FROM audience WHERE status = 0");
        $totalCount = $totalCountResult->fetch_assoc()['count'];

        // latest 3 audience entries that are 'For Verification'
        $registering = $conn->query("SELECT a.*, e.event, e.payment_type, e.type, e.amount, e.schedule 
                                      FROM audience a 
                                      INNER JOIN events e ON e.id = a.event_id 
                                      WHERE a.status = 0 
                                      ORDER BY a.date_created DESC 
                                      LIMIT 3");
        
        if ($registering->num_rows > 0): 
            while ($row = $registering->fetch_assoc()): 
        ?>
		
        <!-- link to Event Audience List -->
        <tr onclick="window.location='index.php?page=audience';" style="cursor: pointer;">
		
            <!-- Event info -->
            <td>
                <p class="mb-1"><b><?php echo htmlspecialchars(ucwords($row['event'])) ?></b></p>
                <small>Schedule: <b><?php echo date("M d, Y h:i A", strtotime($row['schedule'])) ?></b></small><br>
                <small>Type: <b><?php echo $row['type'] == 1 ? "Public" : "Private" ?></b></small><br>
                <small>Fee: <b><?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2) ?></b></small>
            </td>
			
            <!-- audience info -->
            <td>
                <p class="mb-1"><b><?php echo htmlspecialchars(ucwords($row['name'])) ?></b></p>
                <small>Email: <b><?php echo htmlspecialchars($row['email']) ?></b></small><br>
                <small>Contact: <b><?php echo htmlspecialchars($row['contact']) ?></b></small><br>
                <small>Address: <b><?php echo htmlspecialchars(ucwords($row['address'])) ?></b></small>
            </td>
			
            <!-- Status -->
            <td class="text-center">
                <span class="badge badge-secondary">For Verification</span>
            </td>
			
            <!-- date created -->
            <td class="text-center">
                <small><b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></b></small>
            </td>
			
        </tr>
        
        <?php 
            endwhile;             
            $remaining = max(0, $totalCount - 3); 

            if ($remaining > 0): ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="index.php?page=audience" style="text-decoration: none; color: blue;">More <?php echo $remaining; ?>+</a>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <a href="index.php?page=audience" style="text-decoration: none; color: blue;">View All Audience</a>
                    </td>
                </tr>
            <?php endif; 
        else: 
            echo '<tr><td colspan="4" class="text-center"><a href="index.php?page=audience" style="text-decoration: none; color: blue;">View All Audience</a></td></tr>'; 
        endif; 
        ?>
    </tbody>
</table>


<!-- Events Summary -->
    <h4>Latest Events</h4>
<table class="table table-bordered table-condensed table-hover" style="font-size: 16px;">

    <colgroup>
        <col> 
        <col>
        <col>
        <col>
    </colgroup>
	
	<!-- column names -->
    <thead>
        <tr>
            <th>Schedule</th>
            <th>Venue</th>
            <th>Event Info</th>
            <th>Date Created</th>
        </tr>
    </thead>
	
    <tbody>
	
        <?php 
        // Get the latest event
        $latestEvent = $conn->query("SELECT e.*, v.venue FROM events e INNER JOIN venue v ON v.id = e.venue_id ORDER BY e.id DESC LIMIT 1");

        $latestEventId = null;

        if ($latestEvent->num_rows > 0): 
            $row = $latestEvent->fetch_assoc();
            $latestEventId = $row['id']; 

        ?>
		
		<!-- link to Events -->
        <tr onclick="window.location='index.php?page=events';" style="cursor: pointer;">
		
			<!-- schedule -->
            <td><b><?php echo date("M d, Y ", strtotime($row['schedule'])) ?></b></td>
			
			<!-- venue -->
            <td><b><?php echo htmlspecialchars(ucwords($row['venue'])) ?></b></td>
			
			<!-- event info -->
            <td>
                <p class="mb-1">Event: <b><?php echo htmlspecialchars(ucwords($row['event'])) ?></b></p>
                <small>Type: <b><?php echo $row['type'] == 1 ? "Public" : "Private" ?></b></small><br>
                <small>Fee: <b><?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2) ?></b></small>
            </td>
			
			<!-- date created -->
            <td><b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></b></td>
			
        </tr>
        <?php 
        else: 
            echo '<!-- Reviews Summary<tr><td colspan="4" class="text-center">No latest event found.</td></tr> -->';
        endif; 

        // Get additional events 
        if ($latestEventId !== null) {
            $additionalEvents = $conn->query("SELECT e.*, v.venue FROM events e INNER JOIN venue v ON v.id = e.venue_id WHERE e.id != $latestEventId ORDER BY e.id DESC LIMIT 2");

            if ($additionalEvents->num_rows > 0): 
                while ($row = $additionalEvents->fetch_assoc()):
                    $desc = htmlspecialchars(strip_tags(html_entity_decode($row['description'])));
        ?>
        <tr onclick="window.location='index.php?page=events';" style="cursor: pointer;">
		
			<!-- schedule -->
            <td><b><?php echo date("M d, Y ", strtotime($row['schedule'])) ?></b></td>
			
			<!-- venue -->
            <td><b><?php echo htmlspecialchars(ucwords($row['venue'])) ?></b></td>
			
			<!-- event info -->
            <td>
                <p class="mb-1">Event: <b><?php echo htmlspecialchars(ucwords($row['event'])) ?></b></p>
                <small>Type: <b><?php echo $row['type'] == 1 ? "Public" : "Private" ?></b></small><br>
                <small>Fee: <b><?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2) ?></b></small>
            </td>
			
			<!-- date created -->
            <td><b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></b></td>
			
        </tr>
        <?php 
                endwhile; 
            else: 
                echo '<tr><td colspan="4" class="text-center">No additional events found.</td></tr>';
            endif; 
        } else {
            echo '<tr><td colspan="4" class="text-center">No additional events found.</td></tr>';
        }
        
        // total events 
        $totalEvents = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc();
        $totalCount = $totalEvents['total'];

        // If the total count exceeds 3
        if ($totalCount > 3): ?>
            <tr>
                <td colspan="4" class="text-center" style="font-weight: bold;">
                    <a href="index.php?page=events" style="text-decoration: none; color: blue;">More <b><?php echo $totalCount - 3; ?>+</b></a>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Reviews Summary -->
    <h4>Latest Reviews</h4>
<table class="table table-bordered table-condensed table-hover" style="background-color: transparent;">

	<!-- column names -->
    <thead>
        <tr>
            <th class="text-center" style="min-width: 100px; max-width: 100px;">Reviewer Name</th>
            <th class="text-center" style="min-width: 120px; max-width: 120px;">Email</th>
            <th class="text-center" style="min-width: 20px; max-width: 80px;">Category</th>
            <th class="text-center" style="min-width: 15px; max-width: 55px;">Rating</th>
            <th class="text-center" style="max-width: 250px;">Review Content</th>
            <th class="text-center" style="min-width: 60px; max-width: 115px;">Created At</th>
        </tr>
		
    </thead>
	
    <tbody>
	
        <?php 
        // select the latest 3 reviews
        $reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 3");
        
        // Check if there are any reviews
        if ($reviews->num_rows > 0): 
            while ($row = $reviews->fetch_assoc()): 
        ?>
		
		<!-- link to Manage Reviews -->
        <tr onclick="window.location='index.php?page=manage_reviews';" style="cursor: pointer;">		
		
            <!-- reviewer's name -->
            <td style="min-width: 100px; max-width: 100px;"><?php echo htmlspecialchars($row['name']); ?></td>

            <!-- reviewer's email -->
            <td style="min-width: 120px; max-width: 120px;"><?php echo htmlspecialchars($row['email']); ?></td>

            <!-- review category -->
            <td style="min-width: 20px; max-width: 80px;"><?php echo htmlspecialchars($row['category']); ?></td>

            <!-- review rating -->
            <td style="min-width: 15px; max-width: 55px;"><?php echo htmlspecialchars($row['rating']); ?>/5</td>

            <!-- review content  -->
            <td class="review-text" style="max-width: 250px; word-wrap: break-word; white-space: normal;"><?php echo htmlspecialchars($row['review_text']); ?></td>

            <!-- date the review was created -->
            <td style="word-wrap: break-word; white-space: normal; min-width: 60px; max-width: 115px;"><?php echo htmlspecialchars(date('Y-m-d h:i A', strtotime($row['created_at']))); ?></td>
			
        </tr>
        <?php 
            endwhile; 
        else: 
        
        ?>
            <tr>
                <td colspan="6" class="text-center" style="color: red;">
                    No additional reviews found.
                </td>
            </tr>
        <?php endif; ?>

        <?php 
                $total_reviews = $conn->query("SELECT COUNT(*) as count FROM reviews")->fetch_assoc()['count'];        
        
        if ($total_reviews > 3):
            $remaining_reviews = $total_reviews - 3; 
        ?>
            <tr>
                <td colspan="6" class="text-center">
                    <a href="index.php?page=manage_reviews" style="text-decoration: none; color: blue;">
                        More <?php echo $remaining_reviews; ?>+
                    </a>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center" >
                    No More Latest Reviews
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>


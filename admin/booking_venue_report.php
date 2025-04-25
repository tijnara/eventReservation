<?php include 'db_connect.php'; ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
		
            <div class="card-header">
                Booking Venue Report
            </div>
			
            <div class="card-body">
                <div class="col-md-12">
                    <form action="" id="filter">
                        <div class="row form-group">
						
							<!-- selection -->
                            <div class="col-md-6">							
                                <label for="" class="control-label">Venue</label>
                                <select name="venue_id" id="venue_id" class="custom-select select2" required>
                                    <option value="">Select Venue</option>
                                    <?php 
                                    $venue = $conn->query("SELECT * FROM venue ORDER BY venue ASC");
                                    while ($row = $venue->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo ucwords($row['venue']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>								
                            </div>
							
							<!-- buttons -->
                            <div class="col-md-2">
                                <label for="" class="control-label">&nbsp;</label>
                                <button class="btn-primary btn-sm btn-block col-sm-12" type="submit">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <label for="" class="control-label">&nbsp;</label>
                                <button class="btn-success btn-sm btn-block col-sm-12" id="print" type="button">
                                    <i class="fa fa-print"></i> Print
                                </button>
                            </div>
							
                        </div>
                    </form>
					
                    <hr>
					
					<!-- Printable Document -->
                    <div class="row" id="printable">
                        <div id="onPrint">
                            <p class="text-center">Regina's Garden and Restaurant</p>
                            <p class="text-center">Venue's Booking List and Details</p>
                            <hr>
                            <p class="">Venue: <span id="venue"></span></p>
                        </div>
                        <table class="table table-bordered" style="background-color: #f0f0f0;">
                            <thead>
							<th class="text-center" style="width: 5%;">#</th>
							<th class="text-center" style="width: 25%;">Name</th>      
							<th class="text-center" style="width: 30%;">Address</th>   
							<th class="text-center" style="width: 20%;">Email</th>
							<th class="text-center" style="width: 10%;">Contact</th>
							<th class="text-center" style="width: 10%;">Scheduled Time</th>
							<th class="text-center" style="width: 10%;">Time of Event</th>                                
							<th class="text-center" style="width: 10%;">Status</th>
							<th class="text-center" style="width: 10%;">Payment Method</th>
							<th class="text-center" style="width: 10%;">Payment Status</th> 
							<th class="text-center" style="width: 10%;">Created At</th>
						</thead>

                            <tbody>
                                <tr>
                                    <th colspan="10"><center>Select Venue First.</center></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
					
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print button icon -->
<style type="text/css">
    #onPrint {
        display: none;
    }
</style>

<!-- Printable Document -->
<noscript>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        tr, td, th {
            border: 1px solid black;
        }
        .text-center {
            text-align: center;
        }
        p {
            font-weight: 600;
        }
    </style>
</noscript>

<script>
	// form submissions to be handled, fetching data based on user input
    $('#filter').submit(function(e) {
        e.preventDefault();
        start_load();

        $.ajax({
            url: 'ajax.php?action=get_booking_venue_report',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                console.log(resp);
                try {
                    resp = JSON.parse(resp);
                    if (resp.venue) {
                        $('#venue').html(resp.venue.venue);
                    }
                    if (resp.data && resp.data.length > 0) {
                        $('table tbody').html('');
                        let i = 1;
                        resp.data.forEach(function(item) {
                            // Check if the booking is confirmed
                            if (item.status === 1 || item.status === 2) {
                                addBookingRow(item, i++);
                            }
                        });
                    } else {
                        $('table tbody').html('<tr><th colspan="10"><center>No Data.</center></th></tr>');
                    }
                } catch (e) {
                    console.error("Parsing error:", e);
                    alert("An error occurred while processing the data.");
                }
            },
            complete: function() {
                end_load();
            }
        });
    });
	
	//  constructs a new row of data in a table
    function addBookingRow(item, index) {
    const tr = $('<tr class="item"></tr>');
    tr.append('<td class="text-center">' + index + '</td>');
    tr.append('<td>' + item.name + '</td>');
    tr.append('<td>' + item.address + '</td>');
    tr.append('<td>' + item.email + '</td>');
    tr.append('<td>' + item.contact + '</td>');
    tr.append('<td>' + item.datetime + '</td>');
    tr.append('<td>' + item.time_slot + '</td>');
    const statusText = item.status === 1 ? "Confirmed" : "Canceled";
    tr.append('<td>' + statusText + '</td>');
    const paymentMethod = item.payment == 0 ? "Cash" : "Gcash";
    tr.append('<td>' + paymentMethod + '</td>');
    const paymentStatus = item.payment_status === 1 ? "Paid" : "Unpaid";
    tr.append('<td>' + paymentStatus + '</td>');
    tr.append('<td>' + item.created_at + '</td>');

    $('table tbody').append(tr); 
}

	$('#print').click(function() {
        if ($('table tbody').find('.item').length <= 0) {
            alert_toast("No Data to Print", 'warning');
            return false;
        }
        const nw = window.open("", "_blank", "width=900,height=600");
        nw.document.write($('noscript').html());
        nw.document.write($('#printable').html());
        nw.document.close();
        nw.print();
        setTimeout(function() {
            nw.close();
        }, 700);
    });
</script>

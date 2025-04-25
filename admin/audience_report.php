<?php include 'db_connect.php'; ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
		
            <div class="card-header">
                Event Audience Report
            </div>
			
            <div class="card-body">
                <div class="col-md-12">
                    <form action="" id="filter">
                        <div class="row form-group">
						
							<!-- selection dropdown for events -->
                            <div class="col-md-4">
                                <label for="" class="control-label">Event</label>
                                <select name="event_id" id="event_id" class="custom-select select2">
                                    <option></option>
                                    <?php 
                                    $event = $conn->query("SELECT * FROM events ORDER BY event ASC");
                                    while ($row = $event->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>" <?php echo isset($event_id) && $event_id == $row['id'] ? 'selected' : ''; ?>>
                                            <?php echo ucwords($row['event']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
							
							<!-- filter and print buttons -->
                            <div class="col-md-2">
                                <label for="" class="control-label">&nbsp;</label>
                                <button class="btn-primary btn-sm btn-block col-sm-12">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <label for="" class="control-label">&nbsp;</label>
                                <button class="btn-success btn-sm btn-block col-sm-12" id="print" type="button"><i class="fa fa-print"></i> Print</button>
                            </div>
							
                        </div>
                    </form>
					
                    <hr>
					
                    <!-- Printable Document -->
                    <div class="row" id="printable">
                        <div id="onPrint">
                            <p class="text-center">Regina's Garden and Restaurant</p>
                            <p class="text-center">Audience/Guest List and Details</p>
                            <hr>
                            <p class="">Event: <span id="ename"></span></p>
                            <p class="">Venue: <span id="evenue"></span></p>
                        </div>    
                        <table class="table table-bordered" style="background-color: #f0f0f0;">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Name</th>
									<th class="text-center">Email</th>
									<th class="text-center">Contact</th>
									<th class="text-center">Payment Status</th>
									<th class="text-center">Audience Status</th>
									<th class="text-center">Date Created</th> 
								</tr>
							</thead>
							<tbody>
								<tr><th colspan="7"><center>Select Event First.</center></th></tr>
							</tbody>
						</table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #onPrint {
        display: none;
    }
</style>

<!-- for the Printable Document -->
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
	//  fetch and display an audience report for a specific/selected event
    $('#filter').submit(function(e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_audience_report',
            method: 'POST',
            data: {event_id: $('#event_id').val()},
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp);
                    if (!!resp.event) {
                        $('#ename').html(resp.event.event);
                        $('#evenue').html(resp.event.venue);
                    }
                    if (!!resp.data && Object.keys(resp.data).length > 0) {
                        $('table tbody').html(''); 
                        var i = 1;
                        Object.keys(resp.data).map(k => {
                            var paymentStatus = resp.data[k].pstatus; 

                            if (paymentStatus !== 'Pending') {
                                var tr = $('<tr class="item"></tr>');
                                tr.append('<td class="text-center">' + (i++) + '</td>');
                                tr.append('<td class="">' + resp.data[k].name + '</td>');
                                tr.append('<td class="">' + resp.data[k].email + '</td>');
                                tr.append('<td class="">' + resp.data[k].contact + '</td>');
                                
                                tr.append('<td class="">' + paymentStatus + '</td>');
                                tr.append('<td class="">' + resp.data[k].status_text + '</td>'); 
                                tr.append('<td class="">' + resp.data[k].date_created + '</td>'); 
                                $('table tbody').append(tr);
                            }
                        });

                        if ($('table tbody').children().length === 0) {
                            $('table tbody').html('<tr><th colspan="7"><center>No Data.</center></th></tr>');
                        }
                    } else {
                        $('table tbody').html('<tr><th colspan="7"><center>No Data.</center></th></tr>'); 
                    }
                }
            },
            complete: function() {
                end_load();
            }
        });
    });

    $('#print').click(function() {
        if ($('table tbody').find('.item').length <= 0) {
            alert_toast("No Data to Print", 'warning');
            return false;
        }
        var nw = window.open("", "_blank", "width=900,height=600");
        nw.document.write($('noscript').html());
        nw.document.write($('#printable').html());
        nw.document.close();
        nw.print();
        setTimeout(function() {
            nw.close();
        }, 700);
    });
</script>



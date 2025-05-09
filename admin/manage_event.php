<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM events where id= " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="" id="manage-event">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					
                    <!-- event name -->
					<div class="form-group row">	
						<div class="col-md-5">
                            <label for="" class="control-label">Event</label>
                            <input type="text" class="form-control" name="event" value="<?php echo isset($event) ? $event : '' ?>" required>
                        </div>
                    </div>
						
					<!-- schedule -->	
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label for="" class="control-label">Schedule</label>
                            <input type="text" class="form-control datepicker" name="schedule" value="<?php echo isset($schedule) ? date("Y-m-d", strtotime($schedule)) : '' ?>" required autocomplete="off">
                        </div>
                    </div>
					
					<!-- time of event -->
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label for="" class="control-label">Time of Event</label>
                            <select name="event_time" id="event_time" class="custom-select" required>
                                <option value=""></option>
                                <option value="0" <?php echo isset($event_time) && $event_time == 0 ? "selected" : '' ?>>8AM-12PM</option>
                                <option value="1" <?php echo isset($event_time) && $event_time == 1 ? "selected" : '' ?>>12PM-4PM</option>
                                <option value="2" <?php echo isset($event_time) && $event_time == 2 ? "selected" : '' ?>>4PM-8PM</option>
                                <option value="3" <?php echo isset($event_time) && $event_time == 3 ? "selected" : '' ?>>8PM-12AM</option>
                            </select>
                        </div>
                    </div>

					<!-- venue -->	
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label for="" class="control-label">Venue</label>
                            <select name="venue_id" id="" required="" class="custom-select select2">
                                <option value=""></option>
                                <?php
                                    $artist = $conn->query("SELECT * FROM venue order by venue asc");
                                    while ($row = $artist->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($venue_id) && $venue_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['venue']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
					
					<!-- descr-->
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required style="width: 100%; max-width: 500px;"><?php echo isset($description) ? html_entity_decode($description) : '' ?></textarea>
                    </div>
					
					<!-- if checked = free for all -->
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="payment_status" name="payment_status" <?php echo isset($payment_type) && $payment_type == 1 ? "checked" : '' ?>>
                            <label class="form-check-label" for="payment_status">
                                Free For All
                            </label>
                        </div>
                    </div>
					
					<!-- reg fee -->
                    <div class="form-group row" <?php echo isset($payment_type) && $payment_type == 1 ? "style='display:none'" : '' ?>>
                        <div class="col-md-5">
                            <label for="" class="control-label">Registration Fee</label>
                            <input type="number" step="any" class="form-control text-right" name="amount" id='amount' value="<?php echo isset($amount) ? $amount : 0 ?>" required autocomplete="off">
                        </div>
                    </div>
					
					<!-- Audience Capacity -->
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label for="audience_capacity" class="control-label">Audience Capacity</label>
                            <input type="number" step="any" class="form-control text-right" name="audience_capacity" id="audience_capacity" value="<?php echo isset($audience_capacity) ? htmlspecialchars($audience_capacity) : 0; ?>" required autocomplete="off">
                        </div>
                    </div>
					
<!-- banner image -->
<div class="row form-group" style="display: flex; align-items: center;">    
    <div class="col-md-5" style="display: flex; align-items: center; justify-content: center; gap: 10px;">        
        <img src="<?php echo isset($banner) ? 'assets/uploads/' . $banner : '' ?>" 
             alt="Banner Preview" 
             id="banner-preview" 
             style="display: <?php echo isset($banner) ? 'block' : 'none'; ?>; max-width: 100%; max-height: 300px;">
			 <button type="button" class="btn btn-primary btn-upload" onclick="document.getElementById('banner-upload').click();">
            Upload Banner Image</button>       
        <input type="file" id="banner-upload" name="banner" accept="image/*" style="display: none;" onchange="displayImg2(this)">
    </div>
</div>


<!-- additional images -->
<div class="form-group">
    <label class="control-label">Additional Images</label>
    <input type="file" id="chooseFile" multiple accept="image/*" style="display: none" onchange="displayIMG(this)">
    <button type="button" class="btn btn-primary mt-2 mb-3" onclick="document.getElementById('chooseFile').click();">Choose Files</button>
    
    <!-- Drop Area for Drag-and-Drop Upload -->
    <div id="drop" class="border rounded d-flex flex-wrap align-items-center p-3" 
         style="min-height: 50vh; max-height: 100vh; overflow-y: auto; border: 2px dashed #007bff; background-color: #f8f9fa;">
        
        <!-- Drop/Upload Message -->
        <?php if (!isset($images) || count($images) <= 3): ?>
            <span id="dname" class="text-muted text-center w-100" style="font-size: 1.2em; font-weight: 500;">
                Drop Files Here or Click to Upload
            </span>
        <?php endif; ?>
        
        <!-- Display Uploaded Images -->
        <?php 
        $images = array();
        if (isset($id)) {
            $fpath = 'assets/uploads/event_' . $id;
            if (is_dir($fpath)) {
                $images = scandir($fpath);
            }
        }
        foreach ($images as $k => $v) {
            if (!in_array($v, array('.', '..'))) {
                $img = base64_encode(file_get_contents($fpath . '/' . $v));
        ?>
                <div class="imgF position-relative m-2 border border-primary rounded p-1" style="max-width: 300px; overflow: hidden; display: flex; flex-direction: column; align-items: center;">
                    <span class="rem badge badge-danger position-absolute" onclick="rem_func($(this))" 
                          style="top: 0; right: 0; cursor: pointer; font-size: 1.2em; padding: 5px; z-index: 1;">
                        <i class="fa fa-times"></i>
                    </span>
                    <input type="hidden" name="img[]" value="<?php echo $img; ?>">
                    <input type="hidden" name="imgName[]" value="<?php echo htmlspecialchars($v); ?>">
                    <img class="imgDropped rounded" src="<?php echo htmlspecialchars($fpath . '/' . $v); ?>" 
                         style="width: 100%; height: auto; max-height: 200px; object-fit: cover;">
                </div>
        <?php 
            } // end of if (!in_array($v, array('.', '..')))
        } // end of foreach
        ?>
		
    </div>	
</div>

    <div id="list"></div>
</div>
					<!-- save buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-block btn-primary col-sm-2"> Save</button>
                        </div>
                    </div>
					
                </form>
            </div>
        </div>
    </div>

<div class="imgF" style="display: none" id="img-clone">
    <span class="rem badge badge-primary" onclick="rem_func($(this))"><i class="fa fa-times"></i></span>
</div>

<script>

$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
	minDate: 0,
	startDate: '+30d' ,
    autoclose: true
});

// for "Free for All" checkbox
$('#payment_status').on('change keypress keyup', function() {
    if ($(this).prop('checked') == true) {
        $('#amount').closest('.form-group').hide()
    } else {
        $('#amount').closest('.form-group').show()
    }
});

// will handle the save button
$('#manage-event').submit(function(e) {
    e.preventDefault();
    start_load();
    $('#msg').html('');
    $.ajax({
        url: 'ajax.php?action=save_event',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        dataType: 'json', 
        success: function(resp) {
            end_load(); 
            if (resp.status === 'conflict') {
                alert_toast(resp.message, 'danger'); // Display conflict message as a toast
            } else if (resp.status === 'success') {
                alert_toast("Data successfully saved", 'success');
                setTimeout(function() {
                    location.href = "index.php?page=events";
                }, 1500);
            } else {
                alert_toast(resp.message, 'danger'); // Display error message if save failed other error
            }
        },
        error: function() {
            end_load(); 
            alert_toast("An error occurred while processing your request.", 'danger');
        }
    });
});
// alert notification for conflicts
function alert_toast(message, type) {
    var toast = $('<div class="toast" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">' +
        '<div class="toast-body ' + type + '">' + message + '</div>' +
    '</div>');    
    // Append to body
    $('body').append(toast);    
    toast.fadeIn(300).delay(6000).fadeOut(300, function() {
        $(this).remove();
    });
}

// for drag-and-drop file upload feature, specifically for images	
	if (window.FileReader) {
  var drop;
  addEventHandler(window, 'load', function() {
    var status = document.getElementById('status');
    drop = document.getElementById('drop');
    var dname = document.getElementById('dname');
    var list = document.getElementById('list');

    function cancel(e) {
      if (e.preventDefault) {
        e.preventDefault();
      }
      return false;
    }

    // Tells the browser that we *can* drop on this target
    addEventHandler(drop, 'dragover', cancel);
    addEventHandler(drop, 'dragenter', cancel);

    addEventHandler(drop, 'drop', function(e) {
      e = e || window.event; // get window.event if e argument missing (in IE)   
      if (e.preventDefault) {
        e.preventDefault();
      } // stops the browser from redirecting off to the image.
      $('#dname').remove();
      var dt = e.dataTransfer;
      var files = dt.files;
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();

        // handlers here
        reader.readAsDataURL(file);
        addEventHandler(reader, 'loadend', function(e, file) {
          var bin = this.result;
          var imgF = document.getElementById('img-clone');
          	imgF = imgF.cloneNode(true);
          imgF.removeAttribute('id')
          imgF.removeAttribute('style')

          var img = document.createElement("img");
          var fileinput = document.createElement("input");
          var fileinputName = document.createElement("input");
          fileinput.setAttribute('type','hidden')
          fileinputName.setAttribute('type','hidden')
          fileinput.setAttribute('name','img[]')
          fileinputName.setAttribute('name','imgName[]')
          fileinput.value = bin
          fileinputName.value = file.name
          img.classList.add("imgDropped")
          img.file = file;
          img.src = bin;
          imgF.appendChild(fileinput);
          imgF.appendChild(fileinputName);
          imgF.appendChild(img);
          drop.appendChild(imgF)
        }.bindToEventHandler(file));
      }
      return false;

    });

    Function.prototype.bindToEventHandler = function bindToEventHandler() {
      var handler = this;
      var boundParameters = Array.prototype.slice.call(arguments);
      return function(e) {
        e = e || window.event; // get window.event if e argument missing (in IE)   
        boundParameters.unshift(e);
        handler.apply(this, boundParameters);
      }
    };
  });
} else {
  document.getElementById('status').innerHTML = 'Your browser does not support the HTML5 FileReader.'; // handler for browser does not support HTML5 FileReader
}

// make sure the event handlers are compatible in all modern and old browsers
function addEventHandler(obj, evt, handler) {
  if (obj.addEventListener) {
    obj.addEventListener(evt, handler, false);
  } else if (obj.attachEvent) {
    obj.attachEvent('on' + evt, handler);
  } else {
    obj['on' + evt] = handler;
  }
}

// handle image file uploads. allows users to upload images and dynamically displays those images
function displayIMG(input){

    	if (input.files) {
	if($('#dname').length > 0)
		$('#dname').remove();

    			Object.keys(input.files).map(function(k){
    				var reader = new FileReader();
				        reader.onload = function (e) {
				        	// $('#cimg').attr('src', e.target.result);
          				var bin = e.target.result;
          				var fname = input.files[k].name;
          				var imgF = document.getElementById('img-clone');
						  	imgF = imgF.cloneNode(true);
						  imgF.removeAttribute('id')
						  imgF.removeAttribute('style')
				        	var img = document.createElement("img");
					          var fileinput = document.createElement("input");
					          var fileinputName = document.createElement("input");
					          fileinput.setAttribute('type','hidden')
					          fileinputName.setAttribute('type','hidden')
					          fileinput.setAttribute('name','img[]')
					          fileinputName.setAttribute('name','imgName[]')
					          fileinput.value = bin
					          fileinputName.value = fname
					          img.classList.add("imgDropped")
					          img.src = bin;
					          imgF.appendChild(fileinput);
					          imgF.appendChild(fileinputName);
					          imgF.appendChild(img);
					          drop.appendChild(imgF)
				        }
		        reader.readAsDataURL(input.files[k]);
    			})
    			
rem_func()

    }
    }
	
// users can upload images and preview the image	
    function displayImg2(input) {
        const preview = document.getElementById('banner-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            preview.src = '';
        }
    }

// part of a file upload interface. updating the UI based on user actions (x button/remove button to remove or delete the file/image).	
function rem_func(_this){
		_this.closest('.imgF').remove()
		if($('#drop .imgF').length <= 0){
			$('#drop').append('<span id="dname" class="text-center">Drop Files Here</label></span>')
		}
}
</script>
<style>
/* uploading a photo and drag and drop for uploading a photo */
#drop {
   	min-height: 15vh;
    max-height: 30vh;
    overflow: auto;
    width: calc(100%);
    border: 5px solid #929292;
    margin: 10px;
    border-style: dashed;
    padding: 10px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
	#uploads {
		min-height: 15vh;
	width: calc(100%);
	margin: 10px;
	padding: 10px;
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	}
	#uploads .img-holder{
	    position: relative;
	    margin: 1em;
	    cursor: pointer;
	}
	#uploads .img-holder:hover{
	    background: #0095ff1f;
	}
	#uploads .img-holder .form-check{
	    display: none;
	}
	#uploads .img-holder.checked .form-check{
	    display: block;
	}
	#uploads .img-holder.checked{
	    background: #0095ff1f;
	}
	#uploads .img-holder img {
		height: 39vh;
    width: 22vw;
    margin: .5em;
		}
	#uploads .img-holder span{
	    position: absolute;
	    top: -.5em;
	    left: -.5em;
	}
	#dname{
		margin: auto 
	}
img.imgDropped {
    height: 16vh;
    width: 7vw;
    margin: 1em;
}
.imgF {
    border: 1px solid #0000ffa1;
    border-style: dashed;
    position: relative;
    margin: 1em;
}
span.rem.badge.badge-primary {
    position: absolute;
    top: -.5em;
    left: -.5em;
    cursor: pointer;
}
label[for="chooseFile"]{
	color: #0000ff94;
	cursor: pointer;
}
label[for="chooseFile"]:hover{
	color: #0000ffba;
}
.opts {
    position: absolute;
    top: 0;
    right: 0;
    background: #00000094;
    width: calc(100%);
    height: calc(100%);
    justify-items: center;
    display: flex;
    opacity: 0;
    transition: all .5s ease;
}
.img-holder:hover .opts{
    opacity: 1;

}

/* checkbox for "Free For All" */	
	input[type=checkbox]{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}

button.btn.btn-sm.btn-rounded.btn-sm.btn-dark {
    margin: auto;
}

</style>
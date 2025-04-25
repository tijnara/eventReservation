<!DOCTYPE html>  
<html lang="en">	
<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">	
  
  <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></title>
  
<?php
  if(!isset($_SESSION['login_id']))
    header('location:login.php');
 include('./header.php'); 
 ?>

</head>

<!-- styles various elements on the page, for body background and modal components. -->
<style>
	body {
        background: url('assets/img/admin1.png') no-repeat center center fixed; 
        background-size: cover;
    }
    .modal-dialog.large {
        width: 80% !important;
        max-width: unset;
    }
    .modal-dialog.mid-large {
        width: 50% !important;
        max-width: unset;
    }
    #viewer_modal .btn-close {
        position: absolute;
        z-index: 999999;
        background: unset;
        color: white;
        border: unset;
        font-size: 27px;
        top: 0;
    }
    #viewer_modal .modal-dialog {
        width: 80%;
        max-width: unset;
        height: calc(90%);
        max-height: unset;
    }
    #viewer_modal .modal-content {
        background: black;
        border: unset;
        height: calc(100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #viewer_modal img,#viewer_modal video {
        max-height: calc(100%);
        max-width: calc(100%);
    }
</style>

<!-- setting up a web page structure with a navigation bar, toast notifications, main content area, and several modal dialogs. -->
<body>

	<?php include 'topbar.php' ?>
	<?php include 'navbar.php' ?>

<!-- Floater Button -->
<div class="floater">
    <a href="#top" class="float-btn" title="Back to Top">
        <i class="fa fa-arrow-up"></i>
    </a>
</div>

<!-- CSS for Floater -->
<style>
    /* Floater Container */
    .floater {
        position: fixed; 
        bottom: 20px; 
        right: 20px; 
        z-index: 1000; 
    }
    /* Floater Button Styling */
    .float-btn {
        display: flex; 
        justify-content: center;
        align-items: center;
        width: 50px; 
        height: 50px; 
        background-color: gray; 
        color: white; 
        border-radius: 50%; 
        text-decoration: none; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); 
        font-size: 20px; 
        transition: background-color 0.5s; 
    }

    /* Hover Effect */
    .float-btn:hover {
        background-color: black; 
    }
</style>
<!-- Link to Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-jLKHWMZzXbbbwh5Dh5dXEXeArwC0dd8nYgXnMJt54vv4RjDN6Yd7/FTEYPudS3xk" crossorigin="anonymous">
	

<!-- success message or error notification (Bootstrap toast) -->		
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white">
    </div>
  </div>  
  
  <main id="view-panel" >
      <?php $page = isset($_GET['page']) ? $_GET['page'] :'home'; ?>
  	<?php include $page.'.php' ?>
  	</main>

  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<!-- prompt users for confirmation before performing an action -->		
<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap modal dialog (for forms i.e booking details, audience registration, etc) -->	
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  
  <!-- for viewing images -->	
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
  
</body>

<script>
<!-- "preloader" animation that appears during page loads -->
	 window.start_load = function(){
    $('body').prepend('<di id="preloader2"></di>')
  }  
  window.end_load = function(){
    $('#preloader2').fadeOut('fast', function() {
        $(this).remove();
      })
  }
  
<!-- display either an image or a video in a modal  -->
 window.viewer_modal = function($src = '') {
    start_load()
    var t = $src.split('.')
    t = t[1]
    if(t =='mp4'){
      var view = $("<video src='"+$src+"' controls autoplay></video>")
    } else {
      var view = $("<img src='"+$src+"' />")
    }
    $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
    $('#viewer_modal .modal-content').append(view)
    $('#viewer_modal').modal({
                  show:true,
                  backdrop:'static',
                  keyboard:false,
                  focus:true
                })
                end_load()  
}

<!-- display a modal (popup dialogsignUp, registration, information) -->
window.uni_modal = function($title = '' , $url='', $size=""){
    start_load()
    $.ajax({
        url:$url,
        error: err => {
            console.log()
            alert("An error occurred")
        },
        success: function(resp){
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                if($size != ''){
                    $('#uni_modal .modal-dialog').addClass($size)
                } else {
                    $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                }
                $('#uni_modal').modal({
                  show:true,
                  backdrop:'static',
                  keyboard:false,
                  focus:true
                })
                end_load()
            }
        }
    })
}

<!-- a confirmation modal with a custom message and action(ie. Confirm) -->
window._conf = function($msg='', $func='', $params = []){
     $('#confirm_modal #confirm').attr('onclick', $func+"("+$params.join(',')+")")
     $('#confirm_modal .modal-body').html($msg)
     $('#confirm_modal').modal('show')
}

<!-- display a temporary, customizable toast notification on the screen with a specified background color and message -->
window.alert_toast= function($msg = 'TEST', $bg = 'success'){
    $('#alert_toast').removeClass('bg-success')
    $('#alert_toast').removeClass('bg-danger')
    $('#alert_toast').removeClass('bg-info')
    $('#alert_toast').removeClass('bg-warning')

    if($bg == 'success')
      $('#alert_toast').addClass('bg-success')
    if($bg == 'danger')
      $('#alert_toast').addClass('bg-danger')
    if($bg == 'info')
      $('#alert_toast').addClass('bg-info')
    if($bg == 'warning')
      $('#alert_toast').addClass('bg-warning')
    $('#alert_toast .toast-body').html($msg)
    $('#alert_toast').toast({delay:3000}).toast('show');
}

<!-- hide and remove a loading indicator ("preloader") from the screen once the page has finished loading -->
$(document).ready(function(){
    $('#preloader').fadeOut('fast', function() {
        $(this).remove();
    })
})

<!-- calendar -->
$('.datetimepicker').datetimepicker({
    format:'Y/m/d H:i',
    startDate: '+3d'
})

<!-- selection/dropdown menu -->
$('.select2').select2({
    placeholder:"Please select here",
    width: "100%"
})
</script>	


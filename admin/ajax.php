<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

##############################################

##############################################

if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}

if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_venue"){
	$save = $crud->save_venue();
	if($save)
		echo $save;
}

if($action == "save_book"){
	$save = $crud->save_book();
	if($save)
		echo $save;
}
if($action == "cancel_book"){
	$save = $crud->save_book();
	if($save)
		echo $save;
}

############################

if($action == "delete_book"){
	$save = $crud->delete_book();
	if($save)
		echo $save;
}

if($action == "save_register"){
	$save = $crud->save_register();
	if($save)
		echo $save;
}
if($action == "delete_register"){
	$save = $crud->delete_register();
	if($save)
		echo $save;
}
if($action == "delete_venue"){
	$save = $crud->delete_venue();
	if($save)
		echo $save;
}


if($action == "save_event"){
	$save = $crud->save_event();
	if($save)
		echo $save;
}
if($action == "delete_event"){
	$save = $crud->delete_event();
	if($save)
		echo $save;
}


if($action == "get_audience_report"){
	$get = $crud->get_audience_report();
	if($get)
		echo $get;
}
if($action == "get_venue_report"){
	$get = $crud->get_venue_report();
	if($get)
		echo $get;
}
if($action == "get_booking_venue_report"){
	$get = $crud->get_booking_venue_report();
	if($get)
		echo $get;
}


if($action == "get_pdetails"){
	$get = $crud->get_pdetails();
	if($get)
		echo $get;
}
if($action == "save_package"){
	$save = $crud->save_package();
	if($save)
		echo $save;
}
if($action == "delete_package"){
	$save = $crud->delete_package();
	if($save)
echo $save;
}
if($action == "save_menu"){
	$save = $crud->save_menu();
	if($save)
		echo $save;
}
if($action == "delete_menu"){
	$save = $crud->delete_menu();
	if($save)
echo $save;
}

if($action == "get_reviews"){
	$get = $crud->get_reviews();
	if($get)
		echo $get;
}

if($action == "save_review"){
	$save = $crud->save_review();
	if($save)
echo $save;
}


if($action == "delete_review"){
	$save = $crud->delete_review();
	if($save)
echo $save;
}
if ($action == "hide_review") {
    $save = $crud->hide_review();
    if ($save) {
        echo 1; 
    } else {
        echo 0; 
    }
}
if ($action == "show_review") {
    $reviewId = $_POST['id']; 
    $save = $crud->show_review($reviewId); 
    if ($save) {
        echo 1; 
    } else {
        echo 0; 
    }
}
?>


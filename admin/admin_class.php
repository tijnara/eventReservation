<?php include 'db_connect.php' ?>

<?php
// For sending mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Event_Management_System\Event_Management_System\phpmailer\src\SMTP.php';

if (isset($_GET['action']) && $_GET['action'] === 'send_booking_email') {

    // Get booking details
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contact = htmlspecialchars(trim($_POST['contact']));
    $address = htmlspecialchars(trim($_POST['address']));
    $schedule = htmlspecialchars(trim($_POST['schedule']));
    $timeofevent = intval($_POST['timeofevent']);
    $payment = intval($_POST['payment']);

    // Map the timeofevent
    $time_slots = [
        0 => '8AM - 12PM',
        1 => '12PM - 4PM',
        2 => '4PM - 8PM',
        3 => '8PM - 12AM',
    ];
    $selected_time_slot = isset($time_slots[$timeofevent]) ? $time_slots[$timeofevent] : 'Unknown Time Slot';

    // Map payment options
    $payment_methods = [
        0 => 'Cash',
        1 => 'GCash',
    ];
    $selected_payment = isset($payment_methods[$payment]) ? $payment_methods[$payment] : 'Unknown Payment Method';

    // Get reservation fee and GCash details from the system_settings table
    $stmt = $conn->prepare("SELECT reservation_fee, gcash, gcash_name, qr_image, facebook FROM system_settings LIMIT 1");
    $stmt->execute();
    $settings_data = $stmt->get_result()->fetch_assoc();

    $reservation_fee = $settings_data['reservation_fee'];
    $gcash_account = $settings_data['gcash'];
    $gcash_name_display = $settings_data['gcash_name'];
    $qr_image = $settings_data['qr_image'];
    $facebook = $settings_data['facebook'];

    // Function to send email
    function sendEmail($recipient, $recipientName, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tijnara0430@gmail.com';
            $mail->Password = 'pxsebypureoysvby';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('tijnara0430@gmail.com', 'Regina Garden and Restaurant');
            $mail->addAddress($recipient, $recipientName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // For encryption of GCash Name
    function obfuscateName($name) {
        if (strlen($name) <= 4) {
            return str_repeat('*', strlen($name));
        }
        $nameLength = strlen($name);
        $obfuscated = str_repeat('*', 2) . substr($name, 2, $nameLength - 4) . str_repeat('*', 2);
        return $obfuscated;
    }

    $obfuscated_gcash_name = obfuscateName($gcash_name_display);

    // Prepare the email for the client   
$clientEmailBody = "
    <p>Thank you for your reservation. We will reach out to you with more details about your booking once we have confirmed your payment for the reservation fee.</p>
    <p>Below are your booking details. Thank you for choosing us!</p>
    <h1>Booking Details</h1>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Contact Number:</strong> $contact</p>
    <p><strong>Address:</strong> $address</p>
    <p><strong>Desired Event Date:</strong> $schedule</p>
    <p><strong>Desired Time of Event:</strong> $selected_time_slot</p>
    <p><strong>Reservation Fee:</strong> ₱" . number_format($reservation_fee, 2) . "</p>
    <p><i>Reservation fee must be paid within 7 days from the date of booking.</i></p>
    <p><strong>Payment Method:</strong> $selected_payment</p>

    <br><b style='font-size: 2.5rem;'>Payment Instruction for Cash:</b>

    <p><b>Cash Remittance:</b></p>
    <ol>
        <li>Visit your nearest remittance center.</li>
        <li>Provide the following details:</li>
        <ul>
            <li><b>Recipient Name:</b> [Recipient's Name]</li>
            <li><b>Recipient Address:</b> [Recipient's Address]</li>
            <li><b>Contact Number:</b> [Recipient's Contact Number]</li>
        </ul>
        <li>Confirm the transaction and keep the receipt for reference.</li>
        <li>Send a picture of the receipt to [Contact Email or Phone Number].</li>
    </ol>

    <p><b>Bank Deposit:</b></p>
    <ol>
        <li>Visit your bank or use your online banking platform.</li>
        <li>Enter the following details for the deposit:</li>
        <ul>
            <li><b>Bank Name:</b> [Bank Name]</li>
            <li><b>Account Name:</b> [Account Name]</li>
            <li><b>Account Number:</b> [Account Number]</li>
            <li><b>Branch:</b> [Bank Branch]</li>
        </ul>
        <li>Confirm the transaction and save the deposit slip for your records.</li>
        <li>Send a picture of the deposit slip to [Contact Email or Phone Number].</li>
    </ol>

    <p>For any inquiries or assistance, please contact our support team at [Support Email] or [Support Phone Number]. Thank you for your cooperation and support.</p>
    
    <p><br><b style='font-size: 2.5rem;'>Payment Instruction for GCASH: </b></p>
    <div class='d-flex justify-content-between align-items-center'>
        <span class='text-info'>GCash Acct Name: <br><b style='font-size: 2rem;'>" . htmlspecialchars($obfuscated_gcash_name) . "</b></span>
        " . (!empty($qr_image) && file_exists('admin/assets/uploads/' . $qr_image) ? 
            "<img src='admin/assets/uploads/" . htmlspecialchars($qr_image) . "' alt='GCash Instructions' class='img-fluid' style='max-width: 150px; height: auto;'>" : 
            "<p>No QR code available.</p>") . "
    </div>
    <div class='d-flex align-items-center mt-2'>
        <p>GCash Acct Number: <br><b style='font-size: 2rem;'>" . htmlspecialchars($gcash_account) . "</b></p>
    </div>
    <p>Send us a message of your proof of GCash payment to our <a href='" . htmlspecialchars($facebook) . "' target='_blank'>Facebook Page.</a> Provide us the following: 
        <ol>
            <li><b>Proof of Payment:</b> Please send a screenshot of your GCash payment.</li>
            <li><b>Full Name:</b> The name you provided in the booking form.</li>
            <li><b>Contact Number:</b> The contact number you provided in the booking form.</li>
        </ol>
    </p>
    <span class='text-info'>Facebook Page: <a href='" . htmlspecialchars($facebook) . "' target='_blank'>Regina's Garden and Restaurant</a></span>";

$clientResult = sendEmail($email, 'Client', 'Booking Notification!', $clientEmailBody);
if ($clientResult !== true) {
    echo "Client email could not be sent. $clientResult";
} else {
    echo 'Email sent successfully to the client';
}


    $clientResult = sendEmail($email, 'Client', 'Booking Notification!', $clientEmailBody);
    if ($clientResult !== true) {
        echo "Client email could not be sent. $clientResult";
    } else {
        echo 'Email sent successfully to the client';
    }

    // Prepare the email for the admin
    $adminEmailBody = "<h1>Booking Details</h1>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Contact Number:</strong> $contact</p>
        <p><strong>Address:</strong> $address</p>
        <p><strong>Desired Event Date:</strong> $schedule</p>
        <p><strong>Desired Time of Event:</strong> $selected_time_slot</p>
        <p><strong>Reservation Fee:</strong> ₱" . number_format($reservation_fee, 2) . "</p>
        <p><strong>Payment Method:</strong> $selected_payment</p>";

    $adminResult = sendEmail('tijnara0430@gmail.com', 'Admin', 'Booking Notification!', $adminEmailBody);
    if ($adminResult !== true) {
        echo "Admin email could not be sent. $adminResult";
    } else {
        echo 'Email sent successfully to the admin';
    }
}
?>

<?php
/* other actions inside the whole system */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
Class Action {
	private $db;
	public function __construct() {
    ob_start();
    include 'db_connect.php';    
    $this->db = $conn; 
}
function __destruct() {
    
    $this->db->close();
    ob_end_flush(); 
}

// user login.
	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}

// user logout.
function logout(){    
    session_destroy(); 
    foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]); 
    }
    header("location:login.php"); 
}

// save inclusion and package	
function save_package(){
			extract($_POST);
			$data = " inclusion = '$inclusion' ";
			$data .= ", addcharges = '$addcharges' ";
			
			if(empty($id)){
				$save = $this->db->query("INSERT INTO package set ".$data);
			}else{
				$save = $this->db->query("UPDATE package set ".$data." where id = ".$id);
			}
			if($save){
				return 1;
			}
		}
		
// delete inclusion and package		
function delete_package(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM package where id = ".$id);
			if($delete)
				return 1;
		}
		
// save menu (food package)				
function save_menu(){
			extract($_POST);
			$data = " perPax = '$perPax' ";
			$data .= ", foods = '$foods' ";
			
			if(empty($id)){
				$save = $this->db->query("INSERT INTO menu set ".$data);
			}else{
				$save = $this->db->query("UPDATE menu set ".$data." where id = ".$id);
			}
			if($save){
				return 1;
			}
		}
		
// delete menu (food package)		
function delete_menu(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM menu where id = ".$id);
			if($delete)
				return 1;
		}
		
// handles saving a user to the database.
function save_user() {
    global $conn;
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $type = $_POST['type'];
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($username) || empty($type) || empty($contact) || empty($address)) {
        echo "Error: All fields are required.";
        return;
    }

    if (!empty($password)) {
        $password = md5($password);
        $sql = "INSERT INTO users (name, username, password, type, contact, address) 
                VALUES (?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE users SET name=?, username=?, type=?, contact=?, address=? WHERE id=?";
    }
    if ($stmt = $conn->prepare($sql)) {
        if (!empty($password)) {
            $stmt->bind_param("ssssss", $name, $username, $password, $type, $contact, $address);
        } else {
            $stmt->bind_param("ssssis", $name, $username, $type, $contact, $address, $id);
        }

        if ($stmt->execute()) {
            echo 1; 
        } else {
            if ($conn->errno === 1062) {
                echo 0; 
            } else {
                echo "Error: " . $stmt->error; 
            }
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// delete user		
function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

// 	handles user registration
function signup(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$qry = $this->db->query("SELECT * FROM users where username = '".$email."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
			}
			return 1;
		}
	}
	
// responsible for saving various settings for a system	
function save_settings() {
    extract($_POST);
    
    $data = " name = '" . $this->db->real_escape_string($name) . "' ";
    $data .= ", email = '" . $this->db->real_escape_string($email) . "' ";
    $data .= ", contact = '" . $this->db->real_escape_string($contact) . "' ";
    $data .= ", about_content = '" . htmlentities($this->db->real_escape_string($about)) . "' ";
    
    if (isset($gcash)) $data .= ", gcash = '" . $this->db->real_escape_string($gcash) . "' ";
    if (isset($gcash_name)) $data .= ", gcash_name = '" . $this->db->real_escape_string($gcash_name) . "' ";
    if (isset($reservation_fee)) $data .= ", reservation_fee = '" . $this->db->real_escape_string($reservation_fee) . "' ";
    if (isset($facebook)) $data .= ", facebook = '" . $this->db->real_escape_string($facebook) . "' ";
    
    $image_fields = [
        'cover_img' => 'cover_img',
        'gcash_qr' => 'qr_image',
        'wedding_img' => 'wedding_img',
        'debut_img' => 'debut_img',
        'intimateparty_img' => 'intimateparty_img',
        'childrenparty_img' => 'childrenparty_img',
        'bdayparty_img' => 'bdayparty_img',
        'seminar_img' => 'seminar_img'
    ];
    
    foreach ($image_fields as $input_name => $db_column) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['tmp_name'] != '') {
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            $fileType = mime_content_type($_FILES[$input_name]['tmp_name']);
            
            if (in_array($fileType, $allowedTypes)) {
                $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES[$input_name]['name'];
                $move = move_uploaded_file($_FILES[$input_name]['tmp_name'], 'assets/uploads/' . $fname);
                if ($move) {
                    $data .= ", $db_column = '$fname' ";
                }
            } else {
                echo "<script>alert('Invalid file type for {$input_name}. Only PNG and JPEG are allowed.');</script>";
                return 0; 
            }
        }
    }
    
    $chk = $this->db->query("SELECT * FROM system_settings");
    if ($chk->num_rows > 0) {
        $save = $this->db->query("UPDATE system_settings SET $data");
    } else {
        $save = $this->db->query("INSERT INTO system_settings SET $data");
    }

    if ($save) {
        $query = $this->db->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
        foreach ($query as $key => $value) {
            if (!is_numeric($key)) {
                $_SESSION['settings'][$key] = $value;
            }
        }
        return 1; 
    }
    return 0;
}

// adding and updating venue		
function save_venue() {
    extract($_POST);
    $data = " venue = '$venue' ";
    $data .= ", address = '$address' ";
    $data .= ", description = '$description' ";
    $data .= ", rate = '$rate' ";
    $data .= ", max_capacity = '$max_capacity' "; 

    if (empty($id)) {        
        $save = $this->db->query("INSERT INTO venue SET " . $data);
        if ($save) {
            $id = $this->db->insert_id; 
            $folder = "assets/uploads/venue_" . $id;
            if (is_dir($folder)) {                
                $files = scandir($folder);
                foreach ($files as $k => $v) {
                    if (!in_array($v, array('.', '..'))) {
                        unlink($folder . "/" . $v);
                    }
                }
            } else {
                mkdir($folder);
            }
            if (isset($img)) {
                for ($i = 0; $i < count($img); $i++) {
                    $img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
                    $img[$i] = base64_decode($img[$i]);
                    $fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
                    $upload = file_put_contents($folder . "/" . $fname, $img[$i]);
                }
            }
        }
    } else {
        $save = $this->db->query("UPDATE venue SET " . $data . " WHERE id=" . $id);
        if ($save) {
            $folder = "assets/uploads/venue_" . $id;
            if (is_dir($folder)) {
                $files = scandir($folder);
                foreach ($files as $k => $v) {
                    if (!in_array($v, array('.', '..'))) {
                        unlink($folder . "/" . $v);
                    }
                }
            } else {
                mkdir($folder);
            }
            if (isset($img)) {
                for ($i = 0; $i < count($img); $i++) {
                    $img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
                    $img[$i] = base64_decode($img[$i]);
                    $fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
                    $upload = file_put_contents($folder . "/" . $fname, $img[$i]);
                }
            }
        }
    }    
    if ($save) {
        return 1; 
    }
}	

// delete venue		
function delete_venue(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM venue where id = ".$id);
			if($delete){
				return 1;
			}
		}		

// adding and updating booking		
function save_book() {
    extract($_POST);
    $conflict_check = $this->db->prepare("SELECT id FROM venue_booking WHERE venue_id = ? AND datetime = ? AND timeofevent = ? AND id != ?");
    $conflict_check->bind_param('issi', $venue_id, $schedule, $timeofevent, $id);
    $conflict_check->execute();
    $conflict_check_result = $conflict_check->get_result();
    if ($conflict_check_result->num_rows > 0) {
        return "<span style='color: red;'>Booking conflict: The selected date and time are already booked for this venue.</span>";
    }
    $payment_status = isset($payment_status) && $payment_status == 1 ? 1 : 0;

    if (empty($id)) {
        $query = $this->db->prepare("INSERT INTO venue_booking (venue_id, name, address, email, contact, datetime, timeofevent, payment, status, payment_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $status = isset($status) ? $status : 'pending';
        $query->bind_param('issssssiii', $venue_id, $name, $address, $email, $contact, $schedule, $timeofevent, $payment, $status, $payment_status);
    } else {
        $query = $this->db->prepare("UPDATE venue_booking SET venue_id = ?, name = ?, address = ?, email = ?, contact = ?, datetime = ?, timeofevent = ?, payment = ?, status = ?, payment_status = ? WHERE id = ?");
        $status = isset($status) ? $status : 'pending';
        $query->bind_param('issssssiiii', $venue_id, $name, $address, $email, $contact, $schedule, $timeofevent, $payment, $status, $payment_status, $id);
    }
    if ($query->execute()) {
        return 1; 
    } else {
        return 0; 
    }
}

// delete booking
function delete_book(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM venue_booking where id = ".$id);
			if($delete){
				return 1;
			}
		}
		
/* 		function save_registration(){
    // Extract variables from POST request
    extract($_POST);
    
    // Prepare data for database insertion or update
    $data = " event_id = '$event_id' ";
    $data .= ", full_name = '$full_name' "; // Changed name to full_name
    $data .= ", address = '$address' ";
    $data .= ", email = '$email' ";
    $data .= ", contact = '$contact' ";
    $data .= ", registration_date = NOW() "; // Automatically set the registration date to the current date and time

    // Check for optional status field
    if(isset($status))
        $data .= ", status = '$status' ";
    
    // Use the registration table for inserting/updating data
    if(empty($id)){
        // Inserting a new registration
        $save = $this->db->query("INSERT INTO registrations SET ".$data);
    } else {
        // Updating an existing registration
        $save = $this->db->query("UPDATE registrations SET ".$data." WHERE id=".$id);
    }
    
    // Check if the operation was successful
    if($save) {
        return 1; 
    } else {
        return $this->db->error; 
    }
} */

/* 		function save_register(){
			extract($_POST);
			$data = " event_id = '$event_id' ";
			$data .= ", name = '$name' ";
			$data .= ", address = '$address' ";
			$data .= ", email = '$email' ";
			$data .= ", contact = '$contact' ";
			if(isset($status))
			$data .= ", status = '$status' ";
			if(isset($payment_status))
			$data .= ", payment_status = '$payment_status' ";
			else
			$data .= ", payment_status = '0' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO audience set ".$data);
			}else{
				$save = $this->db->query("UPDATE audience set ".$data." where id=".$id);
			}
			if($save) {
    return 1; 
} else {
    return $this->db->error; 
}
		} */

//  handle the registration of an audience member for an event		
function save_register() {
    extract($_POST);
    $data = " event_id = '$event_id' ";
    $data .= ", name = '$name' ";
    $data .= ", address = '$address' ";
    $data .= ", email = '$email' ";
    $data .= ", contact = '$contact' ";    
    if (isset($status)) {
        $data .= ", status = '$status' ";
    }
    if (isset($payment_status)) {
        $data .= ", payment_status = '$payment_status' ";
    } else {
        $data .= ", payment_status = '0' ";
    }
    
    if (empty($id)) {
        $save = $this->db->query("INSERT INTO audience SET " . $data);
    } else {
        $save = $this->db->query("UPDATE audience SET " . $data . " WHERE id=" . $id);
    }

    if ($save) {
        $decrement_capacity = $this->db->query("UPDATE events SET audience_capacity = audience_capacity - 1 WHERE id = '$event_id'");

        if ($decrement_capacity) {
            return 1; 
        } else {
            return $this->db->error; 
        }
    } else {
        return $this->db->error; 
    }
}

// delete register (audience registration)		
function delete_register(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM audience where id = ".$id);
			if($delete){
				return 1;
			}
		}		

//  responsible for saving or updating event information		
function save_event() {
    extract($_POST);

    // Check for conflicts
    $conflict_check = $this->db->query("
        SELECT * FROM events 
        WHERE venue_id = '$venue_id' 
        AND schedule = '$schedule' 
        AND event_time = '$event_time' 
        AND id != '" . (isset($id) ? $id : 0) . "'");

    if ($conflict_check->num_rows > 0) {
        return json_encode(['status' => 'conflict', 'message' => 'Schedule conflict detected.']);
    }

    $data = " event = '$event' ";
    $data .= ", venue_id = '$venue_id' ";
    $data .= ", schedule = '$schedule' ";
    $data .= ", event_time = '$event_time' "; 
    $data .= ", audience_capacity = '$audience_capacity' ";

    if (isset($payment_status)) {
        $data .= ", payment_type = '$payment_status' ";
        if ($payment_status == 1) {
            $amount = 0; 
        }
    } else {
        $data .= ", payment_type = '2' "; 
    }   

    $data .= ", amount = '$amount' "; 
    $data .= ", description = '" . htmlentities(str_replace("'", "&#x2019;", $description)) . "' ";  

    if ($_FILES['banner']['tmp_name'] != '') {
        $_FILES['banner']['name'] = str_replace(array("(", ")", " "), '', $_FILES['banner']['name']);
        $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['banner']['name'];
        $move = move_uploaded_file($_FILES['banner']['tmp_name'], 'assets/uploads/' . $fname);
        $data .= ", banner = '$fname' "; 
    }   

    if (empty($id)) {        
        $save = $this->db->query("INSERT INTO events SET " . $data);
        if ($save) {
            $id = $this->db->insert_id; 
            $folder = "assets/uploads/event_" . $id; 
            if (!is_dir($folder)) {
                mkdir($folder); 
            }            
            if (isset($img)) {
                for ($i = 0; $i < count($img); $i++) {
                    $img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
                    $img[$i] = base64_decode($img[$i]);
                    $fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
                    file_put_contents($folder . "/" . $fname, $img[$i]); // Save the image
                }
            }
            return json_encode(['status' => 'success']);
        }
    } else {
        $save = $this->db->query("UPDATE events SET " . $data . " WHERE id=" . $id);
        if ($save) {
            $folder = "assets/uploads/event_" . $id; 
            if (!is_dir($folder)) {
                mkdir($folder); 
            }            
            if (isset($img)) {
                for ($i = 0; $i < count($img); $i++) {
                    $img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
                    $img[$i] = base64_decode($img[$i]);
                    $fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
                    file_put_contents($folder . "/" . $fname, $img[$i]); // Save the image
                }
            }
            return json_encode(['status' => 'success']);
        }
    }
    
}

// delete event		
function delete_event(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM events where id = ".$id);
			if($delete){
				return 1;
			}
		}	
		
//  retrieve and gather event and audience information		
function get_audience_report() {
    extract($_POST);
    $data = array();

    $event_id = intval($event_id); 
    $event = $this->db->query("SELECT e.*, v.venue FROM events e INNER JOIN venue v ON v.id = e.venue_id WHERE e.id = $event_id")->fetch_array();

    foreach($event as $k => $v) {
        if (!is_numeric($k)) {
            $data['event'][$k] = $v;
        }
    }

    // get audience details
    $audience = $this->db->query("SELECT *, date_created FROM audience WHERE status IN (0, 1, 2) AND event_id = $event_id");
    
    // if there are audience records
    if ($audience->num_rows > 0) {
        while ($row = $audience->fetch_assoc()) {
          
            $row['pstatus'] = $row['payment_status'] == 1 ? "Paid" : "Pending"; 

            // mapping
            switch ($row['status']) {
                case 0:
                    $row['status_text'] = "For Verification";
                    break;
                case 1:
                    $row['status_text'] = "Confirmed";
                    break;
                case 2:
                    $row['status_text'] = "Declined";
                    break;
                default:
                    $row['status_text'] = "Unknown"; 
            }
            $data['data'][] = $row; 
        }
    } else {
        $data['data'] = []; 
    }
    return json_encode($data);
}

// compile a detailed report on a venue and its events		
function get_venue_report() {
    extract($_POST);
    $data = array();
    $venue_query = $this->db->query("SELECT * FROM venue WHERE id = $venue_id");
    if ($venue_query->num_rows > 0) {
        $venue = $venue_query->fetch_array();
        foreach ($venue as $k => $v) {
            if (!is_numeric($k)) {
                $data['venue'][$k] = $v;
            }
        }
    } else {        
        return json_encode(array('status' => 'error', 'message' => 'Venue not found'));
    }

    $events_query = "SELECT * FROM events WHERE venue_id = $venue_id";
    $events = $this->db->query($events_query);    

    if ($events->num_rows > 0) {        
        $time_map = [
            0 => "8AM - 12PM",
            1 => "12PM - 4PM",
            2 => "4PM - 8PM",
            3 => "8PM - 12AM"
        ];        

        while ($row = $events->fetch_assoc()) {
            $row['fee'] = $row['payment_type'] == 1 ? "FREE" : number_format($row['amount'], 2);
            $row['etype'] = $row['type'] == 1 ? "Public" : "Private";
            $row['sched'] = date("M d, Y", strtotime($row['schedule']));

            // Map the event_time to the corresponding time range
            $row['event_time'] = isset($time_map[$row['event_time']]) ? $time_map[$row['event_time']] : "Not Specified";

            $data['data'][] = $row;
        }
    } else {
        $data['data'] = []; 
    }

    return json_encode($data);
}

// retrieve bookings, filtering based on status
function get_booking_venue_report() {
    global $conn;  
    $venue_id = $_POST['venue_id'];

    $query = "SELECT *, 
              CASE timeofevent 
                  WHEN 0 THEN '8AM-12PM' 
                  WHEN 1 THEN '12PM-4PM' 
                  WHEN 2 THEN '4PM-8PM' 
                  WHEN 3 THEN '8PM-12AM' 
              END AS time_slot
          FROM venue_booking 
          WHERE venue_id = ? 
          AND payment_status IN (0, 1) 
          AND status IN (1, 2)"; 

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $venue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $venue_info = function_exists('get_venue') ? get_venue($venue_id) : null;

    echo json_encode([
        'venue' => $venue_info,
        'data' => $data
    ]);
}

// retrieve venue
private function get_venue($venue_id) {
    global $conn;
    $query = "SELECT * FROM venue_booking WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $venue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); 
}

// save a review submitted by a user into a database.
public function save_review() {
    // Ensure connection is established
    global $conn; // Use global if $conn is defined outside this function

    // Check database connection
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }

    // Sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $category = $conn->real_escape_string($_POST['category']);
    $rating = intval($_POST['rating']);
    $review_text = $conn->real_escape_string($_POST['comment']);   

    // Log incoming data
    error_log(print_r($_POST, true)); // Log the POST data for debugging

    // Prepare the SQL statement
    $query = "INSERT INTO reviews (name, email, category, rating, review_text, created_at) 
              VALUES ('$name', '$email', '$category', '$rating', '$review_text', NOW())";   

    // Set header for JSON response
    header('Content-Type: application/json');

    // Execute the query and prepare the response
    if ($conn->query($query)) {        
        echo json_encode([
            'success' => true,
            'data' => [
                'name' => $name,
                'category' => $category,
                'rating' => $rating,
                'comment' => $review_text
            ]
        ]);
    } else {        
        // Log the SQL error
        error_log("SQL Error: " . $conn->error); // Log the error for debugging
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }   

    // Close the connection
    $conn->close();
    exit(); // Stop further execution
}



//delete review from database
function delete_review(){
			extract($_POST);
			$delete = $this->db->query("DELETE FROM reviews where id = ".$id);
			if($delete){
					return 1;
				}
		}

// updates a review in the database, setting it to be hidden (e.i 0 = not Display)
function hide_review() {
    extract($_POST);    
    $update = $this->db->query("UPDATE reviews SET is_displayed = 0 WHERE id = ".$id);    
    if ($update) {
        return 1;  
    }
    return 0;  
}

// updates a review in the database, setting it to be displayed (e.i 1 = Display)
public function show_review() {
        extract($_POST);    
    $update = $this->db->query("UPDATE reviews SET is_displayed = 1 WHERE id = ".$id);    
    if ($update) {
        return 1;  
    }
    return 0;  
    }
	
// retrieves a list of reviews from the database	
public function get_reviews() {
    $reviews = [];    
    $sql = "SELECT name, category, rating, review_text FROM reviews WHERE is_displayed = 1";    
    $result = $this->db->query($sql);
    
    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                'name' => $row['name'],
                'category' => $row['category'],
                'rating' => $row['rating'],
                'review_text' => $row['review_text']
            ];
        }
    }    
    return $reviews;
}

// retrieve the maximum capacity of a venue
function get_venue_capacity($conn) {
    if (isset($_POST['action']) && $_POST['action'] == 'get_venue_capacity') {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $venue_id = intval($_POST['id']);

            $stmt = $conn->prepare("SELECT max_capacity FROM venue WHERE id = ?");
            $stmt->bind_param("i", $venue_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $venue_data = $result->fetch_assoc();
                echo json_encode(['max_capacity' => $venue_data['max_capacity']]);
            } else {
                echo json_encode(['max_capacity' => 0]); 
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Invalid venue ID']);
        }
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
    exit();
}
		
}
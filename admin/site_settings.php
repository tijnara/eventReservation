<!--  serves as a backend system for managing and updating application settings, including the ability to 
upload images related to GCash and various events. It combines PHP for server-side processing, HTML for the user interface,
 and JavaScript for dynamic interactions. -->
<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM system_settings LIMIT 1");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $val) {
        $meta[$k] = $val;
    }
}

// handle file upload
function uploadFile($fileInputName, $uploadDirectory) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['tmp_name'] != '') {
        $fileName = time() . '_' . $_FILES[$fileInputName]['name']; 
        $filePath = $uploadDirectory . $fileName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $filePath)) {
            return $fileName; 
        }
    }
    return null;
}
?>

<div class="container-fluid">
    <div class="card col-lg-12" style="background-color: #f0f0f0;">
        <div class="card-body">
            <form action="ajax.php?action=save_settings" method="POST" enctype="multipart/form-data" id="manage-settings">
			
				<!-- system name field -->
                <div class="form-group">
                    <label for="name" class="control-label">System Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required autocomplete="off">
                </div>
				
				<!-- facebook field -->
                <div class="form-group">
                    <label for="facebook" class="control-label">Facebook Account</label>
                    <input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo isset($meta['facebook']) ? $meta['facebook'] : '' ?>" required autocomplete="off">
                </div>
				
				<!-- email field -->
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required autocomplete="off">
                </div>
				
				<!-- contact field -->
                <div class="form-group">
                    <label for="contact" class="control-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? $meta['contact'] : '' ?>" required autocomplete="off">
                </div>
				
				<!-- gcash account name field -->
                <div class="form-group">
                    <label for="gcash_name" class="control-label">GCash Account Name</label>
                    <input type="text" class="form-control" id="gcash_name" name="gcash_name" value="<?php echo isset($meta['gcash_name']) ? $meta['gcash_name'] : '' ?>" required autocomplete="off">
                </div>
				
				<!-- gcash account number field -->
                <div class="form-group">
                    <label for="gcash" class="control-label">GCash Account Number</label>
                    <input type="text" class="form-control" id="gcash" name="gcash" value="<?php echo isset($meta['gcash']) ? $meta['gcash'] : '' ?>" required autocomplete="off">
                </div>

				<!-- reservation fee field -->
                <div class="form-group">
                    <label for="reservation_fee" class="control-label">Reservation Fee</label>
                    <input type="number" class="form-control" id="reservation_fee" name="reservation_fee" value="<?php echo isset($meta['reservation_fee']) ? $meta['reservation_fee'] : '' ?>" required autocomplete="off">
                </div>	

				<!-- text area for About content -->
                <div class="form-group">
                    <label for="about" class="control-label">About Content</label>
                    <textarea name="about" class="form-control textarea-modern" rows="6"><?php echo isset($meta['about_content']) ? $meta['about_content'] : ''; ?></textarea>
                </div>

                <!-- GCash QR Code Upload Section -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['qr_image']) ? 'assets/uploads/' . $meta['qr_image'] : ''; ?>" alt="GCash QR Code" id="qr_image_preview" style="height: auto; max-width: 15vw;">
                        <input type="file" class="form-control-file" name="gcash_qr" id="gcash_qr" accept="image/png, image/jpeg, image/jpg" onchange="displayImage(this, 'qr_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('gcash_qr').click();">Upload QR Code</button>
                    </div>
                </div>

                <!-- Cover Image -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['cover_img']) ? 'assets/uploads/' . $meta['cover_img'] : ''; ?>" alt="Cover Image" id="cover_image_preview" style="height: auto; max-width: 30vw;">
                        <input type="file" class="form-control-file" name="cover_img" id="cover_img" accept="image/*" onchange="displayImage(this, 'cover_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('cover_img').click();">Upload Cover Image</button>
                    </div>
                </div>

                <!-- Wedding Image -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['wedding_img']) ? 'assets/uploads/' . $meta['wedding_img'] : ''; ?>" alt="Wedding Image" id="wedding_image_preview" style="height: auto; max-width: 30vw;">
                        <input type="file" class="form-control-file" name="wedding_img" id="wedding_img" accept="image/*" onchange="displayImage(this, 'wedding_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('wedding_img').click();">Upload Wedding Image</button>
                    </div>
                </div>

                <!-- Seminar & Conference Image -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['seminar_img']) ? 'assets/uploads/' . $meta['seminar_img'] : ''; ?>" alt="Seminar & Conference Image" id="seminar_image_preview" style="height: auto; max-width: 30vw;">
                        <input type="file" class="form-control-file" name="seminar_img" id="seminar_img" accept="image/*" onchange="displayImage(this, 'seminar_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('seminar_img').click();">Upload Seminar Image</button>
                    </div>
                </div>

                <!-- Birthday Party Image -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['bdayparty_img']) ? 'assets/uploads/' . $meta['bdayparty_img'] : ''; ?>" alt="Birthday Party Image" id="bdayparty_image_preview" style="height: auto; max-width: 30vw;">
                        <input type="file" class="form-control-file" name="bdayparty_img" id="bdayparty_img" accept="image/*" onchange="displayImage(this, 'bdayparty_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('bdayparty_img').click();">Upload Birthday Party Image</button>
                    </div>
                </div>

                <!-- Children's Party Image -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['childrenparty_img']) ? 'assets/uploads/' . $meta['childrenparty_img'] : ''; ?>" alt="Children's Party Image" id="childrenparty_image_preview" style="height: auto; max-width: 30vw;">
                        <input type="file" class="form-control-file" name="childrenparty_img" id="childrenparty_img" accept="image/*" onchange="displayImage(this, 'childrenparty_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('childrenparty_img').click();">Upload Children's Party Image</button>
                    </div>
                </div>

                <!-- Intimate Party Image -->
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo isset($meta['intimateparty_img']) ? 'assets/uploads/' . $meta['intimateparty_img'] : ''; ?>" alt="Intimate Party Image" id="intimateparty_image_preview" style="height: auto; max-width: 30vw;">
                        <input type="file" class="form-control-file" name="intimateparty_img" id="intimateparty_img" accept="image/*" onchange="displayImage(this, 'intimateparty_image_preview')" style="display: none;">
                        <button type="button" class="btn btn-primary btn-modern ml-2" onclick="document.getElementById('intimateparty_img').click();">Upload Intimate Party Image</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-modern">Save Settings</button>
            </form>
        </div>
    </div>
</div>

<style>
	/* styles for button on uploading images for every occasions */
    .btn-modern {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
    }

	/* text area styles for description */
    .textarea-modern {
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
</style>

<script>
	// display a preview of an uploaded image
    function displayImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
	
	// request to admin_class to save the settings
    $('#manage-settings').submit(function(e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_settings',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            error: err => {
                console.log(err);
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Data successfully saved.', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    });
</script>

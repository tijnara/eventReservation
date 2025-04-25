<?php include 'db_connect.php' ?>

<?php
if(isset($_GET['id'])){
$food = $conn->query("SELECT * FROM package where id = ".$_GET['id']);
foreach($food->fetch_array() as $k => $v){
    $$k = $v;
}
}
?>

<div class="container-fluid">
    <form action="" id="manage-package">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

        <div class="form-group">
            <label for="" class="control-label">Package Inclusion</label>
            <textarea rows="5" required name="inclusion" class="form-control custom-textarea"><?php echo isset($inclusion) ? $inclusion : '' ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="" class="control-label">Additional Charges</label>
            <textarea rows="5" required name="addcharges" class="form-control custom-textarea"><?php echo isset($addcharges) ? $addcharges : '' ?></textarea>
        </div>
    </form>
</div>

<style>
    .custom-textarea {
        width: 100%; /* Adjust the width to your needs */
        max-width: 1000x; /* Optional: set a max width */
        min-height: 100px; /* Optional: set a minimum height */
    }
	
	.custom-container {
        width: 100%; /* Adjust this value as needed (e.g., 100%, 90%, etc.) */
		min-width: 800px
		max-width: 2000px
        margin: 0 auto; /* Center the container */
    }
</style>
<script>
    $('#manage-package').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_package',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    alert_toast("Successfully updated","success")
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    })
</script>
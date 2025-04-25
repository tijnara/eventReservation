<?php include 'db_connect.php' ?>

<?php
if(isset($_GET['id'])){
$food = $conn->query("SELECT * FROM menu where id = ".$_GET['id']);
foreach($food->fetch_array() as $k => $v){
    $$k = $v;
}
}
?>

<div class="container-fluid">
    <form action="" id="manage-menu">
	
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
        
        <div class="form-group">
            <label for="" class="control-label">Price per Pax</label>
            <input type="text" class="form-control" name="perPax"  value="<?php echo isset($perPax) ? $perPax :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Foods</label>
            <textarea cols="50" rows = "5" required="" name="foods" class="form-control"><?php echo isset($foods) ? $foods :'' ?></textarea>
        </div> 
		
	</form>
</div>

<script>
    $('#manage-menu').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_menu',
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
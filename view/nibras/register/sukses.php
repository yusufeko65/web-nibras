<?php
if(isset($_SESSION['register'])) {
    //echo $_SESSION['register'];
	unset($_SESSION['register']);
	$messagenota = explode("::",$_SESSION['messagenota']);
	$message   = str_replace("[PELANGGAN]",$messagenota[1],$config_notaregisweb);
    $message   = str_replace("[GRUP PELANGGAN]",$messagenota[2],$message);
    $message   = str_replace("[ID PELANGGAN]",$messagenota[0],$message);
	$message   = str_replace("[NAMAWEBSITE]",$config_namatoko,$message);
	unset($_SESSION['messagenota']);
?>
<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Register</span></h2>
	</section>
	<div class="col-sm-12">
		 <?php echo $message ?>
	</div>
</div>

<?php
} else {
  echo "<script>location='".URL_PROGRAM."'</script>";
    
}
?> 
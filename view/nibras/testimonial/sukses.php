<?php
if(isset($_SESSION['register'])) {
    //echo $_SESSION['register'];
	unset($_SESSION['register']);
	$member = $dtFungsi->fcaridata('_reseller_grup','rs_grupnama','rs_grupid',$_SESSION['tipemember']);
?>
<div class="box">
  <div class="box-heading-2"><div class="pitagelap">Sukses Daftar Reseller</div></div>
   <div class="box-content-2">
        Terima Kasih <span class="biru"><?php if(isset($_SESSION['namamember'])) echo $_SESSION['kodereseller']. ' '.$_SESSION['namamember'] ?></span> telah mendaftar sebagai <span class="biru"><?php echo $member ?></span><br> <br>
		<?php if($_SESSION['biayamember'] > 0) { ?>
		Biaya yang dikenakan untuk registrasi <span class="biru"><?php echo $member ?></span> sebesar <span class="biru"><?php echo $dtFungsi->fFormatuang($_SESSION['biayamember']) ?></span> <br>
		Silahkan transfer ke rekening Bank Kami yang telah tercantum di halaman website ini, atau silahkan belanja terlebih dahulu. <br><br>
		ID Reseller Anda akan aktif setelah proses pembayaran pendaftaran register dan proses approval dari Admin HS <br><br>
		Apabila dalam jangka waktu 3hari tidak ada konfirmasi transfer, maka registrasi keanggotaan Anda otomatis akan berubah menjadi reseller bebas/eceran
		<?php } else { ?>
		ID Reseller Anda : <?php echo $_SESSION['kodereseller'] ?>,harap dicatat dan diingat guna mempermudahkan proses belanja Anda
		<?php } ?>
		Selamat berbelanja di Hijabsupplier.com.<br>
		<img src="<?php echo URL_IMAGE.'logofooter.gif'?>">
   </div>
</div>
<?php
} else {
  ECHO "<script>location='".URL_PROGRAM."'</script>";
    
}
?> 
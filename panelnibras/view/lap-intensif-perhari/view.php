<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    <div class="left">
			
		</div>
		<div class="right">
			<form name="frmcari" id="frmcari">
			
			Tanggal <input type="text" name="tgl" id="tgl" class="tglbox" size="10" value="<?php echo $tgl ?>">
			<a class="tombols" id="tblcari">Search</a>
			<a class="tombols" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?tgl='+$('#tgl').val()">Export to excel</a>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			     <th style="text-align:center" width="10px">No</th>
				 <th style="text-align:center">Tanggal Konfirmasi</th>
				 <th>Username</th>
				 <th>Nama Admin Order</th>
				 <th style="text-align:right">Bonus</th>
				 
			  </tr>
			  
			  <?php 
			      $no = 1;
			      foreach($dataview as $datanya) {
			  ?>
			  <tr>
			    <td style="text-align:center"><?php echo $no++?></td>
				<td style="text-align:center"><?php echo $dtFungsi->ftanggalFull2($datanya['tglkonfirm'])?></td>
				<td><?php echo $datanya["login"]?></td>
				<td><?php echo $datanya["nama"]?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["bonus"])?></td>
			  </tr>
			  <?php } ?>
			  
			</table>
			
		</div>
		
		<!-- Table -->
	</div>
</div>
<script>
$(function(){
   
   $( "#tgl" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
		
	});
   $('#tblcari').click(function(){
        caridata();
		return false;
   });
   $("#datacari").keypress(function(event) {
        if(event.which == 13) {
 		   caridata();
	      return false;
		} else {
		   return true;
		}
   });
});
function caridata(){
   var tgl = escape($('#tgl').val());
   
   
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?tgl=' ?>'+tgl);

}

function cetakpdf(){
	window.open('<?php echo URL_PROGRAM_ADMIN."view/".folder?>/cetakpdf.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val());
}

</script>


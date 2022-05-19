<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    
		<div class="right">
			<form name="frmcari" id="frmcari">
			PENCARIAN
			<input id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari']:'' ?>">  
			<a class="tombols" id="tblcari">Search</a>
			<a class="tombols" id="tblreset" >Refresh</a>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			     <th style="text-align:center" width="5%">No</th>
				 <th style="text-align:center" width="8%">Kode Produk</th>
				 <th style="text-align:center">Nama Produk</th>
				 <th style="text-align:right">Stok Tersedia</th>
				 <th style="text-align:right">Stok di Booking</th>
				 <th style="text-align:center">Detail</th>
			  </tr>
			  
			  <?php 
			      $no = 1;
				  
			      foreach($dataview as $datanya) {
				     
			  ?>
			  <tr>
			    <td style="text-align:center"><?php echo $no++?></td>
				<td><?php echo trim($datanya['kd_produk'])?></td>
				<td><?php echo $datanya["nm_produk"]?></td>
				<td style="text-align:right"><?php echo $datanya["sisa"]?></td>
				<td style="text-align:right"><?php echo $datanya["booking"] != Null ? $datanya["booking"]:'0' ?></td>
				<td style="text-align:right"><a href="<?php echo URL_PROGRAM_ADMIN."lap-stok/?op=detail&id=".$datanya["idprod"] ?>">Detail</td>
			  </tr>
			  <?php } ?>
			  
			</table>
			
		</div>
		
		<!-- Table -->
	</div>
</div>
<script>
$(function(){
   
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
   var zdata = escape($('#datacari').val());
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata);

}

function cetakpdf(){
	window.open('<?php echo URL_PROGRAM_ADMIN."view/".folder?>/cetakpdf.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val());
}

</script>


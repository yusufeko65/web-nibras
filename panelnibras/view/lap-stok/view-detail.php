<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	  
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			     <th style="text-align:center" width="5%">No</th>
				 <th style="text-align:center" width="15%">Nama Produk</th>
				 <th style="text-align:center" width="15%">Warna</th>
				 <th style="text-align:center">Ukuran</th>
				 <th style="text-align:right">Stok Tersedia</th>
				 <th style="text-align:right">Stok di Booking</th>
				 <!-- <th style="text-align:center">Detail</th>-->
			  </tr>
			  
			  <?php 
			      $no = 1;
				  $totbook = 0;
				  $totsisa = 0;
			      foreach($dataview as $datanya) {
				    $datanya["booking"] = $datanya["booking"] != Null ? $datanya["booking"]:0;
				    $totsisa += $datanya["sisa"];
					$totbook += $datanya["booking"];
				     
			  ?>
			  <tr>
			    <td style="text-align:center"><?php echo $no++?></td>
				<td><?php echo $datanya['nmprod']?></td>
				<td><?php echo $datanya['warna']?></td>
				<td><?php echo $datanya["ukuran"]?></td>
				<td style="text-align:right"><?php echo $datanya["sisa"]?></td>
				<td style="text-align:right"><?php echo $datanya["booking"]  ?></td>
				<!--<td style="text-align:right"><a href="<?php echo URL_PROGRAM_ADMIN."lap-stok/?op=detail&id".$datanya["idprod"] ?>">Detail</td>-->
			  </tr>
			  <?php } ?>
			  <tr>
			     <td colspan="4"></td>
				 <td style="text-align:right"><?php echo $totsisa  ?></td>
				 <td style="text-align:right"><?php echo $totbook  ?></td>
			  </tr>
			  <tr>
			     <td colspan="5" style="text-align:right"><b>TOTAL STOK (STOK TERSEDIA + STOK DI BOOLING)</b></td>
				 <td style="text-align:right"><b><?php echo $totsisa + $totbook ?></b></td>
			  </tr>
			</table>
			
		</div>
		
		<!-- Table -->
	</div>
</div>
<script>
$(function(){
   $("#tahun").focus();
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
   var bulan = escape($('#bulan').val());
   var tahun = escape(parseInt($('#tahun').val()));
   if(tahun.length < 4) {
       alert('Masukkan Tahun');
	   $('#tahun').focus();
	   return false;
   }
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?bulan=' ?>'+bulan+'&tahun='+tahun);

}

function cetakpdf(){
	window.open('<?php echo URL_PROGRAM_ADMIN."view/".folder?>/cetakpdf.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val());
}

</script>


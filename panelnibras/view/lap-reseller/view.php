<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    <div class="left">
			
		</div>
		<div class="right">
			<form name="frmcari" id="frmcari">
			Grup Reseller
			<?php echo $dtFungsi->cetakcombobox('- Filter Grup -','120',$grup,'fgrup','_reseller_grup','rs_grupid','rs_grupnama') ?>
			Bulan
			<select id="bulan" name="bulan" class="selectbox">
			   <?php $nmbulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Otkober","November","Desember");?>
			   <?php for($i=1;$i < 13;$i++) {?>
				  <option value="<?php echo $i ?>" <?php if($bulan == $i ) echo 'selected' ?>><?php echo $nmbulan[$i-1] ?></option>
			   <?php } ?>
			</select>  
			Tahun <input type="text" class="inputbox" style="width:100px" name="tahun" id="tahun" value="<?php echo $tahun ?>">
			<a class="tombols" id="tblcari">Search</a>
			<a class="tombols" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val()">Export to excel</a>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			     <th style="text-align:center" width="10px">No</th>
				 <th style="text-align:center">Tgl Register</th>
				 <th style="text-align:right">Reseller ID</th>
				 <th style="text-align:right">Nama</th>
				 <th style="text-align:right">Grup</th>
			  </tr>
			  
			  <?php 
			      $no = 1;
				 
			      foreach($dataview as $datanya) {
				     
			     
			  ?>
			  <tr>
			    <td style="text-align:center"><?php echo $no++?></td>
				<td style="text-align:center"><?php echo $dtFungsi->ftanggalFull2($datanya['reg_tgl'])?></td>
				<td style="text-align:center"><?php echo $datanya["kode"]?></td>
				<td><?php echo $datanya["nama"]?></td>
				<td><?php echo $datanya["grup"]?></td>
			  </tr>
			  <?php } ?>
			  
			</table>
			
		</div>
		
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
   var grup = escape($('#fgrup').val());
   if(tahun.length < 4) {
       alert('Masukkan Tahun');
	   $('#tahun').focus();
	   return false;
   }
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?bulan=' ?>'+bulan+'&tahun='+tahun+'&grup='+grup);

}

function cetakpdf(){
	window.open('<?php echo URL_PROGRAM_ADMIN."view/".folder?>/cetakpdf.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val()+'&grup='+$('#fgrup').val());
}

</script>


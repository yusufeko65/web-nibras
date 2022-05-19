<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		  <form role="form-inline" id="frmcari" name="frmcari">
		     <div class="col-md-4">
			    <select class="form-control" id="fstatus" name="fstatus">
				   <option value="1" <?php if($status=='1') echo 'selected' ?>>Aktif</option>
				   <option value="0" <?php if($status=='0') echo 'selected' ?>>Tidak Aktif</option>
				</select>
			 </div>
		     <div class="col-md-4">
			    <?php echo $dtFungsi->cetakcombobox2('- Grup -','',$grup,'fgrup','_customer_grup','cg_id','cg_nm','input-sm form-control') ?>
			 </div>
			
		   	 <div class="col-md-4">
				<button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
			  </div>
		   </form>
		 </div>
	   </div>
	   <div class="col-md-4 bagian-tombol">
	   <a class="btn btn-sm btn-default" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val()+'&grup='+$('#fgrup').val()">Export to excel</a>
	   </div>
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah">No</td>
		   <td class="text-center">Kode</td>
		   <td>Nama</td>
		   <td>Grup</td>
		   <td class="text-right">Jumlah</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
	    <?php $no = 0 ?>
		
		<?php foreach($dataview as $datanya) {?>
		<tr>
		   <td class="tengah"><?php echo $no=$no+1 ?></td>
		   <td><?php echo sprintf('%08s', (int)$datanya["kode"]);?></td>
		   <td><?php echo $datanya["nama"]?></td>
		   <td><?php echo $datanya["grup"]?></td>
		   <td class="text-right"><?php echo $datanya["jml"]?></td>
		</tr>
		<?php } ?>
	   </tbody>
	 </table>
	 
  </div>

<script>
$(function(){
   $("#datacari").focus();
   
   $('#fgrup').change(function(){
        caridata();
		return false;
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
   
   var grup 	= escape($('#fgrup').val());
   var status 	= escape($('#fstatus').val());
  
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?grup=' ?>'+grup+'&status='+status;

}

</script>



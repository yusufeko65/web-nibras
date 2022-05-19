<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		  <form role="form-inline" id="frmcari" name="frmcari">
			 <div class="col-md-4">
			 <select id="bulan" name="bulan" class="form-control input-sm">
			   <?php $nmbulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Otkober","November","Desember");?>
			   <?php for($i=1;$i < 13;$i++) {?>
				  <option value="<?php echo $i ?>" <?php if($bulan == $i ) echo 'selected' ?>><?php echo $nmbulan[$i-1] ?></option>
			   <?php } ?>
			</select>  
			
			 </div>
		   	 <div class="col-md-4">
				<div class="input-group">
			      <input type="text" class="form-control input-sm" name="tahun" id="tahun" value="<?php echo $tahun ?>" placeholder="Tahun">
				  <span class="input-group-btn">
			 		 <button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
				  </span>
				</div>	 
			  </div>
		   </form>
		 </div>
	   </div>
	   <div class="col-md-4 bagian-tombol">
	   <a class="btn btn-sm btn-default" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val()+'&status='+$('#fstatus').val()">Export to excel</a>
	   </div>
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah">No</td>
		   <td width="15%" class="text-center">Kode Produk</td>
		   <td>Nama Produk</td>
		   <td class="text-right">Jumlah yang terjual</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
	    <?php $no = 0 ?>
		<?php $grandtot = 0 ?>
		<?php foreach($dataview as $datanya) {?>
		<?php $grandtot = $grandtot + (int)$datanya["jml"]; ?>
		<tr>
		   <td class="tengah"><?php echo $no=$no+1 ?></td>
		   <td class="text-center"><?php echo $datanya["kode"]?></td>
		   <td><?php echo $datanya["nama"]?></td>
		   <td class="text-right"><?php echo $datanya["jml"]?></td>
		</tr>
		<?php } ?>
	   </tbody>
	   <tfooter>
	     <tr>
		   <td colspan="3" class="text-right">Total Produk Yang Terjual</td>
		   <td class="text-right"><?php echo $grandtot ?></td>
		 </tr>
	   </tfooter>
	 </table>
	 
  </div>

<script>
$(function(){
   $("#datacari").focus();
   
   $('#fstatus').change(function(){
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
   
   var bulan = escape($('#bulan').val());
   var tahun = escape(parseInt($('#tahun').val()));
   if(tahun.length < 4) {
       alert('Masukkan Tahun');
	   $('#tahun').focus();
	   return false;
   }
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?bulan='?>'+bulan+'&tahun='+tahun;

}

</script>



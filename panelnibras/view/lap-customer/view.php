<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		  <form role="form-inline" id="frmcari" name="frmcari">
		     <div class="col-md-4">
			    <?php echo $dtFungsi->cetakcombobox2('- Grup -','',$grup,'fgrup','_customer_grup','cg_id','cg_nm','input-sm form-control') ?>
			 </div>
			 <div class="col-md-4">
			 <select id="bulan" name="bulan" class="form-control input-sm">
			   <?php $nmbulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");?>
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
	   <a class="btn btn-sm btn-default" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val()+'&grup='+$('#fgrup').val()">Export to excel</a>
	   </div>
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah">No</td>
		   <td class="text-center">Kode</td>
		   <td>Nama</td>
		   <td>Email</td>
		   <td>Telp</td>
		   <td>Kota/Kabupaten</td>
		   <td>Grup</td>
		   <td>Tgl Regis</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
	    <?php $no = 0 ?>
		
		<?php foreach($dataview as $datanya) {?>
		<tr>
		   <td class="tengah"><?php echo $no=$no+1 ?></td>
		   <td><?php echo sprintf('%08s', (int)$datanya["cust_id"]);?></td>
		   <td><?php echo $datanya["cust_nama"]?></td>
		   <td><?php echo $datanya["cust_email"]?></td>
		   <td><?php echo $datanya["cust_telp"]?></td>
		   <td><?php echo $datanya["kabupaten_nama"]?></td>
		   <td><?php echo $datanya["cg_nm"]?></td>
		   <td><?php echo $datanya["cust_tgl_add"]?></td>
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
   var bulan = escape($('#bulan').val());
   var tahun = escape(parseInt($('#tahun').val()));
   if(tahun.length < 4) {
       alert('Masukkan Tahun');
	   $('#tahun').focus();
	   return false;
   }
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?grup=' ?>'+grup+'&bulan='+bulan+'&tahun='+tahun;

}

</script>



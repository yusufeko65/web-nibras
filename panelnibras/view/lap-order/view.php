<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		  <form role="form-inline" id="frmcari" name="frmcari">
		     <div class="col-md-4">
			    <?php echo $dtFungsi->cetakcombobox2('- Status -','',$status,'fstatus','_status_order','status_id','status_nama','input-sm form-control') ?>
			 </div>
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
		   <td width="15%" class="text-center">Tgl</td>
		   <td>Order ID</td>
		   <td>Customer</td>
		   <td>Status</td>
		   <td class="text-right">Jumlah</td>
		   <td class="text-right">Total</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
	    <?php $no = 0 ?>
		<?php $jmlord = 0 ?>
		<?php $grandtot = 0 ?>
		<?php foreach($dataview as $datanya) {?>
		<?php $jmlord = $jmlord+(int) $datanya["jml"]; ?>
		<?php $total = $datanya["pesanan_kurir"] + $datanya["subtotal"] - $datanya['dari_poin'] - $datanya['dari_deposito'] ?>
		<?php $grandtot = $grandtot + $total ?>
		<tr>
		   <td class="tengah"><?php echo $no=$no+1 ?></td>
		   <td class="text-center"><?php echo $datanya["tgl"]?></td>
		   <td class="text-center"><?php echo sprintf('%08s', (int)$datanya["pesanan_no"]);?></td>
		   <td><?php echo $datanya["cust_nama"]?></td>
		   <td><?php echo $datanya["status"]?></td>
		   <td class="text-right"><?php echo $datanya["jml"]?></td>
		   <td class="text-right"><?php echo $dtFungsi->fFormatuang($total)?></td>
		</tr>
		<?php } ?>
	   </tbody>
	   <tfooter>
	     <tr>
		   <td colspan="5" class="text-right">Total</td>
		   <td class="text-right"><?php echo $jmlord ?></td>
		   <td class="text-right"><?php echo $dtFungsi->fFormatuang($grandtot) ?></td>
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
   
   var status 	= escape($('#fstatus').val());
   var bulan = escape($('#bulan').val());
   var tahun = escape(parseInt($('#tahun').val()));
   if(tahun.length < 4) {
       alert('Masukkan Tahun');
	   $('#tahun').focus();
	   return false;
   }
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?status=' ?>'+status+'&bulan='+bulan+'&tahun='+tahun;

}

</script>



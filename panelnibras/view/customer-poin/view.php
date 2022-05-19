<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		   <form role="form-inline" id="frmcari" name="frmcari">
		    
		   	 <div class="col-md-6">
				<div class="input-group">
			      <input type="text" class="form-control input-sm" id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari']:'' ?>" placeholder="Pencarian <?php echo $judul ?> ">
				  <span class="input-group-btn">
			 		 <button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
				  </span>
				</div>	 
			  </div>
		   </form>
		 </div>
	   </div>
	  
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
		<thead>
			<tr>
				<td>Pelanggan</td>
				<td>Grup</td>
				
				<td class="text-right">Poin</td>
				<td style="min-width:5%" class="text-center">Info</td>
			</tr>
		</thead>
		<tbody id="viewdata">
			<?php foreach($ambildata as $datanya) {?>
			<tr>
			   
			   <td><?php echo $datanya["cust_nama"]?></td>
			   <td><?php echo $datanya["cg_nm"]?></td>
			   <td class="text-right"><?php echo $datanya["totalpoin"]==Null ? "0":$dtFungsi->fuang($datanya["totalpoin"]) ?></td>
			   <td class="text-center"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=poin&pid=".$datanya['cust_id']?>" class="btn btn-info btn-sm">Detail</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	 <?php if($total>0) { ?>
	  <!-- Pagging -->
	    <div class="col-md-6">
		  <div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		</div>
        <div class="col-md-6 text-right">
		   <ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage) ?></ul>
		</div>
			  
		<!-- End Pagging -->
		<?php } ?>
  </div>

<script>
$(function(){
   $("#datacari").focus();
   
   $('#tblreset').click(function(){
        tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
		return false;
   });
   $('#fgrup').change(function(){
        caridata();
		return false;
   });
   $('#dropship').change(function(){
        caridata();
		return false;
   });
   $('#approve').change(function(){
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
   var zdata 	= escape($('#datacari').val());
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata;

}
function hapusdata(){
	var ids = [];
	var dataId;
	var datahapus;
	$('.chk').each(function () {
    if (this.checked) {
		if($(this).val()!="ON"){
			//ss=ss+$(this).val()+",";
			ids.push($(this).val());
		}
	}
	});
	dataId = ids.join(':');
	datahapus = 'aksi=hapus&id=' + dataId;
	if(dataId==""){
	   alert('Tidak Ada Pilihan');
	   return false;
	} else {
	   var a = confirm('Apakah ingin menghapus data yang terpilih?');
	   if (a == true) {
			$.ajax({
				type: "POST",
				url: "<?php echo $_SERVER['PHP_SELF'] ?>",
				data: datahapus,
				success: function(msg){
					hasilnya = msg.split("|");
					if(hasilnya[0]=="gagal") alert('Error \n' + hasilnya[1]); 
					location='<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>';
					return false;
				},  
					error: function(e){  
					alert('Error: ' + e);  
				}  
			});  
		}
	}
}
</script>
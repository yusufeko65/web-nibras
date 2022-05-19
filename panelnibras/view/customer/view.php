<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		   <form role="form-inline" id="frmcari" name="frmcari">
		     <div class="col-md-4">
			    <select id="fgrup" name="fgrup" class="form-control input-sm">
				  <option value="0">- Filter Grup -</option>
				  <?php foreach($datagrup as $dgrup) {?>
				  <option value="<?php echo $dgrup['id'] ?>" <?php if($grup == $dgrup['id']) echo "selected" ?>><?php echo $dgrup['nm'] ?></option>
				  <?php } ?>
				</select>
			 </div>
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
	   <div class="col-md-4 bagian-tombol"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=add"?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> <a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Hapus</a></div>
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah"><input type="checkbox" id="checkall" onchange="cekall()" name="checkall" value="ON"></td>
		   <td>Kode</td>
		   <td>Pelanggan</td>
		   <td>Grup</td>
		   <td>Email</td>
		   <td>Kontak</td>
		   <td style="min-width:5%" class="tengah">Ubah</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
		<?php foreach($ambildata as $datanya) {?>
		<tr>
		   <td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['cust_id']?>" /></td>
		   <td><?php echo sprintf('%04s', $datanya["cust_id"]);?></td>
		   <td><?php echo $datanya["cust_nama"]?></td>
		   <td><?php echo $datanya["cg_nm"]?></td>
		   <td><?php echo $datanya["cust_email"]?></td>
		   <td><?php echo $datanya["cust_telp"]?></td>
		   <td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=".$datanya['cust_id']?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
   var grup 	= escape($('#fgrup').val());
   //var dropship = escape($('#dropship').val());
   var approve	= escape($('#approve').val());
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata+'&grup='+grup;

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
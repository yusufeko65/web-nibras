<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="row">
		<div class="col-md-6 bagian-frm-cari">
			<form role="form" id="frmcari" name="frmcari">
				<input type="hidden" id="pid" name="pid" value="<?php echo $pid ?>">
				<input type="hidden" id="shipping_code" name="shipping_code" value="<?php echo $kodeshipping ?>">
				<div class="row">
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" class="form-control input-sm" id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari']:'' ?>" placeholder="Pencarian">
							<span class="input-group-btn">
							 <button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
							</span>
						</div> 
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-6 bagian-tombol">
			<?php if($rajaongkir == '1') { ?>
			<a href="javascript:void(0)" id="btnimportservis" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-import"></span> Import from RajaOngkir.com</a>
			<?php } ?>
			<a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=addservis&pid=".$pid ?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> <a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
		</div>
    </div>
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah"><input type="checkbox" id="checkall" onchange="cekall()" name="checkall" value="ON"></td>
		   <td>Kode Servis</td>
		   <td>Nama Servis</td>
		   <td style="min-width:5%" class="tengah">Ubah</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
		<?php foreach($ambildata as $datanya) {?>
		<tr>
		   <td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['servis_id']?>" /></td>
		   <td><?php echo $datanya["servis_code"]?></td>
		   <td><?php echo $datanya["servis_nama"]?></td>
		   <td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=editservis&pid=".$datanya['servis_id']?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
        tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?op=servis&id='.$pid ?>');
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
	$('#btnimportservis').click(function(){
		importServis();
		
	});
});
function caridata(){
   var zdata = escape($('#datacari').val());
   location='<?php echo URL_PROGRAM_ADMIN.folder.'/?op=servis&id='.$pid.'&datacari=' ?>'+zdata;

}

function importServis(){
	var kurir 		= $('#pid').val();
	var kurir_kode 	= $('#shipping_code').val();
	
	var dataimport = "aksi=importservis&shipping_code="+kurir_kode+"&shipping_id="+kurir;
	
	$.ajax({
		url: '<?php echo $_SERVER['PHP_SELF'] ?>',
		method: "POST",
		data: dataimport,
		dataType: 'json',
		success: function(msg){
			
			$('#loadingweb').hide(0);
			
			if(msg['status']=="error") alert(msg['result']); 
			else location.reload();
		
			return false;
		}
	
	});
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
	
	datahapus = 'aksi=hapusservis&id=' + dataId;
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
				dataType: 'json',
				success: function(msg){
					$('#loadingweb').hide(0);
					
					if(msg['status']=="error") alert(msg['result']); 
					else location.reload();
					
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
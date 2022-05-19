<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="panel-body">
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
			<div class="col-md-4 bagian-tombol"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=add"?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> <a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Hapus</a></div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover tabel-grid">
				<tr>
					<td>
	<?php if($ambildata) { ?>
	<?php foreach($ambildata as $shipping) { ?>
			<div class="col-md-3" >
				<div class="thumbnail" style="min-height:150px">
					<div class="pull-left">
						<input type="checkbox" name="selected[]" class="chk" value="<?php echo $shipping['shipping_id'] ?>" />
					</div>
					<div class="pull-right">
						<?php echo $shipping['tampil'] != '1' ? '<span class="label label-danger">Disabled</span>' : '' ?>
					</div>
					<div class="clearfix"></div>
					<div class="text-center">
					<?php 
					
						if($shipping['shipping_logo'] != '' && file_exists(DIR_IMAGE.'_other/other_'.$shipping['shipping_logo'])) {
							echo '<img src="'.URL_IMAGE.'_other/other_'.$shipping['shipping_logo'].'">';
						} else {
							echo $shipping['shipping_nama'];
						}								
					?>
					</div>
					<br>
					<div class="text-center">
						<a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=".$shipping['shipping_id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Edit</a>
						<a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=servis&pid=".$shipping['shipping_id'] ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Servis</a>
						<?php if($shipping['shipping_konfirmadmin']=='0' && $shipping['shipping_rajaongkir'] == '0') { ?>
						<!--<a href="<?php //echo URL_PROGRAM_ADMIN.folder."/?op=tarifkurir&pid=".$shipping['shipping_id'] ?>" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Tarif</a>-->
						<?php } ?>
					</div>
				</div>
			</div>
	<?php } // end foreach ?>
			<div class="clearfix"></div>
	<?php }// end if ambildata ?>
					</td>
				</tr>
			</table>
		</div>
	<?php if($total>0) { ?>
		  <!-- Pagging -->
		<div class="pull-left">
			<div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		</div>
		<div class="pull-right">
		   <ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage) ?></ul>
		</div>
		<div class="clearfix"></div>	  
		<!-- End Pagging -->
	<?php } ?>
	</div>
</div>

<script>
$(function(){
   $("#datacari").focus();
   $('#tblreset').click(function(){
        tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
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
   var zdata = escape($('#datacari').val());
   location='<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata;

}
function hapusdata(){
	var ids = [];
	var dataId;
	var datahapus;
	$('.chk').each(function () {
    if (this.checked) {
		if($(this).val()!="ON"){
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
				url: "<?php echo $_SERVER['PHP_SELF']?>",
				data: datahapus,
				success: function(msg){
					hasilnya = msg.split("|");
					if(hasilnya[0]=="gagal") alert(hasilnya[1]); 
					window.location='<?php echo URL_PROGRAM_ADMIN.folder ?>';
					
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
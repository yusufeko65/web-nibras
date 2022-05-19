<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		     
					<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
					<input type="hidden" name="idproduk" id="idproduk" value="<?php echo isset($idproduk) ? $idproduk : 0 ?>">
					<input type="hidden" name="produklama" id="produklama" value="<?php echo $produk_nama ?>">
					<input type="hidden" name="kodelama" id="kodelama" value="<?php echo $produk_kode ?>">

					<div id="hasil" style="display: none;"></div>
					<div role="tabpanel">

						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#tabketerangan" aria-controls="tabketerangan" role="tab" data-toggle="tab">Keterangan Produk</a></li>
							<?php if($modul == 'ubah') { ?>
							<li role="presentation"><a href="#tabgbr" aria-controls="tabgbr" role="tab" data-toggle="tab">Gambar + Warna</a></li>
							<?php } ?>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tabketerangan">
								<div class="well">
									<div class="form-group">
										<label class="col-sm-2 control-label">Kode Produk</label>
										<div class="col-sm-2">
											<input type="text" placeholder="Kode Produk" id="kode_produk" name="kode_produk" class="form-control forms" value="<?php echo $produk_kode ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Nama Produk</label>
										<div class="col-sm-6">
											<input type="text" placeholder="Nama Produk" id="nama_produk" name="nama_produk" class="form-control forms" value="<?php echo stripslashes($produk_nama) ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Kategori</label>
										<div class="col-sm-6">
											<input type="text" id="kategori_nama" name="kategori_nama" class="form-control forms" autocomplete="off" value="<?php echo $kategori_nama ?>">
											<input type="hidden" id="idkategori" name="idkategori" value="<?php echo $kategori_produk ?>" class="form-control forms">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Cover Depan</label>
										<div class="col-sm-6">
											<input name="gbr_produk" id="gbr_produk" type="file">
											<input type="hidden" value="<?php echo $produk_gbr ?>" id="gbr_produk_lama" name="gbr_produk_lama">
											<?php if($produk_gbr != '') {?>
											<br>
											<img id="image_produk" src="<?php echo URL_PROGRAM.'assets/image/_small/small_gproduk'.$produk_gbr ?>">
											<?php } ?>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label">Keterangan</label>
										<div class="col-sm-10">
											<textarea cols="80" class="form-control" id="keterangan_produk" name="keterangan_produk" ><?php echo trim(stripslashes(html_entity_decode($keterangan))) ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Meta Deskripsi</label>
										<div class="col-sm-10">
											<textarea cols="80" class="form-control" id="metatag_deskripsi" name="metatag_deskripsi" ><?php echo trim(stripslashes(html_entity_decode($metadeskripsi))) ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Keyword</label>
										<div class="col-sm-10">
											<textarea cols="80" class="form-control" id="metatag_keyword" name="metatag_keyword" ><?php echo trim(stripslashes(html_entity_decode($metakeyword))) ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Alias URL</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="alias_url" name="alias_url" value="<?php echo $aliasurl ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Status</label>
										<div class="col-sm-2">
											<select id="status_produk" name="status_produk" class="form-control">
												<option value="1" <?php if($status==1) echo "selected" ?>>Enabled</option>
												<option value="0" <?php if($status==0) echo "selected" ?>>Disabled</option>
											</select>
										</div>
									</div>
						
								</div>
								<div class="clearfix"></div>
							</div>
							<?php if($modul == 'ubah') { ?>
							<!-- warna dan gambar -->
							<div role="tabpanel" class="tab-pane" id="tabgbr">
								<input type="hidden" name="actiondata" id="actiondata" value="uploadwarna">
								
								
								<div class="well">
									<div class="form-group">
										<label class="col-sm-3 control-label">Warna</label>
										<div class="col-sm-5">
											<select class="form-control input-sm" data-placeholder="Pilih Warna..." tabindex="1" name="idwarna" id="idwarna" style="width:200px">
												<?php echo $combowarna; ?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">File</label>
										<div class="col-sm-5">
											<input type="file" name="produk_image" id="produk_image" />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label"></label>
										<div class="col-sm-5">
											<button id="btngambarwarna" type="button" class="btn btn-sm btn-info" onclick="uploadWarna();">Upload Gambar</button>
										</div>
									</div>
									<div class="form-group">
										<div class="alert alert-info">Catatan : <br>
											<ul>
												<li>Jika warna sudah tersedia / sudah memiliki gambar, maka otomatis akan terupdate gambar sesuai warna</li>
											</ul>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12 text-right">
											Jumlah Warna : <span id="jmlwarna"><?php echo count($datawarna) ?></span>
										</div>
									</div>
									<table class="table table-bordered table-striped table-hover tabel-grid" id="tbgambarwarna">
										<thead>
											<tr>
												<th width="1%" class="text-center">No</th>
												<th>Warna</th>
												<th>Gambar</th>
												<th width="1%">Hapus</th>
											</tr>
										</thead>
										<tbody id="bodywarna">
											<?php if($datawarna) { ?>
											<?php $no = 1;?>
											<?php foreach($datawarna as $warna){ ?>
												<tr id="image_warna_row<?php echo $image_warna_row ?>">
													<td class="text-center"><?php echo $no ?></td>
													<td><?php echo $warna['warna'] ?></td>
													<td><img src="<?php echo URL_PROGRAM.'assets/image/_small/small_gproduk'.$warna['image_head'] ?>"></td>
													<td><button type="button" id="btnhapuswarna<?php echo $image_warna_row ?>" onclick="hapusWarna('<?php echo $image_warna_row ?>','<?php echo $idproduk ?>','<?php echo $warna['idwarna'] ?>')" class="btn btn-danger btn-sm">Hapus</button></td>
												</tr>
												<?php $no++ ?>
												<?php $image_warna_row++ ?>
											<?php } ?>
											<?php } ?>
											<tr id="tbfootwarna"></tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- end warna dan gambar -->
							<?php } ?>
							
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 text-right">
							<?php if($modul == 'ubah') { ?>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder.'?op=add' ?>'" class="btn btn-sm btn-success">Tambah Baru</a>
							<?php }?>
							<button type="submit" class="btn btn-sm btn-primary" id="btnsimpan">Simpan</button>
							<a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
						</div>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
	    </div> 
	</div>
</div>
<script type="text/javascript">
var action = $('#frmdata').prop('action');
var image_warna_row = <?php echo $image_warna_row ?>;

$(function(){
   
	$('#kode').focus();
	$('#keterangan_produk').summernote({
		height: "300px"
    });
	$("#frmdata").submit(function(event){
		event.preventDefault();
		simpandata();
	});
   
	/* autocomplete */
	$('#kategori_nama').autocomplete({
		delay: 0,
		source: function( request, response ) {
			$.ajax({
				url: action,
				dataType: "json",
				data: {
					loads: 'kategori',
					cari: request.term,
					spesial:'1'
				},
				success: function( data ) {
					response( $.map( data, function( item ) {
						return {
							label: item.name,
							value: item.name,
							kode: item.category_id,
				 
						}
					}));
				},
				error: function(e){ 
					
					alert('Error: ' + e);  
				}  
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			$('#kategori_nama').val(ui.item.value);
			$('#idkategori').val(ui.item.kode);
		  
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
   
	/* @end autocomplete */
	
	
	/* warna  */
	$("#idwarna").chosen({
		no_results_text: "Tidak Ada Warna!",
		width: "100%"
	}); 
	/* @end warna  */
	
});

function simpandata()
{
	var action = $('#frmdata').prop("action");
	var kategori = $('#kode_produk').val();
	var nama_produk = $('#nama_produk').val();
	var rv = true;
	$('#btnsimpan').button("loading");
	$('#hasil').removeClass();
	
	if(kategori.length < 1 && kategori.length > 10) {
		alert(kategori.length);
		$('#hasil').addClass('alert alert-danger');
		$('#hasil').html('Masukkan Kode Produk. Maksimal 10 Karakter');
		$('#hasil').show();
		$('#btnsimpan').button("reset");
		rv = false;
	}
	if(nama_produk == '' || nama_produk.length > 100) {
		$('#hasil').addClass('alert alert-danger');
		$('#hasil').html('Masukkan Nama Produk. Maksimal 100 Karakter');
		$('#hasil').show();
		$('#btnsimpan').button("reset");
		rv = false;
	}
	if(rv){
		$.ajax({
			url: action,
			method: "POST",
			data: new FormData(frmdata),
			processData:false,
			contentType:false,
			dataType: 'json',
			success: function(json){
				
				if(json['status'] == 'error') {
					
					$('#hasil').addClass('alert alert-danger');
					$('#btnsimpan').button("reset");
					
				} else {
					
					$('#hasil').addClass('alert alert-success');
					
					if($('#aksi').val() == 'tambah') {
						location.href = '<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid="?>' + json['produk_id'];
					} else {
						if(json['produk_gbr'] != '') {
							$('#image_produk').attr('src','<?php echo URL_PROGRAM ?>assets/image/_small/small_'+json['produk_gbr']);
							$('#gbr_produk_lama').val(json['produk_gbr']);
						}
						$("#gbr_produk").replaceWith($("#gbr_produk").val('').clone(true));
					}
				}
				$('#hasil').show();
				$('#hasil').html(json['result']);
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				$('#btnsimpan').button("reset");
			}
		});
	} else {
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}
	return rv;
}

function uploadWarna()
{
	$('#btngambarwarna').button("loading");
	$('#hasil').removeClass();
	$('#hasil').hide();
	var idproduk = $('#idproduk').val();
	$.ajax({
		url: '<?php echo URL_PROGRAM_ADMIN.'view/'.folder ."/action.php" ?>',
		method: "POST",
		data: new FormData(frmdata),
		processData:false,
		contentType:false,
		dataType: 'json',
		success: function(json){
			
			if(json['status'] == 'error') {
				
				$('#hasil').addClass('alert alert-danger');
				
				
			} else {
				var html = '';
				var jmlwarna = parseInt($('#jmlwarna').html());
				var no = 1;
				var datawarna = json['datawarna'];
				$('#bodywarna').html("<tr><td colspan='4' class='text-center'>Tunggu sedang load data..</td></tr>");
				for(i=0;i<datawarna.length;i++)
				{
					html += '<tr id="image_warna_row'+image_warna_row+'">';
					html += '<td class="text-center">'+no+'</td>';
					html += '<td>'+datawarna[i]['warna']+'</td>';
					html += '<td><img src="<?php echo URL_PROGRAM ?>assets/image/_small/small_gproduk'+datawarna[i]['image_head']+'"></td>';
					html += '<td><button type="button" id="btnhapuswarna'+image_warna_row+'" onclick="hapusWarna('+image_warna_row+','+idproduk+','+datawarna[i]['idwarna']+')" class="btn btn-danger btn-sm">Hapus</button></td>';
					html += '</tr>';
					image_warna_row++;
					no++;
				}
				
				$('#bodywarna').html(html);
				
				$('#hasil').addClass('alert alert-success');
				$('#idwarna').val('0');
				$('#produk_image').val('');
				
				$('#jmlwarna').html(jmlwarna + 1);
			}
			$('#btngambarwarna').button("reset");
			$('#hasil').show();
			$('#hasil').html(json['result']);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			
		}
	});
}
function hapusWarna(imagewarnarow,idproduk,idwarna)
{
	$('#btnhapuswarna'+imagewarnarow).button("loading");
	var data = "actiondata=hapuswarnagambar&idproduk="+idproduk+"&idwarna="+idwarna;
	
	$.ajax({
		url: '<?php echo URL_PROGRAM_ADMIN.'view/'.folder ."/action.php" ?>',
		method: "POST",
		data: data,
		dataType: 'json',
		success: function(json){
			
			if(json['status'] == 'error') {
				
				$('#hasil').addClass('alert alert-danger');
				$('#btnhapuswarna'+imagewarnarow).button("reset");
				$('#hasil').show();
				$('#hasil').html(json['result']);
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
				var jmlwarna = parseInt($('#jmlwarna').html());
				$('#image_warna_row'+imagewarnarow).remove();
				
				$('#jmlwarna').html(jmlwarna - 1);
			}
			
			
			
		}
	});
	
}

</script> 
<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" target="upload-frame" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <iframe name="upload-frame" id="upload-frame" style="display:none"></iframe>
			  <input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
		      <div id="hasil" style="display: none;"></div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Kategori</label>
				 <div class="col-sm-4">
				   <input type="text" id="kategori" name="kategori" class="form-control" value="<?php echo $kategori_nama ?>" size="40">
				 </div>
		      </div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Induk</label>
				 <div class="col-sm-4">
				   <input type="text" id="kategori_nama" name="kategori_nama" class="form-control forms" autocomplete="off" value="<?php echo $namakat['name'] ?>" <?php echo $lock ?>>
				   <input type="hidden" id="induk" name="induk" value="<?php echo $kategori_induk ?>" class="form-control forms">
				  
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">File</label>
				 <div class="col-sm-4">
				   <input name="filelogo" type="file" id="filelogo">
				   <input type="hidden" value="<?php echo $kategori_image ?>" id="filelama" name="filelama">
				   <br>
				   <?php if($kategori_image != '') { ?>
					<img src="<?php echo URL_IMAGE.'_other/other_'.$kategori_image ?>">
					<?php } ?>
				 </div>
		      </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Keterangan</label>
				 <div class="col-sm-10">
				    <textarea cols="80" class="form-control" id="keterangan" name="keterangan" ><?php echo trim(stripslashes(html_entity_decode($keterangan))) ?></textarea>
				 </div>
			  </div>
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Alias URL</label>
				 <div class="col-sm-4">
				   <input type="text" id="aliasurl" name="aliasurl" class="form-control" value="<?php echo $kategori_aliasurl ?>" size="50">
				 </div>
		      </div>
			  
			  <div class="form-group">
			     <label class="col-sm-2 control-label">Sort Order</label>
				 <div class="col-sm-4">
				   <input type="text" id="urutan" name="urutan" class="form-control" value="<?php echo $kategori_urutan ?>" size="50">
				 </div>
		      </div>
			  
		      <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
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
<script>
var action = $('#frmdata').prop('action');
$(function(){
   /* autocomplete */
   $('#kategori_nama').autocomplete({
		delay: 0,
		source: function( request, response ) {
		  $.ajax({
			url: action,
			dataType: "json",
			data: {
			   loads: 'kategori',
			   cari: request.term
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
		  $('#induk').val(ui.item.kode);
		  
		  return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
   
   /* @end autocomplete */
});
</script>
<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>
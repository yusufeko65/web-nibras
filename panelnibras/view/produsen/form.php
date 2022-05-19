<script src="<?php echo URL_PROGRAM_ADMIN_JS.folder."/validasi.js" ?>"></script>
<div class="kotakplat">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
	    
		<form method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>" target="upload-frame">
			<iframe name="upload-frame" id="upload-frame" style="display:none"></iframe> 
			<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
			<input type="hidden" name="iddata" id="iddata" value="<?php echo $iddata ?>">
			<input type="hidden" name="produsenlama" id="produsenlama" value="<?php echo $produsen_nama ?>">
			
			   <table class="form">
			     <tr><td colspan="2" align="right">
						<?php echo $dtFungsi->tombol("submit") ?>
						<?php echo $dtFungsi->tombol("view") ?>
				 	</td>
				 </tr>
			   </table>
		
			<div id="hasil" style="display: none;"></div> 
			<div id="tabs_container">
				<ul id="tabs">
					<li><a href="#tb1" class="active">Data Produsen</a></li>
					<li><a href="#tb2">Keterangan Produksi</a></li>
					
				</ul>
            </div>
			
			<div id="tabs_content_container">
			<div id="tb1" class="tab_content" style="display:block">
			<fieldset>
			  <legend>Data Produsen :</legend>
			    <table class="form">
				   <tr><td width="30%">Nama Produsen</td>
					   <td width="70%"><input type="text" id="produsen" name="produsen" class="inputbox forms" value="<?php echo $produsen_nama ?>"></td>
				   </tr>
				   <tr><td width="30%">Produsen Alias</td>
					   <td width="70%"><input type="text" id="alias" name="alias" class="inputbox forms" value="<?php echo isset($produsen_alias) ? $produsen_alias:''  ?>"></td>
				   </tr>
				   <tr><td>Logo</td>
					  <td><input name="filelogo" type="file" id="filelogo" class="inputbox">
						  <input type="hidden" value="<?php echo $produsen_logo ?>" id="filelama" name="filelama">
						  <?php if($produsen_logo != '-' && $produsen_logo != '') { ?>
						  <br>
						  <img src="<?php echo URL_IMAGE.'_other/other_'.$produsen_logo ?>">
						  <?php } ?>
					  </td>
				   </tr>
				   
				   <tr><td>Telpon</td>
					   <td><input type="text" value="<?php echo $produsen_telp ?>" id="telp" name="telp" class="inputbox"></td>
				   </tr>
				   <tr><td>Email</td>
					   <td><input type="text" value="<?php echo $produsen_email ?>" id="email" name="email" class="inputbox"></td>
				   </tr>
				   <tr><td>Website</td>
					   <td><input type="text" value="<?php echo $produsen_web ?>" id="web" name="web" class="inputbox"></td>
				   </tr>
				   <tr><td>Facebook</td>
					   <td><input type="text" value="<?php echo $produsen_fb ?>" id="facebook" name="facebook" class="inputbox"></td>
				   </tr>
				   <tr><td>Alamat</td>
					   <td><textarea id="alamat" name="alamat" class="textareabox" rows="2" cols="70"><?php echo $produsen_alamat ?></textarea></td>
				   </tr>
				   <tr><td>Keterangan</td>
					   <td><textarea id="keterangan" name="keterangan" class="textareabox" rows="5" cols="70"><?php echo stripslashes($produsen_keterangan) ?></textarea></td>
				   </tr>
				   
				</table>
			 </fieldset>
			 </div>
			 <div id="tb2" class="tab_content">
				<fieldset>
					<legend>Keterangan Produksi :</legend>
						<table class="form">
							<tr><td>Melayani pembelian grosir? (Jika ya, berapa diskon maksimal yang diberikan?)</td>
								<td><textarea id="ketgrosir" name="ketgrosir" class="textareabox" rows="5" cols="70"><?php echo isset($produsen_grosir) ? stripslashes($produsen_grosir):'' ?></textarea></td>
							</tr>
							<tr><td>Kapasitas produksi per bulan</td>
								<td><input type="text" value="<?php echo isset($produsen_kapasitas) ? $produsen_kapasitas:''  ?>" id="kapasitas" name="kapasitas" class="inputbox" style="width:50px"> pcs</td>
							</tr>
						</table>
						<table id="images" class="form table" style="width:50%;margin:10px">
					 <thead>
				     <tr>
					     <th colspan="3">Sampel Produk</th>
					 </tr>
					 </thead>
					 <?php $image_row = 0; ?>
					 <?php $gbrproduk = $dtProdusen->getGambarProduk($iddata) ?>
					 <?php foreach($gbrproduk as $g) { ?>
					<tbody id="image-row<?php echo $image_row; ?>">
					    <tr>
						   <td><img src="<?php echo URL_IMAGE.'_other/other_'.$g['gbr']?>">
						       <input type="hidden" name="gbrlama[]" value="<?php echo $g['gbr'] ?>"><input type="hidden" name="idgbrlama[]" value="<?php echo $g['idgbr'] ?>">
							   <input  type="file" name="produk_image[]" value="" />
						   </td>
						   <td><a onclick="delImage(<?php echo $g['idgbr'] ?>,<?php echo $image_row ?>);">Remove</a></td>
						</tr>
					 </tbody>
					 <?php $image_row++; ?>
					 <?php } ?>
					 <tfoot>
                     <tr>
                       <td colspan="3"><a onclick="addImage();" class="tombols">Add Image</a></td>
                      </tr>
                      </tfoot>
                     </table>
				</fieldset>
			 </div>
			</div>
			
			
		</form>
	</div>
</div>
<script type="text/javascript">
var image_row = <?php echo $image_row ?>;
function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td><input  type="file" name="produk_image[]" /><input type="hidden" name="idgbrlama[]" value=""><input type="hidden" name="gbrlama[]" value=""></td>';
	html += '    <td><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button">Remove</a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
}
function delImage(id,image_row) {
  var datadel = "aksi=delgbr&gid=" + id;
  //alert(action);
  //alert(datadel);
  $.ajax({
 		type: "POST",
   		url: action,
    	data: datadel,
 		cache: false,
    	success: function(msg){
		    
			$('#waiting').hide(0);
			hasilnya = msg.split("|");
			
			if(hasilnya[0]=="sukses" ){
			   $('#image-row' + image_row).remove();
			} else {
			   $('#hasil').html('<div class="warning">' + hasilnya[1] + '</div>');
			   $('#hasil').show(0);
			   $('html, body').animate({ scrollTop: 0 }, 'slow');
			}
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  });
}
</script>
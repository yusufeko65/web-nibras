<script src="<?php echo URL_PROGRAM_ADMIN_JS.folder."/validasi.js" ?>"></script>
<div class="kotakplat" style="width:60%;margin: 10px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
	    
		<form method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>" target="upload-frame">
			<iframe name="upload-frame" id="upload-frame" style="display:none"></iframe> 
			<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
			<input type="hidden" name="iddata" id="iddata" value="<?php echo $iddata ?>">
			<fieldset>
			<table class="form">
			    <tr><td width="30%">Slot Banner</td>
					<td width="70%"><select id="slot" name="slot" class="selectbox" <?php echo $readonly ?>>
							<option value="footer1" <?php if($slot=='footer1') echo "selected" ?>> Footer 1 </option>
							<option value="footer2" <?php if($slot=='footer2') echo "selected" ?>> Footer 2 </option>
						</select>
					</td>
				</tr>
				<tr><td>Nama Banner</td>
					<td><input type="text" id="banner" name="banner" class="inputbox" value="<?php echo $banner_nama ?>" style="width:260px"></td>
				</tr>
				<tr><td>File Gambar</td>
					<td><input name="filegbr" type="file" id="filegbr" class="inputbox"><?php echo $banner_gbr ?>
						<input type="hidden" value="<?php echo $banner_gbr ?>" id="filelama" name="filelama">
						<br>
						<?php if($banner_gbr != '') { ?>
						<img src="<?php echo URL_IMAGE.'_other/other_'.$banner_gbr ?>" style="max-width:50%">
						<?php } ?>
					</td>
				</tr>
				<tr><td width="30%">Ukuran Banner</td>
					<td width="70%">P <input type="text" id="panjang" name="panjang" class="inputbox" value="<?php echo $panjang ?>" style="width:30px"> x L <input type="text" id="lebar" name="lebar" class="inputbox" value="<?php echo $lebar ?>" style="width:30px"> </td>
				</tr>
				<tr><td width="30%">Link Banner</td>
					<td width="70%"><input type="text" id="urllink" name="urllink" class="inputbox" value="<?php echo $url_link ?>" style="width:260px"></td>
				</tr>
				
				<tr><td>Status</td>
					<td><select id="status" name="status" class="selectbox">
							<option value="1" <?php if($banner_status=='1') echo "selected" ?>> Aktif </option>
							<option value="0" <?php if($banner_status=='0') echo "selected" ?>> Tidak Aktif </option>
						</select>
					</td>
				</tr>
				<tr><td colspan="2" align="right">
						<?php echo $dtFungsi->tombol("submit") ?>
						<?php echo $dtFungsi->tombol("view") ?>
					</td>
				</tr>
			</table>
			</fieldset>
			<div id="hasil" style="display: none;"></div> 
			<span id="waiting" style="display: none"><?php echo WAITING_SAVE ?></span>
		</form>
	</div>
</div>
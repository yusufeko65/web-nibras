<div class="kotakplat" style="width:400px;margin: 10px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method=POST name=frmdata id=frmdata onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" id="aksi" value="<?php echo $modul ?>">
			<input type="hidden" id="iddata" value="<?php echo $iddata ?>">
			<fieldset>
			<table class="form">
				<tr><td width="40%">Nama Grup</td>
					<td width="60%"><input type="text" id="grup" class="inputbox" value="<?php echo $grup_nama ?>" size="40">
									<input type="hidden" id="gruplama" class="inputbox" value="<?php echo $grup_nama ?>" size="50">
					</td>
				</tr>
				<tr style="display:none">
				    <td>Gunakan Warna</td>
					<td><select id="warna" class="selectbox">
					       <option value='1' <?php if($warna==1) echo 'selected' ?>>Ya</option>
						   <option value='0' <?php if($warna==0) echo 'selected' ?>>Tidak</option>
					    </select>
					</td>
				</tr>
				<tr><td colspan="2" align="right">
						<?php echo $dtFungsi->tombol("simpan") ?>
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
<script src="<?php echo URL_PROGRAM_ADMIN_JS.folder."/validasi.js" ?>"></script>
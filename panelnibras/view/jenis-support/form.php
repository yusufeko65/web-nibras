<div class="kotakplat" style="width:400px;margin: 10px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method=POST name=frmdata id=frmdata onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" id="aksi" value="<?php echo $modul ?>">
			<input type="hidden" id="iddata" value="<?php echo $iddata ?>">
			<fieldset>
			<table class="form">
				<tr><td width="30%">Nama Propinsi</td>
					<td width="70%"><input type="text" id="propinsi" class="inputbox" value="<?php echo $propinsi_nama ?>" size="35"></td>
				</tr>
				<tr><td width="30%">Negara</td>
					<td width="70%"><?php echo $dtNegara->cetakcomboboxnegara($idnegara,'negara') ?></td>
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
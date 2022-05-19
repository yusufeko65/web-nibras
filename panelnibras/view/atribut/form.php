<div class="kotakplat" style="width:400px;margin: 10px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method=POST name=frmdata id=frmdata onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" id="aksi" value="<?php echo $modul ?>">
			<input type="hidden" id="iddata" value="<?php echo $iddata ?>">
			<fieldset>
			<table class="form">
				<tr><td width="40%">Nama Atribut</td>
					<td width="60%"><input type="text" id="atribut" class="inputbox" value="<?php echo $nama ?>" size="40">
									<input type="hidden" id="atributlama" class="inputbox" value="<?php echo $nama ?>">
					</td>
				</tr>
				<tr>
				    <td>Grup Atribut</td>
					<td><?php echo $dtFungsi->cetakcombobox(" Pilih Grup ","200",$grup,"grup","_atribut_grup","CONCAT(id_atribut_grup,'::',warna)","nama_atribut_grup") ?>
					</td>
				</tr>
				<tr style="display:none" class="sembunyi" <?php //echo $display ?>><td width="40%">Warna</td>
					<td width="60%"><input type="text" id="warna" class="inputbox color" value="<?php echo $value ?>" size="20">
					</td>
				</tr>
				<tr><td colspan="2" align="right">
						<?php echo $dtFungsi->tombol("simpan") ?>
						<?php if($iddata != '') echo $dtFungsi->tombol("hapus") ?>
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
<script src="<?php echo URL_PROGRAM_ADMIN."js/jscolors.js" ?>"></script>
<script src="<?php echo URL_PROGRAM_ADMIN_JS.folder."/validasi.js" ?>"></script>

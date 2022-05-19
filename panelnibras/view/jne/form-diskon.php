<div class="kotakplat" style="width:700px;margin: 10px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method=POST name=frmdata id=frmdata onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
			<input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
			
			<fieldset>
			  <table class="form">
			    <tr>
				   <td>Nama Diskon</td>
				   <td><input type="text" id="nmdisk" name="nmdisk" class="inputbox elmi" value="<?php echo $nmdisk ?>"></td>
				</tr>
				<tr>
				   <td>Nilai Diskon</td>
				   <td><input type="text" id="jmldisk" name="jmldisk" class="inputbox elmi" style="width:100px" value="<?php echo $jmldisk ?>"> <input type="checkbox" id="persen" name="persen" class="chk" value="1" <?php if($persen=='1') echo "checked" ?> /> Dalam Persen ?</td>
				</tr>
				<tr>
				   <td>Jenis Servis</td>
				   <td>
				      <select id="jservis" name="jservis[]" data-placeholder="Pilih Servis JNE..." multiple style="width:200px;height:50px" class="elmi">
					     <?php $dtservis = $dtJne->getServisJne(); ?>
						 <?php foreach($dtservis as $dt ) { ?>
						 <option value="<?php echo $dt['id'] ?>" <?php if(in_array($dt['id'],$dtjneservis)) { echo "selected='selected'"; } ?>><?php echo $dt['nm'] ?></option>
						 <?php } ?>
					  </select>
				   </td>
				</tr>
			    <tr><td width="20%">Negara</td>
					<td width="80%"><?php echo $dtFungsi->cetakcombobox(' Pilih Negara ','200',$idnegara,'negara','_negara','negara_id','negara_nama'); ?></td>
				    
				</tr>
				<tr>
				   <td width="20%">Propinsi</td>
					<td width="80%">
					<?php echo $dtFungsi->cetakcombobox(' Pilih Propinsi ','200',$idpropinsi,'propinsi','_provinsi','provinsi_id','provinsi_nama','negara_id='.$idnegara); ?>
					</td>
				</tr>
				<tr><td>Kotamadya/Kabupaten</td>
					<td>
					  <?php if($modul=='tambahdiskon') { ?>
					  <?php echo $dtFungsi->cetakcomboboxmultiple('','200','80',$idkabupaten,'kabupaten','_kabupaten','kabupaten_id','kabupaten_nama','provinsi_id='.$idpropinsi); ?>
					  <?php } else {?>
					  <select id="kabupaten" name="kabupaten[]" multiple style="width:200px;height:80px">
					  <?php foreach($datakab as $dtk) {?>
					    <option value="<?php echo $dtk['idk'] ?>" <?php if(in_array($dtk['idk'],$dtjnetujuan)) { echo "selected='selected'"; } ?>><?php echo $dtk['nmk'] ?></option>
					  <?php } ?>
					  </select>
					  <?php } ?>
					  <p>
					</td>
				</tr>
				<tr>
				   <td></td>
				   <td><a id="selectall">Select All</a> / <a id="deselectall">Deselect All</a></td>
				</tr>
				<tr>
					<td width="20%">Status</td>
					<td width="80%">
					    <select id="stsdiskon" name="stsdiskon" class="selectbox">
						   <option value="1" <?php if($stsdisk=='1') echo "selected" ?>>Enable</option>
						   <option value="0" <?php if($stsdisk=='0') echo "selected" ?>>Disable</option>
					    </select> 
					</td>
					
				</tr>
				</table>
			</fieldset>
			
			<fieldset>
			  <table class="form">
				<tr><td colspan="2" align="right">
						<?php echo $dtFungsi->tombol("simpan") ?>
						<a id="tombollihat" href="javascript:void(0)" onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder .'/?op=view-diskon'?>')" class="tombolview">Lihat Data</a>
					</td>
				</tr>
			</table>
			</fieldset>
			<div id="hasil" style="display: none;"></div> 
			<span id="waiting" style="display: none"><?php echo WAITING_SAVE ?></span>
		</form>
	</div>
</div>
<script type="text/javascript">
			
  $('#kabupaten').attr("data-placeholder","Pilih Kota / Kabupaten...").chosen({no_results_text:'Kota/Kabupaten tidak ditemukan....!'});
  $('#jservis').chosen();
</script>
<script src="<?php echo URL_PROGRAM_ADMIN_JS.folder."/validasidiskon.js" ?>"></script>
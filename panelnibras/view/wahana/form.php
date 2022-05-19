<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
	 
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <input type="hidden" id="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" value="<?php echo $iddata ?>">
		      <div id="hasil" style="display: none;"></div>
			  <fieldset>
			    <legend>Daerah Tujuan</legend>
			  </fieldset>
			  <div class="well">
			     
				 <div class="col-sm-4">
				   <select id="propinsi" name="propinsi" class="form-control">
				     <option value="0">- Pilih Propinsi -</option>
				   <?php foreach($dataprop as $dprop) { ?>
				     <option value="<?php echo $dprop['idp']?>" <?php if($dprop['idp'] == $idpropinsi) echo "selected"?>><?php echo $dprop['nmp']?></option>
				   <?php } ?>
				   </select>
				 </div>
				 <div class="col-sm-4">
				   <select id="kabupaten" name="kabupaten" class="form-control">
				     <option value="0">- Pilih Kotamadya/Kabupaten -</option>
					 <?php if($idkabupaten != '') {?>
				     <?php foreach($datakabupaten as $dkab) { ?>
				     <option value="<?php echo $dkab['idk']?>" <?php if($dkab['idk'] == $idkabupaten) echo "selected"?>><?php echo $dkab['nmk']?></option>
				    <?php } ?>
					<?php } ?>
				   </select>
				 </div>
				 <div class="col-sm-4">
				   <select id="kecamatan" name="kecamatan" class="form-control">
				     <option value="0">- Pilih Kecamatan -</option>
					 <?php if($idkecamatan != '') {?>
				     <?php foreach($datakecamatan as $dkec) { ?>
				     <option value="<?php echo $dkec['idn']?>" <?php if($dkec['idn'] == $idkecamatan) echo "selected"?>><?php echo $dkec['nmn']?></option>
				    <?php } ?>
					<?php } ?>
				   </select>
				 </div>
				 <div class="clearfix"></div>
		      </div>
			  
			  <fieldset>
			    <legend>Tarif</legend>
			  </fieldset>
			  <div class="well">
			     <table class="table">
					 <tr> 
					     <td><b>Servis</b></td>
						 <td><b>Tarif per Kilo (Rp)</b></td>
						 <td><b>Tarif per Kilo (Rp) berikutnya</b></td>
						 <!--<td><b>ETD (Keterangan estimasi waktu pengiriman)</b><br></td>-->
					 </tr>
				     <?php 
					     if($modul=='tambah') {
					     
					       foreach($dtservis as $dt ) { ?>
						      <tr>
							      <td><input type="hidden" value="<?php echo $dt['id'] ?>" class="servis" id="idservis"><?php echo $dt['nm'] ?></td>
								  <td><input type="text" class="form-control input-sm tarif"  style="width:100px" id='tarif'></td>
								  <td><input type="text" class="form-control input-sm tarifberikut"  style="width:100px" id='tarifberikut'></td>
								  <!--<td><input type="text" class="form-control input-sm keterangan"  style="width:100px" id='keterangan'></td>-->
							  </tr>
					<?php  } 
					     } else { ?>
						 <tr>
							<td><input type="hidden" value="<?php echo $idservis ?>" class="servis" id="idservis"><?php echo $servis_nama ?></td>
							<td><input type="text" class="form-control input-sm tarif" value="<?php echo $tarif[0] ?>" id='tarif' style="width:100px"></td>
							<td><input type="text" class="form-control input-sm tarifberikut" value="<?php echo $tarif[1] ?>" id='tarifberikut' style="width:100px"></td>
							<!--<td><input type="text" class="form-control input-sm keterangan" id='keterangan'  style="width:100px" value="<?php echo $keterangan ?>"></td>-->
						 </tr>
					<?php } ?>
					 <tr>
					    <td colspan="4">
						   <br>
						   Catatan : <cite>Untuk Tarif, masukkan harga perkilo, tanpa ada koma</cite>
						</td>
					 </tr>
					</table>
		      </div>
			  
		      <div class="form-group">
                <div class="col-sm-12 text-right">
		          <a onclick="simpandata()" class="btn btn-sm btn-primary">Simpan</a>
		          <a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
		        </div>
			  </div>
			  <div class="clearfix"></div>
	        </form>
		  </div>
	    </div> 
	 </div>
  </div>

<script src="<?php echo URL_PROGRAM_ADMIN_VIEW.folder."/validasi.js" ?>"></script>
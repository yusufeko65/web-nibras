<div class="kotakplat" style="width:90%;margin: 0px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method=POST name=frmdata id=frmdata onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
			<input type="hidden" name="iddata" id="iddata" value="<?php echo $iddata ?>">
			<input type="hidden" name="remaillama" id="remaillama" value="<?php echo $reseller['reseller_email'] ?> ">
			<input type="hidden" name="rtiperesellerlama" id="rtiperesellerlama" value="<?php echo $reseller['reseller_grup'] ?>" >
			<div id="hasil" style="display: none;"></div> 
			<fieldset>
               <legend>
				   <h2>RESELLER <?php echo $grupreseller['rs_grupnama'] ?> <?php if($reseller['stsapprove'] == '0') echo '[PENDING]' ?> 
				   <?php if($reseller['reseller_grup'] == $grpbayar && $reseller['stsapprove'] == '1') { ?>
				   (Tgl Expired / Masa Aktif sampai <?php echo isset($reseller['tgl_batas']) ? $reseller['tgl_batas']: ''; ?>)
				   <?php } ?>
				   </h2>
			   </legend>
            </fieldset>
			<table class="form">
				
				
				<tr>
				  <td colspan="2" class="bariskanan">
				      <?php if($reseller['reseller_grup'] == $grpbayar && $reseller['stsapprove'] == '1') { ?>
					  <a class="btn btn-sm btn-primary" onclick="renew()">Perpanjang Akun</a>
					  <?php } ?>
					  <a class="btn btn-sm btn-warning" onclick="location='<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=$iddata" ?>'">Ubah Data</a>
					  <!--<a class="btn btn-sm btn-warning" href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=print&pid=$iddata" ?>" target="_blank">Print Data</a>-->
					  <a class="btn btn-sm btn-success" onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'">Kembali ke View Resellers</a>
				  </td>
				</tr>
			</table>
			
			<fieldset>
				<legend>Data Personal</legend>
				<table class="form">
				<tr>
					<td>Nama Reseller</td>
					<td><?php echo $reseller['reseller_nama'] ?></td>
				</tr>
				<?php if($reseller['reseller_email'] != '') { ?>
				<tr>
				   <td>Email</td>
				   <td><?php echo $reseller['reseller_email'] ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td>No. Telepon</td>
					<td><?php echo $reseller['reseller_telp'] ?></td>
				</tr>
				
				<tr>
					<td>Handphone</td>
					<td><?php echo $reseller['reseller_hp'] ?></td>
				</tr>
				
				<tr>
					<td>Jenis Kelamin</td>
					<td><?php echo $reseller['reseller_kelamin']=="Laki-laki" ? "Laki-laki":'Perempuan'?></td>
				</tr>
				
				<tr>
					<td>Tgl Lahir</td>
					<td><?php echo $reseller['reseller_tgllahir'] ?></td>
				</tr>
				
				<tr>
					<td>Alamat</td>
					<td>
					<?php echo $reseller['reseller_alamat'] ?><br>
					Kec. <?php echo $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$reseller['reseller_kecamatan']) ?>, 
					<?php if($reseller['reseller_kelurahan'] != '') { ?>
					Kel. <?php echo $reseller['reseller_kelurahan'] ?> <Br>
					<?php } ?>
					Kota/Kabupaten <?php echo $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$reseller['reseller_kabupaten']) ?>, <br>
					<?php echo $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$reseller['reseller_propinsi']) ?>, <?php echo $dtFungsi->fcaridata('_negara','negara_nama','negara_id',$reseller['reseller_negara']) ?> <?php echo $reseller['reseller_kodepos'] ?>
					</td>
				</tr>
				
			</table>
		</fieldset>
		<?php $style = ""; ?>
		<?php if($grupreseller['rs_frm_toko'] != '1') { ?>
		<?php $style = 'style="display:none"'; ?>
		<?php } ?>
		
	    <fieldset <?php echo $style ?> id="fstoko">
	    <legend>Toko</legend>
			<table class="form">
				<tr>
					<td>Nama Toko/Usaha</td>
					<td><?php echo $reseller['reseller_toko'] ?></td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset id="fssosmed">
	    <legend>Sosial Media</legend>
			<table class="form">
				<tr>
					<td>Facebook</td>
					<td>
					<?php if($reseller['reseller_fb'] != '') { ?>
					<a href="<?php echo $reseller['reseller_fb'] ?>" target="_blank">
					<?php echo $reseller['reseller_fb'] ?>
					</a>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Twitter</td>
					<td><?php echo $reseller['reseller_twitter'] != '' ? '<a href="'.$reseller['reseller_twitter'].'" target="_blank">'.$reseller['reseller_twitter'].'</a>' : '' ?></td>
				</tr>
				<tr>
					<td>Pin BBM</td>
					<td><?php echo $reseller['reseller_bb'] ?></td>
				</tr>
				<tr>
					<td>WhatsApp</td>
					<td><?php echo $reseller['reseller_wa'] ?></td>
				</tr>
				<tr>
					<td>Instagram</td>
					<td>
					<?php echo $reseller['reseller_instagram'] != '' ? '<a href="'.$reseller['reseller_instagram'].'" target="_blank">'.$reseller['reseller_instagram'].'</a>' : '' ?>
					</td>
				</tr>
				<tr>
					<td>Google Plus</td>
					<td><?php echo $reseller['reseller_gplus'] ?></td>
				</tr>
				<tr>
					<td>Website/Blog</td>
					<td><?php echo $reseller['reseller_blog'] ?></td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
		<table class="form">
			<tr>
				<td>Status</td>
				<td><?php echo $reseller['reseller_status'] == '1' ? 'Enabled':'Disabled' ?>
				</td>
			</tr>
			
		</table>
	    </fieldset>
		<table class="form">
  		   <tr>
			 <td colspan="2" class="bariskanan">
			 <?php if($reseller['reseller_grup'] == $grpbayar && $reseller['stsapprove'] == '1') { ?>
			 <a class="btn btn-sm btn-primary" onclick="renew()">Perpanjang Akun</a>
			 <?php } ?>
			 <a class="btn btn-sm btn-warning" onclick="location='<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=$iddata" ?>'">Ubah Data</a>
			 <!--<a class="btn btn-sm btn-warning" href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=print&pid=$iddata" ?>" target="_blank">Print Data</a>-->
			 <a class="btn btn-sm btn-success" onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'">Kembali ke View Resellers</a>
			 </td>
			</tr>
		 </table>
		</form>
	</div>
</div>
<script>

var action = $('#frmdata').attr('action');

function renew() {
  var id 			= $('#iddata').val();
  var datarenew 	= 'aksi=renew&id=' + id;
  var a = confirm('Apakah ingin memperpanjang akun ini?');
  if (a == true) {
	  $.ajax({
		type: "POST",
		url: action,
		data: datarenew,
		success: function(msg){
		   //alert(msg)
			hasilnya = msg.split("|");
			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0).fadeOut(5000);
			//if(hasilnya[0]=="gagal") alert('Error' + hasilnya[1]); 
			alert(hasilnya[1]);
			location ='<?php echo URL_PROGRAM_ADMIN.folder."/?op=info&pid=$iddata" ?>'
			//tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
		
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
			}  
	   });  
	   $('html, body').animate({ scrollTop: 0 }, 'slow');
  }
}

</script>


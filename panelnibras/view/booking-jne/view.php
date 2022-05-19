<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    <div class="left">
			
		</div>
		<div class="right">
			<form name="frmcari" id="frmcari">
			
			Tanggal <input type="text" class="inputbox" style="width:100px" name="tglkomplet" id="tglkomplet" value="<?php echo $tgl ?>">
			<a class="tombols" id="tblcari">Search</a>
			<a class="tombols" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?tgl='+$('#tglkomplet').val()">Export to excel</a>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				 <th style="text-align:center">NAMA PENGIRIM</th>
				 <th style="text-align:center">ALAMAT PENGIRIM 1</th>
				 <th style="text-align:center">ALAMAT PENGIRIM 2</th>
				 <th style="text-align:center">ALAMAT PENGIRIM 3</th>
				 <th style="text-align:center">CONTACT PENGIRIM</th>
				 <th style="text-align:center">TLP PENGIRIM</th>
				 <th style="text-align:left">NAMA PENERIMA</th>
				 <th style="text-align:left">ALAMAT PENERIMA 1</th>
				 <th style="text-align:left">ALAMAT PENERIMA 2</th>
				 <th style="text-align:left">ALAMAT PENERIMA 3</th>
				 <th style="text-align:center">KODE POS</th>
				 <th style="text-align:left">CONTACT PENERIMA</th>
				 <th style="text-align:left">TLP PENERIMA</th>
				 <th style="text-align:center">QTY / JUMLAH BARANG</th>
				 <th style="text-align:center">WEIGHT / BERAT</th>
				 <th style="text-align:center">NAMA BARANG</th>
			  </tr>
			  
			  <?php 
			     
			      foreach($dataview as $datanya) {
				     $pelanggan	        = $datanya['pelanggan_id'];
					 $nama_pengirim	    = $datanya['nama_pengirim'];
					 $kontak_pengirim   = $nama_pengirim;
					 $telp_pengirim     = $datanya['telp_pengirim'];
					 $hp_pengirim       = $datanya['hp_pengirim'];
					 $propinsi_pengirim = $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$datanya['propinsi_pengirim']);
					 $kota_pengirim     = $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$datanya['kota_pengirim']);
					 $kec_pengirim      = $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$datanya['kecamatan_pengirim']);
					 $kel_pengirim      = $datanya['kelurahan_pengirim'];
					 $alamat_pengirim   = $datanya['alamat_pengirim'].', '.$propinsi_pengirim.', '.$kota_pengirim.', '.$kec_pengirim.'. '.$kel_pengirim;
					 $kodepos_pengirim  = $datanya['kodepos_pengirim'];
					 $nama_penerima     = $datanya['nama_penerima'];
					 $telp_penerima     = $datanya['telp_penerima'];
					 $hp_penerima       = $datanya['hp_penerima'];
					 
					 $propinsi_penerima = $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$datanya['propinsi_penerima']);
					 $kota_penerima     = $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$datanya['kota_penerima']);
					 $kec_penerima      = $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$datanya['kecamatan_penerima']);
					 $kel_penerima      = $datanya['kelurahan_penerima'];
					 $alamat_penerima   = $datanya['alamat_penerima'].', '.$propinsi_penerima.', '.$kota_penerima.', '.$kec_penerima.', '.$kel_penerima;
					 $kodepos_penerima  = $datanya['kodepos_penerima'];
					 $jml				= $datanya['jml'];
					 $berat             = $datanya['berat'];
					 
		
				     if($berat < 1) $berat = ceil($berat);
					 
					 if($berat > 1) {
						$berats = floor($berat);
					    $jarakkoma = $berat - $berats;
					    if($jarakkoma > 0.3) $berat = ceil($berat);
						else $berat = floor($berat);
				     } else {
						$jarakkoma = 0;
				     }
					 
			         $field 	    = 'reseller_nama,reseller_toko,rs_dropship,reseller_grup';
					 $tabel 		= '_reseller INNER JOIN _reseller_grup ON _reseller.reseller_grup = _reseller_grup.rs_grupid';
				     $where 		= "reseller_id = '".$pelanggan."'";
					 $reseller 		= $dtFungsi->fcaridata2($tabel,$field,$where);
					 $nmreseller 	= $reseller[0];
					 $tokoreseller	= $reseller[1];
					 $dropship		= $reseller[2];
					 $grupreseller	= $reseller[3];
					 /*
					 if ($nama_penerima != $nmreseller && $dropship=='1') {
					     if($tokoreseller != '-' || $tokoreseller != '') {
							$nama_pengirim = $tokoreseller;
					     } 
					 } else {
					     $nama_pengirim = $nama_toko;
						 $alamat_pengirim = $alamat_toko;
						 $hp_pengirim = $tlp_toko;
						 $kontak_pengirim = $pemilik;
                         						 
					 }
					 */
					 
					 if ($dropship!='1' || $nama_penerima == $nmreseller){
					     $nama_pengirim = $nama_toko;
						 $alamat_pengirim = $alamat_toko;
						 $hp_pengirim = $tlp_toko;
						 $kontak_pengirim = $pemilik;
					 } 
					  
			  ?>
			  <tr>
			    <td><?php echo $nama_pengirim ?></td>
				<td><?php echo $alamat_pengirim ?></td>
				<td></td>
				<td></td>
				<td><?php echo $kontak_pengirim ?></td>
				<td><?php echo $hp_pengirim ?></td>
				<td><?php echo $nama_penerima ?></td>
				<td><?php echo $alamat_penerima ?></td>
				<td></td>
				<td></td>
				<td><?php echo $kodepos_penerima ?></td>
				<td><?php echo $nama_penerima ?></td>
				<td><?php echo $hp_penerima ?></td>
				<td><?php echo $jml ?></td>
				<td><?php echo $berat ?></td>
				<td><?php echo 'Jilbab' ?></td>
			  </tr>
			  <?php } ?>
			  
			</table>
			
		</div>
		
		<!-- Table -->
	</div>
</div>
<script>
$(function(){
   $("#tahun").focus();
   $('#tblcari').click(function(){
        caridata();
		return false;
   });
   $("#datacari").keypress(function(event) {
        if(event.which == 13) {
 		   caridata();
	      return false;
		} else {
		   return true;
		}
   });
   $( "#tglkomplet" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});
function caridata(){
   var tgl = escape($('#tglkomplet').val());
   
   if(tgl.length == 0) {
       alert('Masukkan Tanggal');
	   $('#tglkomplet').focus();
	   return false;
   }
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?tgl=' ?>'+tgl);

}

function cetakpdf(){
	window.open('<?php echo URL_PROGRAM_ADMIN."view/".folder?>/cetakpdf.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val());
}

</script>


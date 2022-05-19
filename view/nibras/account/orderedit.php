<div class="col-xs-9 hskonten">
  <div class="judul-konten"><h2>Edit Belanja <?php echo $dataorder[1] ?></h2></div>
  <form method="POST" name="frmdata" id="frmdata" action="<?php echo URL_PROGRAM.$modul.'/'?>">
    <input type="hidden" name="reseller" id="reseller" value="<?php echo $reseller['reseller_id'] ?>">
	<input type="hidden" name="noorder" id="noorder" value="<?php echo $noorder ?>">
	<input type="hidden" name="reseller_bayar" id="reseller_bayar" value="<?php echo $reseller_bayar ?>">
	<input type="hidden" name="grpreseller" id="grpreseller" value="<?php echo $reseller['reseller_grup'] ?>">
   <div class="isi-konten">
      <div class="panel panel-default">
	    <div class="panel-body">
		  <div class="col-sm-3 form-label tulisan-label"><b>No. Order</b></div>
	      <div class="col-sm-9 form-objek">
	        <div class="col-sm-6">: <?php echo $dataorder[1] ?></div>
          </div>
	      <div class="clearfix"></div>
		  <div class="col-sm-3 form-label tulisan-label"><b>Tgl. Pesan</b></div>
	      <div class="col-sm-9 form-objek">
	        <div class="col-sm-6">: <?php echo $dtFungsi->ftanggalFull1($dataorder[6]) ?></div>
          </div>
	      <div class="clearfix"></div>
		  <hr>
	      <div class="col-xs-6">
		     <h3>Alamat Pengirim (Reseller)</h3>
			 <div class="deskripsi">
			     <b><?php echo $dataalamat[11]?></b><br>
				 <?php echo $dataalamat[14]?><br>
				 <?php echo $dataalamat[16]?>, <?php echo $dataalamat[17]?> <br> 
				 Kec. <?php echo $dataalamat[18]?>, 
				 Kel. <?php echo $dataalamat[19]?> <br>
				 <?php echo $dataalamat[15]?> <?php echo $dataalamat[20]?><br>
				 Hp. <?php echo $dataalamat[13]?><?php if($dataalamat[12] !='') echo ', Telp. '.$dataalamat[12] ?>
			 </div>
		  </div>
		  <div class="col-xs-6">
			  <h3>Alamat Penerima (Pelanggan) [ <a href="javascript:void(0)" onclick="editPenerima()">edit</a> ]</h3>
			  <div class="deskripsi">
			     <b><?php echo $dataalamat[1]?></b><br>
				 <?php echo $dataalamat[4]?><br>
				 <?php echo $dataalamat[6]?>, <?php echo $dataalamat[7]?> <br> 
				 Kec. <?php echo $dataalamat[8]?>, 
				 Kel. <?php echo $dataalamat[9]?> <br>
				 <?php echo $dataalamat[5]?> <?php echo $dataalamat[10]?><br>
				 Hp. <?php echo $dataalamat[3]?><?php if($dataalamat[2] !='') echo ', Telp. '.$dataalamat[2] ?>
			 </div>
		  </div>
	    
	      <!-- Cari produk untuk tambah produk -->
		  <fieldset>
		    <br>
		    <table class="table">
			  <tr>
			    <td class="marginkanan">
				<a id="btnaddprod" class="btn btn-sm btn-success">Tambah Produk</a>
				<div id="platcariprod" class="alert alert-success" style="display:none">
				<b>Cari Nama Produk dibawah ini untuk menambah produk di Orderan Anda <br>
				<input type="text" id="cariproduk" name="cariproduk" placeholder="Cari Nama produk yang ingin di tambah" class="form-control">
				</div>
				</td>
				
			  </tr>
		    </table>
		  </fieldset>
		  <!-- @end Cari produk untuk tambah produk -->
		
		  <table class="table table-bordered">
			  <thead>
				 <tr>
				    <th class="margintengah" colspan="2" width="15%">Aksi</th>
					<th class="marginkiri">Produk</th>
			        <th class="marginkanan">Jumlah</th>
			        <th class="marginkanan">Harga</th>
			        <th class="marginkanan">Total</th>
		         </tr>
		      </thead>
		      <tbody>
			    <?php $totberat = 0; ?>
		        <?php foreach($datadetail as $dt) {?>
				<?php
				   $dtwarna = '';
			       $dtukuran = '';
				   $totberat = $totberat + $dt['berat'];
			       if($dt['warnaid'] || $dt['ukuranid']) {
				 
				      $dtwarna = $dtFungsi->fcaridata('_warna','warna','idwarna',$dt['warnaid']);
				      $dtukuran =  $dtFungsi->fcaridata('_ukuran','ukuran','idukuran',$dt['ukuranid']);
			       }
				?>
		        <tr>
				   <td><span class="glyphicon glyphicon-trash"></span> <a href="javascript:void(0)" onclick="delProduk('<?php echo $noorder ?>','<?php echo $dt['iddetail'] ?>','<?php echo $dt['produkid'] ?>','<?php echo $dt['nama_produk'] ?>','<?php echo $dt['warnaid'] ?>','<?php echo $dt['ukuranid'] ?>','<?php echo $dt['jml'] ?>','<?php echo $reseller['reseller_grup'] ?>','<?php echo $reseller['reseller_id'] ?>','<?php echo $dtwarna ?>','<?php echo $dtukuran ?>')">Hapus</a></td>
				   <td><span class="glyphicon glyphicon-pencil"></span> <a href="javascript:void(0)" onclick="editProduk('<?php echo $noorder ?>','<?php echo $dt['iddetail'] ?>','<?php echo $dt['produkid'] ?>','<?php echo $dt['nama_produk'] ?>','<?php echo $dt['warnaid'] ?>','<?php echo $dt['ukuranid'] ?>','<?php echo $dt['jml'] ?>','<?php echo $reseller['reseller_grup'] ?>','<?php echo $reseller['reseller_id'] ?>')">Ubah</a></td>
				   <td class="marginkiri"><b><?php echo $dt['nama_produk'] ?></b>
					<?php if($dt['warnaid'] || $dt['ukuranid']) {?>
					<br>
					<?php echo 'Warna  :'.$dtFungsi->fcaridata('_warna','warna','idwarna',$dt['warnaid']);?><br>
					<?php echo 'Ukuran :'.$dtFungsi->fcaridata('_ukuran','ukuran','idukuran',$dt['ukuranid']);?>
					<?php } ?>
				   </td>
				   <td class="marginkanan"><?php echo $dt['jml'] ?></td>
				   <td class="marginkanan"><?php echo $dtFungsi->fFormatuang($dt['harga']) ?></td>
				   <td class="marginkanan"><?php echo $dtFungsi->fFormatuang(((int)$dt['jml']) * (int)$dt['harga']) ?></td>
			    </tr>
			    <?php } ?>
			    <tr>
			        <td colspan="5" class="marginkanan"><b>Subtotal</b></td>
				    <td class="marginkanan"><?php echo $dtFungsi->fFormatuang($dataorder[4]) ?></td>
			    </tr>
			    <tr>
			        <td colspan="5" class="marginkanan"><span class="glyphicon glyphicon-pencil"></span> <a onclick="editKurir();" href="javascript:void(0)">[ Ubah ]</a> <b><?php echo $namaservis ?></b></td>
				    <td class="marginkanan"><?php echo $dtFungsi->fFormatuang($dataorder[5]) ?></td>
			    </tr>
			    <?php if($dataorder[14] > 0) {?>
			    <tr>
			       <td colspan="5" class="marginkanan"><b>Infaq</b></td>
				   <td class="marginkanan"><?php echo $dtFungsi->fFormatuang($dataorder[14]) ?></td>
			    </tr> 
			    <?php } ?>
			    <?php if($dataorder[14] > 0) {?>
			    <tr>
			       <td colspan="5" class="marginkanan"><b>POTONGAN TABUNGAN</b></td>
				   <td class="marginkanan"><?php echo $dtFungsi->fFormatuang($dataorder[19]) ?></td>
			    </tr>
			    <?php } ?>
			    <tr>
			        <td colspan="5" class="marginkanan"><b>TOTAL</b></td>
				    <td class="marginkanan"><b><?php echo $dtFungsi->fFormatuang(((int)$dataorder[5] + (int)$dataorder[4] + (int)$dataorder[14])-(int)$dataorder[19]) ?></b></td>
			    </tr>
		     </tbody>
		  </table>
		 
		 </div>
	  </div>
   </div>
   </form>
   <!-- form dialog edit alamat penerima -->
   <div id="dialog-form-editpenerima" title="Edit Alamat Penerima">
      <div id="hasileditpenerima" style="display:none"></div>
	  <input type="hidden" id="eptotberat" name="eptotberat" value="<?php echo $totberat ?>">
	  <input type="hidden" id="epdkurir" name="epdkurir" value="<?php echo $idservis.':'.$dataorder[9].':'.$dataorder[5].':'.$namaservis ?>">
      <input type="hidden" id="ephrgkurir" name="ephrgkurir" value ="<?php echo $dataorder[5] ?>">
	  <h3>Masukkan Alamat Baru Penerima</h3>
      <div class="col-sm-3 form-label">Nama Penerima</div>
	      <div class="col-sm-9 form-objek">
	         <div class="col-sm-10">
		        <input type="text" class="form-control input-sm" name="epnama" id="epnama" placeholder="Nama Penerima">
		     </div>
		     <span class="required">*</span>
	      </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">
			Alamat
	      </div>
	      <div class="col-sm-9 form-objek">
	         <div class="col-sm-10">
		       <textarea name="epalamat" id="epalamat" class="form-control" placeholder="Alamat"></textarea>
		     </div>
		     <span class="required">*</span>
	     </div>
	     <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">Negara</div>
	  <div class="col-sm-9 form-objek">
	      <div class="col-sm-9 combonegara">
		       <?php echo $dtFungsi->cetakcombobox('- Negara -','210',0,'epnegara','_negara','negara_id','negara_nama') ?>
		 </div>
		     <span class="required">*</span>
	  </div>
	  <div class="clearfix"></div>
      <div class="col-sm-3 form-label">Propinsi</div>
      <div class="col-sm-9 form-objek">
         <div class="col-sm-9 combopropinsi">
	       <select name="eppropinsi" id="eppropinsi" style="width:210px" class="selectbox"></select>
	     </div>
	     <span class="required">*</span>
      </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">Kota/Kabupaten</div>
      <div class="col-sm-9 form-objek">
   	     <div class="col-sm-9 combokabupaten">
		    <select name="epkabupaten" id="epkabupaten" style="width:210px" class="selectbox"></select>
		 </div>
		 <span class="required">*</span>
      </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">Kecamatan</div>
      <div class="col-sm-9 form-objek">
	    <div class="col-sm-9">
		   <select name="epkecamatan" id="epkecamatan" style="width:210px" class="selectbox"></select>
		</div>
		<span class="required">*</span>
      </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">Kelurahan</div>
      <div class="col-sm-9 form-objek">
	      <div class="col-sm-10">
		      <input type="text" name="epkelurahan" id="epkelurahan" class="form-control input-sm" placeholder="Kelurahan">
		  </div>
	  </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">Kodepos</div>
      <div class="col-sm-9 form-objek">
	      <div class="col-sm-6">
		    <input type="text" name="epkdpos" id="epkdpos" class="form-control input-sm" placeholder="Kodepos">
		  </div>
		  <span class="required">Apabila dikirim pake POS</span>
	  </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">No. Telepon</div>
      <div class="col-sm-9 form-objek">
	     <div class="col-sm-10">
		    <input type="text" class="form-control input-sm" name="eptelp" id="eptelp" placeholder="No. Telpon">
		 </div>
	  </div>
	  <div class="clearfix"></div>
	  <div class="col-sm-3 form-label">Handphone</div>
      <div class="col-sm-9 form-objek">
	     <div class="col-sm-10">
		   <input type="text" class="form-control input-sm" name="ephandphone" id="ephandphone" placeholder="Handphone">
		 </div>
		 <span class="required">*</span>
      </div>
	  <div class="clearfix"></div>
      <a id="esimpanpenerima" class="btn btn-sm btn-success">Simpan</a>
      <a class="btn btn-sm btn-warning" onclick="location = '<?php echo URL_PROGRAM.$modul."/?order=$noorder"?>';">Tutup</a>
   </div>
   <!-- @end form dialog edit kurir -->
   <!-- form dialog edit kurir -->
		<div id="dialog-form-editkurir" title="Edit Kurir">
		   <div id="hasileditkurir" style="display:none"></div>
		   <input type="hidden" id="nnegaraid" name="nnegaraid" value="<?php echo $dataalamat['21'] ?>">
		   <input type="hidden" id="npropid" name="npropid" value="<?php echo $dataalamat['22'] ?>">
		   <input type="hidden" id="nkotaid" name="nkotaid" value="<?php echo $dataalamat['23'] ?>">
		   <input type="hidden" id="nkecid" name="nkecid" value="<?php echo $dataalamat['24'] ?>">
		   <input type="hidden" id="nkdpos" name="nkdpos" value="<?php echo $dataalamat['25'] ?>">
		   <input type="hidden" id="ntotberat" name="ntotberat" value="<?php echo $totberat ?>">
		   <div id="shipping"></div>
		   <a id="esimpankurir" class="btn btn-sm btn-success">Simpan</a>
           <a class="btn btn-sm btn-warning" onclick="$( '#dialog-form-editkurir' ).dialog( 'close' );">Tutup</a>
		</div>
		<!-- @end form dialog edit kurir -->
   <!-- form delete produk -->
		<div id="dialog-form-delproduk" title="Hapus Produk Order">
		   <h5 style="padding-top:5px;font-weight:bold">Apakah ingin menghapus produk ini dalam Order ini ?</h3>
		   <div id="hasildelprod"></div>
		   <table class="form">
			  <tr>
				 <td width="25%">Nama Produk</td>
				 <td width="75%">
				   <input type="hidden" id="didproduk" name="didproduk" value="">
				   <input type="hidden" id="diddetail" name="diddetail">
				   <input type="hidden" id="didmember" name="didmember">
				   <input type="hidden" id="didgrup" name="didgrup">
				   <input type="hidden" id="dqtylama" name="dqtylama">
				   <input type="hidden" id="duklama" name="duklama">
				   <input type="hidden" id="dwnlama" name="dwnlama">
				   <input type="hidden" id="dnopesan" name="dnopesan">
				   <input type="text" id="dnmproduk" name="dnmproduk" class="form-control" readonly>
				 </td>
			  </tr>
			  <tbody>
			     <tr id="trwarna">
				    <td>Warna</td>
					<td><input type="text" id="dwarna" name="dwarna" class="form-control" readonly></td>
				 </tr>
				 <tr id="trukuran">
				    <td>Ukuran</td>
					<td><input type="text" id="dukuran" name="dukuran" class="form-control" readonly></td>
				 </tr>
			  </tbody>
			  <tr>
			     <td></td>
				 <td><a id="delcart" class="btn btn-sm btn-info">Ya</a> <a id="btntutup" class="btn btn-sm btn-warning" onclick="$( '#dialog-form-delproduk' ).dialog( 'close' );">Tidak</a></td>
			  </tr>
			 </table>
		</div>
		<!-- /form delete produk -->
   <!-- Form Modal edit Produk -->
   <div id="dialog-form-editproduk" title="Edit Produk Order">
	  <div id="hasilproduk" style="display: none;"></div>
		<table width="100%">
		  <tr>
			 <td width="20%">Produk</td>
	   		  <td>
				   <input type="hidden" id="eidproduk" name="eidproduk" value="">
				   <input type="hidden" id="eiddetail" name="eiddetail">
				   <input type="hidden" id="eidmember" name="eidmember">
				   <input type="hidden" id="eidgrup" name="eidgrup">
				   <input type="hidden" id="eqtylama" name="eqtylama">
				   <input type="hidden" id="euklama" name="euklama">
				   <input type="hidden" id="ewnlama" name="ewnlama">
				   <input type="hidden" id="enopesan" name="enopesan">
				   <input type="text" id="enmproduk" name="enmproduk" class="form-control" readonly>
				 </td>
			  </tr>
			  <tbody id="option"></tbody>
			  <tr>
				 <td>Jumlah</td>
				 <td><input type="text" class="form-control" id="eqty" name="eqty" style="width:60px"><span id="keterangan"></span></td>
			  </tr>
			  <tr>
			     <td></td>
				 <td><a id="editcart" class="btn btn-sm btn-info">Simpan Perubahan</a> <a id="btntutup" class="btn btn-sm btn-warning" onclick="$( '#dialog-form-editproduk' ).dialog( 'close' );">Tutup</a></td>
			  </tr>
			 </table>
	</div>
	 <!-- // form Modal edit Produk -->
	 <!-- form Modal add produk -->
		<div id="dialog-form-addproduk" title="Data Produk">
		  <div id="hasiladd" style="display: none;"></div>
			<table class="table" width="100%">
			  <tr>
				<td width="20%">Kode Produk</td>
				<td  width="80%">
				  <input type="text" id="akdproduk" name="akdproduk" class="form-control" readonly>
				  <input type="hidden" id="aidproduk" name="aidproduk">
				</td>
			  </tr>
			  <tr>
				<td>Nama Produk</td>
				<td><input type="text" id="anmproduk" name="anmproduk" class="form-control" value="" readonly></td>
			  </tr>
			  <tbody id="optionadd"></tbody>
				<tr>
					<td>QTY</td>
					<td><input type="text" class="form-control" id="aqty" name="aqty" value="1" style="width:50px"><span id="keterangan"></span></td>
				</tr>
				<tr>
				   <td></td>
				   <td>
				      <a id="aaddcart" class="btn btn-sm btn-info">Tambah Produk</a>
				      <a class="btn btn-sm btn-warning" onclick="location = '<?php echo URL_PROGRAM.$modul."/?order=$noorder"?>';">Tutup</a>
				   </td>
				</tr>
						
			</table>
	    </div>
		<!-- end form Modal add produk -->
</div>
<script>
var action = $('#frmdata').attr('action');
$(function(){
  $('#esimpankurir').click(function(){
	  simpaneditkurir();
  });
  
  $('#esimpanpenerima').click(function(){
	  simpaneditpenerima();
  });
  
  
  $('#epnegara').change(function(){
	$('.combonegara').after('<span class="loading"><img src="<?php echo URL_THEMES.'assets/img/loading.gif' ?>" style="padding-left: 5px;padding-right:10px" /></span>');
	$('#eppropinsi').load(action + '?order='+$('#noorder').val()+'&load=propinsi&negara=' + $(this).val(),function(responseTxt,statusTxt,xhr)
	{
	
	  if(statusTxt=="success") {
	
		$('.loading').remove();
		return false;
	  }
		
	});
	return false;
  });

  $('#eppropinsi').change(function(){
	$('.combopropinsi').after('<span class="loading"><img src="<?php echo URL_THEMES.'assets/img/loading.gif' ?>" style="padding-left: 5px;padding-right:10px" /></span>');
	$('#epkabupaten').load(action + '?order='+$('#noorder').val()+'&load=kabupaten&propinsi=' + $(this).val(),function(responseTxt,statusTxt,xhr)
	{
	  if(statusTxt=="success")
		$('.loading').remove();
		
	});
	return false;
  });
  $('#epkabupaten').change(function(){
	$('.combokabupaten').after('<span class="loading"><img src="<?php echo URL_THEMES.'assets/img/loading.gif' ?>" style="padding-left: 5px;padding-right:10px" /></span>');
	$('#epkecamatan').load(action + '?order='+$('#noorder').val()+'&load=kecamatan&kabupaten=' + $(this).val(),function(responseTxt,statusTxt,xhr)
	{
	  if(statusTxt=="success")
		$('.loading').remove();
		
	});
	return false;
  });
  
  $("#dialog-form-editpenerima").dialog({
	autoOpen: false,
	height: 630,
	width: 650,
	modal: true,
	close: function() {
		$('#hasileditpenerima').removeClass();
		$('#hasileditpenerima').hide();
		location = action + '?order=' + $('#noorder').val();
	}
  });

  $("#dialog-form-editkurir").dialog({
	autoOpen: false,
	height: 460,
	width: 800,
	modal: true,
	close: function() {
	  $('#hasileditkurir').removeClass();
	  $('#hasileditkurir').hide();
	  location = action + '?order=' + $('#noorder').val();
	}
  });
	 
  $("#dialog-form-editproduk").dialog({
	autoOpen: false,
	height: 430,
	width: 600,
	modal: true,
	close: function() {
		$('#hasilproduk').removeClass();
		$('#hasilproduk').hide();
	}
  });
  
  $("#dialog-form-delproduk").dialog({
	autoOpen: false,
	height: 320,
	width: 550,
	modal: true,
	close: function() {
  	  $('#hasilproduk').removeClass();
	  $('#hasilproduk').hide();
	}
  });
  
  $("#dialog-form-addproduk").dialog({
	autoOpen: false,
	height: 430,
	width: 600,
	modal: true,
	close: function() {
		$('#hasiladd').removeClass();
		$('#hasiladd').hide();
		location = action + '?order=' + $('#noorder').val();
	}
  });
  
  $('#editcart').click(function(){
	 simpaneditproduk();
  });
  
  $('#delcart').click(function(){
	 hapusprodukorder();
  });
  
  $('#btnaddprod').click(function(){
     $('#platcariprod').fadeToggle();
	 $('#cariproduk').focus();
  });
  
  $('#aaddcart').click(function(){
	  simpanaddprodukorder();
  });
  
  /* pencarian produk*/
	$('#cariproduk').autocomplete({
		delay: 0,
		source: function( request, response ) {
		  $.ajax({
			url: action,
			dataType: "json",
			data: {
			   mdl: 'cariproduk',
			   grupreseller: $('#grpreseller').val(),
			   cariproduk: request.term
			},
			success: function( data ) {
			  response( $.map( data, function( item ) {
				return {
				  label: item.kode + ' :: ' + item.name,
				  value: item.name,
				  kode: item.kode,
				  nama: item.name,
				  id: item.product_id,
				  ukuran: item.ukuran,
				  warna: item.warna,
				  berat: item.berat,
				  harga: item.harga,
				  hrgsatuan: item.satuan,
				  stok: item.stok,
				  gbr: item.gbr
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
		  
	      $( "#dialog-form-addproduk" ).dialog( "open" );
		  $('#cariproduk').val("");
		  $('#aqty').focus();
		  $("input[name='aidproduk']").attr('value', ui.item.id);
		  $("input[name='akdproduk']").attr('value', ui.item.kode);
		  $("input[name='anmproduk']").attr('value', ui.item.nama);
		  var html = '';
		  html += '<tr><td>Berat</td><td><input type="hidden" value="'+ ui.item.berat +'" readonly><span class="required">'+ ui.item.berat +' Gram </span></td></tr>';
         		 
		 if($('#reseller').val() == '') {
			 $('#addcart').hide();
			 $('#keterangan').html('Masukkan ID Reseller Untuk Add to Cart');
			 html += '<tr><td>Eceran </td><td><input type="text" value="Rp. '+ numeral(ui.item.hrgsatuan).format("0,0") +'" class="form-control" readonly></td></tr>';
		  } else {
			 $('#addcart').show();
			 $('#keterangan').html('');
				
			 var jmldt = ui.item.harga.length;
			 var hit = 0;
			 var labelketerangan = '';
			 var label = '';
			 
			 if( $('#grpreseller').val() != $('#reseller_bayar').val() || jmldt < 1) {
			     html += '<tr><td> Beli 1</td><td>Rp. ' + numeral(ui.item.hrgsatuan).format("0,0") + '</td></tr>';
				 
			 }
			
			for (j = 0; j < jmldt; j++) {
			  if(j < jmldt-1) {
			    hit = ui.item.harga[j+1]['minimal'] - 1;
				labelketerangan = ' (' + ui.item.harga[j]['minimal'] + ' - ' + hit + ' Pcs)';
			  } else {
				labelketerangan = ' (' + ui.item.harga[j]['minimal'] + ' Pcs atau lebih)';
			  }
					
			  if (ui.item.harga[j]['minimal'] < 2) {
				 label = 'Harga ';
			  } else {
				 label = 'Beli ' + ui.item.harga[j]['minimal'];
			  }
			  html += '<tr><td>' + label + '</td><td>Rp. ' + numeral(ui.item.harga[j]['harga']).format("0,0") + labelketerangan + '</td></tr>';
			}
		  }
	   
		  if(ui.item.ukuran != '' || ui.item.warna != ''){
	      
			if(ui.item.ukuran != ''){
				html += '<tr><td>Ukuran </td>';
				html += '<td><select id="aukuran" name="aukuran" style="width:200px" class="form-control" onchange="pilihwarna(this.value,\'awarna\',\'aidproduk\')" >';
					
				for (j = 0; j < ui.item.ukuran.length; j++) {
					html += '<option value="' + ui.item.ukuran[j]['id'] + '">' + ui.item.ukuran[j]['nm'] + '</option>';
				}
			 
				html += '</select>';
				html += '</td></tr>';
			}
		  
			if(ui.item.warna != ''){
				html += '<tr><td>Warna </td>';
				html += '<td><select id="awarna" name="awarna" class="form-control" onchange="pilihstok(this.value)">';
				html += '<option value="0">- Pilih Warna -</option>';
					
				for (j = 0; j < ui.item.warna.length; j++) {
					html += '<option value="' + ui.item.warna[j]['id'] + '">' + ui.item.warna[j]['nm'] + '</option>';
				}
					
				html += '</select>';
				html += '</td></tr>';
			}
		  }
	   
		  html += '<tr><td>Stok</td><td><input type="text" id="stok" value="'+ ui.item.stok +' Pcs" class="form-control" style="width:100px"></td></tr>';
	   
		  $('#optionadd').html(html);
		  return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	/* @end pencarian produk */
});
function pilihstok(warna){
   $('#loadingweb').show();
   ukuran = $('#aukuran').val();
   var dataload = 'warna='+warna+'&ukuran='+ukuran+'&idproduk='+$('#aidproduk').val();
   $.ajax({
	 type: "GET",
	 url: action + '?mdl=caristok',
	 data: dataload,
	 cache: false,
	 dataType: 'html',
	 success: function(msg){
	   $('#stok').val(msg);
	   $('#loadingweb').hide();
	   return false;
	 },  
	   error: function(e){  
	   alert('Error: ' + e);  
	 }  
   });  
}
function simpaneditpenerima(){
   $('#loadingweb').show(500);
   var datanya  = "eptotberat="+$('#eptotberat').val()+"&epdkurir="+$('#epdkurir').val();
       datanya += "&ephrgkurir="+$('#ephrgkurir').val()+"&epnama="+$('#epnama').val();
	   datanya += "&epalamat="+$('#epalamat').val()+"&epnegara="+$('#epnegara').val();
	   datanya += "&eppropinsi="+$('#eppropinsi').val()+"&epkabupaten="+$('#epkabupaten').val();
	   datanya += "&epkecamatan="+$('#epkecamatan').val()+"&epkelurahan="+$('#epkelurahan').val();
	   datanya += "&epkdpos="+$('#epkdpos').val()+"&eptelp="+$('#eptelp').val();
	   datanya += "&ephandphone="+$('#ephandphone').val();
	   datanya += "&noorder="+$('#noorder').val();
  
   if($('#epnama').val() == ''){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Masukkan Nama Penerima');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }

   if($('#epalamat').val() == ''){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Masukkan Alamat Penerima');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   if($('#epnegara').val() == '' || $('#epnegara').val() == '0'){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Pilih Negara');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   if($('#eppropinsi').val() == '' || $('#eppropinsi').val() == '0'){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Pilih Propinsi');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   if($('#epkabupaten').val() == '' || $('#epkabupaten').val() == '0'){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Pilih Kota/Kabupaten');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   if($('#epkecamatan').val() == '' || $('#epkecamatan').val() == '0'){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Pilih Kecamatan');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   if($('#ephandphone').val() == ''){
       $('#hasileditpenerima').addClass("alert alert-danger");
	   $('#hasileditpenerima').html('Masukkan No. Hp Penerima');
	   $('#hasileditpenerima').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   
   $.ajax({
		type: "POST",
		url: action +'?mdl=editpenerima',
		data: datanya,
		cache: false,
		success: function(msg){
		    $('#loadingweb').hide(500);
			//alert(msg);
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   $('#hasileditpenerima').addClass("alert alert-danger");
			   $('#hasileditpenerima').html(hasilnya[1]);
			   $('#hasileditpenerima').show(0);
			  
			} else {
			   $('#hasileditpenerima').addClass("alert alert-success");
			   $('#hasileditpenerima').html(hasilnya[1]);
			   $('#hasileditpenerima').show(0);
			   
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  });  

}
function simpanaddprodukorder(){
   $('#loadingweb').show(500);
   var datanya = "aidproduk="+$('#aidproduk').val()+'&aidmember='+$('#reseller').val();
       datanya += '&aqty='+$('#aqty').val()+'&aukuran='+$('#aukuran').val()+'&awarna='+$('#awarna').val()+'&aidgrup='+$('#grpreseller').val();
       datanya += "&anopesan="+$('#noorder').val()+"&zhrgkurir="+$("#zhrgkurir").val();
   
   //alert(datanya);
   if($('#aukuran').val() == '0'){
       $('#hasiladd').addClass("alert alert-danger");
	   $('#hasiladd').html('Pilih Ukuran');
	   $('#hasiladd').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }

   if($('#awarna').val() == '0'){
       $('#hasiladd').addClass("alert alert-danger");
	   $('#hasiladd').html('Pilih Warna');
	   $('#hasiladd').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   $.ajax({
		type: "POST",
		url: action +'?mdl=addorderproduk',
		data: datanya,
		cache: false,
		success: function(msg){
		    $('#loadingweb').hide(500);
			
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   $('#hasiladd').addClass("alert alert-danger");
			   $('#hasiladd').html(hasilnya[1]);
			   $('#hasiladd').show(0);
			  
			} else {
			   $('#hasiladd').addClass("alert alert-success");
			   $('#hasiladd').html(hasilnya[1]);
			   $('#hasiladd').show(0);
			   
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  });  

}
function delProduk(nopesan,iddetail,produkid,produknm,warna,ukuran,qty,idgrup,idmember,nmwarna,nmukuran) {
   
   $('#loadingweb').show(500);
   $( "#dialog-form-delproduk" ).dialog( "open" );
   $("input[name='diddetail']").attr('value', iddetail);
   $("input[name='didproduk']").attr('value', produkid);
   $("input[name='dnmproduk']").attr('value', produknm);
   $("input[name='didgrup']").attr('value', idgrup);
   $("input[name='didmember']").attr('value', idmember);
   $("input[name='dwnlama']").attr('value', warna);
   $("input[name='duklama']").attr('value', ukuran);
   $("input[name='dqtylama']").attr('value', qty);
   $("input[name='dnopesan']").attr('value', nopesan);
   $("input[name='dwarna']").attr('value', nmwarna);
   $("input[name='dukuran']").attr('value', nmukuran);
   $('#loadingweb').hide(500);
}
function editPenerima(){
  $( "#dialog-form-editpenerima" ).dialog( "open" );
}
function editProduk(nopesan,iddetail,produkid,produknm,warna,ukuran,qty,idgrup,idmember) {
  $('#loadingweb').show(500);
  var datanya = "mdl=carieditproduk&produkid=" + produkid + "&warna=" + warna + "&ukuran=" + ukuran + '&nopesan=' + nopesan;
  
  $.ajax({
	url: action,
	dataType: "json",
	type: "GET",
	data: datanya,
	success: function( zdata ) {
	 
	 var jmlwarna = zdata.warna.length;
	 var jmlukuran = zdata.ukuran.length;
	 var html = '';
	 $('#loadingweb').hide(500);
	 var selected = '';
	    $( "#dialog-form-editproduk" ).dialog( "open" );
	    $("input[name='eiddetail']").attr('value', iddetail);
	    $("input[name='eidproduk']").attr('value', produkid);
	    $("input[name='enmproduk']").attr('value', produknm);
		$("input[name='eidgrup']").attr('value', idgrup);
		$("input[name='eidmember']").attr('value', idmember);
		$("input[name='ewnlama']").attr('value', warna);
		$("input[name='euklama']").attr('value', ukuran);
		$("input[name='eqtylama']").attr('value', qty);
		$("input[name='enopesan']").attr('value', nopesan);
		$("input[name='eqty']").attr('value', qty);
		$('#eqty').focus();
		if(jmlukuran > 0) {
		   html += '<tr><td>Ukuran </td>';
		   html += '<td><select id="eukuran" name="eukuran" class="form-control" onchange="pilihwarna(this.value,\'ewarna\',\'eidproduk\')" >';
		   for (j = 0; j < jmlukuran; j++) {
		      if(zdata.ukuran[j]['id'] == ukuran ) {
			     selected = 'selected';
			  } else {
			     selected = '';
			  }
			  html += '<option value="' + zdata.ukuran[j]['id'] + '"'+ selected +'>' + zdata.ukuran[j]['nm'] + '</option>';
		   }
		   html += '</select>';
		   html += '</td></tr>';
		}
		if(jmlwarna > 0){
		   html += '<tr><td>Warna </td>';
		   html += '<td><select id="ewarna" name="ewarna" class="form-control" onchange="pilihstokwarna(this.value)">';
		   html += '<option value="0">- Pilih Warna -</option>';
		   for (j = 0; j < jmlwarna; j++) {
		      if(zdata.warna[j]['id'] == warna ) {
			     selected = 'selected';
			  } else {
			     selected = '';
			  }
			  html += '<option value="' + zdata.warna[j]['id'] + '"'+ selected +'>' + zdata.warna[j]['nm'] + '</option>';
		   }
					
		   html += '</select>';
		   html += '</td></tr>';
		   			
		}
		html += '<tr><td>Stok</td><td><input type="text" id="stok" value="'+ zdata.jmlstok +' Pcs" class="form-control" style="width:70px" readonly></td></tr>';
	    $('#option').html(html);
	 
	  return false;
	  
	},
	 error: function(e){  
		alert('Error: ' + e);  
	}  
  });

}
function pilihwarna(ukuran,warna,idproduk){
   $('#'+warna).hide();
   $('#loadingweb').show(500);
   var dataload = '&ukuran='+ukuran+'&idproduk='+$('#'+idproduk).val()+'&idtxtwarna='+warna;
   $('#'+warna).load(action + '?mdl=cariwarna' + dataload,function(responseTxt,statusTxt,xhr)
	{
		  if(statusTxt=="success") {
		    $('#'+warna).show();
			$('.loading').remove();
			$('#loadingweb').hide(500);
			
		  } 
	});
     
}
function pilihstokwarna(warna){
   $('#loadingweb').show(500);
   ukuran = $('#eukuran').val();
   var dataload = 'warna='+warna+'&ukuran='+ukuran+'&idproduk='+$('#eidproduk').val();
   //alert(datalog);
   $.ajax({
	 type: "GET",
	 url: action + '/?mdl=caristok',
	 data: dataload,
	 cache: false,
	 dataType: 'html',
	 success: function(msg){
	   //alert(msg);
	   $('#loadingweb').hide(500);
	   $('#stok').val(msg);
	   return false;
	 },  
	   error: function(e){  
	   alert('Error: ' + e);  
	 }  
   });  
}
function simpaneditproduk(){
   $('#loadingweb').show(500);
   var datanya = "eidproduk="+$('#eidproduk').val()+'&eidmember='+$('#eidmember').val();
       datanya += '&eqty='+$('#eqty').val()+'&eukuran='+$('#eukuran').val()+'&ewarna='+$('#ewarna').val()+'&eidgrup='+$('#eidgrup').val();
       datanya += "&enopesan="+$('#enopesan').val()+'&eqtylama='+$('#eqtylama').val();
	   datanya += "&euklama="+$('#euklama').val()+'&ewnlama='+$('#ewnlama').val();
	   datanya += "&eiddetail="+$('#eiddetail').val()+"&zhrgkurir="+$("#zhrgkurir").val();
	   
   
   if($('#eukuran').val() == '0'){
       $('#hasilproduk').addClass("alert alert-danger");
	   $('#hasilproduk').html('Pilih Ukuran');
	   $('#hasilproduk').show(0);
	   $('#loadingweb').hide(500);
	   return false;
   }
   
   if($('#ewarna').val() == '0'){
       $('#hasilproduk').addClass("alert alert-danger");
	   $('#hasilproduk').html('Pilih Warna');
	   $('#hasilproduk').show(0);
	   $('#loadingweb').hide(500);
	   return false;
	  
   }
	
   $.ajax({
		type: "POST",
		url: action + '/?mdl=editorderproduk',
		data: datanya,
		cache: false,
		success: function(msg){
		    //alert(msg);
			$('#loadingweb').hide(500);
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   $('#hasilproduk').addClass("alert alert-danger");
			   $('#hasilproduk').html(hasilnya[1]);
			   $('#hasilproduk').show(0);
			  
			} else {
			   $('#hasilproduk').addClass("alert alert-success");
			   $('#hasilproduk').html(hasilnya[1]);
			   $('#hasilproduk').show(0);
			   location = action + '/?order=' + $('#enopesan').val(); 
			   
			   
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  });  
}
function hapusprodukorder(){
   var datanya = "didproduk="+$('#didproduk').val()+'&didmember='+$('#didmember').val();
       datanya += '&dukuran='+$('#dukuran').val()+'&dwarna='+$('#dwarna').val()+'&didgrup='+$('#didgrup').val();
       datanya += "&dnopesan="+$('#dnopesan').val()+'&dqtylama='+$('#dqtylama').val();
	   datanya += "&duklama="+$('#duklama').val()+'&dwnlama='+$('#dwnlama').val();
	   datanya += "&diddetail="+$('#diddetail').val()+"&zhrgkurir="+$("#zhrgkurir").val();
   
   $('#loadingweb').show(500);
   $.ajax({
		type: "POST",
		url: action + '/?mdl=hapusorderproduk',
		data: datanya,
		cache: false,
		success: function(msg){
			//alert(msg);
			$('#loadingweb').hide(500);
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   $('#hasildelprod').addClass("alert alert-warning");
			   $('#hasildelprod').html(hasilnya[1]);
			   $('#hasildelprod').show(0);
			  
			} else {
			   $('#hasildelprod').addClass("alert alert-success");
			   $('#hasildelprod').html(hasilnya[1]);
			   $('#hasildelprod').show(0);
		
			   location = action + '?order=' + $('#dnopesan').val(); 
			   
			   
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  }); 
	
}
function simpaneditkurir() {
    $('#loadingweb').fadeOut(2000);
    if($('input[name=serviskurir]:checked').length == 0){
	   alert('Pilih Kurir Pengiriman');
	   return false;
    } 
	var error = '';
    var idtxt = $('input[name=serviskurir]:checked').attr('rel');
    var x;
    var hrgkurir = 0;
	$('.valueclass').each(function () {
	   x = $(this).attr("rel");
	   if(idtxt == x){
		  if($(this).val() == '') {
             hrgkurir = '0';
		  } else {
			 hrgkurir = $(this).val();
		  }
		  return false;
		}
    });
		
	if(error != ''){
	   alert(error);
	   return false;
	}
	dkurir = $('input[name=serviskurir]:checked').val();
    kurir	= dkurir.split(":");
	var negara = $('#nnegaraid').val();
    var propinsi = $('#npropid').val();
    var kabupaten = $('#nkotaid').val();
    var kecamatan = $('#nkecid').val();
    var totberat = $('#ntotberat').val();
	var datanya = "nnegaraid="+negara+"&npropid="+propinsi+"&nkotaid="+kabupaten+"&nkecid="+kecamatan+"&ntotberat="+totberat+"&dkurir="+dkurir+"&hrgkurir="+hrgkurir+"&nopesan="+$('#noorder').val();
	$.ajax({
		type: "POST",
		url: action + '?mdl=simpaneditkurir',
		data: datanya,
		success: function(msg){
		  $('#loadingweb').fadeOut(2000);
		  hasilnya = msg.split("|");
		  if(hasilnya[0]=="gagal") {
			 $('#hasileditkurir').addClass("warning");
			 $('#hasileditkurir').html(hasilnya[1]);
			 $('#hasileditkurir').show(0);
			  // $('html, body').animate({ scrollTop: 0 }, 'slow');
		  } else {
			   $('#hasileditkurir').addClass("success");
			   $('#hasileditkurir').html(hasilnya[1]);
			   $('#hasileditkurir').show(0);
		
			   location = action + '?order='+$("#noorder").val();
			   
			   
			}
		  return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
    });  
}
function editKurir(){
  $('#loadingweb').show(500);
  var negara = $('#nnegaraid').val();
  var propinsi = $('#npropid').val();
  var kabupaten = $('#nkotaid').val();
  var kecamatan = $('#nkecid').val();
  var totberat = $('#ntotberat').val();
  var datanya = '&negara='+negara+'&propinsi='+propinsi;
			    datanya +='&kabupaten='+kabupaten+'&kecamatan='+kecamatan;
				datanya +='&totberat='+totberat;
  $( "#dialog-form-editkurir" ).dialog( "open" );
  $('#shipping').load(action+'?mdl=editshipping'+datanya,function(responseTxt,statusTxt,xhr)
  {
     if(statusTxt=="success") {
		$('#loadingweb').hide(500);
      } 
  });
 
}
</script>

<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
			  <input type="hidden" id="urlredirect" name="urlredirect" value="<?php echo URL_PROGRAM_ADMIN.folder ?>">
		      <div id="hasil" style="display: none;"></div>
				<div class="text-right">
					<a href="<?php echo URL_PROGRAM_ADMIN."customer-poin/" ?>" class="btn btn-sm btn-info">Kembali</a>
				</div>
				<br>
			  <div class="well well-sm">
			     <div class="col-md-6">
				   <table class="table">
				      <tr>
					     <td><b>Kode</b></td>
						 <td> : <?php echo sprintf('%04s', $customer["cust_id"]);?></td>
					  </tr>
					  
				      <tr>
					     <td><b>Nama</b></td>
						 <td> : <?php echo $customer['cust_nama'] ?></td>
					  </tr>
					  <tr>
					    <td colspan="2"></td>
					  </tr>
				   </table>
				 </div>
				 <div class="col-md-6"> 
				    <table class="table">
				      <tr>
					     <td><b>Grup</b></td>
						 <td> : <?php echo $customer["cg_nm"] ?></td>
					  </tr>
					  
				      <tr>
					     <td><b>No. Telp</b></td>
						 <td> : <?php echo $customer['cust_telp'] ?></td>
					  </tr>
					  <tr>
					    <td colspan="2"></td>
					  </tr>
				   </table>
				 </div>
					
			  <div class="clearfix"></div>
			  </div>
			  
			  <div class="clearfix"></div>
			  <div class="well well-sm">
					<label>
					IN : Poin Masuk <br>
					OUT : Menggunakan poin Saat Pelanggan Membeli Produk
					</label>
			  </div>
				<div class="text-right">
					<h3>Total Poin <?php echo $totalpoin ?></h3>

				</div>
			  <table class="table table-bordered table-striped table-hover tabel-grid">
			    <thead>
				  <tr>
				    <td style="min-width:3%" class="tengah">No.</td>
					<td>Poin</td>
					<td>Tipe</td>
					<td>Tgl</td>
					<td>No. Order</td>
				  </tr>
				</thead>
				<tbody>
					<?php $no = 1 ?>
				   <?php foreach($datadeposit as $dp) { ?>
				   <tr>
				      <td class="text-center"><?php echo $no++ ?></td>
					  <td class="text-right"><?php echo $dtFungsi->fuang($dp['cph_poin']) ?></td>
					  <td class="text-center"><?php echo $dp['cph_tipe'] ?></td>
					  <td class="text-center"><?php echo $dp['cph_tgl'] ?></td>
					  <td class="text-center">#<?php echo sprintf('%08s', $dp['cph_order']);?></td>
				   </tr>
				   <?php } ?>
				</tbody>
			  </table>
			  <?php if($total>0) { ?>
	          <!-- Paging -->
	          <div class="col-md-6">
		         <div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		      </div>
              <div class="col-md-6 text-right">
		         <ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage) ?></ul>
		      </div>
			  
		      <!-- End Pagging -->
		      <?php } ?>
			  <div class="clearfix"></div>
	        </form>
		  </div>
	    </div> 
	 </div>
  </div>

<script>
$(function(){
   $('#remail').focus();
	
   $('#rnegara').change(function() {
      //alert(this.value);
	  $('#rpropinsi').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=propinsi&negara=' + this.value);
	  return false;
   });
   $('#rpropinsi').change(function() {
	  $('#rkabupaten').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=kabupaten&propinsi=' + this.value);
	  $('#rkecamatan').html('<option value="0">- Kecamatan -</option>');
	  return false;
	});
	$('#rkabupaten').change(function() {
    	$('#rkecamatan').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=kecamatan&kabupaten=' + this.value);
		return false;
	});
	$("#poinku").hide();
	if($("#aksi").val()!="ubah") {
  	  $('#rtipecust').change(function() {
	     var deposit =$(this).find('option:selected').attr('rel');
		 if(deposit == '1') {
		    $("#poinku").show();
		 } else {
		    $("#poinku").hide();
		 }
	  });
	}
});

var action = $('#frmdata').attr('action');

function kosongform(){
  $('#remail').focus();
  $('.form-control').each(function () {
    $(this).val("");
  });
}
function disableEnterKey(e){ //Disable Tekan Enter
    var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13){ // Jika ditekan tombol enter
		  simpandata(); // Panggil fungsi simpandata()
          return false;
     } else {
          return true;
	 }
}

function simpandata(){ //Proses Simpan
	$('#loadingweb').show(500);
	$('#btnsimpan').button('loading');
	var urlredirect = $('#urlredirect').val();
	$.ajax({
 		type: "POST",
   		url: action,
    	data: $('#frmdata').serialize(),
 		cache: false,
    	success: function(msg){
		    
		    $('#btnsimpan').button('reset');
		    $('#loadingweb').fadeOut(500);
			hasilnya = msg.split("|");
			$('#hasil').show(0);
			if(hasilnya[0]=="sukses") {
			   if(hasilnya[1]=="input") kosongform();
			   hasilnya[2] = '<div class="alert alert-success">'+hasilnya[2]+'</div>';
			   location = urlredirect+'?op=poin&pid='+$('#iddata').val();
			} else {
			   hasilnya[2] = '<div class="alert alert-danger">'+hasilnya[2]+'</div>';
			}
			$('#hasil').html(hasilnya[2]);
			return false;
      	}  
  	});  
	$('html, body').animate({ scrollTop: 0 }, 'slow');
}
</script>


<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
    <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
			<div class="widget-content nopadding">
				<form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
					<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
					<input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
					<input type="hidden" id="urlredirect" name="urlredirect" value="<?php echo URL_PROGRAM_ADMIN.folder ?>">
					<div class="text-right">
						<a href="<?php echo URL_PROGRAM_ADMIN.folder ?>" class="btn btn-sm btn-warning">Kembali</a><br><br>
					</div>
					<div class="well well-sm">
						
						<div class="col-md-6">
							<table class="table">
								<tr>
									<td><b>Kode</b></td>
									<td> : <?php echo sprintf('%04s', $reseller["cust_id"]);?></td>
								</tr>
					  
								<tr>
									<td><b>Nama</b></td>
									<td> : <?php echo $reseller['cust_nama'] ?></td>
								</tr>
								<tr>
									<td colspan="2"></td>
								</tr>
							</table>
						</div>
						<div class="col-md-6"> 
							<table class="table">
								<tr>
									<td><b>Email</b></td>
									<td> : <?php echo $reseller["cust_email"] ?></td>
								</tr>
					  
								<tr>
									<td><b>No. Telp</b></td>
									<td> : <?php echo $reseller['cust_telp'] ?></td>
								</tr>
								<tr>
									<td colspan="2"></td>
								</tr>
							</table>
						</div>
						<div class="col-md-12">
							<h3>Saldo Sekarang : <?php echo $dtFungsi->fuang($totaldeposito) ?></h3>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="well well-sm">
						<label>
						IN : Transfer Saldo <br>
						OUT : Menggunakan Saldo Saat Pelanggan Membeli Produk
						</label>
					</div>
					<table class="table table-bordered table-striped table-hover tabel-grid">
						<thead>
							<tr>
								<th class="text-right">Saldo</th>
								<th>Tipe</th>
								<th>Tgl</th>
								<th>Keterangan</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
						<?php if($total>0) { ?>
						<?php foreach($datadeposit as $dp) { ?>
							<tr>
								<td class="text-right"><?php echo $dtFungsi->fFormatuang($dp['cdh_deposito']) ?></td>
								<td class="text-center"><?php echo $dp['cdh_tipe'] ?></td>
								<td class="text-center"><?php echo $dp['cdh_tgl'] ?></td>
								<td><?php echo $dp['cdh_keterangan'] ?></td>
								<td>
									<?php 
										if($dp['cdh_tipe']=='IN'){
											$status = '';
											if(empty($dp['cdh_bukti'])){
												$status = 'disabled';
											}

											echo '<a href="javascript:void(0)" class="btn btn-xs btn-primary btnViewBukti" data-image="'.$dp['cdh_bukti'].'" '.$status.'>View</a>';
										} 
									?>
								</td>
							</tr>
						<?php } ?>
						<?php } else { ?>
							<tr>
								<td colspan="5" class="text-center">Tidak Ada Data</td>
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
var action = $('#frmdata').attr('action');

$('.btnViewBukti').click(function(){
	$.post(action + '?op=viewBukti&u_token=<?php echo $u_token ?>', {

		stsload: "load",

		data: $(this).attr('data-image'),

	}, function(data) {

		//alert(data);

		$("#loadingweb").fadeOut();

		$('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function() {

			$(this).remove();

		});

	});
});

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
			   location = urlredirect+'?op=deposito&pid='+$('#iddata').val();
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


 <?php if(!defined('URL_PROGRAM')) echo "<script>location='".URL_PROGRAM."'</script>"; ?>
 <div class="title-module">
  <h3>Pembayaran</h3>
</div>
<div class="clearfix"></div>
<div class="isi-konten">
  <div id="hasil" style="display: none;"></div> 
  <div class="panel panel-default">
	<div class="panel-body">
	  <form method="POST" name="frmkasir" id="frmkasir" action='<?php echo URL_PROGRAM."cart/konfirm"?>'>
		<h3>TOTAL BELANJA</h3>
		<div class="col-md-6">
		    <h3>Alamat Tagihan</h3>				 
			<div class="deskripsi">
			    <b><?php echo $reseller['cust_nama']?></b><br>
			    <?php echo $reseller['cust_alamat']?><br>
			    <?php echo $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$reseller['cust_propinsi'])?>,  
				<?php echo $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$reseller['cust_kota'])?> <br> 
				Kec. <?php echo $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$reseller['cust_kecamatan'])?> 
				<?php if($reseller['cust_kelurahan'] != '') { ?>
				  , Kel. <?php echo $reseller['cust_kelurahan']?> 
				<?php } ?><br>
				<?php echo $dtFungsi->fcaridata('_negara','negara_nama','negara_id',$reseller['cust_negara'])?> <?php echo $reseller['cust_kdpos']?><br>
				<?php if($reseller['cust_telp'] !='') echo 'Telp. '.$reseller['cust_telp'] ?>
			</div>
		</div>
		<div class="col-md-6">
		    <h3>Alamat Penerima (tujuan)</h3>				 
			<div class="deskripsi"> 
			    <b><?php echo $_SESSION['frmnama']?></b><br>
				<?php echo $_SESSION['frmalamat']?><br>
				<?php echo $dtFungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id',$_SESSION['frmpropinsi'])?>,  
				<?php echo $dtFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$_SESSION['frmkabupaten'])?> <br> 
				Kec. <?php echo $dtFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$_SESSION['frmkecamatan'])?>, 
			    Kel. <?php echo $_SESSION['frmkelurahan']?> <br>
				<?php echo $dtFungsi->fcaridata('_negara','negara_nama','negara_id',$_SESSION['frmnegara'])?> <?php echo $_SESSION['frmkodepos']?><br>
				<?php if($_SESSION['frmtelp'] != '') echo 'Telp. '.$_SESSION['frmtelp']?>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="table-responsive">
		<table class="table table-bordered">
	        <thead>
			    <tr>
				  <th class="text-left">Nama Produk</th>
				  <th class="text-right">Jumlah</th>
				  <th class="text-right">Berat</th>
				  <th class="text-right">Harga</th>
				  <th class="text-right">Sub Total</th>
				</tr>
			</thead>
			<tbody>
			<?php if($jmlcart == 0) { ?>
		       <tr>
		          <td colspan="6" style="text-align:center">Tidak ada belanja</td>
		       </tr>
			<?php } else { ?>
			<?php
				 
				 $subtotal 	= 0;
				 $i = 0;
				 $totberat = 0;
				 $cart = $dtFungsi->urutkan($hcart,'katid');
				 foreach($cart as $c){
					$pid 		 = $c['product_id'];
					$nama_produk = $c['product'];
					$jml 		 = $c['qty'];
					$satuanberat = $c['satuanberat'];
					$berat 		 = $c['berat'];
					$harga 		 = $c['harga'];
					$total 		 = $c['total'];
					$subtotal	+= $total;
					$totberat   += $berat;
					$idwarna     = $c['warna'];
					$idukuran    = $c['ukuran'];
					if($idwarna !='') $warna		 = $dtFungsi->fcaridata('_warna','warna','idwarna',$idwarna);
					else $warna = '';
					
					if($idukuran != '')	$ukuran		 = $dtFungsi->fcaridata('_ukuran','ukuran','idukuran',$idukuran);
					else $ukuran = '';
					$option  	 = $idukuran.','.$idwarna;
					$options     = array($idukuran,$idwarna);
					$alias_url	 = $c['aliasurl'];
					$katalias    = $c['katalias'];
					$katid       = $c['katid'];
					$katname     = $c['katname'];
					if(!in_array($katid,$kategori)) {
					   $kategori[] = $katid;
			?>
			   <tr>
		          <td colspan="6" style="background-color:#333;color:#fff"><?php echo $katname ?></td>
		       </tr>
		        <?php } ?>
			   <tr>
				 <td class="text-left"><a href="<?php echo URL_PROGRAM.$katalias.'/'.$alias_url?>"><?php echo $nama_produk ?></a><br><?php echo $warna?><br><?php echo $ukuran?></td>
				 <td class="text-right"><?php echo $jml ?></td>
				 <td class="text-right"><?php echo $berat ?> Gram</td>
				 <td class="text-right"><?php echo $dtFungsi->fFormatuang($harga) ?></td>
				 <td class="text-right"><?php echo $dtFungsi->fFormatuang($total) ?></td>
			   </tr>
		   <?php $i++;
		      } ?>
			   <tr>
				  <td colspan="3" class="text-right"> Total Berat Pesanan Anda :</td>
				  <td class="text-right"><b><?php echo number_format($totberat,0,",",".") ?></b> Gram / <?php echo number_format((int)$totberat/1000,2,",",".") ?> Kg</td>
				  <td colspan="2"></td>
			   </tr>
			 <?php } ?>
		    </tbody>
		</table>
		</div>
		<?php if($jmlcart > 0) { ?>
		<div class="col-md-12">
		   <div class="row">
			<div class="text-right"><b>Sub Total : </b><?php echo $dtFungsi->fFormatuang($subtotal) ?></div>
		   </div>
		</div>
		<?php } ?>
		<table>
		   <tr>
		      <td><img src="<?php echo URL_IMAGE.$logoshipping ?>"></td>
		   </tr>
		</table>
	
		<table class="table table-bordered">
		    <?php
				if($tabeldiskon != Null || $tabeldiskon != '') {
				   $zdizkon = explode("::",$tabeldiskon);
				   $tabel = $zdizkon[0];
				   $fieldambil = $zdizkon[1];
				   $where = " $zdizkon[2]='".$servisid."' AND $zdizkon[3]=1";
					
				   $dtdiskon = $dtFungsi->fcaridata2($tabel,$fieldambil,$where);
				   $dtdiskon['$fieldambil'] = isset($dtdiskon['$fieldambil']) ? $dtdiskon['$fieldambil'] :0;
				   $diskon = $dtdiskon['$fieldambil'] / 100;
				 } else {
				   $diskon = 0;
				 }
			?>
		    <?php $tarif = $dtShipping->getTarif($servisid,$frmnegara,$frmpropinsi,$frmkabupaten,$frmkecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping)?>
			<?php //if($tarif[1] > 0) {?>
  		    <?php $tarif[1] = $tarif[1] - ($tarif[1]*$diskon); ?>
			<?php  if($tarif[1] > 0 || $tabeltarif == '') { ?>
  			<tr>
			    <td style="width: 80%">
				   <input type="hidden" name="serviskurir" value="<?php echo $servisid ?>"><b><?php echo $namaservis ?></b>
				   <?php if($servisket != '') { ?>
				   <?php echo '<br><b>'.$servisket.'</b>' ?>
				   <?php } ?>
			    </td>
				<td style="width: 20%" align="right"><b><span class="hijau">
				<?php if($tarif[1] > 0) {?>
				<?php echo $dtFungsi->fFormatuang($tarif[1]) ?>
				<?php } else { ?>
				Konfirmasi Admin
				<?php } ?>
				</span></b></td>
			</tr>
			<?php } ?>
			<?php //} ?>
		</table>
        <?php if($poin != '' && $poin > 0) { ?>
	    <h4>POIN</h4>
		<div class="well">
		   <div class="col-md-8 text-right">
			    Anda telah menggunakan poin :
		  </div>
		  <div class="col-md-4">
			 <?php echo $dtFungsi->fFormatuang($poin) ?>
		  </div>
		  <div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<?php } ?>
	    <div class="col-md-12">
		    <div class="row">
			    <div class="text-right"><h2>Total : <?php echo $dtFungsi->fFormatuang((int)$subtotal+(int)$tarif[1]-(int)$poin) ?></h2></div>
				<div class="well text-right"><b><?php echo $dtFungsi->terbilang((int)$subtotal+(int)$tarif[1]-(int)$poin) ?> rupiah</b></div>
			</div>
		</div>
		<div class="col-md-12 text-right"><div class="row"><a id="btnnext" href="javascript(0)" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-briefcase"></span> Selesai</a></div></div>
  	     </form>
	   </div>
	  </div>
	</div>

<script type="text/javascript">
jQuery(document).ready(function(){
    $("#btnnext").click(function(){
	     $.ajax({
			type: "POST",
			url: '',
			data: $('#frmkasir').serialize(),
			cache: false,
			success: function(msg){
			   alert(msg);
			   hasilnya = msg.split("|");
			   if(hasilnya[0]=="gagal") {
			      $('#hasil').addClass("alert alert-danger");
			      $('#hasil').html(hasilnya[1]);
			      $('#hasil').show(0);
			   } else {
				  location='<?php echo URL_PROGRAM."cart/sukses"?>';
			   }
			   return false;
		    },  
			   error: function(e){  
			   alert('Error: ' + e);  
		    }  
	      });
		 $('#loadingweb').show();
		 $('html, body').animate({ scrollTop: 0 }, 'slow');
		 
     });

});
</script>
 <?php if(!defined('URL_PROGRAM')) echo "<script>location='".URL_PROGRAM."'</script>"; ?>
<div class="title-module">
  <h3>Metode Pengiriman</h3>
</div>
<div class="clearfix"></div>
   <div class="isi-konten">
       <div id="hasil" style="display: none;"></div> 
	   
		   <form method="POST" name="frmmetode" id="frmmetode" action='<?php echo URL_PROGRAM."cart/metode"?>'>
		    <input type="hidden" id="url_redirect" value="<?php echo URL_PROGRAM."cart/sukses"?>">
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
						<td colspan="6" class="text-center">Tidak ada belanja</td>
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
					   //$gbr 		 = $c['gbr'];
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
				<?php $i++; } ?>
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
			<div class="text-right"><h2><b>Sub Total : </b><?php echo $dtFungsi->fFormatuang($subtotal) ?></h2></div>
		
		   </div>
		</div>
		<?php } ?>
	      <?php $zloop = 0; ?>
		  <?php $logoserv = array() ?>
		  <?php foreach($shipping as $ship) { ?>
		  <?php $servis = $dtShipping->getServis($ship['tabel']) ?>
		   
		   <table class="table table-bordered">
			  
			  <?php foreach($servis as $serv) { ?>
			  <?php
			     
					/*if($ship['tabeldiskon'] != Null || $ship['tabeldiskon'] != '') {
					    $zdizkon = explode("::",$ship['tabeldiskon']);
						$tabel = $zdizkon[0];
						$fieldambil = $zdizkon[1];
						$where = " $zdizkon[2]='".$serv['id']."' AND $zdizkon[3]=1";
					    
						$dtdiskon = $dtFungsi->fcaridata2($tabel,$fieldambil,$where);
						$dtdiskon['$fieldambil'] = isset($dtdiskon['$fieldambil']) ? $dtdiskon['$fieldambil'] :0;
						$diskon = $dtdiskon['$fieldambil'] / 100;
					} else {
					    $diskon = 0;
					}
					*/
					$diskon = 0;
			  ?>
			  <?php $tarif = $dtShipping->getTarif($serv['id'],$frmnegara,$frmpropinsi,$frmkabupaten,$frmkecamatan,$totberat,$serv['minkilo'],$ship['tabeltarif'],$ship['detek_kdpos'],$ship['nama'])?>
		      
			  <?php $tarif[4] = $tarif[4] - ($tarif[4]*$diskon); ?>
			  <?php $tarif[1] = $tarif[1] - ($tarif[1]*$diskon); ?>
			  <?php
			  $ketservis = '';
			  if($ship['nama'] == 'JNE') {
			    if($serv["minkilo"] > 0) {
				   $ketservis = '('.number_format((int)$totberat/1000,2,",",".").' Kg, dibawah '.number_format($serv["minkilo"],0,",",".") .' - '.number_format($serv["minkilo"] + 0.3,2,",",".") .' Kg akan dibulatkan '. $serv['minkilo'] .' Kg) '. number_format($tarif[3],0,",",".") .' x '.$dtFungsi->fFormatuang($tarif[4]);
                } 
			  }
			  ?>
			  <?php if($tarif[1] > 0 || $ship['tabeltarif'] == '') { ?>
			  <?php if(!in_array($ship['logo'],$logoserv)) { ?>
			  <?php $logoserv[] = $ship['logo'] ?>
			    <tr>
				   <td colspan="2"><img src="<?php echo URL_IMAGE.$ship['logo'] ?>"></td>
				</tr>
			  <?php } ?>
  				<tr>
				    <td style="width: 85%">
					<div class="radio">
					   <label>
						<input type="radio" name="serviskurir" id="serviskurir" value="<?php echo $serv['id']?>:<?php echo $ship['nama'] ?>"><b><?php echo $serv['nama']?></b>
						
				       </label>
				    </div>
				   </td>
				   <td align="right">
				   <?php if($tarif[1] > 0 ) {?>
				   <b><span class="hijau"><?php echo $dtFungsi->fFormatuang($tarif[1]) ?></span></b>
				   <?php } else { ?>
				   <b><span class="merah">Konfirmasi Admin</span></b>
				   <?php } ?>
				   </td>
				</tr>
				  <?php if($serv['keterangan'] != '') { ?>
				<tr>
				    <td><b><?php echo trim($serv['keterangan']) ?></b></td>
				    <td></td>
				</tr>
				<?php } ?>
				<?php } ?>
				<?php //} ?>
				<?php } ?>
			</table>
			
		   <?php } ?>
           
			<?php if($grupreseller['cg_deposito'] == '1' ) { ?>
			<?php if($reseller['cd_deposito'] > 0 ) { ?>
			<div class="alert alert-info">
			  Catatan : <br>
			  Anda memiliki deposito sebesar <?php echo $dtFungsi->fuang($reseller['cd_deposito']) ?>
			</div>
			<?php } ?>
			<?php } ?>
			<div class="clearfix"></div>
	        <div class="col-md-6 text-left"><div class="row"><a href="<?php echo URL_PROGRAM.'cart/kasir' ?>" id="btnform" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-tasks"></span> Kembali ke Form</a></div></div>
			<div class="col-md-6 text-right"><div class="row"><a id="btnnext" href="javascript:void(0)" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-briefcase"></span> Lanjutkan</a></div></div>
	  </form>
</div>

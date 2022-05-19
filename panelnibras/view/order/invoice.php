 <div style="page-break-after: always;">
 <div style="min-height:900px"> 
  <div class="header" style="background-color:#eee">
  <h1>NOTA PEMBAYARAN</h1>
  <h2>ID ORDER : <?php echo $order['pesanan_no'] ?></h2>
  </div>
  <table class="store">
	
    <tr>
	  <td valign="top" width="116px"><img src="<?php echo URL_PROGRAM_ADMIN.'images/logoinvoice.jpg' ?>"></td>
      <td valign="top" width="250px"><b><?php echo $datasetting['toko_nama']; ?></b><br />
        <?php echo $datasetting['toko_alamat']; ?><br />
        Telp. <?php echo $datasetting['toko_telp']; ?><br />
        <?php if ($datasetting['toko_fax']) { ?>
        Fax .<?php echo $datasetting['toko_fax']; ?><br />
        <?php } ?>
		<?php echo $datasetting['url_web']; ?>
	   </td>
      <td align="right" style="padding-left:30px" valign="top" width="480px">
	   <table>
	   <tr><td>
	    <b>Kepada Yth : </b><br>
		<b><?php echo stripslashes($dataalamat[1])?></b><br>
		<?php echo $dataalamat[4]?><br>
		Kel. <?php echo $dataalamat[9]?>, Kec. <?php echo $dataalamat[8]?>, <br>
		<?php echo $dataalamat[7]?>, <?php echo $dataalamat[6]?>  <br> 
	
		<?php echo $dataalamat[5]?> <?php echo $dataalamat[10]?><br>
		Hp. <?php echo $dataalamat[3]?><?php if($dataalamat[2] !='') echo ', Telp. '.$dataalamat[2] ?>
		</td></tr>
		</table>
	  </td>
    </tr>
  </table>
  
  <table class="product">
    <tr class="heading">
      <td width="250px">Nama Barang/Keterangan</td>
	  <td width="150px">Warna</td>
	  <td width="10px">Jumlah</td>
	  <td width="100px" align="right">Harga Satuan</td>
	  <td width="100px" align="right">Harga Grosir</td>
	  <td width="100px" align="right">Diskon</td>
	  <td width="100px" align="right">Total</td>
	  <td align="center" width="25px">Cek List</td>
    </tr>
    <?php 
	    $berat = 0;
		$totdiskon = 0;
	    foreach($datadetail as $dt) {
		   //$hrgjual = $dtFungsi->fcaridata('_produk','hrg_jual','idproduk',$dt['produkid']);
		   $diskon = $dt['satuan'] - $dt['harga'];
		   $totdiskon += $diskon;
		   $berat += $dt['berat'];
     ?>
    <tr>
      <td><b><?php echo $dt['nama_produk'] ?></b>
				<?php if($dt['warnaid'] || $dt['ukuranid']) {?>
				<br><small>
				
				<?php echo 'Ukuran :'.$dtFungsi->fcaridata('_ukuran','ukuran','idukuran',$dt['ukuranid']);?>
				</small>
				<?php } ?>
	  </td>
	  <td><?php echo $dtFungsi->fcaridata('_warna','warna','idwarna',$dt['warnaid']);?></td>
	  <td align="center"><?php echo $dt['jml'] ?></td>
	  <td align="right"><?php echo  $dtFungsi->fuang($dt['satuan']);?></td>
      <td align="right"><?php echo $dtFungsi->fuang($dt['harga']) ?></td>
	  <td align="right"><?php echo $dtFungsi->fuang($diskon) ?></td>
      <td align="right"><?php echo $dtFungsi->fuang(((int)$dt['jml']) * (int)$dt['harga']) ?></td>
	  <td></td>
    </tr>
    <?php } ?>
	<?php
	    $totberat = $berat/1000;
	    if($totberat < 1) $totberat = ceil($totberat);
		if($minkilo > $totberat) $totberat = $minkilo;
		if($totberat > 1) {
		    $berats = floor($totberat);
			$jarakkoma = $totberat - $berats;
			if($jarakkoma > 0.3) $totberat = ceil($totberat);
			else $totberat = floor($totberat);
		} else {
		   $jarakkoma = 0;
		}
	?>
    <tr>
		<td colspan="5" class="right"><b>Total Harga Barang</b></td>
		<td class="right"><b><?php echo $dtFungsi->fuang($totdiskon) ?></b></td>
		<td class="right"><b><?php echo $dtFungsi->fuang($order[4]) ?></b></td>
		<td style="border-right:1px solid #FFF;border-bottom:0px solid #000"></td>
	</tr>
	<tr>
	    <td class="right">Ongkos Kirim <?php echo $namaservis ?></td>
		<td></td>
		<td align="center"><?php echo $berat/1000 ?> Kg </td>
		<td class="right"><?php echo $dtFungsi->fuang($order[12]) ?></td>
		<td></td>
		<td></td>
		<td class="right"><?php echo $dtFungsi->fuang($order[5]) ?></td>
		<td style="border-right:1px solid #FFF;border-bottom:0px solid #000"></td>
	</tr>
	<?php if($order[14] > 0) {?>
	<tr>
	    <td colspan="6" class="right"><b>INFAQ</b></td>
		<td class="right"><?php echo $dtFungsi->fFormatuang($order[14]) ?></td>
		<td style="border-right:1px solid #FFF;border-bottom:0px solid #000"></td>
	</tr>
	<?php } ?>
	<?php
		//if((int)$biayaregis[0] > 0) { 
	   //$regis = $biayaregis[0];
	   
    ?>
	<!--
	<tr>
	    <td colspan="3" class="bariskanan"><b>BIAYA REGISTER</b></td>
		<td class="bariskanan"><?php echo $dtFungsi->fFormatuang($regis) ?></td>
	</tr>
	-->
	<?php //} ?>
	<?php  if((int)$order[19] > 0) { ?>
			<tr>
			    <td colspan="6" class="right"><b>POTONGAN TABUNGAN</b></td>
				<td class="right">(<?php echo $dtFungsi->fFormatuang($order[19]) ?> )</td>
				<td style="border-right:1px solid #FFF;border-bottom:0px solid #000"></td>
			</tr>
			<?php } ?>
	<tr>
	    <td colspan="6" class="right"><b>Total Biaya yang sudah ditransfer</b></td>
		<td class="right"><b><?php echo $dtFungsi->fuang(((int)$order[5] + (int)$order[4] + + (int)$order[14])-(int)$order[19]) ?></b></td>
		<td style="border-right:1px solid #FFF;border-bottom:0px solid #000"></td>
	</tr>
	
  </table>
  <table class="store">
     <tr>
	    <td colspan="2" align="center">
		Terimakasih Anda telah belanja di Hijab Supplier. Semoga berkah dan bisa menjadi langganan
		</td>
	 <tr>
     <tr>
	    <td width="80%"></td>
	    <td width="20%" align="center">
		
		Jakarta, <?php echo $dtFungsi->ftanggalBulan1($tglprint[0]) ?> 
		<br>
		<?php echo $datasetting['toko_nama']; ?>
		<br>
		<br>
		<br>
		<br>
		<br>
		(an&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
		</td>
	 </tr>
  </table>
  
</div>
<!--<div class="footer">Terimakasih Anda telah belanja di Hijab Supplier. Semoga berkah dan bisa menjadi langganan </div>-->
</div>
<div style="page-break-after: always;">
<br>
<table style="width:100%;border:1px solid #000">
   <tr>
		<td valign="top" style="width:50%;padding-left:10px">
		 <table class="address">
			<tr>
				<td><h3>Pengirim</h3>
		<?php 
		if ($dataalamat[1] != $nmreseller && $dropship=='1') {
		    if($tokoreseller != '-') {
				$nama = stripslashes($tokoreseller);
			} else {
				$nama = stripslashes($dataalamat[11]);
			}
			/*
			$alamat  = $dataalamat[14].'<br>'.$dataalamat[16].', '.$dataalamat[17].'<br>';
			$alamat .= 'Kec. '.$dataalamat[18].', Kel. '.$dataalamat[19].'<br>';
			$alamat .= $dataalamat[15].' '.$dataalamat[20].'<br>';
			$alamat .= 'Hp. '.$dataalamat[13];
			if($dataalamat[12] !='') {
				$alamat .= 'Telp. '.$dataalamat[12];
			}
			*/
			$alamat = 'Hp. '.$dataalamat[13];
			if($dataalamat[12] !='') {
				$alamat .= 'Telp. '.$dataalamat[12];
			}
		} else {
			$nama = $datasetting['toko_nama'];
			$gbrlogo = URL_PROGRAM_ADMIN.'images/logoinvoice.jpg';
			$alamat =  "<img src=\"".$gbrlogo."\" width=\"60\" height=\"60\" style=\"float:left;padding-right:10px\">".$datasetting['toko_alamat'].'<br />Telp. '.$datasetting['toko_telp'].'<br />';
			if ($datasetting['toko_fax']) { 
				$alamat .= 'Fax . '.$datasetting['toko_fax'].'<br />';
			} 
			$alamat .= $datasetting['url_web'];
		}
		echo '<b>'.strtoupper($nama).'</b><br>';
		echo $alamat.'<br><br>';
		echo $namaservis.' '.$dtFungsi->fuang($order[5]);
		?>
		</td>
		  </tr>
		 </table>
	</td>
		<td valign="top" style="width:50%;border-left:1px solid #000;padding-left:10px">
    		<table class="address">
			  <tr>
				<td><h3>Penerima</h3>
				<b><?php echo strtoupper(stripslashes($dataalamat[1]))?></b><br>
				<?php echo $dataalamat[4]?><br>
				<?php echo $dataalamat[9]!='' ? 'Kel. '.$dataalamat[9].', ':'';?><?php echo 'Kec. '.$dataalamat[8].',<br>'?>
				<!--Kel. <?php echo $dataalamat[9]?>, Kec. <?php echo $dataalamat[8]?>, <br>-->
				<?php echo $dataalamat[7]?>, <?php echo $dataalamat[6]?>  <br> 
				<?php echo $dataalamat[5]?> <?php echo $dataalamat[10]?><br>
				Hp. <?php echo $dataalamat[3]?><?php if($dataalamat[2] !='') echo ', Telp. '.$dataalamat[2] ?>
				</td>
			  </tr>
			</table>
				
		</td>
   </tr>
</table>
</div>
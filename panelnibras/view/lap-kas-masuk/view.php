<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    <div class="left">
			
		</div>
		<div class="right">
			<form name="frmcari" id="frmcari">
			Bulan
			<select id="bulan" name="bulan" class="selectbox">
			   <?php $nmbulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Otkober","November","Desember");?>
			   <?php for($i=1;$i < 13;$i++) {?>
				  <option value="<?php echo $i ?>" <?php if($bulan == $i ) echo 'selected' ?>><?php echo $nmbulan[$i-1] ?></option>
			   <?php } ?>
			</select>  
			Tahun <input type="text" class="inputbox" style="width:100px" name="tahun" id="tahun" value="<?php echo $tahun ?>">
			<a class="tombols" id="tblcari">Search</a>
			<a class="tombols" id="tblexport" onclick="location='<?php echo URL_PROGRAM_ADMIN."view/".folder?>'+'/exportexcel.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val()">Export to excel</a>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			     <th style="text-align:center" width="10px">No</th>
				 <th style="text-align:center">Tgl</th>
				 <th style="text-align:right">Order ID</th>
				 <th style="text-align:right">Total Belanja</th>
				 <th style="text-align:right">Potongan</th>
				 <th style="text-align:right">Total Barang</th>
				 <th style="text-align:right">Ongkos Kirim</th>
				 <th style="text-align:right">Infaq</th>
				 <th style="text-align:right">Keuntungan</th>
				 <th style="text-align:right">Deposit</th>
				 <th style="text-align:right">Kekurangan</th>
				 <th style="text-align:right">Kas Masuk</th>
			  </tr>
			  
			  <?php 
			      $no = 1;
				  $jmlOrder = 0;
				  $totBelanja = 0;
				  $totBarang = 0;
				  $totInfaq = 0;
				  $totLaba = 0;
				  $totDeposit = 0;
				  $totKekurangan = 0;
				  $totKM = 0;
				  $hrgpotogan = 0;
				  $totPotongan = 0;
			      foreach($dataview as $datanya) {
				     
			          $laba = ($datanya["subtotal"] - $datanya["hrgbeli"]) - $datanya["kekurangan"];
					  $total= ($datanya["subtotal"] + $datanya["ongkir"] + $datanya["infaq"]);
					  $hrgpotongan = $datanya["hrgsatuan"] - $datanya["subtotal"];
					  
					  $totBelanja = $totBelanja + $datanya["hrgsatuan"];
					  $totBarang = $totBarang + (int)$datanya["hrgbeli"];
					  $totInfaq = $totInfaq + $datanya["infaq"];
					  $totLaba = $totLaba + $laba;
					  $totDeposit = $totDeposit + $datanya["penambahan"];
					  $totPotongan = $totPotongan + $hrgpotongan;
					  //$KM	= ($total + $datanya["penambahan"]) - $datanya["kekurangan"];
					  $KM	= ((int)$datanya["hrgbeli"] + (($datanya["subtotal"] - $datanya["hrgbeli"])-$datanya["kekurangan"]) + $datanya["infaq"] + $datanya["ongkir"]) - $datanya["penambahan"];
					  $totKekurangan = $totKekurangan + $datanya["kekurangan"];
					  //{ Total barang + [(Total belanja - Total barang)-kekurangan] + Infak + Ongkir } - deposit
					  $totKM = $totKM + $KM;
					  $jmlOrder = $jmlOrder+1;
					  
					  /*
					  $laba = ($datanya["hrgsatuan"] - $datanya["hrgbeli"]) - $datanya["kekurangan"];
					  $total= ($datanya["hrgsatuan"] + $datanya["ongkir"] + $datanya["infaq"]);
					  
					  $totBelanja = $totBelanja + $datanya["hrgsatuan"];
					  $totBarang = $totBarang + (int)$datanya["hrgbeli"];
					  $totInfaq = $totInfaq + $datanya["infaq"];
					  $totLaba = $totLaba + $laba;
					  $totDeposit = $totDeposit + $datanya["penambahan"];
					  //$KM	= ($total + $datanya["penambahan"]) - $datanya["kekurangan"];
					  $KM	= ((int)$datanya["hrgbeli"] + (($datanya["hrgsatuan"] - $datanya["hrgbeli"])-$datanya["kekurangan"]) + $datanya["infaq"] + $datanya["ongkir"]) - $datanya["penambahan"];
					  $totKekurangan = $totKekurangan + $datanya["kekurangan"];
					  //{ Total barang + [(Total belanja - Total barang)-kekurangan] + Infak + Ongkir } - deposit
					  $totKM = $totKM + $KM;
					  $jmlOrder = $jmlOrder+1;
					  */
			  ?>
			  <tr>
			    <td style="text-align:center"><?php echo $no++?></td>
				<td style="text-align:center"><?php echo $dtFungsi->ftanggalFull2($datanya['tglkomplet'])?></td>
				<td style="text-align:right"><?php echo $datanya["noorder"]?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["hrgsatuan"])?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($hrgpotongan)?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["hrgbeli"])?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["ongkir"])?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["infaq"])?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($laba)?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["penambahan"])?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($datanya["kekurangan"])?></td>
				<td style="text-align:right"><?php echo $dtFungsi->fuang($KM) ?></td>
			  </tr>
			  <?php } ?>
			  
			</table>
			
		</div>
		<div style="padding-top:5px;" align="right">
			<table class="tabel" width="70%" border="1" cellspacing="0" cellpadding="0">
			    <tr>
				   <th>Total Order</td>
				   <th>Total Belanja</td>
				   <th>Total Potongan</td>
				   <th>Total Barang</td>
				   <th>Total Infaq</td>
				   <th>Total Keuntungan</td>
				   <th>Total Deposit</td>
				   <th>Total Kekurangan</td>
				   <th>Total Kas Masuk</td>
				 </tr>
				 <tr>
				   <td style="text-align:center"><?php echo $jmlOrder ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totBelanja) ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totPotongan) ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totBarang)?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totInfaq) ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totLaba) ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totDeposit) ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totKekurangan) ?></td>
				   <td style="text-align:right"><?php echo $dtFungsi->fuang($totKM) ?></td>
				 </tr>
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
});
function caridata(){
   var bulan = escape($('#bulan').val());
   var tahun = escape(parseInt($('#tahun').val()));
   if(tahun.length < 4) {
       alert('Masukkan Tahun');
	   $('#tahun').focus();
	   return false;
   }
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?bulan=' ?>'+bulan+'&tahun='+tahun);

}

function cetakpdf(){
	window.open('<?php echo URL_PROGRAM_ADMIN."view/".folder?>/cetakpdf.php?bulan='+$('#bulan').val()+'&tahun='+$('#tahun').val());
}

</script>


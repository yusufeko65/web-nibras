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
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<table class="form">
		   <tr>
				<td><h2>Grafik 10 Produk Terlaris <?php echo $thnnow ?> </h2></td>
			</tr>
			<tr>
			    <td>
				   <?php 
				   
				   $jmldata = count($dataview); 
				   $strXML  = "";
				   $x=0;
				   $warna = array("AFD8F8","ff7e00","7ba215","646464","cccc33","47a8c6","eac2a9","8273aa","a63e00","ff8740","a68600","a68192");
				   $blns = array("Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agus","Sep","Okt","Nov","Des");
				   $strXML .= "<graph bgcolor='FFFFFF' bgAlpha='50' caption='Grafik 10 Produk Terlaris ".$blns[$bulan-1]. " ".$tahun."' subCaption=''  decimalPrecision='0' showNames='1' numberSuffix=' Pcs' formatNumberScale='0' rotateNames='1'>";
				   for($i=0;$i<$jmldata;$i++){
				      $produk = $dataview[$i]['nama'];
					  $jml = $dataview[$i]['jml'];
					  $strXML .= "<set name='$produk' value='".$jml."' color='$warna[$i]' hoverText='$produk'/>";
					 }
				   $strXML .= "</graph>";
					echo renderChartHTML("../_libchart/FCF_Column2D.swf", "", $strXML, "diagram10produkterlaris", 900,400);
				   ?>
				   
				</td>
			</tr>
		</table>
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

</script>


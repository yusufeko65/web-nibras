<div class="kotakplat">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>" target="cetak-frame">
			<input type="hidden" id="aksi" name="aksi" value="cetak">
			<fieldset>
			<table class="form">
				<tr><td width="15%">Bulan</td>
					<td width="85%">
					<select id="bulan" name="bulan" class="selectbox">
					<?php $nmbulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Otkober","November","Desember");?>
					<?php for($i=1;$i < 13;$i++) {?>
					  <option value="<?php echo $i ?>"><?php echo $nmbulan[$i-1] ?></option>
					<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
				    <td>Tahun</td>
					<td><input type="text" class="inputbox" style="width:100px" name="tahun" id="tahun">
				</tr>
				<tr><td></td><td><input type="submit" id="tombolcetak" class="tombolsubmit" value="Cetak"></td>
				</tr>
			</table>
			<div id="hasil" style="display: none;"></div> 
			<iframe name="cetak-frame" id="cetak-frame" style="display:none"></iframe> 
		    </fieldset>	
		</form>
	</div>
</div>
<script>
$(function(){
	$("#frmdata").submit(function(){
	    var tinggi = $('html').height();
		var lebar  = $('html').width();
		$('#tombolcetak').after('<span class="loading"><img src="../images/22.gif" style="padding-left: 5px;" /> Tunggu Sebentar..</span>');
		$('#cetak-frame').height(tinggi + 'px');
		$('#cetak-frame').width('100%');
		$('#cetak-frame').show();
		var cek = checkdata();
		if(!cek) {
		   return false;
		} else {
		   $('.loading').remove();
		   return true;
		}
	 });
});
function checkdata(){
  var tahun = $('#tahun').val();
  var tinggi = $('html').height();

   if(tahun.length==0 || tahun.length > 4){
	$('#tahun').focus();
	$('#hasil').html("<div class=\"warning\"> Masukkan Tahun</div>");
	$('#hasil').show(500);
	$('.loading').remove();	
	return false;
  }
 
  return true;
}
function suksesdata(pesan){
	hasilnya = pesan.split("|");
	$('#hasil').html(hasilnya[2]);
	$('#hasil').show(0);
	if(hasilnya[0]=="sukses" && hasilnya[1]=="input") kosongform();
	$('.loading').remove();
	return false;
}
</script>
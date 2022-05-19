<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    <div class="left">
			<a class="tombols merah" onclick="hapusdata()">X Hapus</a>
			<a onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder."/?op=add"?>')" class="tombols biru">+ Tambah</a>
		</div>
		<div class="right">
			<form name="frmcari" id="frmcari">
			PENCARIAN
			<input id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari']:'' ?>">  
			<a class="tombols" id="tblcari">Search</a>
			<a class="tombols" id="tblreset" >Refresh</a>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				 <th width="13"><input type="checkbox" class="chk" id="checkall" onchange="cekall()" name="checkall" value="ON" /></th>
				 <th>Nama Banner</th>
				 <th>Gambar</th>
				 <th>Ukuran</th>
				 <th>Slot</th>
				 <th width="13">Tampil</th>
       			 <th width="13" class="ac">Action</th>
			  </tr>
			  <?php foreach($ambildata as $datanya) {?>
			  <?php if($b % 2 == 0) $kelasgrid='';
				    else $kelasgrid="odd";
				   
				    $b=$b+1;
			   ?>
			  <tr <?php echo $kelasgrid ?>>
				<td><input type="checkbox" class="chk" value="<?php echo $datanya['idbanner']?>" /></td>
				<td><?php echo $datanya["nama_banner"]?></td>
				<td><?php if($datanya['gbr_banner'] != '') { ?>
				<img src="<?php echo URL_IMAGE.'_other/other_'.$datanya["gbr_banner"]?>" style="max-width:60%"><?php } ?></td>
				<td><?php echo $datanya["panjang_banner"]?> x <?php echo $datanya["lebar_banner"]?></td>
				<td><?php echo $datanya["slot_banner"]?></td>
				<td><?php if($datanya['tampil']=='1') echo 'Ya';
				          else echo 'Tidak';
					?>
				 </td>
				<td><a class="ico edit" onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=".$datanya['idbanner']?>')">Edit</a></td>
			  </tr>
			  <?php } ?>
			</table>
			<?php if($total>0) { ?>
			<!-- Pagging -->
			<div class="pagging">
				<div class="left">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
				<div class="right">
				   <?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage) ?>
				</div>
			</div>
			<!-- End Pagging -->
			<?php } ?>
		</div>
		<!-- Table -->
	</div>
</div>
<script>
$(function(){
   $("#datacari").focus();
   $('#tblreset').click(function(){
        tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
		return false;
   });
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
   var zdata = escape($('#datacari').val());
   tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata);

}
function hapusdata(){
	var ids = [];
	var dataId;
	var datahapus;
	$('.chk').each(function () {
    if (this.checked) {
		if($(this).val()!="ON"){
			//ss=ss+$(this).val()+",";
			ids.push($(this).val());
		}
	}
	});
	dataId = ids.join(':');
	datahapus = 'aksi=hapus&id=' + dataId;
	if(dataId==""){
	   alert('Tidak Ada Pilihan');
	   return false;
	} else {
	   var a = confirm('Apakah ingin menghapus data yang terpilih?');
	   if (a == true) {
			$.ajax({
				type: "POST",
				url: "<?php echo $_SERVER['PHP_SELF'] ?>",
				data: datahapus,
				success: function(msg){
					hasilnya = msg.split("|");
					if(hasilnya[0]=="gagal") alert('Error' + hasilnya[1]); 
					//window.location='<?php echo URL_PROGRAM_ADMIN.folder ?>';
					tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
					return false;
				},  
					error: function(e){  
					alert('Error: ' + e);  
				}  
			});  
		}
	}
}
</script>
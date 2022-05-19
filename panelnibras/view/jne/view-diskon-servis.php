<div id="hasil"></div>
<div class="kotakplat">
		<?php echo $dtFungsi->judulModul($judul,"data") ?>
	<div class="body">
	    <div id="toolbar">
	    <div class="left">
			<a class="tombols merah" onclick="hapusdata()">X Hapus</a>
			<a onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder."/?op=add-diskon-servis"?>')" class="tombols biru">+ Tambah</a>
		</div>
		<div class="right">
			<!--<form method="GET" name="frmcari" id="frmcari" action="<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>">-->
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
				 <th width="3%"><input type="checkbox" class="chk" id="checkall" onchange="cekall()" name="checkall" value="ON" /></th>
				 <th width="50%" class="ac">Servis</th>
				 <th width="40%" class="ac">Nilai Diskon</th>
       			 <th width="7%" class="ac">Action</th>
			  </tr>
			  <?php foreach($ambildata as $datanya) {?>
			  <?php if($b % 2 == 0) $kelasgrid='';
				    else $kelasgrid="odd";
				   
				    $b=$b+1;
			   ?>
			  <tr <?php echo $kelasgrid ?>>
				<td><input type="checkbox" class="chk" value="<?php echo $datanya['idservisdisk']?>" /></td>
				<td class="bariskiri"><?php echo $datanya["servis_nama"]?></td>
				<td class="bariskanan">
				<?php echo $datanya["jml_disk"]?> %

				</td>
				<td><a class="ico edit" onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit-diskon-servis&pid=".$datanya['idservisdisk']?>')">Edit</a></td>
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
			ids.push($(this).val());
		}
	}
	});
	dataId = ids.join(':');
	datahapus = 'aksi=hapusdiskonservis&id=' + dataId;
	if(dataId==""){
	   alert('Tidak Ada Pilihan');
	   return false;
	} else {
	   var a = confirm('Apakah ingin menghapus data yang terpilih?');
	   if (a == true) {
			$.ajax({
				type: "POST",
				url: "<?php echo $_SERVER['PHP_SELF']?>",
				data: datahapus,
				success: function(msg){
					hasilnya = msg.split("|");
					if(hasilnya[0]=="gagal") alert(hasilnya[1]); 
					//window.location='<?php echo URL_PROGRAM_ADMIN.folder ?>';
					tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/?op=view-diskon-servis' ?>');
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
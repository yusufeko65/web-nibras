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
			<form method="GET" name="frmcari" id="frmcari" action="<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>">
			PENCARIAN
			<input id="datacari" name="datacari" id="datacari">  
			<a class="tombols" id="tblcari" onclick="document.frmcari.submit()">Search</a>
			<?php if($cari!='') echo '<a class="tombols" id="tblreset" >Reset</a>' ?>
			</form>
		</div>
		<div class="clear"></div>
		</div>
		<div class="table">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				 <th width="13"><input type="checkbox" class="chk" id="checkall" onchange="cekall()" name="checkall" value="ON" /></th>
				 <th>Propinsi</th>
				 <th>Negara</th>
       			 <th width="13" class="ac">Action</th>
			  </tr>
			  <?php foreach($ambildata as $datanya) {?>
			  <?php if($b % 2 == 0) $kelasgrid='';
				    else $kelasgrid="odd";
				   
				    $b=$b+1;
			   ?>
			  <tr <?php echo $kelasgrid ?>>
				<td><input type="checkbox" class="chk" value="<?php echo $datanya['provinsi_id']?>" /></td>
				<td><?php echo $datanya["provinsi_nama"]?></td>
				<td><?php echo $datanya["negara_nama"]?></td>
				<td><a class="ico edit" onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=".$datanya['provinsi_id']?>')">Edit</a></td>
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
   $('#tblreset').click(function(){
        tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
		return false;
   });
});
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
					if(hasilnya[0]=="gagal") alert(hasilnya[1]); 
					window.location='<?php echo URL_PROGRAM_ADMIN.folder ?>';
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
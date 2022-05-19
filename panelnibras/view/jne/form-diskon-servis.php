<div class="kotakplat" style="width:700px;margin: 10px auto">
	<?php echo $dtFungsi->judulModul($judul,"form") ?> 
	<div class="body">
		<form method=POST name=frmdata id=frmdata onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
			<input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
			
			<fieldset>
			  <table class="form">
			   
				<tr>
				   <td>Nilai Diskon (%)</td>
				   <td><input type="text" id="jmldisk" name="jmldisk" class="inputbox elmi" style="width:100px" value="<?php echo $jmldisk ?>"> </td>
				</tr>
				<tr>
				   <td>Jenis Servis</td>
				   <td>
				      <select id="jservis" name="jservis" class="elmi selectbox" style="width:200px" <?php echo $readonly ?>>
					     <?php $dtservis = $dtJne->getServisJne(); ?>
						 <?php foreach($dtservis as $dt ) { ?>
						 <option value="<?php echo $dt['id'] ?>" <?php if($dt['id']==$servisid) echo "selected='selected'";  ?>><?php echo $dt['nm'] ?></option>
						 <?php } ?>
					  </select>
				   </td>
				</tr>
				<tr>
					<td width="20%">Status</td>
					<td width="80%">
					    <select id="stsdiskon" name="stsdiskon" class="selectbox">
						   <option value="1" <?php if($stsdisk=='1') echo "selected" ?>>Enable</option>
						   <option value="0" <?php if($stsdisk=='0') echo "selected" ?>>Disable</option>
					    </select> 
					</td>
					
				</tr>
				</table>
			</fieldset>
			
			<fieldset>
			  <table class="form">
				<tr><td colspan="2" align="right">
						<?php echo $dtFungsi->tombol("simpan") ?>
						<a id="tombollihat" href="javascript:void(0)" onclick="tampilkan('<?php echo URL_PROGRAM_ADMIN.folder .'/?op=view-diskon-servis'?>')" class="tombolview">Lihat Data</a>
					</td>
				</tr>
			</table>
			</fieldset>
			<div id="hasil" style="display: none;"></div> 
			<span id="waiting" style="display: none"><?php echo WAITING_SAVE ?></span>
		</form>
	</div>
</div>
<script type="text/javascript">
 
  var action = $('#frmdata').attr('action');
  jQuery(document).ready(function(){
	$('#jmldisk').focus();

  });

function kosongform(){
  $('#jmldisk').focus();
  $('.elmi').each(function () {
	$(this).val("");
  });
   $('#jservis').prop("selected", false).trigger('chosen:updated');
   $('#jservis').val("").trigger('chosen:updated');

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
	var jmldisk     = $('#jmldisk').val();
	var pesan       = "";
	var aksi        = $('#aksi').val();
	var iddata      = $('#iddata').val();
	var servis 		= $('#jservis').val();


	if(jmldisk=='' || isNaN(jmldisk)){
		$('#jmldisk').focus();
		$('#hasil').html("<div class=\"warning\"> Masukkan Jumlah Diskon JNE </div>");
		$('#hasil').show(500);
		return false;
	}
	if(servis==''){
	    $('#hasil').html("<div class=\"warning\"> Masukkan Servis JNE </div>");
		$('#hasil').show(500);
	}

    
	$('#waiting').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: $('#frmdata').serialize(),
 		cache: false,
    	success: function(msg){
	
			$('#waiting').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').html(hasilnya[2]);
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses" && hasilnya[1]=="input") kosongform();
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}
</script>

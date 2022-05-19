$(function(){
	$('#grup').focus();
	$("#tabs li").click(function() {
		//	First remove class "active" from currently active tab
		$("#tabs li").removeClass('active');

		//	Now add class "active" to the selected/clicked tab
		$(this).addClass("active");

		//	Hide all tab content
		$(".tab_content").hide();

		//	Here we get the href value of the selected tab
		var selected_tab = $(this).find("a").attr("href");

		//	Show the selected tab content
		$(selected_tab).fadeIn();

		//	At the end, we add return false so that the click on the link is not executed
		return false;
	});
});

function kosongform(){
	$('#grup').focus();
	$('#frmdata')[0].reset();
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
	var grup = $('#grup').val();
    var keterangan = $('#keterangan').val();
    
    var min_beli = $('#min_beli').val();
	var diskon = $('#diskon').val();
    var minbeli_syarat = $('#minbeli_syarat').val();
	var chk_wjb;
    var urutan = $('#urutan').val();
	var urutan_lama = $('#urutan_lama').val();
	var chk_deposit;
	var chk_dropship;
	
    if($('#chk_wjb').prop("checked")) {
	   
	   chk_wjb = $('#chk_wjb').val();
	} else {
	   chk_wjb = '0';
	}
	
	if($('#chk_deposito').prop("checked")) {
	   chk_deposit = $('#chk_deposito').val();
	} else {
	   chk_deposit = '0';
	}
	
	if($('#chk_dropship').prop("checked")) {
	   chk_dropship = $('#chk_dropship').val();
	} else {
	   chk_dropship = '0';
	}
	
	var pesan = "";
	var aksi = $('#aksi').val();
	var iddata = $('#iddata').val();
	var action = $('#frmdata').attr('action');
    $('#btnsimpan').button("loading");
	
	datasimpan = "aksi="+aksi+"&grup="+grup+"&keterangan="+keterangan+
	             "&min_beli="+min_beli+
				 "&minbeli_syarat="+minbeli_syarat+"&chk_wjb="+chk_wjb+
				 "&urutan="+urutan+"&iddata="+iddata+
				 "&chk_deposito="+chk_deposit+"&urutan_lama="+urutan_lama+
				 "&diskon="+diskon+"&chk_dropship="+chk_dropship;
	
	if(grup==''){
		$('#grup').focus();
		$('#hasil').html("<div class=\"alert alert-danger\"> Masukkan Nama Grup </div>");
		$('#hasil').show(500);
		$('#btnsimpan').button("reset");
		return false;
	}
	
	
	$('#loadingweb').show(500);
	$.ajax({
 		type: "POST",
   		url: action,
    	data: datasimpan,
 		cache: false,
    	success: function(msg){
		    
		    $('#btnsimpan').button("reset");
			$('#loadingweb').hide(0);
			hasilnya = msg.split("|");
			$('#hasil').show(0).fadeOut(5000);
			if(hasilnya[0]=="sukses") {
  			   if(hasilnya[1]=="input") kosongform();
			   $('#hasil').html('<div class="alert alert-success">'+hasilnya[2]+'</div>');   
			} else {
			   $('#hasil').html('<div class="alert alert-danger">'+hasilnya[2]+'</div>');
			}
			return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}


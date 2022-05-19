var action = $('#frmdata').attr('action');
$(function(){
	$('#produsen').focus();
	$("#frmdata").submit(function(){
		$('#tombollihat').after('<span class="loading"><img src="../images/22.gif" style="padding-left: 5px;" /> Tunggu Sebentar..</span>');
		var cek = checkdata();
		if(!cek) return false;
		else return true;
	 });
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
$('#produsen').focus();
$('#produsen').val("");
$('#telp').val("");
$('#email').val("");
$('#alamat').val("");
$('#keterangan').val("");
$('#filelogo').val("");
$('#facebook').val("");
$('#web').val("");
}
function checkdata(){
  var produsen     = $('#produsen').val();
  var filelogo     = $('#filelogo').val();
  var filelama     = $('#filelama').val();
    
  if(produsen.length == 0){
	$('#produsen').focus();
	$('#hasil').html("<div class=\"warning\"> Masukkan Nama Produsen</div>");
	$('#hasil').show(500);
	$('.loading').remove();	
	return false;
  }
  
  //if(filelama==''){
  //  if(filelogo=='') {
//		$('#hasil').html("<div class=\"warning\"> Masukkan File Logo</div>");
	//	$('#hasil').show(500);
	//	$('.loading').remove();	
	//	return false
//	}
 // }
  
  return true;
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

function suksesdata(pesan){
	hasilnya = pesan.split("|");
	$('#hasil').html(hasilnya[2]);
	$('#hasil').show(0);
	if(hasilnya[0]=="sukses" && hasilnya[1]=="input") kosongform();
	$('.loading').remove();
	return false;
}
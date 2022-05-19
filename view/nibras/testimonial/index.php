<?php
$modul = isset($_GET['modul']) ? $_GET['modul']:'';
$dtCart 		= new controller_Cart();
$dtTestim 		= new controller_Testimonial();
$dtProduk 		= new controller_Produk();
include path_to_includes.'bootcart.php';
switch($modul){
  case "form":
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	      if(isset($_POST['knama'])) {
		      echo $dtTestim->simpandata();
			  
		  }
		  exit;
	  }
      $file= '/add-testim.php';
	  $script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/testimonial.js"></script>';
  break;
  default:
  case "view":
     $dataview  		= $dtTestim->tampildata();
     $total 	   		= $dataview['total'];
	 $baris 	   		= $dataview['baris'];
	 $page 	   		    = $dataview['page'];
     $jmlpage   		= $dataview['jmlpage'];
	 $ambildata 		= $dataview['rows'];
	 $dtPaging 		    = new Paging();
	 $linkpage 		    = '/';
     $linkcari 		    = '';
	 $file= '/view-testim.php';
	 $script='';
  break;
 
}

include DIR_THEMES."header.php";
include DIR_THEMES."left.php";
?>
 <div class="col-md-9">
  <!-- daftar artikel -->
  <div class="row">
     <?php if($file!='') include DIR_THEMES.$folder.$file; ?>
	 <div class="clearfix"></div>
	
  </div>
   <!-- @end daftar artikel -->
 </div>

<?php include DIR_THEMES."script.php";?>
<?php echo $script ?>
<?php include DIR_THEMES."footer.php";?>
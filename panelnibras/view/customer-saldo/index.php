<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "customer-saldo");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('customer-saldo','',0);


$dtReseller = new controllerCustomer();
$dtResellerGrup = new controllerCustomerGrup();

if(isset($_GET['loads'])) {
	switch($_GET['loads']) {
		case "customer":
			$dtReseller->customerAutocomplete();
			exit;
		break;
	}
}

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
	case "tambah":
		$dtReseller->simpandata('simpan');
		exit;
	break;
	case "ubah":
		echo $dtReseller->simpandata('ubah');
		exit;
	break;
	case "hapus":
		echo $dtReseller->hapusdata();
		exit;
	break;

	case "adddeposito":
		
		$dtReseller->simpandatadeposito('simpan');
		exit;
	break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Saldo Pelanggan';

$iddata = '';
$b = 1;
$lock = '';
$grupreseller['rs_grupnama'] = '';
$grupreseller['rs_frm_toko'] = '';
$reseller['cust_email'] = '';
$reseller['cust_pass'] = '';
$reseller['cust_grup_id'] = 0;
$reseller['cust_nama'] = '';
$reseller['cust_telp'] = '';
$reseller['cust_alamat'] = '';
$reseller['cust_negara'] = '33';
$reseller['cust_propinsi'] = '';
$reseller['cust_kabupaten'] = '';
$reseller['cust_kecamatan'] = '';
$reseller['cust_kelurahan'] = '';
$reseller['cust_kdpos'] = '';
$reseller['cust_newsletter'] = '';
$reseller['cust_approve'] = '1';
$reseller['cust_status'] = '1';


$datagrup = $dtResellerGrup->getResellerGrup();

//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtFungsi->cekHak("customer-saldo","view",0);
	    $dtPaging 	= new Paging();
		$dataview 	= $dtReseller->tampildatadeposito();
		$total 	  	= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
	    $ambildata	= $dataview['rows'];
		$cari 		= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$grup 		= isset($_GET['grup']) ? $_GET['grup']:'';
		
		$linkpage 	= '';
		if($cari!='') $linkpage .= '&datacari='.trim(strip_tags(urlencode($cari)));
		
		include "view.php"; 
	break;
	
	case "add":
		$dtFungsi->cekHak("customer-saldo","add",0);
		$modul = "adddeposito"; 
		include "form_add.php"; 
	break;
	
	case "saldo":
	   $dtFungsi->cekHak("customer-saldo","edit",0);
	   $modul = "adddeposito"; $iddata = $_GET["pid"];
	   $reseller = $dtReseller->dataResellerByID($iddata);
	   $dataview = $dtReseller->dataDeposito($iddata);
	   $totaldeposito = $dtReseller->totalDepositoById($iddata);
	   $linkpage 	= '';
	   $dtPaging 	= new Paging();
	   $total 	  	= $dataview['total'];
	   $baris 	  	= $dataview['baris'];
	   $page 	  	= $dataview['page'];
	   $jmlpage  	= $dataview['jmlpage'];
	   $datadeposit	= $dataview['rows'];
	   include "form.php";
	break;
	
	case "viewBukti"
		?>
			<div class="modal-dialog" style="width:60%">
				<div class="modal-content">
					<div class="modal-header">
						<a class="close" data-dismiss="modal">&times;</a>
						<h4 class="modal-title">Bukti Pembayaran</h4>
					</div>
					<div class="modal-body">
						<img style="width: 100%;border: 1px solid #666;border-radius: 5px;" src="<?php echo URL_IMAGE . '_other/other_' . $_POST['data'] ;?>">
					</div>
				</div>
			</div>
		<?php
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>
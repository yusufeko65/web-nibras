<?php
define("path_toincludes", "../../_includes/");
define("folder", "customer");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('customer','',0);


$dtReseller = new controllerCustomer();
$dtResellerGrup = new controllerCustomerGrup();
$dtPropinsi = new controllerPropinsi();
$dtKabupaten = new controllerKabupaten();
$dtKecamatan = new controllerKecamatan();
$datagrup = $dtResellerGrup->getResellerGrup();
$dataprop = $dtPropinsi->getPropinsi();

if(isset($_REQUEST['load'])) {
    $opt = '';
    switch($_GET['load']) {
		case "frmEditAlamat":
		case "frmAddAlamat":
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			$idcust = isset($_POST['idcust']) ? $_POST['idcust'] : '';
			$modul = isset($_POST['modul']) ? $_POST['modul'] : 'input';
			if($id != '') {
				if($modul == 'input') {
					$titleform = 'Tambah';
					$nama = '';
					$hp = '';
					$alamat = '';
					$propinsi = '';
					$kabupaten = '';
					$optkabupaten = '';
					$optkecamatan = '';
					$kelurahan = '';
					$kodepos = '';
					$default = '';
				} else {
					$titleform = 'Ubah';
					$dataalamat = $dtReseller->getAlamatCustomerByID($id);
					$nama = isset($dataalamat['ca_nama']) ? $dataalamat['ca_nama'] : '';
					$hp = isset($dataalamat['ca_hp']) ? $dataalamat['ca_hp'] : '';
					$alamat = isset($dataalamat['ca_alamat']) ? $dataalamat['ca_alamat'] : '';
					$propinsi = isset($dataalamat['ca_propinsi']) ? $dataalamat['ca_propinsi'] : 0;
					$kabupaten = isset($dataalamat['ca_kabupaten']) ? $dataalamat['ca_kabupaten'] : 0;
					$kecamatan = isset($dataalamat['ca_kecamatan']) ? $dataalamat['ca_kecamatan'] : 0;
					
					if($propinsi != '0' || $propinsi != '') {
						$datakabupaten = $dtKabupaten->getKabupatenByPropinsi($propinsi);
						$optkabupaten = '';
						foreach($datakabupaten as $kab) {
							if($kab['idk'] == $kabupaten) {
							   $selected = 'selected';
							} else {
							   $selected = '';
							}
							$optkabupaten .= '<option value="'.$kab['idk'].'"'.$selected.'>'.$kab['nmk'].'</option>';
						}
					}
					
					if($kabupaten != '0' || $kabupaten != '') {
						$datakecamatan = $dtKecamatan->dataKecamatanByKabupaten($kabupaten);
						$optkecamatan = '';
						
						foreach($datakecamatan as $kec) {
							if($kec['idn'] == $kecamatan) {
							   $selected = 'selected';
							} else {
							   $selected = '';
							}
							$optkecamatan .= '<option value="'.$kec['idn'].'"'.$selected.'>'.$kec['nmn'].'</option>';
						}
					}
					$kelurahan = isset($dataalamat['ca_kelurahan']) ? $dataalamat['ca_kelurahan'] : '';
					$kodepos = isset($dataalamat['ca_kodepos']) ? $dataalamat['ca_kodepos'] : '';
					$default = isset($dataalamat['ca_default']) ? $dataalamat['ca_default'] : '';
				}
				include DIR_INCLUDE."view/customer/formalamat.php"; 
			}
			exit;
		break;
		case 'kabupaten':
			$id = isset($_GET['propinsi']) ? $_GET['propinsi']:'';
			$opt = '<option value="0">- Kotamadya/Kabupaten -</option>';
			if($id != '' && $id != '0') {
				$datakabupaten = $dtKabupaten->getKabupatenByPropinsi($id);
				foreach($datakabupaten as $kab) {
					if($kab['idk'] == $id) {
					   $selected = 'selected';
					} else {
					   $selected = '';
					}
					$opt .= '<option value="'.$kab['idk'].'"'.$selected.'>'.$kab['nmk'].'</option>';
				}
			} 
		  
	   break;
	   case 'kecamatan':
	      $id = isset($_GET['kabupaten']) ? $_GET['kabupaten']:'';
		  $opt = '<option value="0">- Kecamatan -</option>';
          if($id != '' && $id != '0') {
			$datakecamatan = $dtKecamatan->dataKecamatanByKabupaten($id);
            foreach($datakecamatan as $kec) {
			    if($kec['idk'] == $id) {
				   $selected = 'selected';
				} else {
				   $selected = '';
				}
				$opt .= '<option value="'.$kec['idn'].'"'.$selected.'>'.$kec['nmn'].'</option>';
			}
		  }
	   break;
	}
    echo $opt;
	exit;
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
		$dtReseller->simpandata('ubah');
		exit;
	break;
	case "hapus":
		echo $dtReseller->hapusdata();
		exit;
	break;
	case "approve":
		echo $dtReseller->approvedata();
		exit;
	break;
	case "renew":
	   echo $dtReseller->renew();
	   exit;
	break;
	case "inputalamat":
		$dtReseller->simpanalamat("input");
		exit;
	break;
	case "updatealamat":
		$dtReseller->simpanalamat("update");
		exit;
	break;
	case "hapusalamat":
		$dtReseller->hapusalamat();
		exit;
	break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
	include(DIR_INCLUDE."header.php");
	include(DIR_INCLUDE."menu.php");
}
$judul = 'Pelanggan';

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




//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtFungsi->cekHak("customer","view",0);
	    $dtPaging 	= new Paging();
		$dataview 	= $dtReseller->tampildata();
		$total 	  	= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
	    $ambildata	= $dataview['rows'];
		$cari 		= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$grup 		= isset($_GET['grup']) ? $_GET['grup']:'';
		//$dropship 	= isset($_GET['dropship']) ? $_GET['dropship']:'';
		$approve 	= isset($_GET['approve']) ? $_GET['approve']:'';
		$linkpage 	= '';
		if($cari!='') $linkpage .= '&datacari='.trim(strip_tags(urlencode($cari)));
		if($grup!='') $linkpage .= '&grup='.trim(strip_tags(urlencode($grup)));
		//if($dropship!='') $linkpage .= '&dropship='.trim(strip_tags(urlencode($dropship)));
		if($approve!='') $linkpage .= '&approve='.trim(strip_tags(urlencode($approve)));
		
		include "view.php"; 
	break;
	case "view-notif":
	    //$dtFungsi->cekHak("reseller","view",0);
	    $dtPaging 	= new Paging();
		$dataview 	= $dtReseller->tampildatanotif();
		$total 	  	= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
	    $ambildata	= $dataview['rows'];
		$cari 		= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$grup 		= isset($_GET['grup']) ? $_GET['grup']:'';
		
		$linkpage 	= '';
		if($cari!='') $linkpage .= '&datacari='.trim(strip_tags(urlencode($cari)));
		if($grup!='') $linkpage .= '&grup='.trim(strip_tags(urlencode($grup)));
		
		
		include "view-notif.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("customer","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "info":
	case "edit": 
		$dtFungsi->cekHak("customer","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$reseller = $dtReseller->dataResellerByID($iddata);
		
		if($reseller){
		    $grupreseller = $dtResellerGrup->dataResellerGrupByID($reseller['cust_grup_id']);
			$dataalamat = $dtReseller->getAlamatCustomer($iddata);
			
			if($menupage == 'edit') {
			   include "form.php";
			} else {
			   $modul = "info";
			   include "info.php";
			}
		}
	break;
	case "deposito":
	   $dtFungsi->cekHak("customer","edit",0);
	   $modul = "adddeposito"; $iddata = $_GET["pid"];
	   $reseller = $dtReseller->dataResellerByID($iddata);
	   $datadeposit = $dtReseller->dataDeposito($iddata);
	   
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>
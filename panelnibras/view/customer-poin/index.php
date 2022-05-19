<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "customer-poin");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('customer-poin','',0);


$dtCustomer = new controllerCustomer();
$dtCustomerGrup = new controllerCustomerGrup();
$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
	case "tambah":
		echo $dtCustomer->simpandata('simpan');
		exit;
	break;
	case "ubah":
		echo $dtCustomer->simpandata('ubah');
		exit;
	break;
	case "hapus":
		echo $dtCustomer->hapusdata();
		exit;
	break;
  
   
	case "addpoin":
		echo $dtCustomer->simpandatapoin('simpan');
		exit;
	break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Poin Pelanggan';

$iddata = '';
$b = 1;
$lock = '';


$datagrup = $dtCustomerGrup->getResellerGrup();

//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtFungsi->cekHak("customer-poin","view",0);
	    $dtPaging 	= new Paging();
		$dataview 	= $dtCustomer->tampildatapoin();
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
		$dtFungsi->cekHak("customer-poin","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	
	case "poin":
	   $dtFungsi->cekHak("customer-poin","edit",0);
	   $modul = "addpoin"; $iddata = $_GET["pid"];
	   $customer = $dtCustomer->dataResellerByID($iddata);
	   $dataview = $dtCustomer->datapoin($iddata);
	   $totalpoin = $dtCustomer->totalPoinById($iddata);
	   $linkpage 	= '';
	   $dtPaging 	= new Paging();
	   $total 	  	= $dataview['total'];
	   $baris 	  	= $dataview['baris'];
	   $page 	  	= $dataview['page'];
	   $jmlpage  	= $dataview['jmlpage'];
	   $datadeposit	= $dataview['rows'];
	   include "form.php";
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>
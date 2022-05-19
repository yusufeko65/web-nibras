<?php
define("path_toincludes", "../../_includes/");
define("folder", "kategori");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('kategori','',0);

$dtKategori = new controllerKategori();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

if(isset($_GET['loads'])) {
	/*
	if($_GET['loads']=='kategori'){
		$dtKategori->getAutoCompleteKategori();
		exit;
	}
	*/
	switch($_GET['loads']) {
		case "kategori":
			$dtKategori->getAutoCompleteKategori();
			exit;
		break;

		case "ukuran":
			$dtUkuran = new controllerUkuran();
			$dtUkuran->getAutoCompleteUkuran();
			exit;
		break;
	}
	
}

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtKategori->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtKategori->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtKategori->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Kategori';
$kategori_nama = '';
$kategori_aliasurl = '';
$kategori_induk = 0;
$kategori_image = '';
$kategori_urutan = 0;
$kategori_spesial = 0;
$keterangan = '';
$lock = '';
$iddata = '';
$datawarna = false;
$combowarna = false;
$b = 1;
$namakat['name'] = '';
$dataukurans = [];
//Ini untuk tampilan

$results = $dtKategori->getKategori();
$categories = array();
foreach ($results as $result) {
	$categories[] = array(
	   'kategori_id' => $result['kategori_id'],
	   'kategori_nama'  => $result['kategori_nama']
	);
}
switch($menupage){
	case "view": default:
	    
		$dataview = $dtKategori->tampildata();
	    $ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("kategori","add",0);
		$modul = "tambah";
		include "form.php";
	break;
	case "edit": 
		$dtFungsi->cekHak("kategori","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datakategori = $dtKategori->dataKategoriByID($iddata);
		if(!empty($datakategori)){
			$iddata = $datakategori['category_id'];
			$kategori_nama = $datakategori['name'];
			$kategori_induk = $datakategori['parent_id'];
			$kategori_aliasurl = $datakategori['alias_url'];
			$kategori_image = $datakategori['image'];
			$kategori_urutan = $datakategori['sort_order'];
			$kategori_spesial = $datakategori['spesial'];
			$keterangan = $datakategori['description'];
			$namakat = $dtKategori->dataKategoriByIDs($iddata);
			$dataukurans = $dtKategori->getKategoriUkuran($iddata);
			$lock = 'disabled';
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php");

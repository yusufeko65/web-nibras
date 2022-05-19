<?php
define("path_toincludes", "../../_includes/");
define("folder", "menu");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('menu-grup','',0);

$dtMenu = new controllerMenuGrup();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

if(isset($_GET['loads'])) {
	if($_GET['loads']=='menu'){
		$menu = isset($_GET['cari']) ? $_GET['cari']:'';
		echo $dtMenu->getAutoCompleteMenu($menu);
		exit;
	}
}

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtMenu->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtMenu->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtMenu->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Grup Menu';
$menu_nama = '';
$menu_konten = '';
$menu_urutan = 0;
$menu_status = '1';

$lock = '';
$iddata = '';
$b = 1;

//Ini untuk tampilan

switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtMenu->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
	    $ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("menu","add",0);
		$modul = "tambah";
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("menu","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datamenu = $dtMenu->datamenuByID($iddata);
		if(!empty($datamenu)){
			$iddata = $datamenu['category_id'];
			$menu_nama = $datamenu['name'];
			$menu_induk = $datamenu['parent_id'];
			$menu_aliasurl = $datamenu['alias_url'];
			$menu_image = $datamenu['image'];
			$menu_urutan = $datamenu['sort_order'];
			$keterangan = $datamenu['description'];
			$namakat = $dtMenu->datamenuByIDs($iddata);
			$lock = 'disabled';
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>
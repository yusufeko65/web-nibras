<?php
define("path_toincludes", "../../_includes/");
define("folder", "produk-head");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('produk-head','',0);

$dtProduk = new controllerProduk();
$dtKategori = new controllerKategori();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";


if(isset($_GET['loads'])) {
	if($_GET['loads']=='kategori'){
		$dtKategori->getAutoCompleteKategori('1');
		exit;
	}
}
// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";


switch($aksipage){
	case "tambah":
		echo $dtProduk->simpanheadproduk('simpan');
		exit;
	break;
	case "ubah":
	 
		echo $dtProduk->simpanheadproduk('ubah');
		exit;
	break;
	case "hapus":
		echo $dtProduk->hapusdataheadproduk();
		exit;
	break;
	case "delgbr":
		echo $dtProduk->hapusgambarheadproduk();
		exit;
	break;
  
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE.'header.php');
  include(DIR_INCLUDE.'menu.php');
}
$judul = 'Product Head';
$produk_kode = '';
$produk_nama = '';
$produk_logo = '';
$produk_gbr = '';
$keterangan = '';
$metadeskripsi = '';
$metakeyword = '';
$aliasurl = '';
$status = '1';
$warna = '';
$idproduk = 0;
$b = 1;
$lock = '';
$style= 'style="display:none"';
$sale = '0';
$produk_katalog = '';
$kategori_nama = '';
$kategori_produk = '';
//Ini untuk tampilan
$image_warna_row = 0; 
$datakat = false;
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtProduk->getHeadProdukListAll();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
		$ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$kat = isset($_GET['k']) ? $_GET['k']:'';
		$linkpage = '';
		$katname = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		if($kat!='') {
			$linkpage = '&k='.trim(strip_tags($kat));
			$katname	= $dtFungsi->fcaridata("_category_description","name","category_id",$kat);
		}
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("produk","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("produk","edit",0);
		$modul = "ubah"; 
		$idproduk = $_GET["pid"];
		$dataproduk = $dtProduk->dataProdukHeadByID($idproduk);
		if($dataproduk){
			$idproduk = $dataproduk['head_idproduk'];
			$produk_kode = $dataproduk['kode_produk'];
			$produk_nama = $dataproduk['nama_produk'];
			$produk_gbr = $dataproduk['gbr_produk'];
			
			$keterangan = $dataproduk['deskripsi_head'];
			$metadeskripsi = $dataproduk['tag_deskripsi'];
			$metakeyword = $dataproduk['tag_keyword'];
			$aliasurl = $dataproduk['url_alias'];
			$status = $dataproduk['status_produk'];
			$kategori_produk = $dataproduk['kategori_produk'];
			$kategori_nama = $dataproduk['kategori_nama'];
            $lock = 'readonly';
			$style= '';
			
			$datawarna 			= $dtProduk->getWarnaProdukHeadByProduk($idproduk);
			$combowarna 		= $dtFungsi->cetakcombobox3('- Warna -',0,0,'','_warna ORDER BY trim(warna) ASC','idwarna','trim(warna)');
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE.'footer.php');
?>
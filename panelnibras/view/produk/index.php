<?php
define("path_toincludes", "../../_includes/");
define("folder", "produk");
include "../../../includes/config.php";
include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('produk','',0);

$dtProduk = new controllerProduk();
$dtKategori = new controllerKategori();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";


if(isset($_GET['loads'])) {
	switch($_GET['loads']) {
		case "kategori":
			$dtKategori->getAutoCompleteKategori();
			exit;
		break;
		case "produkhead":
			$dtProduk->getAutoCompleteProdukHead();
			exit;
		break;
	}
}
// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";


switch($aksipage){
   case "tambah":
		//echo 'tes';
		echo $dtProduk->simpandata('simpan');
		exit;
   break;
   case "ubah":
     // echo "tes";
		echo $dtProduk->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtProduk->hapusdata();
		exit;
   break;
   case "delgbr":
        //echo "gagal";
		echo $dtProduk->hapusgambar();
		exit;
   break;
   case "delgbrdetail":
        //echo "gagal";
		echo $dtProduk->hapusGambarDetail();
		exit;
   break;
   case "delopt":
		echo $dtProduk->hapusoption();
		exit;
   break;
   case "deldiskon":
		echo $dtProduk->hapusdiskon();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE.'header.php');
  include(DIR_INCLUDE.'menu.php');
}
$judul = 'Produk';
$produk_kode = '';
$produk_nama = '';
$produk_logo = '';
$produk_diskon = '0';
$produsen = 0;
$produk_gbr = '';
$berat = 0;
$stok = 0;
$harga_satuan = 0;
$reward_poin = 0;
$harga_diskon_satuan = 0;
$persen_diskon_satuan = 0;
$keterangan = '';
$produsen = '';
$metadeskripsi = '';
$metakeyword = '';
$aliasurl = '';
$status = '1';
$warna = '';
$ukuran = '';
$idgrupatt = 0;
$idproduk = 0;
$b = 1;
$lock = '';
$style= 'style="display:none"';
$sale = '0';
$produk_katalog = '';
$namakat['name'] = '';
$dataproduk['idkategori'] = '';

$image_warna_row = 0; 
$option_row = 0;
$tambahan_hrg_row = 0;
$gbr_detail_row = 0;
$datakat = false;
$producthead_nama = '';
$producthead_id = '';
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtProduk->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
		$ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$kat = isset($_GET['k']) ? $_GET['k']:'';
		$statusP = isset($_GET['sts']) ? $_GET['sts']:'';
		$linkpage = '';
		$katname = '';
		if($cari!='') $linkpage .= '&datacari='.trim(strip_tags($cari));
		if($kat!='') {
			$linkpage .= '&k='.trim(strip_tags($kat));
			$katname	= $dtFungsi->fcaridata("_category_description","name","category_id",$kat);
			
		}
		if($statusP!='') $linkpage .= '&sts='.trim(strip_tags($statusP));
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("produk","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("produk","edit",0);
		$modul = "ubah"; $idproduk = $_GET["pid"];
		$dataproduk = $dtProduk->dataProdukByID($idproduk);
		if($dataproduk){
			$idproduk = $dataproduk['idproduk'];
			$produk_kode = $dataproduk['kode_produk'];
			$produk_nama = $dataproduk['nama_produk'];
			$produk_gbr = $dataproduk['gbr_produk'];
			$stok = $dataproduk['jml_stok'];
			$berat = $dataproduk['berat_produk'];
			$harga_satuan = $dataproduk['hrg_jual'];
			$reward_poin = $dataproduk['poin'];
			$harga_diskon_satuan = $dataproduk['hrg_diskon'];
			$persen_diskon_satuan = $dataproduk['persen_diskon'];
			$keterangan = $dataproduk['keterangan_produk'];
			$metadeskripsi = $dataproduk['metatag_deskripsi'];
			$metakeyword = $dataproduk['metatag_keyword'];
			$aliasurl = $dataproduk['alias_url'];
			$status = $dataproduk['status_produk'];
			$producthead_nama = $dataproduk['producthead_nama'];
			$producthead_id = $dataproduk['producthead_id'];
            $lock = 'readonly';
			$style= '';
			
			$datakat = $dtProduk->getProdukKategori($idproduk);
			
			/* $namakat 			= $dtKategori->dataKategoriByIDs($dataproduk['idkategori']); */
			$datawarna 			= $dtProduk->getWarnaProdukByProduk($idproduk);
			$tablewarna = '_warna';
			if($producthead_id != '0') {
				$tablewarna .= " inner join _produk_head_warna on _warna.idwarna = _produk_head_warna.idwarna where idhead_produk='".$producthead_id."'";
			}
			$combowarna 		= $dtFungsi->cetakcombobox3('- Warna -',0,0,'',$tablewarna.' ORDER BY trim(warna) ASC','_warna.idwarna','trim(warna)');
			$comboukuran 		= $dtFungsi->cetakcombobox3('- Ukuran -',0,0,'id_ukuran','_ukuran ORDER BY trim(ukuran) ASC','idukuran','trim(ukuran)');
			$datagambardetail 	= $dtProduk->getGambarDetailByProduk($idproduk);
			$dataallstokoption  = $dtProduk->getAllStokOptionByProduk($idproduk);
			$dataalltambahanhrg  = $dtProduk->getAllHargaTambahanByProduk($idproduk);
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE.'footer.php');
?>
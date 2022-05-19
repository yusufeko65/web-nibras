<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "wahana");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('wahana','',0);

include DIR_INCLUDE."controller/controlWahana.php";



$dtwahana = new controllerWahana();
$dtKabupaten = new controllerKabupaten();
$dtPropinsi  = new controllerPropinsi();
$dtKecamatan = new controllerKecamatan();
$dataprop   = $dtPropinsi->getPropinsi();
$dtservis = $dtwahana->getServisWahana();
if(isset($_GET['load'])) {
   	$id = isset($_GET['idp']) ? $_GET['idp']:'0';
    switch($_GET['load']){
	   
	   case "kabupaten":
	     $datakabupaten = $dtKabupaten->getKabupatenByPropinsi($id);
		 $select = '<option value="0">- Pilih Kotamadya/Kabupaten -</option>';
		 if($id!='' && $id!='0'){
		   foreach($datakabupaten as $dkab) {
		     $select .= '<option value="'.$dkab['idk'].'">'.$dkab['nmk'].'</option>';
		   }
		 }
		 echo $select;
		 exit;
	   break;
	   case "kabupaten2":
		 echo $dtFungsi->cetakcombobox('','200',0,'kabupaten','_kabupaten','kabupaten_id','kabupaten_nama','provinsi_id='.$id);
		 exit;
	   break;
	   case "kecamatan":
	     $datakecamatan = $dtKecamatan->dataKecamatanByKabupaten($id);
		 $select = '<option value="0">- Pilih Kecamatan -</option>';
		 if($id!='' && $id!='0'){
		   foreach($datakecamatan as $dkec) {
		     $select .= '<option value="'.$dkec['idn'].'">'.$dkec['nmn'].'</option>';
		   }
		 }
		 echo $select;
		 exit;
	   break;
		
	}
}
$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:'';
switch($aksipage){
   case "tambah":
		echo $dtwahana->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtwahana->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtwahana->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Tarif WAHANA";
$servis_nama = '';
$iddata = '';
$idnegara = "''";
$idkabupaten = "''";
$idpropinsi = "''";
$idkecamatan = "''";
$nmdisk = '';
$jmldisk = '';
$persen = '0';
$stsdisk = '0';
$b = 1;
$dtwahanaservis = array();
$servisid = 0;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtwahana->tampildata();
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
	case "view-diskon": default:
	    $judul = "Diskon wahana";
	    $dtPaging = new Paging();
		$dataview = $dtwahana->tampildatadiskon();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
	    $ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view-diskon.php"; 
	break;
	
	case "add":
		$dtFungsi->cekHak("wahana","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	
	case "edit": 
		$dtFungsi->cekHak("wahana","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datawahana = $dtwahana->dataWahanaByID($iddata);
		if(!empty($datawahana)){
			$iddata = $datawahana['idt'];
			$idkabupaten = $datawahana['kabupaten_id'];
			$idpropinsi = $datawahana['provinsi_id'];
			$idkecamatan = $datawahana['kecamatan_id'];
			$idnegara = $datawahana['negara_id'];
			$idservis = $datawahana['servis_id'];
			$servis_nama = $datawahana['servis_nama'];
			$tarif = explode("::",$datawahana['hrg_perkilo']);
			
			$keterangan = $datawahana['keterangan'];
			$datakabupaten = $dtKabupaten->getKabupatenByPropinsi($idpropinsi);
			$datakecamatan = $dtKecamatan->dataKecamatanByKabupaten($idkabupaten);
			include "form.php";
		}
	break;
	
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>
<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "jne");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('jne','',0);

include DIR_INCLUDE."controller/controlJne.php";



$dtJne = new controllerJne();
$dtKabupaten = new controllerKabupaten();
$dtPropinsi  = new controllerPropinsi();
$dtKecamatan = new controllerKecamatan();
$dataprop   = $dtPropinsi->getPropinsi();
$dtservis = $dtJne->getServisJne();
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
		echo $dtJne->simpandata('simpan');
		exit;
   break;
   case "tambahdiskon":
		echo $dtJne->simpandiskon('simpan');
		
		exit;
   break;
   case "tambahdiskonservis":
		echo $dtJne->simpandiskonservis('simpan');
		
		exit;
   break;
   case "ubah":
		echo $dtJne->simpandata('ubah');
		exit;
   break;
   case "ubahdiskon":
		echo $dtJne->simpandiskon('ubah');
		exit;
   break;
   case "ubahdiskonservis":
		echo $dtJne->simpandiskonservis('ubah');
		exit;
   break;
   case "hapus":
		echo $dtJne->hapusdata();
		exit;
   break;
   case "hapusdiskon":
		echo $dtJne->hapusdiskon();
		exit;
   break;
    case "hapusdiskonservis":
		echo $dtJne->hapusdiskonservis();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = "Tarif JNE";
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
$dtjneservis = array();
$servisid = 0;
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtJne->tampildata();
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
	    $judul = "Diskon JNE";
	    $dtPaging = new Paging();
		$dataview = $dtJne->tampildatadiskon();
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
	case "view-diskon-servis": default:
	    $judul = "Diskon JNE";
	    $dtPaging = new Paging();
		$dataview = $dtJne->tampildatadiskonservis();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
	    $ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view-diskon-servis.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("jne","add",0);
		$modul = "tambah"; 
		include "form.php"; 
	break;
	case "add-diskon":
		$dtFungsi->cekHak("jne","add",0);
		$judul = "Diskon JNE";
		$modul = "tambahdiskon"; 
		include "form-diskon.php"; 
	break;
	case "add-diskon-servis":
		//$dtFungsi->cekHak("jne","add",0);
		$judul = "Diskon JNE";
		$modul = "tambahdiskonservis";
		$readonly = "";
		include "form-diskon-servis.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("jne","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datajne = $dtJne->dataJneByID($iddata);
		if(!empty($datajne)){
			$iddata = $datajne['idjne'];
			$idkabupaten = $datajne['kabupaten_id'];
			$idpropinsi = $datajne['provinsi_id'];
			$idkecamatan = $datajne['kecamatan_id'];
			$idnegara = $datajne['negara_id'];
			$idservis = $datajne['servis_id'];
			$servis_nama = $datajne['servis_nama'];
			$tarif = $datajne['hrg_perkilo'];
			$keterangan = $datajne['keterangan'];
			$datakabupaten = $dtKabupaten->getKabupatenByPropinsi($idpropinsi);
			$datakecamatan = $dtKecamatan->dataKecamatanByKabupaten($idkabupaten);
			include "form.php";
		}
	break;
	case "edit-diskon": 
		//$dtFungsi->cekHak("jne","edit",0);
		$judul = "Diskon JNE";
		$modul = "ubahdiskon"; $iddata = $_GET["pid"];
		$datajne = $dtJne->dataJneDiskonByID($iddata);
		if(!empty($datajne)){
		    $dtkab = new controllerKabupaten();
			$iddata     = $datajne['idjnedisk'];
			$nmdisk     = $datajne['nm_diskon'];
			$jmldisk    = $datajne['jml_disk'];
			$persen     = $datajne['persen'];
			$stsdisk    = $datajne['stsdiskon'];
			$idnegara   = $datajne['negara'];
			$idpropinsi = $datajne['propinsi'];
			$datakab    = $dtkab->getKabupatenByPropinsi($idpropinsi);
			$dtjnetujuan= $dtJne->dataJneDiskonTujuanByID($iddata);
			$dtjneservis= $dtJne->dataJneDiskonServisByID($iddata);
			include "form-diskon.php";
		}
	break;
	case "edit-diskon-servis": 
		//$dtFungsi->cekHak("jne","edit",0);
		$judul = "Diskon JNE";
		$modul = "ubahdiskonservis"; $iddata = $_GET["pid"];
		$datajne = $dtJne->dataJneServisDiskonByID($iddata);
		$readonly = "disabled";
		if(!empty($datajne)){
			$iddata     = $datajne['idservisdisk'];
			$jmldisk    = $datajne['jml_disk'];
			$stsdisk    = $datajne['stsdisk'];
			$servisid   = $datajne['servis_id'];
			include "form-diskon-servis.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>
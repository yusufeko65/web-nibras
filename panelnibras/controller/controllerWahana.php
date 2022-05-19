<?php
require_once DIR_INCLUDE."model/modelWahana.php";
class controllerWahana {
  
   private $page;
   private $rows;
   private $offset;
   private $db;
   private $model;
   private $dataFungsi;
   private $data=array();
      
   function __construct(){
		$this->model= new modelWahana();
		$this->dataFungsi= new FungsiUmum();
	}
   
   public function simpandata($aksi){
	  $hasil = '';
      
	  $this->data['id'] 	    = isset($_POST['iddata']) ? $_POST['iddata']:'';
	  $this->data['kecamatan']	= isset($_POST['kecamatan']) ? $_POST['kecamatan']:'';
	  $this->data['kabupaten']	= isset($_POST['kabupaten']) ? $_POST['kabupaten']:'';
	  $this->data['propinsi']	= isset($_POST['propinsi']) ? $_POST['propinsi']:'';
	 // $this->data['negara']	    = isset($_POST['negara']) ? $_POST['negara']:'33';
	  $this->data['negara']	    = '33';
	  $this->data['servis']	    = isset($_POST['servis']) ? $_POST['servis']:'';
	  $this->data['tarif']	    = isset($_POST['tarif']) ? $_POST['tarif']:0;
	  $this->data['tarifberikut']  = isset($_POST['tarifberikut']) ? $_POST['tarifberikut']:0;
	  $this->data['keterangan'] = isset($_POST['keterangan']) ? $_POST['keterangan']:'';
	  
	  if ($aksi=='simpan') $hasil = $this->adddata();
	  else $hasil = $this->editdata();
	  
	  return $hasil;
   } 
   
   public function adddata() {
      $pesan="";
	  $modulnya = "input";
	  $value = array();
	  $dataservis = explode(":",$this->data['servis']);
	  $datatarif  = explode(":",$this->data['tarif']);
	  $datatarifberikut  = explode(":",$this->data['tarifberikut']);
	  $dataket    = explode(":",$this->data['keterangan']);
	  //$jmldt = count($datatarif);
	  $jmlds = count($dataservis);
	  $cek = $this->dataFungsi->cekHak(folder,"add",1);
	  if($cek) {
	  	$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
	  } else {
	       	
			$jmldt = 0;
	        //for($i=0; $i < $jmldt ; $i++) {
			for($i=0; $i < $jmlds ; $i++) {
			  if($datatarif[$i] == '' && $datatarif[$i] == 0) {
			     $jmldt++;
			  }
		      if($this->model->checkDataWahana($this->data['kecamatan'],$this->data['kabupaten'],$dataservis[$i])){
			    $servisn = $this->dataFungsi->fcaridata('_servis_wahana','servis_nama','ids',$dataservis[$i]);
				$kecamatann = $this->dataFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$this->data['kecamatan']);
				$pesan = "Tarif Wahana untuk kecamatan $kecamatann dan servis $servisn tersebut telah terdaftar. ";
				break;
			  //} else {
			  } elseif($datatarif[$i] != '' && $datatarif[$i] != 0) {
			    $tarif = $datatarif[$i].'::'.$datatarifberikut[$i];
		        $value[$i] = "('','".$this->data['kecamatan']."','".$this->data['kabupaten']."','".$this->data['propinsi']."','".$this->data['negara']."','".$dataservis[$i]."','".$tarif."','".$dataket[$i]."')";
			  }
		    }
			
			//if($this->data['negara'] =='' || $this->data['negara'] == '0') $pesan = 'Pilih Negara Terlebih dahulu';
			if($this->data['propinsi'] =='' || $this->data['propinsi'] == '0') $pesan = 'Pilih Propinsi Terlebih dahulu';
			if($this->data['kabupaten'] =='' || $this->data['kabupaten'] == '0') $pesan = 'Pilih Kota/Kabupaten Terlebih dahulu';
			if($this->data['kecamatan'] =='' || $this->data['kecamatan'] =='0') $pesan = 'Pilih Kecamatan Terlebih dahulu';
			if($jmldt > ($jmlds-1)) $pesan = 'Masukkan Tarif Wahana';
		
	  }
	  
      if($pesan == ''){
		 $zdata = implode(",",$value);
		 if(!$this->model->simpanWahana($zdata)) $pesan="SQL Salah";
	  }
	  return $this->dataFungsi->pesandata($pesan,$modulnya);
   }
   
   function editdata(){
		$modulnya = "update";
		$pesan = "";
		$cek = $this->dataFungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataWahanaByID($this->data['id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editWahana($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->dataFungsi->pesandata($pesan,$modulnya);
   }
  
  public function simpandiskon($aksi){
	  $hasil = '';
      
	  $this->data['id'] 	    = isset($_POST['iddata']) ? $_POST['iddata']:'';
	  $this->data['kabupaten']	= isset($_POST['kabupaten']) ? $_POST['kabupaten']:'';
	  $this->data['propinsi']	= isset($_POST['propinsi']) ? $_POST['propinsi']:'';
	  $this->data['negara']	    = isset($_POST['negara']) ? $_POST['negara']:'';
	  $this->data['servis']	    = isset($_POST['jservis']) ? $_POST['jservis']:'';
	  $this->data['jmldisk']	= isset($_POST['jmldisk']) ? $_POST['jmldisk']:'';
	  $this->data['nmdisk']		= isset($_POST['nmdisk']) ? $_POST['nmdisk']:'';
	  $this->data['persen']     = isset($_POST['persen']) ? $_POST['persen']:'0';
	  $this->data['stsdiskon']  = isset($_POST['stsdiskon']) ? $_POST['stsdiskon']:'0';
	  if ($aksi=='simpan') $hasil = $this->adddiskon();
	  else $hasil = $this->editdiskon();
	  
	  return $hasil;
   }
   
  public function tampildata(){
	$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
	$this->rows		= 10;
	$result 			= array();
	$filter				= array();
	$where = '';
	$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
	if($caridata!='') $filter[] = " kecamatan_nama like '%".trim(strip_tags($caridata))."%'";
	if(!empty($filter))	$where = implode(" and ",$filter);
	
	$result["total"] = 0;
	$result["rows"] = '';
	$this->offset = ($this->page-1)*$this->rows;

	$result["total"]   = $this->model->totalWahana($where);
	$result["rows"]    = $this->model->getWahanaLimit($this->offset,$this->rows,$where);
	$result["page"]    = $this->page; 
	$result["baris"]   = $this->rows;
	$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
	
	return $result;
  }
  
  function hapusdata(){
	$id = isset($_POST['id']) ? $_POST['id']:'';
	$dataId = explode(":",$id);
	$dataError=array();
	$modul = "hapus";
	$pesan = '';
	$cek = $this->dataFungsi->cekHak(folder,"del",1);
	if($cek) {
		$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
	} else {
		foreach($dataId as $data){
			//if(!$this->model->checkRelasi($data)){
				if(!$this->model->hapusWahana($data)) $pesan="SQL Salah";
			//} else {
			//	$Wahana = $this->model->getWahanaByID($data);
			//	$dataError[] = $Wahana['kecamatan_nama'];
			//}
		}
	}
	if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
	if($pesan!='') $pesan="gagal|".$pesan;
	return $pesan;
	
  }
  
  function getServisWahana(){
	return $this->model->getServisWahana();
  }
  
  function dataWahanaByID($iddata){
	return $this->model->getWahanaByID($iddata);
  }
 
}
?>

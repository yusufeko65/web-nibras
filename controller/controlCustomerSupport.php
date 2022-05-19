<?php
//require_once DIR_INCLUDE."model/modelCustomerSupport.php";
class controller_CustomerSupport {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $data=array();
      
   function __construct(){
		$this->dataModel= new model_CustomerSupport();
		$this->Fungsi	= new FungsiUmum();
	}
   
   public function simpandata($aksi){
	  $hasil = '';
	  $this->data['id']	  = isset($_POST['iddata']) ? $_POST['iddata']:'';
	  $this->data['cs_nama']	  = isset($_POST['nama']) ? $_POST['nama']:'';
	  $this->data['cs_jsupport']	  = isset($_POST['nama']) ? $_POST['jenis']:'';
	  $this->data['cs_akun'] = isset($_POST['akun']) ? $_POST['akun']:'';
	  $this->data['status']	  = isset($_POST['status']) ? $_POST['status']:'';
	  
	  if ($aksi=='simpan') {
		  $hasil = $this->adddata();
	  } else {
	      $hasil = $this->editdata();
	  }
	  return $hasil;
   } 
   
   public function adddata() {
      $pesan="";
	  $modulnya = "input";
	  //$cek = $this->Fungsi->cekHak(folder,"add",1);
	  //if($cek) {
	  //	$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
	  //} else {
			if($this->dataModel->checkDataCustomerSupport($this->data['cs_akun'],$this->data['cs_jsupport'])){
				$pesan = ' <br>Customer Support telah dipergunakan. Silahkan Masukkan Customer Support lainnya ';	
			} else {
				if(!$this->dataModel->simpanCustomerSupport($this->data)) $pesan="SQL Salah";
			}
	  //}
	  return $this->Fungsi->pesandata($pesan,$modulnya);
   }
   
   function editdata(){
		$modulnya = "update";
		$pesan = '';
		//$cek = $this->Fungsi->cekHak(folder,"edit",1);
		//if($cek) {
		//	$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		//} else {
			if(!$this->dataModel->checkDataCustomerSupportByID($this->data['id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				$akunlama = $this->Fungsi->fcaridata2('_customer_support','cs_akun,cs_jsupport','idsupport ='.$this->data['id']);
				if($akunlama[0] != $this->data['cs_akun'] && $akunlama[1] != $this->data['cs_jsupport']) {
				   if($this->dataModel->checkDataCustomerSupport($this->data['cs_akun'], $this->data['cs_jsupport'])){
				       $pesan = '<br>Customer Support telah dipergunakan. Silahkan Masukkan Customer Support lainnya ';	
			       } 
				}
			}
		//}
		if($pesan == '')
     		if(!$this->dataModel->editCustomerSupport($this->data)) $pesan="SQL Salah";
	
		return $this->Fungsi->pesandata($pesan,$modulnya);
   }
  
  
  public function tampildata(){
	$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
	//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$this->rows		= 10;
	$result 			= array();
	$filter				= array();
	$where = '';
	$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
	if($caridata!='') $filter[] = " cs_nama like '%".trim(strip_tags($caridata))."%' OR cs_akun like '%".trim(strip_tags($caridata))."%'";
	if(!empty($filter))	$where = implode(" and ",$filter);
	//if(!empty($filter))	$where = " where ". implode(" and ",$filter);
	
	
	$result["total"] = 0;
	$result["rows"] = '';
	$this->offset = ($this->page-1)*$this->rows;

	$result["total"]   = $this->dataModel->totalCustomerSupport($where);
	$result["rows"]    = $this->dataModel->getCustomerSupportLimit($this->offset,$this->rows,$where);
	$result["page"]    = $this->page; 
	$result["baris"]   = $this->rows;
	$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
	return $result;
  }
  
  function hapusdata($_POST){
	$id = isset($_POST['id']) ? $_POST['id']:'';
	$dataId = explode(":",$id);
	$dataError=array();
	$modul = "hapus";
	$pesan = '';
	//$cek = $this->Fungsi->cekHak(folder,"del",1);
	//if($cek) {
	//	$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
	//} else {
		foreach($dataId as $data){
			if(!$this->dataModel->hapusCustomerSupport($data)) $pesan="SQL Salah";
		}
//	}
	if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
	if($pesan!='') $pesan="gagal|".$pesan;
	return $pesan;
	
  }
    
  function getCustomerSupport(){
	$support = $this->dataModel->getCustomerSupport();
	return $support;
  }
  
  function dataCustomerSupportByID($iddata){
	$support = $this->dataModel->getCustomerSupportByID($iddata);
	return $support;
  }

}
?>

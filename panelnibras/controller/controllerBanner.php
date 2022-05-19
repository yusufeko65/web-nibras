<?php
require_once DIR_INCLUDE."model/modelBanner.php";
class controllerBanner {
   private $page;
   private $rows;
   private $offset;
   private $model;
   private $Fungsi;
   private $data=array();
   
   function __construct(){
		$this->model= new modelBanner();
		$this->Fungsi= new FungsiUmum();
	}
   
   public function simpandata($aksi){
	  $hasil = '';
      $data = array();
	  $this->data['banner_id']     = isset($_POST['iddata']) ? $_POST['iddata']:'';
	  $this->data['banner_nama']   = isset($_POST['banner']) ? $_POST['banner']:'';
	  $this->data['logo_tmp']      = $_FILES['filegbr']['tmp_name'];
	  $this->data['logo_name']     = isset($_FILES['filegbr']['name']) ? $_FILES['filegbr']['name']:'';
	  $this->data['logo_size']     = isset($_FILES['filegbr']['size']) ? $_FILES['filegbr']['size']:0;
	  $this->data['logo_lama']     = isset($_POST['filelama']) ? $_POST['filelama']:'';
	  $this->data['panjang']       = isset($_POST['panjang']) ? $_POST['panjang']:'';
	  $this->data['lebar']         = isset($_POST['lebar']) ? $_POST['lebar']:'';
	  $this->data['urllink']       = isset($_POST['urllink']) ? $_POST['urllink']:'';
	  $this->data['slot']          = isset($_POST['slot']) ? $_POST['slot']:'';
	  $this->data['banner_status'] = isset($_POST['status']) ? $_POST['status']:'';
	  
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
			if($this->model->checkDataBanner($this->data['slot'])){
				$pesan = "Slot Banner telah dipergunakan. Silahkan Pilih Slot Banner lainnya";	
			} else {
				if(is_uploaded_file($this->data['logo_tmp'])) {
				   if($this->file_size < 1000000) {
				       $upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],'banner'.$this->data['logo_name'],$this->data['panjang'],$this->data['lebar']);
					   if(!$upload){
					      $pesan = " Upload Tidak berhasil";
					   } else {
					      	$this->data['logo_name'] = 'banner'.$this->data['logo_name'];
							if(!$this->model->simpanBanner($this->data)) $pesan="SQL Salah";
					   }
					 
				   } else {
				      $pesan = " Maksimal File 1MB ";
				   }
				}
				
			}
	  //}
	  $pesan = $this->Fungsi->pesandata($pesan,$modulnya);
	  $pesan = "<script>parent.suksesdata('$pesan')</script>";
	  return $pesan;
   }
   
   function editdata(){
		$modulnya = "update";
		$pesan = "";
		
		//$cek = $this->Fungsi->cekHak(folder,"edit",1);
		//if($cek) {
		//	$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		//} else {
			if(!$this->model->checkDataBannerByID($this->data['banner_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				
				if($this->data['logo_lama'] != '' && $this->data['logo_name'] == '') $this->data['logo_name'] = $this->data['logo_lama'];
				if($this->data['logo_name'] != '') {
				    if($this->data['logo_size'] < 1000000) {
					   if(is_uploaded_file($this->data['logo_tmp'])) {
						   if(file_exists(DIR_IMAGE.'_other/other_'.$this->data['logo_lama'])) unlink(DIR_IMAGE.'_other/other_'.$this->data['logo_lama']);
						   //$this->data['logo_name'] = 'banner'.$this->data['logo_name'];
						   //$upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],$this->data['Banner_logo'],100);
					       $this->data['logo_name'] = 'banner'.$this->data['logo_name'];
						   $upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],$this->data['logo_name'],$this->data['panjang'],$this->data['lebar']);
						   if(!$upload) $pesan = ' Upload Tidak berhasil';
						   
					   }
					}
				
				}
				if($pesan=='') {
				   
				   if(!$this->model->editBanner($this->data)) $pesan="SQL Salah";
				}
				
			}
		//}
	  $pesan = $this->Fungsi->pesandata($pesan,$modulnya);
	  $pesan = "<script>parent.suksesdata('$pesan')</script>";
	  return $pesan;
   }
    
  public function tampildata(){
	$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
	//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$this->rows		= 10;
	$result 			= array();
	$filter				= array();
	$where = '';
	$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
	if($caridata!='') $filter[] = " banner_nama like '%".trim(strip_tags($caridata))."%'";
	if(!empty($filter))	$where = implode(" and ",$filter);
	
	$result["total"] = 0;
	$result["rows"] = '';
	$this->offset = ($this->page-1)*$this->rows;

	$result["total"]   = $this->model->totalBanner($where);
	$result["rows"]    = $this->model->getBannerLimit($this->offset,$this->rows,$where);
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
	//$cek = $this->Fungsi->cekHak(folder,"del",1);
	//if($cek) {
	//	$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
	//} else {
		foreach($dataId as $data){
		   $banner = $this->model->getBannerByID($data);
			//if(!$this->model->checkRelasi($data)){
			    if(!$this->model->hapusBanner($data)) {
    				 $pesan="SQL Salah";
				} else {
			       if(file_exists(DIR_IMAGE.'_other/other_'.$banner['gbr_banner'])) unlink(DIR_IMAGE.'_other/other_'.$banner['gbr_banner']);
				   $pesan = '';
				}
				
			//} else {
			//	$dataError[] = $Banner['Banner_nama'];
			//}
		}
//	}
	if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
	if($pesan!='') $pesan="gagal|".$pesan;
	return $pesan;
	
  }
    
  function getBanner(){
	$Banner = $this->model->getBanner();
	return $Banner;
  }
  
  function dataBannerByID($iddata){
	$Banner = $this->model->getBannerByID($iddata);
	return $Banner;
  }
  
  function cetakcomboboxBanner($idBanner=0,$idobject){
	$aBanner = $this->model->getBanner();
	$createcomboBanner= "<select id=\"$idobject\" class=\"selectbox\" style=\"width:200px\">";
	$createcomboBanner.= "<option value=\"0\">- Pilih Banner -</option>";
	foreach ($aBanner as $key => $dneg) {
		if($idBanner==$dneg['idn']) $selected=" selected ";
		else $selected=" ";
		$createcomboBanner.="<option value=\"".$dneg['idn']."\" ".$selected.">".$dneg['nmn']."</option>";
	}
	$createcomboBanner.="</select>";
	return $createcomboBanner;
  }
}
?>

<?php
class controllerSetting {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
	private $error=array();   
	public function __construct(){
		$this->model= new modelSetting();
		$this->Fungsi	= new FungsiUmum();
	}
   
	public function simpandata(){
		$pesan="";
		$modulnya = "input";

		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				foreach ($_POST as $key => $value) {
					$this->data["$key"]	= isset($_POST["$key"]) ? $value : '';
				}
			 
				if ($this->validasi()) {
					if( !$this->model->simpanSetting($this->data) ) $pesan="SQL Salah";
				} else {
					$pesan = implode (", ",$this->error);
				}
			} else {
				$pesan = 'Tidak valid';
			}
		}

		return $this->Fungsi->pesandata($pesan,$modulnya);
	} 
  
	private function validasi(){
		if($this->data['config_jdlsite'] == '') 
			$this->error[]	= 'Masukkan Judul Site';
		
		if($this->data['config_alamatsite'] == '') 
			$this->error[]	= 'Masukkan Alamat Site';
				
		if( !filter_var( $this->data['config_email'], FILTER_VALIDATE_EMAIL ) )
			$this->error[]	= 'Masukkan Email';
		
		if ( !$this->error ) 
			return true;
		else 
			return false;
	
	}
  
 
	public function getSetting(){
		return $this->model->getSetting();
	}

	public function getSettingByKey($key){
		return $this->model->getSettingByKey($key);
	}

	public function getSettingByKeys($key){
		return $this->model->getSettingByKeys($key);
	}

	public function setSettingByKey($key, $value){
		return $this->model->setSettingByKey($key, $value);
	}

}
?>

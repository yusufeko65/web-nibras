<?php
class controller_Login {
   private $emailuser;
   private $passuser;
   private $dataModel;
   private $Fungsi;
   private $data=array();
   private $error = array();
   
   public function __construct(){
		$this->dataModel= new model_Login();
		$this->Fungsi= new FungsiUmum();
	}
  
    
	public function getLogin(){
		$pesan = '';
		$datalogin = [];
		$this->data['emailuser'] = isset($_POST['lemailuser']) ? $_POST['lemailuser']:'';
		$this->data['passuser']  = isset($_POST['lpassuser']) ? $_POST['lpassuser']:'';
		$this->data['passuser'] = $this->Fungsi->fEnkrip($this->data['passuser']);
	
		if($this->validasi()) {
		   $datalogin = $this->dataModel->getLogin($this->data);
		   $_SESSION['idmember']    = $datalogin['cust_id'];
		   $_SESSION['usermember']  = $datalogin['cust_email'];
		   $_SESSION['namamember']  = $datalogin['cust_nama'];
		   $_SESSION['tipemember']  = $datalogin['cust_grup_id'];
		   $_SESSION['dropship']	= $datalogin['cg_dropship'];
		   $pesan = "berhasil login";
		   $status = 'success';
		   
		   
		} elseif($this->error) {
			$status = 'error';
			$pesan = implode ("<br>",$this->error);
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}	
  
	private function validasi(){
		if($this->data['emailuser'] == '' || $this->data['passuser'] == '') {
			$this->error['email'] = 'Masukkan Email dan Password Anda';
		} elseif(!$this->dataModel->checkDataLogin($this->data)) {
			$this->error['salah'] = 'Masukkan Email dan Password yang valid';
		}
	 
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
  
}
?>

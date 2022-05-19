<?php
//require_once DIR_MODEL."modelProdusen.php";
class controller_Produsen {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $bank;
   private $data=array();
   private $error = array();
   private $kirim_email;
   
   function __construct(){
		$this->dataModel	= new model_Produsen();
		$this->Fungsi		= new FungsiUmum();
  }
   
   public function simpandata(){
      $pesan = '';
	  $valgbr = array();
	  
	  $this->data['produsen_nama'] = isset($_POST['pnama']) ? mysql_real_escape_string(htmlentities($_POST['pnama'])):'';
	  $this->data['logo_tmp']   = $_FILES['pfilelogo']['tmp_name'];
	  $this->data['logo_name']  = isset($_FILES['pfilelogo']['name']) ? $_FILES['pfilelogo']['name']:'';
	  $this->data['logo_size']  = isset($_FILES['pfilelogo']['size']) ? $_FILES['pfilelogo']['size']:0;
	  $this->data['produsen_logo']  = 'produsen'.date('HisYmd').$this->data['logo_name'];
	  $this->data['produsen_telp'] = isset($_POST['pphone']) ? $_POST['pphone']:'';
	  $this->data['produsen_email'] = isset($_POST['pemail']) ? $_POST['pemail']:'';
	  $this->data['produsen_alamat'] = isset($_POST['palamat']) ? mysql_real_escape_string(htmlentities($_POST['palamat'])):'';
	  $this->data['produsen_keterangan'] = isset($_POST['pketerangan']) ? mysql_real_escape_string(htmlentities($_POST['pketerangan'])):'';
	  $this->data['produsen_web'] = isset($_POST['pwebsite']) ? $_POST['pwebsite']:'';
	  $this->data['produsen_fb'] = isset($_POST['pfacebook']) ? mysql_real_escape_string(htmlentities($_POST['pfacebook'])):'';
	  $this->data['produsen_ketgrosir'] = isset($_POST['pketpembelian']) ? mysql_real_escape_string(htmlentities($_POST['pketpembelian'])):'';
	  $this->data['produsen_kapasitas'] = isset($_POST['pkapasitasprod']) ? $_POST['pkapasitasprod']:'';
	  
	  $this->data['produk_image']    = isset($_FILES['pcontoh_prod']['name']) ?  $_FILES['pcontoh_prod']['name']:'';
	 
	  if($this->data['produk_image'] != '') $this->jmlgbr = count($this->data['produk_image']);
	  else $this->jmlgbr = 0;
	 
	  $this->data['aliasurl'] = $this->Fungsi->friendlyURL($this->data['produsen_nama']);
	  
	  if($this->validasi()) {
	      if(is_uploaded_file($this->data['logo_tmp'])) {
			 if($this->data['logo_size'] < 1000000) {
				 $upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],$this->data['produsen_logo'],200);
			     if(!$upload) $pesan = " Upload Tidak berhasil ";
					   
			 } else {
			     $pesan = " Maksimal File 1MB ";
			 }
		  } else {
				  $this->data['produsen_logo'] = '';
		  }
		  
		  if($pesan == ''){
		     $idakhirprodusen = $this->Fungsi->fIdAkhir('_produsen','produsen_id');
             $idakhirprodusen = (int)$idakhirprodusen + 1;
			 $gbrs = array();
			 if($this->jmlgbr > 0) {
			     for($i=0 ; $i < $this->jmlgbr ; $i++){
				    $gbr_tmp 	= isset($_FILES['pcontoh_prod']['tmp_name'][$i]) ? $_FILES['pcontoh_prod']['tmp_name'][$i]:'';;
	                $gbr_name 	= isset($_FILES['pcontoh_prod']['name'][$i]) ? $_FILES['pcontoh_prod']['name'][$i]:'';
	                $gbr_size 	= isset($_FILES['pcontoh_prod']['size'][$i]) ? $_FILES['pcontoh_prod']['size'][$i]:0;
					
					$gbr_nmpr = "sample".date('HisYmd').$i.$gbr_name;
					$gbrs[]   = $gbr_nmpr;
					if($gbr_name != '') {
					   if($gbr_size < 1000000) {
						   $upload = $this->Fungsi->UploadImagebyUkuran($gbr_tmp,$gbr_name,$gbr_nmpr,200);
							if(!$upload) {
								$pesan = " Upload Tidak berhasil ";
								break;
							} else {
							    $valgbr[] = "('','".$idakhirprodusen."','".$gbr_nmpr."')";
								
							}
					   } else {
							$pesan = " Maksimal File 1MB ";
							break;
						}
					}
					
				 }
			 
			 }
		  }
		  
		  if($pesan == '') {
			 $this->data['produsen_id']   = $idakhirprodusen;
			 $pesan = 'sukses|Terimakasih, telah mengirimkan data Anda. Kami akan mereview data yang telah Anda kirimkan kepada kami';
			 if(!$this->dataModel->simpanProdusen($this->data)) {
			    $pesan="gagal|<br>Gagal Menyimpan Data Produsen";
				 
			 }
			if(count($valgbr) > 0) {
			    $datagbr = implode(",",$valgbr);
				if(!$this->dataModel->simpanGambarProduk($datagbr)) $pesan = "gagal|<br> Gagal Menyimpan Gambar";
			}
			
			
		  }
		  
	  } elseif($this->error) {
	       
		      $pesan = implode ("<br>",$this->error);
			  $pesan = 'gagal|'.$pesan;
		 
	  }
	  
	  $pesan = "<script>parent.suksesdata('$pesan')</script>";
	  return $pesan;
   } 
   
   private function validasi(){
      if(strlen($this->data['produsen_nama']) < 3 ) {
	      $this->error[] = 'Masukkan Nama Anda';
	  } //elseif($this->dataModel->checkProdusen($this->data)) {
	   //   $this->error[] = 'Nama Anda telah ada';
	 // }
		  
	  if( !filter_var( $this->data['produsen_email'], FILTER_VALIDATE_EMAIL ) )
		$this->error[]	= 'Email salah format';
      
		  
	  if($this->data['produsen_alamat'] == '') 
	      $this->error[] = 'Masukkan Alamat';
	  
	  if( $this->data['produsen_telp'] == '' )
		$this->error[]	= 'Masukkan No. Telp';
	  
	  if($this->data['produsen_ketgrosir'] == '') 
	      $this->error[] = 'Masukkan keterangan Melayani pembelian grosir?';
	  
	  if($this->data['produsen_kapasitas'] == '') 
	      $this->error[] = 'Masukkan Kapasitas Produksi per bulan';
	  
	  
	  if (!$this->error) {
      		return true;
      } else {
      		return false;
      }
   }
   
   public function getProdusenByIDAlias($pid,$j){
      return $this->dataModel->getProdusenByIDAlias($pid,$j);
   }
   
   public function getProdusenByID($id){
      return $this->dataModel->getProdusenByID($id);
   }
   
   public function getProdusen(){
      return $this->dataModel->getProdusen();
   }
}
?>

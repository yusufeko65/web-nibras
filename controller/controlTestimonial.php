<?php
//require_once DIR_MODEL."modelTestimonial.php";
class controller_Testimonial {
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
		$this->dataModel	= new model_Testimonial();
		$this->Fungsi		= new FungsiUmum();
			}
   
   public function simpandata(){
      $pesan = '';
	  $this->data['email']	  		= isset($_POST['kmail']) ? $_POST['kmail']:'';
	  $this->data['nama'] 			= isset($_POST['knama']) ? mysql_real_escape_string($_POST['knama']):'';
	  $this->data['komentar']  		= isset($_POST['kkomentar']) ? htmlentities($_POST['kkomentar']):'';
	  $this->data['web']	  		= isset($_POST['kweb']) ? $_POST['kweb']:'';
	  $this->data['tglkirim']		= date('Y-m-d H:i:s');
	 
	  if($this->validasi()) {
	      
		  if($this->dataModel->Simpan($this->data)) $pesan = 'sukses|Terimakasih telah memberikan testimonial Anda. Testimonial akan tampil setelah persetujuan dari Admin';
		  
	  } elseif($this->error) {
	       if(count($this->error) == 3) {
		      $pesan = 'gagal|Harap isi form testimonial dibawah ini';
		   } else { 
		      $pesan = implode ("<br>",$this->error);
			  $pesan = 'gagal|'.$pesan;
		   }  
	  }
	  return $pesan;
   } 
   
   private function validasi(){
      if(strlen($this->data['nama']) < 4) 
	      $this->error[] = 'Masukkan Nama';
		  
      if(!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) 
	      $this->error[] = 'Masukkan Email dengan Benar';
		
	  if($this->data['komentar'] == '') 
	      $this->error[] = 'Masukkan Komentar Anda';
		  
	  if (!$this->error) {
      		return true;
      } else {
      		return false;
      }
   }
   public function tampildata(){
	$this->page 	    = isset($_GET['page']) ? intval($_GET['page']) : 1;
	//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	
	$this->rows		    = 10;
	$result 			= array();
	$filter				= array();
	$where 				= '';
	
	
	$result["total"] = 0;
	$result["rows"] = '';
	$this->offset = ($this->page-1)*$this->rows;

	$result["total"]   		= $this->dataModel->totalTestimonial($where);
	$result["rows"]    		= $this->dataModel->getTestimonialLimit($this->offset,$this->rows,$where);
	$result["page"]    		= $this->page; 
	$result["baris"]   		= $this->rows;
	$result["jmlpage"] 		= ceil(intval($result["total"])/intval($result["baris"]));
	//echo "select count(*) from _produk INNER JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk ".$where;
	return $result;
  }
   

}
?>

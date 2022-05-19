<?php
class controller_Register {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $bank;
   private $data=array();
   private $error = array();
   private $kirim_remail;
   
   function __construct(){
		$this->dataModel	= new model_Register();
		$this->Fungsi		= new FungsiUmum();
		$this->bank 		= new model_Bank();
		$this->kirim_remail  = new PHPMailer();
	}
   
   public function simpandata(){
		$pesan = '';
	  
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value){
				$this->data["{$key}"] = trim($value);
			}
		
			$this->data['ipaddress']		= $this->Fungsi->get_client_ip();
			$this->data['namagrup']       = $this->Fungsi->fcaridata('_customer_grup','cg_nm','cg_id',$this->data['rtipereseller']);

			$this->data['tglregis']		= date('Y-m-d H:i:s');
			$this->data['tglexpired']		= date('Y-m-d',mktime(0,0,0,date('m')+0,date('d')+0,date('Y')+1));
			$this->data['keterangan']      = 'Register';
		  
			if($this->validasi()) {
				//proses simpan
			  
				$this->data['approve'] = '1';
				$this->data['status'] = '1';
				$this->data['rpass'] = $this->Fungsi->fEnkrip($this->data['rpass']);
			  
				$this->data['aktivitas'] = 'Register '.$this->data['namagrup'];
				$this->data['keterangan'] = 'Register '.$this->data['namagrup'].' via Front Site ';
			  
				$simpan = $this->dataModel->Simpan($this->data);
			  
				//$this->dataModel->simpanHistory($this->data);
				//$this->dataModel->simpanAktivitas($this->data);
			  
				if($simpan['status'] == 'success'){
					
					$setting = $this->Fungsi->fcaridata3("_setting",'setting_value',null);
					$datasetting = array();
					if($setting){
						
						foreach($setting as $key => $value){
							$datasetting["{$key}"] = $value;
						}
					
					} 
					$_SESSION['register']    = 'ya';
					$_SESSION['idmember']    = $simpan['idcust'];
					$_SESSION['usermember']  = $this->data['remail'];
					$_SESSION['namamember']  = $this->data['rnama'];
					$_SESSION['tipemember']  = $this->data['rtipereseller'];
					$_SESSION['messagenota'] = $simpan['idcust'].'::'.$this->data['rnama'].'::'.$this->data['namagrup'];
				  
					$from = isset($datasetting['config_emailnotif']) ? $datasetting['config_emailnotif'] : '';
					$from_name = isset($datasetting['config_namatoko']) ? $datasetting['config_namatoko'] : '';
					$subject = 'Pendaftaran '.$simpan['idcust'].' '.$this->data['rnama'];
				 
					$headermessage = isset($datasetting['config_headernotaemail']) ? $datasetting['config_headernotaemail'] : '';
					
					$message   = isset($datasetting['config_notaregisweb']) ? $datasetting['config_notaregisweb'] : '';
					$message   = str_replace("[PELANGGAN]",$this->data['rnama'],$message);
					$message   = str_replace("[GRUP PELANGGAN]",$this->data['namagrup'],$message);
					$message   = str_replace("[ID PELANGGAN]",$simpan['idcust'],$message);
					$message   = str_replace("[NAMAWEBSITE]",$from_name,$message);
					$bodys	 = '<body style="margin: 10px;">';
					$bodys	.= $headermessage.$message;
					$bodys	.= '</body>';
					
					if($from != '') {
						$to = $this->data['remail'];
						$this->kirim_remail->IsHTML(true);
						$this->kirim_remail->SetFrom($from, $from_name);
						$this->kirim_remail->Subject = $subject;
						$this->kirim_remail->WordWrap = 50;
						$this->kirim_remail->AltBody    = "To view the message, please use an HTML compatible remail viewer!"; // optional, comment out and test
						$this->kirim_remail->MsgHTML($bodys);
						$this->kirim_remail->CharSet="UTF-8";
						$this->kirim_remail->AddAddress($to,$this->data['rnama']);
						$this->kirim_remail->Send();
					}
					$pesan = 'Proses registrasi berhasil';
					$status = 'success';
				} else {
					$status = 'error';
					$pesan = 'Proses Registrasi tidak berhasil';
				}
				
			  
			} elseif($this->error) {
				if(count($this->error) == 11) $pesan = 'Harap isi form pendaftaran dibawah ini';
				else $pesan = implode ("<br>",$this->error);
				
				$status = 'error';
			}
		} else {
			$status = 'error';
			$pesan = 'Tidak valid';
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	} 
   
	private function validasi(){
		if(!filter_var($this->data['remail'], FILTER_VALIDATE_EMAIL)) 
			$this->error['remail'] = 'Masukkan email dengan Benar';
		  
		if($this->dataModel->checkDataRegister($this->data['remail']))
			$this->error['remail'] = 'email telah terdaftar, silahkan menggunakan remail lainnya';
		  
		if(strlen($this->data['rpass']) < 4 || strlen($this->data['rpass']) > 20) 
			$this->error['rpass'] = 'Masukkan Password, 4 - 20 karakter';
		  
		if($this->data['rrepass'] != $this->data['rpass']) 
			$this->error['rrepass'] = 'Ulangi Password dan Password tidak sama';
		  
		if(strlen($this->data['rnama']) < 4) 
			$this->error['rnama'] = 'Masukkan Nama Anda';
		  
		if(strlen($this->data['rtelp']) < 4)
			$this->error['rtelp'] = 'Masukkan No. Telepon Anda';
		  
		if($this->dataModel->checkDataRegHP($this->data['rtelp']))
			$this->error['rtelp'] = 'No. Telepon telah terdaftar, silahkan menggunakan No. Telepon lainnya';
	
	  
		if(strlen($this->data['ralamat']) < 8) 
			$this->error['ralamat'] = 'Masukkan Alamat Anda';
	  
		  
		if($this->data['rpropinsi'] == '0' || $this->data['rpropinsi'] == '')
			$this->error['rpropinsi'] = 'Harap Pilih Propinsi';
	  
		if($this->data['rkabupaten'] == '0' || $this->data['rkabupaten'] == '')
			$this->error['rkabupaten'] = 'Harap Pilih Kabupaten';
	  
		if($this->data['rkecamatan'] == '0' || $this->data['rkecamatan'] == '')
			$this->error['rkecamatan'] = 'Harap Pilih Kecamatan';
	  		  
	  
	  
		if($this->data['rprivasi'] == '') 
			$this->error['rprivasi'] = 'Centang kebijakan privasi';
		  
		if (!$this->error) {
			return true;
		} else {
      		return false;
		}
	}
   
	public function resetpassword(){
       $this->data['sesicapca']	= isset($_SESSION['kodesekuriti']) ? $_SESSION['kodesekuriti']:'';
	   $this->data['capcaku']	= isset($_POST['capcaku']) ? htmlentities($_POST['capcaku']):'';
	   $this->data['remail']	    = isset($_POST['lremail']) ? $_POST['lremail']:'';
	   $this->data['resetpass'] = '';
	   $jmlkarakter 		    = 6;
	   $randomkarakter 	        = '012345678910abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   $i = 0;
	   while ($i < $jmlkarakter) { 
			$this->data['resetpass'] .= substr($randomkarakter, mt_rand(0, strlen($randomkarakter)-1), 1);
			$i++;
	   }
	   $password = $this->data['resetpass'];
	   $this->data['resetpass'] = $this->Fungsi->fEnkrip($this->data['resetpass']);
	   if($this->dataModel->checkDataRegister($this->data['remail'])) {
	      $this->dataModel->resetPassword($this->data);
		  $this->data['tglregis'] = date('Y-m-d H:i:s');
		  $this->data['aktivitas'] = 'Reset Password ';
		  $this->data['keterangan'] = 'Reset Password via Front Site. IP '.$this->Fungsi->get_client_ip();;
		  
		  $this->dataModel->simpanAktivitas($this->data);
		  
		  /* kirim password ke remail */
		  
		  $from = 'info@taskerjawanita.com'; //alamat toko
		  $from_name = 'taskerjawanita.com';
		 
		  $subject = 'Reset Password';
		  
		  $bodys	= '<body style="margin: 10px;">';
		  $bodys    .= '<div style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
						<br><br>
					-------------------------------------------------------------------------------------<br>
					JANGAN BALAS remail NOTIFIKASI INI. <br>
					-------------------------------------------------------------------------------------<br>
						<br>';
		  $bodys    .= "Anda telah melakukan Reset Password Login di Toko kami<br><br>";
		  $bodys	.= "remail : ".$this->data['remail']."<br>";
		  $bodys	.= "Password : ".$password."<br>";
		  $bodys	.= '<a href="http://taskerjawanita.com'.URL_PROGRAM.'login">Klik disini login</a><br><br>';
		  $bodys	.= '<br><br>Selamat berbelanja di Toko taskerjawanita.com.<br>';
		 
		  $bodys	.= '</body>';
		  
		  $to = $this->data['remail'];
		  $this->kirim_remail->IsHTML(true);
		  $this->kirim_remail->SetFrom($from, $from_name);
		  $this->kirim_remail->Subject = $subject;
		  $this->kirim_remail->WordWrap = 50;
		  $this->kirim_remail->AltBody    = "To view the message, please use an HTML compatible remail viewer!"; // optional, comment out and test
		  $this->kirim_remail->MsgHTML($bodys);
		  $this->kirim_remail->CharSet="UTF-8";
		  $this->kirim_remail->AddAddress($to,$this->data['remail']);
		  $this->kirim_remail->Send();
		  /***************************/
	   }
	   
	   return 'sukses| Reset Password telah dikirim ke remail Anda';
	   
   }

}
?>

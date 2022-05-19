<?php

class controller_Konfirmasi {
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
		$this->dataModel	= new model_Konfirmasi();
		$this->Fungsi		= new FungsiUmum();
	}
   
	public function simpandata(){
		$pesan = '';
		$this->data['noorder']  		= isset($_POST['noorder']) ? $_POST['noorder']:'';
		$this->data['jmlbayar'] 		= isset($_POST['jmlbayar']) ? abs($_POST['jmlbayar']):'';
		$this->data['bankfrom']  		= isset($_POST['bankfrom']) ? $_POST['bankfrom']:'';
		$this->data['norekfrom']	  	= isset($_POST['norekfrom']) ? $_POST['norekfrom']:'';
		$this->data['atasnamafrom']	= isset($_POST['atasnamafrom']) ? $_POST['atasnamafrom']:'';
		$this->data['bankto']  		= isset($_POST['bankto']) ? abs($_POST['bankto']):'';
		$this->data['tglbayar']		= isset($_POST['tglbayar']) ? $this->Fungsi->ftanggal3($_POST['tglbayar']):'';
		/* $this->data['status']		    = $this->Fungsi->fcaridata("_setting_toko","konfirm_status","1","1");
		$this->data['sts_pending']    = $this->Fungsi->fcaridata("_setting_toko","order_status","1","1");
		*/
	  
		$this->data['buktitransfer_tmp']        = $_FILES['buktitransfer']['tmp_name'];
		$this->data['buktitransfer_name']       = isset($_FILES['buktitransfer']['name']) ? $_FILES['buktitransfer']['name']:'';
		$this->data['buktitransfer_size']       = isset($_FILES['buktitransfer']['size']) ? $_FILES['buktitransfer']['size']:0;
		$this->data['buktitransfer_type']       = isset($_FILES['buktitransfer']['type']) ? $_FILES['buktitransfer']['type']:'';
		/*
		$dataextgbr = explode(".",$this->data['buktitransfer_name']);
		
		$extgbr = end($dataextgbr);
		*/
		$ext = pathinfo($this->data['buktitransfer_name'] , PATHINFO_EXTENSION);
		$randomkarakter 	= '012345678910abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$i = 0;
		$kode = '';
		while ($i < 6) { 
			$kode .= substr($randomkarakter, mt_rand(0, strlen($randomkarakter)-1), 1);
			$i++;
		}
		$this->data['buktitransfer'] = "buktitransfer".$kode.'.'.$ext;

		$this->data['sts_pending']    = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_orderstatus');
		$this->data['sts_now']        = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_konfirmstatus');
		$this->data['tglinput']		= date('Y-m-d H:i:s');
		$this->data['ipdata']			= $_SERVER['REMOTE_ADDR'];
		$proses = array();
		if($this->validasi()) {
	     
			$upload = $this->Fungsi->UploadImagebyUkuran($this->data['buktitransfer_tmp'],$this->data['buktitransfer_name'],$this->data['buktitransfer'],'700');
			$validextensions = array("jpeg", "jpg", "png", "gif");
			if ((($this->data['buktitransfer_type'] == "image/gif") || ($this->data['buktitransfer_type'] == "image/png") || ($this->data['buktitransfer_type'] == "image/jpg") || ($this->data['buktitransfer_type'] == "image/jpeg")) && ($this->data['buktitransfer_size'] < 100000) && in_array($ext, $validextensions)) {
				$pesan = $_FILES["buktitransfer"]["error"];
			}
			if($upload && $pesan == '') {
				/*
				$proses[] = $this->dataModel->Simpan($this->data);
		  
				$proses[] = $this->dataModel->UpdateStatusOrder($this->data);
		  
				$proses[] = $this->dataModel->SimpanStatusOrder($this->data);
				*/
				$simpan = $this->dataModel->simpanKonfirm($this->data);
		  
		  
				//$prosestransaksi = $this->dataModel->prosesTransaksi($proses);
				if(!$simpan) {
					
					$status = 'error';
					$pesan = 'Proses Konfirmasi tidak berhasil, silahkan ulangi kembali';
				} else {
					$status = 'success';
					$pesan = 'Terimakasih telah memberikan Konfirmasi Pembayaran Anda. Kami akan segera memproses pembelian Anda';
				}
			} else {
				$status = 'error';
			}
		
		  
		} elseif($this->error) {
	      
			$pesan = implode ("<br>",$this->error);
			$status = 'error';
		
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	} 
   
	private function validasi(){
		if(strlen($this->data['noorder']) == '' ) {
			$this->error[] = 'Masukkan Order Anda';
		} elseif(!$this->dataModel->checkOrder($this->data)) {
			$this->error[] = 'Order Anda tidak ditemukan atau status order sudah di proses';
		}
		  
		if(!is_int((int)$this->data['jmlbayar']) || abs($this->data['jmlbayar'])== 0 ) 
			$this->error[] = 'Masukkan Jumlah Bayar Anda';
		  
	 
		
		if($this->data['bankfrom'] == '') 
			$this->error[] = 'Pilih Bank Anda';
		   
		if($this->data['norekfrom'] == '') 
			$this->error[] = 'Masukkan No. Rekening Anda';
	  
		if($this->data['atasnamafrom'] == '') 
			$this->error[] = 'Masukkan Atas Nama Bank Anda';
	  
		if($this->data['bankto'] == '') 
			$this->error[] = 'Pilih Bank Tujuan Transfer';
	  
		$datatgl = explode("-",$this->data['tglbayar']);
		$bulan   = (int)$datatgl[1];
		$tgl     = (int)$datatgl[2];
		$thn     = (int)$datatgl[0];
	 
		if($this->data['tglbayar'] == '' && !checkdate($bulan,$tgl,$thn))
			$this->error[] = 'Masukkan Tanggal Bayar';
		  
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
   
	function getKonfirm($noorder) {
		return $this->dataModel->getKonfirm($noorder);
	}
}
?>

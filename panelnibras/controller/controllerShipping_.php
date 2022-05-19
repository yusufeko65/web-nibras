<?php

class controllerShipping {

  

	private $page;

	private $rows;

	private $offset;

	private $db;

	private $model;

	private $dataFungsi;

	private $data=array();

      

	function __construct(){

		$this->model= new modelShipping();

		$this->dataFungsi= new FungsiUmum();

	}

	

	public function tampildata()

	{

		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;

		$this->rows		= 10;

		$result 			= array();

		$filter				= array();

		

		

		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';

		

		$result["total"] = 0;

		$result["rows"] = '';

		

		$this->offset = ($this->page-1)*$this->rows;



		$result["total"]   = $this->model->totalShipping($data);

		$result["rows"]    = $this->model->getShippingLimit($this->offset,$this->rows,$data);

		$result["page"]    = $this->page; 

		$result["baris"]   = $this->rows;

		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));

		

		return $result;

	}

	public function getAllServicesAndTarifByWilayahRajaOngkir($origin,$kecamatan_penerima,$berat,$apiurl,$apikey){

		$shipping = $this->model->getShippingRajaOngkir();

		$kurir = [];

		$cekKurir = [];

		

		foreach($shipping as $ship){

			//$kurir[] = $ship['shipping_kode'];

			if(!in_array($ship['shipping_kdrajaongkir'],$cekKurir)){

				$kurir["shipping_kdrajaongkir"][] = $ship['shipping_kdrajaongkir'];

				

				$cekKurir[] = $ship['shipping_kdrajaongkir'];

			}

			

			$kurir["shipping_id"][] = $ship['shipping_id'];

			$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis"=>$ship['servis_id'],"shipping_code"=>$ship['shipping_kdrajaongkir']);

			$kurir["servis_nama"][] = $ship['servis_nama'];

			

							 

		}

		

		$kurir["shipping_kdrajaongkir"] = isset($kurir["shipping_kdrajaongkir"]) ? $kurir["shipping_kdrajaongkir"] : array();

		

		$shipping_code = implode(":",$kurir["shipping_kdrajaongkir"]);

		

		$data['curl'] 		= curl_init();

		$data['urlcurl'] 	= $apiurl.'cost';

		$data['postfield'] 	= "origin=$origin&originType=city&destination=$kecamatan_penerima&destinationType=subdistrict&weight=$berat&courier=".strtolower($shipping_code);

		

		$data['httpheader'] =	array(

									"content-type: application/x-www-form-urlencoded",

									"key: ".$apikey

								);

		$grab = $this->dataFungsi->grabData($data);

		$datagrab = json_decode($grab, true);

		

		//$jmldata 		= count($datagrab['rajaongkir']['results'][0]['costs']);

		$jmldata 		= isset($datagrab['rajaongkir']['results']) ? count($datagrab['rajaongkir']['results']) : 0;

		$dataship = [];

		if($jmldata > 0 ) {

			for ($i=0; $i < $jmldata; $i++) {

			

				$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);

				for($x=0;$x < $jmlservis; $x++){

					$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

					

					$dataship[] = array("shipping_code_rajaongkir"=>$kode_ship,

										"shipping_code"=>$kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"],

										"servis_id"=>$kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"],

										"servis_code"=>$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],

										"tarif"=>$datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],

										"etd"=>$datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],

										"shipping_konfirmadmin"=>0,

										"shipping_rajaongkir"=>1);

				}

				

			}

		}

	

		return $dataship;

		

	}

	public function getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan){

		return $this->model->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);

	}

	public function getAllServicesTarifByWilayahJSON(){

		$data = [];

		foreach ($_POST as $key => $value) {

			$data["{$key}"]	= isset($_POST["$key"]) ? $value : '';

			

		}

		//print_r($data);

		/*

		$getservis = $this->model->getAllServicesAndTarifByWilayah($data['propinsi_penerima'],$data['kabupaten_penerima'],$data['kecamatan_penerima']);

		if($getservis) {

			$status = 'success';

			$result = $getservis;

		} else {

			$status = 'error';

			$result = 'Data tidak ada';

		}

		*/

		$modelsetting = new modelSetting();

		$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin','config_apiurlongkir','config_apikeyongkir'));

		foreach($setting as $st){

			$key 	= $st['setting_key'];

			$value 	= $st['setting_value'];

			$$key = $value;

		}

		

		$servis_rajaongkir = $this->getAllServicesAndTarifByWilayahRajaOngkir($config_lokasiorigin,$data['kecamatan_penerima'],$data['totberat'],$config_apiurlongkir,$config_apikeyongkir);

		$servis_ondb = $this->model->getAllServisKonfirmAdmin();

		

		$services = [];

		foreach($servis_rajaongkir as $ship) {

			$services[] = $ship;

		}

		foreach($servis_ondb as $servdb) {

			$services[] = $servdb;

		}

		if(count($services) > 0) {

			$status = 'success';

		} else {

			$status = 'error';

		}

		echo json_encode(array("status"=>$status,"result"=>$services));

		//echo json_encode(array("status"=>$status,"result"=>$result));

		

	}

	public function tarifkurir(){

		$wil = '';

		/*

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			$wilayah = [];

			foreach ($_POST as $key => $value) {

				$data["$key"]	= isset($_POST["$key"]) ? $value : '';

				if($key == 'kecamatan_penerima'){

					$wilayah[] = $value;

				}

			}

			$cektarif = $this->model->tarifkurir($data);

			if($cektarif == 'Konfirmasi Admin') {

				$tarif = 'Konfirmasi Admin';

				$nilaitarif = 0;

				$total = 'Konfirmasi Admin';

				$nilaitotal = 0;

			} else {

				$tarif = "Rp. ".$this->dataFungsi->fuang($cektarif);

				$nilaitarif = $cektarif;

				$nilaitotal = (int)$data['subtotal'] + $cektarif;

				$total = "Rp. ".$this->dataFungsi->fuang($nilaitotal);

			}

			if(count($wilayah) > 0){

				$wil = implode(",",$wilayah).','.$data['serviskurir'];

			}

			$result = 'valid';

			$status = 'success';

		} else {

			$status = 'error';

			$result = 'Tidak valid';

			$tarif = '';

			$nilaitarif = 0;

			$nilaitotal = 0;

			$total = '';

		}

		*/

		$wil = '';

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$data = [];

			$wilayah = [];

			foreach ($_POST as $key => $value) {

				$data["$key"]	= isset($_POST["$key"]) ? $value : '';

				if($key == 'kecamatan_penerima'){

					$wilayah[] = $key;

				}

			}

			$biaya_packing = 0;

			$serviskurir = isset($data['serviskurir']) ? explode("::",$data['serviskurir']) : array();

			if($data['cg_biaya_packing'] == 1 && $data['cust_tanpa_biaya_packing'] != 1){
				$biaya_packing = (int)$data['biaya_packing'];
			}
			
			$cektarif	= $serviskurir[1];

			//$cektarif = $this->model->tarifkurir($data);

			if($cektarif == 'Konfirmasi Admin') {

				$tarif = 'Konfirmasi Admin';

				$nilaitarif = 0;

				$total = 'Konfirmasi Admin';

				$nilaitotal = 0;

			} else {

				$tarif = "Rp. ".$this->dataFungsi->fuang($cektarif);

				$nilaitarif = $cektarif;

				$nilaitotal = (int)$data['subtotal'] + $cektarif + $biaya_packing;

				$total = "Rp. ".$this->dataFungsi->fuang($nilaitotal);

			}

			if(count($wilayah) > 0){

				$wil = implode(",",$wilayah).','.$data['serviskurir'];

			}

			$result = 'valid';

			$status = 'success';

		} else {

			$status = 'error';

			$result = 'Tidak valid';

			$tarif = '';

			$nilaitarif = 0;

			$nilaitotal = 0;

			$total = '';

		}

		echo json_encode(array("status"=>$status,
		"tarif"=>$tarif,
		"nilaitarif"=>$nilaitarif,
		"total"=>$total,
		"nilaitotal"=>$nilaitotal,
		"wil"=>$wil,
		"biayaPacking"=>$biaya_packing
		));

	}

	

	function datashippingByID($id){

		return $this->model->getShippingByID($id);

	}

	

	function simpandata($jenis){

		$data = [];

		foreach ($_POST as $key => $value) {

			$data["{$key}"]	= isset($_POST["$key"]) ? $value : '';

			

		}

		$data['shipping_bataskoma'] = isset($data['shipping_bataskoma']) && $data['shipping_bataskoma'] != '' ? $data['shipping_bataskoma'] : 0;

		if($jenis == 'tambah') $this->add($data);

		else $this->edit($data);

	}

	

	function add($data){

		$ext = pathinfo($_FILES['shipping_logo']['name'], PATHINFO_EXTENSION);

		$data['shipping_logo_nama'] = $data['shipping_kode'].'.'.$ext;

		$cek = $this->dataFungsi->cekHak(folder,"add",1);

		if($cek){

			

			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";

			$status = 'error';

			

		} else {

			

			if($this->model->checkDataShipping($data['shipping_kode'])){

				

				$pesan = "Kode Shipping telah dipergunakan. Silahkan Masukkan Kode Shipping lainnya ";	

				$status = 'error';

				

			} else {

				

				if(!empty($_FILES['shipping_logo']['name'])){

					if(is_uploaded_file($_FILES['shipping_logo']['tmp_name'])) {

						if($_FILES['shipping_logo']['size'] < 1000000) {

							$modelsetting = new modelSetting();

							$setting = $modelsetting->getSettingByKeys(array('config_logokurir_p','config_logokurir_l'));

							foreach($setting as $st){

								$key 	= $st['setting_key'];

								$value 	= $st['setting_value'];

								$data["{$key}"] = $value;

							}

							

							$ext = pathinfo($_FILES['shipping_logo']['name'], PATHINFO_EXTENSION);

							$data['shipping_logo_nama'] = strtolower($data['shipping_kode']).'.'.$ext;

							

							$upload = $this->dataFungsi->UploadImagebyUkuran($_FILES['shipping_logo']['tmp_name'],$_FILES['shipping_logo']['name'],$data['shipping_logo_nama'],$data['config_logokurir_p'],$data['config_logokurir_l']);

							if(!$upload){

								$pesan = "Upload Tidak berhasil";

								$status = 'error';

							}

							

						} else {

							$pesan = " Maksimal File 1 MB ";

							$status = 'error';

						}

					}

				} else {

					$data['shipping_logo_nama'] = '';

				}

				$simpan = $this->model->simpanShipping($data);

				if($simpan['status'] == 'success') {

					$status = 'success';

					$pesan = 'Berhasil Menyimpan Produk';

				

				} else {

					$status = 'error';

					$pesan = 'Gagal proses menyimpan produk';

					$this->dataFungsi->hapusfilegambar($data['shipping_logo_nama'],'other');

				}

				

			}

			

		}

		echo json_encode(array("status"=>$status,"result"=>$pesan,"shipping_id"=>$data['shipping_id']));

	}

	

	function edit($data){

		$pesan = '';

		$status = '';

		$ext = pathinfo($_FILES['shipping_logo']['name'], PATHINFO_EXTENSION);

		$data['shipping_logo_nama'] = strtolower($data['shipping_kode']).'.'.$ext;

		$cek = $this->dataFungsi->cekHak('shipping',"edit",1);

		

		if($cek){

			

			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";

			$status = 'error';

			

		} else {

			$stsupload = false;

			if($data['shipping_logo_old'] != '' && $_FILES['shipping_logo']['name'] == '') {

				$data['shipping_logo_nama'] = $data['shipping_logo_old'];

				

			}

			$modelsetting = new modelSetting();

			$setting = $modelsetting->getSettingByKeys(array('config_logokurir_p','config_logokurir_l'));

			foreach($setting as $st){

				$key 	= $st['setting_key'];

				$value 	= $st['setting_value'];

				$data["{$key}"] = $value;

			}

			if(!empty($_FILES['shipping_logo']['name'])){

				if($_FILES['shipping_logo']['size'] < 1000000) {

					if(is_uploaded_file($_FILES['shipping_logo']['tmp_name'])) {

					      

						//if(file_exists(DIR_IMAGE.'_other/other_'.$data['shipping_logo_old'])) unlink(DIR_IMAGE.'_other/other_'.$data['shipping_logo_old']);

						   

						$upload = $this->dataFungsi->UploadImagebyUkuran($_FILES['shipping_logo']['tmp_name'],$_FILES['shipping_logo']['name'],$data['shipping_logo_nama'],$data['config_logokurir_p'],$data['config_logokurir_l']);

						if(!$upload){

							$pesan = "Upload Tidak berhasil";

							$status = 'error';

							$stsupload = true;

						}

					}

				} else {

					

					$pesan = " Maksimal File 1 MB ";

					$status = 'error';

					

				}

				

				

			}

			

			if($status != 'error' ) {

				

				if ($data['shipping_kode_lama'] != $data['shipping_kode']) {

					if($this->model->checkDataShipping($data['shipping_kode'])){

						$pesan = "Kode Shipping telah dipergunakan. Silahkan Masukkan Kode Shipping lainnya ";	

						$status = 'error';

					}

				}	

				if($status != 'error') {

					//$data['shipping_logo_nama'] = 'other_'.$data['shipping_logo_nama'];

					$simpan = $this->model->editShipping($data);

					if($simpan['status'] == 'success') {

						$status = 'success';

						$pesan = 'Berhasil Menyimpan Shipping';

						if($stsupload === true && $data['shipping_logo_old'] != '') {

							$this->dataFungsi->hapusfilegambar($data['shipping_logo_old'],'other');

						}

					} else {

						$status = 'error';

						$pesan = 'Gagal proses menyimpan Shipping';

						

						$this->dataFungsi->hapusfilegambar($data['shipping_logo_nama'],'other');

					}

					

				}

		

				

			}

		}

		echo json_encode(array("status"=>$status,"result"=>$pesan,"shipping_id"=>$data['shipping_id']));

	}

	function getServisAllByKurir(){

		$data = [];

		$data['idkurir']	= isset($_GET['pid']) ? $_GET['pid'] : 0;

		$page 				= isset($_GET['page']) ? intval($_GET['page']) : 1;

		$rows				= 10;

		$result 			= array();

		$filter				= array();

		

		

		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';

		

		$result["total"] = 0;

		$result["rows"] = '';

		

		$offset = ($page-1)*$rows;



		$result["total"]   = $this->model->totalServisAll($data);

		$result["rows"]    = $this->model->getServisAllByKurir($offset,$rows,$data);

		$result["page"]    = $page; 

		$result["baris"]   = $rows;

		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));

		

		return $result;

	}

	function dataservisByID($idservis){

		return $this->model->getServisByID($idservis);

	}

	

	function simpanservis($jenis){

		$data = [];

		foreach ($_POST as $key => $value) {

			$data["{$key}"]	= isset($_POST["$key"]) ? $value : '';

			

		}

		

		if($jenis == 'tambah') $this->addservis($data);

		else $this->editservis($data);

	}

	

	private function addservis($data){

		$pesan = '';

		$status = '';

		

		$cek = $this->dataFungsi->cekHak('shipping',"add",1);

		

		if($cek){

			

			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";

			$status = 'error';

			

		} else {

			if($this->model->checkDataServis($data)){

				$pesan = "Kode Servis telah dipergunakan. Silahkan Masukkan Kode Servis lainnya ";	

				$status = 'error';

			} else {

				$simpan = $this->model->simpanServis($data);

				if($simpan['status'] == 'success') {

					$status = 'success';

					$pesan = 'Berhasil Menyimpan Servis';

					

				} else {

					$status = 'error';

					$pesan = 'Gagal proses Menyimpan Servis';

					

				}

			}

			

		}

		echo json_encode(array("status"=>$status,"result"=>$pesan));

	}

	

	private function editservis($data){

		$pesan = '';

		$status = '';

		

		$cek = $this->dataFungsi->cekHak('shipping',"edit",1);

		

		if($cek){

			

			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";

			$status = 'error';

			

		} else {

			if ($data['servis_code_lama'] != $data['servis_code']) {

				if($this->model->checkDataServis($data)){

					$pesan = "Kode Shipping telah dipergunakan. Silahkan Masukkan Kode Shipping lainnya ";	

					$status = 'error';

				}

			}

			if($status != 'error') {

				

				$simpan = $this->model->editServis($data);

				if($simpan['status'] == 'success') {

					$status = 'success';

					$pesan = 'Berhasil Menyimpan Servis';

					

				} else {

					$status = 'error';

					$pesan = 'Gagal proses Menyimpan Servis';

					

				}

				

			}

		}

		echo json_encode(array("status"=>$status,"result"=>$pesan));

	}

	

	function hapusservis(){

		$id = isset($_POST['id']) ? $_POST['id']:'';

		$dataId = explode(":",$id);

		$dataError=array();

		$pid = [];

		$modul = "hapus";

		$pesan = '';

		$cek = $this->dataFungsi->cekHak("shipping","del",1);

		if($cek) {

			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";

		} else {

			foreach($dataId as $data){

				$servis = $this->model->getServisByID($data);

				if(!$this->model->checkRelasiServis($data)){

					array_push($pid,$data);

					

				} else {

					$dataError[] = $servis['servis_nama'];

				}

			}

			if(count($pid)) {

				

				$this->model->hapusServis($pid);

			}

		}

		if($dataError) {

			$status = 'error';

			$pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);

		} else {

			$status = 'success';

			$pesan = 'Berhasil menghapus data servis';

		}

		echo json_encode(array("status"=>$status,"result"=>$pesan));

	}

	

	function importservis(){

		

		foreach ($_POST as $key => $value) {

			$data["{$key}"]	= isset($_POST["$key"]) ? $value : '';

			

		}

		$modelsetting = new modelSetting();

		$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin','config_apikeyongkir','config_apiurlongkir'));

		foreach($setting as $st){

			$key 	= $st['setting_key'];

			$value 	= $st['setting_value'];

			$data["{$key}"] = $value;

		}

		$data['curl'] 		= curl_init();

		$data['urlcurl'] 	= $data['config_apiurlongkir'].'cost';

		$data['postfield'] 	= "origin=153&originType=city&destination=153&destinationType=city&weight=1000&courier=".strtolower($data['shipping_code']);

		

		$data['httpheader'] =	array(

									"content-type: application/x-www-form-urlencoded",

									"key: ".$data['config_apikeyongkir']

								);

		$grab = $this->dataFungsi->grabData($data);

		$datagrab = json_decode($grab, true);

		

		$jmldata 		= count($datagrab['rajaongkir']['results'][0]['costs']);

		$servisdb		= $this->model->getServisByKurir($data['shipping_id']);

		$dataserv = [];

		foreach($servisdb as $serv){

			$dataserv[] = $serv['servis_code'];

		}

		$dataloop = [];

		$jmlimport = 0;

		for ($i=0; $i < $jmldata; $i++) {

			if(!in_array($datagrab['rajaongkir']['results'][0]['costs'][$i]['service'],$dataserv)) {

				$dataloop[] = "(null,'".$datagrab['rajaongkir']['results'][0]['costs'][$i]['service']."','".$datagrab['rajaongkir']['results'][0]['costs'][$i]['description']."','".$data['shipping_id']."')";

				$jmlimport = $jmlimport+1;

			}

		}

		//print_r($dataloop);

		if(count($dataloop)>0) {

			$value = implode(",",$dataloop);

			

			$import = $this->model->importservis($value);

			if($import) {

				$status = 'success';

				$pesan = 'Berhasil mengimport '.$jmlimport.'servis dari rajaongkir.com';

			} else {

				$status = 'error';

				$pesan = 'Gagal mengimport servis dari rajaongkir.com';

			}

		} else {

			$status = 'error';

			$pesan = 'Servis dari rajaongkir tidak ditemukan yang baru';

		}

		echo json_encode(array("status"=>$status,"result"=>$pesan));

	}

}

?>


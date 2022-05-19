<?php
class controllerTarif {
  
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $dataFungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelTarif();
		$this->dataFungsi= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';

		$this->data['id'] 	    = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['kecamatan']	= isset($_POST['kecamatan']) ? $_POST['kecamatan']:'';
		$this->data['kabupaten']	= isset($_POST['kabupaten']) ? $_POST['kabupaten']:'';
		$this->data['propinsi']	= isset($_POST['propinsi']) ? $_POST['propinsi']:'';
		$this->data['negara']	    = isset($_POST['negara']) ? $_POST['negara']:'';
		$this->data['servis']	    = isset($_POST['servis']) ? $_POST['servis']:'';
		$this->data['tarif']	    = isset($_POST['tarif']) ? $_POST['tarif']:'';
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
			  if($this->model->checkDataJne($this->data['kecamatan'],$this->data['kabupaten'],$dataservis[$i])){
				$servisn = $this->dataFungsi->fcaridata('_servis_jne','servis_nama','idsJNE',$dataservis[$i]);
				$kecamatann = $this->dataFungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$this->data['kecamatan']);
				$pesan = "Tarif Jne untuk kecamatan $kecamatann dan servis $servisn tersebut telah terdaftar. ";
				break;
			  //} else {
			  } elseif($datatarif[$i] != '' && $datatarif[$i] != 0) {
				$value[$i] = "('','".$this->data['kecamatan']."','".$this->data['kabupaten']."','".$this->data['propinsi']."','".$this->data['negara']."','".$dataservis[$i]."','".$datatarif[$i]."','".$dataket[$i]."')";
			  }
			}
			
			
			if($this->data['propinsi'] =='' || $this->data['propinsi'] == '0') $pesan = 'Pilih Propinsi Terlebih dahulu';
			if($this->data['kabupaten'] =='' || $this->data['kabupaten'] == '0') $pesan = 'Pilih Kota/Kabupaten Terlebih dahulu';
			if($this->data['kecamatan'] =='' || $this->data['kecamatan'] =='0') $pesan = 'Pilih Kecamatan Terlebih dahulu';
			if($jmldt > ($jmlds-1)) $pesan = 'Masukkan Tarif';

		}

		if($pesan == ''){
			$zdata = implode(",",$value);
			if(!$this->model->simpanJne($zdata)) $pesan="SQL Salah";
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
			if(!$this->model->checkDataJneByID($this->data['id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editJne($this->data)) $pesan="SQL Salah";
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
	public function adddiskon() {
		$pesan="";
		$modulnya = "input";
		$valkot = array();
		$valservis = array();
	  
		$idakhir = $this->dataFungsi->fcaridata('_tarif_jne_diskon','MAX(idjnedisk)+1','','');
		if($idakhir == Null)  $idakhir = 1;

		if(count($this->data['kabupaten']) > 0 ) {
			for($i=0 ; $i < count($this->data['kabupaten']) ; $i++){		
				if(!$this->model->checkDiskonJneKota($this->data['kabupaten'][$i])) {
					$valkot[$i] = "('".$idakhir."', '".$this->data['kabupaten'][$i]."')";
				} else {
					$kota = $this->dataFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$this->data['kabupaten'][$i]); 
					$pesan = " Kota/Kabupaten ".$this->data['kabupaten'][$i]." telah terdaftar, pilih kota/kabupaten lainnya ";
					break;
				}
			}

		} else {
		   $pesan = " Kota/Kabupaten harap diisi terlebih dahulu ";
		}
  
		if($pesan == ''){
			for($i=0 ; $i < count($this->data['servis']) ; $i++){
			   $valservis[$i] = "('".$idakhir."','".$this->data['servis'][$i]."')";
			}
			if(count($valkot) > 0 ) {
				$datakota = implode(",",$valkot);
			}
			if(count($valservis) > 0 ) {
				$dataservis = implode(",",$valservis);
			}
			$this->data['id'] = $idakhir;
			if(!$this->model->simpanDiskon($this->data)) $pesan=" SQL JNE Diskon Salah ";
			if(!$this->model->simpanDiskonServis($dataservis)) $pesan=" SQL JNE Diskon Servis Salah ";
			if(!$this->model->simpanDiskonTujuan($datakota)) $pesan=" SQL JNE Diskon Tujuan Salah ";
		}
		return $this->dataFungsi->pesandata($pesan,$modulnya);
	}
	function editdiskon(){
		$modulnya = "update";
		$pesan = "";
		$valkot = array();
	    $valservis = array();
		$dtjnetujuan= $this->dataJneDiskonTujuanByID($this->data['id']);
		$cek = $this->dataFungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataJneDiskonByID($this->data['id'])){
				$pesan = " Ada kesalahan data ";
			} else {
			    if(count($this->data['kabupaten']) > 0 ) {
			      for($i=0 ; $i < count($this->data['kabupaten']) ; $i++){
				    if(!in_array($this->data['kabupaten'][$i],$dtjnetujuan)) {
					   if($this->model->checkDiskonJneKota($this->data['kabupaten'][$i])) {
                          $kota = $this->dataFungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$this->data['kabupaten'][$i]); 
						  $pesan = " Kota/Kabupaten ".$kota." telah terdaftar, pilih kota/kabupaten lainnya ";
						  break;  
					   }
					}
					if($pesan =='')
						$valkot[$i] = "('".$this->data['id']."', '".$this->data['kabupaten'][$i]."')";
					
				  }

			    } else {
			      $pesan = " Kota/Kabupaten harap diisi terlebih dahulu ";
			    }
				
			}
			if($pesan == ''){
				for($i=0 ; $i < count($this->data['servis']) ; $i++){
					$valservis[$i] = "('".$this->data['id']."','".$this->data['servis'][$i]."')";
				}
				if(count($valkot) > 0 ) {
					$datakota = implode(",",$valkot);
				}
				if(count($valservis) > 0 ) {
					$dataservis = implode(",",$valservis);
				}
				$this->model->hapusJneDiskon($this->data['id'],'servis');
				$this->model->hapusJneDiskon($this->data['id'],'tujuan');
				if(!$this->model->editDiskon($this->data)) $pesan=" SQL JNE Diskon Salah ";
				if(!$this->model->simpanDiskonServis($dataservis)) $pesan=" SQL JNE Diskon Servis Salah ";
				if(!$this->model->simpanDiskonTujuan($datakota)) $pesan=" SQL JNE Diskon Tujuan Salah ";
			}
		}
		return $this->dataFungsi->pesandata($pesan,$modulnya);
	}
	public function simpandiskonservis($aksi){
		$hasil = '';

		$this->data['id'] 	    = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['servis']	    = isset($_POST['jservis']) ? $_POST['jservis']:'';
		$this->data['jmldisk']	= isset($_POST['jmldisk']) ? $_POST['jmldisk']:'';
		$this->data['stsdiskon']  = isset($_POST['stsdiskon']) ? $_POST['stsdiskon']:'0';
		if ($aksi=='simpan') $hasil = $this->adddiskonservis();
		else $hasil = $this->editdiskonservis();

		return $hasil;
	}
	public function adddiskonservis() {
		$pesan="";
		$modulnya = "input";
		if(!$this->model->checkDataJneDiskonServisByServis($this->data['servis'])){
			if(!$this->model->simpanServisDiskon($this->data)) $pesan=" SQL JNE Diskon Salah ";
		} else {
			$pesan = ' Servis JNE sudah terdaftar, silahkan pilih Servis JNE lainnya ';
		}


		return $this->dataFungsi->pesandata($pesan,$modulnya);
	}
	function editdiskonservis(){
		$modulnya = "update";
		$pesan = "";
		$cek = $this->dataFungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataServisJneDiskonByID($this->data['id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				if(!$this->model->editServisDiskon($this->data)) $pesan="SQL Salah";
			}
		}
		return $this->dataFungsi->pesandata($pesan,$modulnya);
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

		$result["total"]   = $this->model->totalJne($where);
		$result["rows"]    = $this->model->getJneLimit($this->offset,$this->rows,$where);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		
		return $result;
	}
	public function tampildatadiskon(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		//$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		//if($caridata!='') $filter[] = " kota_nama like '%".trim(strip_tags($caridata))."%'";
		//if(!empty($filter))	$where = implode(" and ",$filter);
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalJneDiskon($where);
		$result["rows"]    = $this->model->getJneLimitDiskon($this->offset,$this->rows,$where);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		
		return $result;
	}
	public function tampildatadiskonservis(){
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		//$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		//if($caridata!='') $filter[] = " kota_nama like '%".trim(strip_tags($caridata))."%'";
		//if(!empty($filter))	$where = implode(" and ",$filter);
		
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalJneServisDiskon($where);
		$result["rows"]    = $this->model->getJneLimitServisDiskon($this->offset,$this->rows,$where);
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
					if(!$this->model->hapusJne($data)) $pesan="SQL Salah";
				//} else {
				//	$jne = $this->model->getJneByID($data);
				//	$dataError[] = $jne['kecamatan_nama'];
				//}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
	function hapusdiskon(){
  
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
					if(!$this->model->hapusJneDiskon($data,'diskon')) $pesan="Hapus diskon Salah";
					if(!$this->model->hapusJneDiskon($data,'servis')) $pesan="Hapus JNE DIskon servis Salah";
					if(!$this->model->hapusJneDiskon($data,'tujuan')) $pesan="Hapus JNE DIskon tujuan Salah";
				//} else {
				//	$jne = $this->model->getJneByID($data);
				//	$dataError[] = $jne['kecamatan_nama'];
				//}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
	function hapusdiskonservis(){
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
					if(!$this->model->hapusServisDiskon($data)) $pesan="SQL Salah";
				//} else {
				//	$jne = $this->model->getJneByID($data);
				//	$dataError[] = $jne['kecamatan_nama'];
				//}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;
	
	}
	function getServisJne(){
		return $this->model->getServisJne();
	}

	function dataJneByID($iddata){
		return $this->model->getJneByID($iddata);
	}
	function dataJneDiskonByID($iddata){
		return $this->model->getJneDiskonByID($iddata);
	}
	function dataJneDiskonTujuanByID($iddata){
		return $this->model->getJneDiskonTujuanByID($iddata);
	}
	function dataJneDiskonServisByID($iddata){
		return $this->model->getJneDiskonServisByID($iddata);
	}
	function dataJneServisDiskonByID($iddata){
		return $this->model->getJneServisDiskonByID($iddata);
	}
}
?>

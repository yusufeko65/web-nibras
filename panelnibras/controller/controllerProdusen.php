<?php
class controllerProdusen {
   private $page;
   private $rows;
   private $offset;
   private $model;
   private $Fungsi;
   private $jmlgbr;
   private $data=array();
   
   function __construct(){
		$this->model= new modelProdusen();
		$this->Fungsi= new FungsiUmum();
	}
   
	public function simpandata($aksi){
		$hasil = '';
		$this->data['produsen_id']     = isset($_POST['iddata']) ? $_POST['iddata']:'';
		$this->data['produsen_nama'] = isset($_POST['produsen']) ? $_POST['produsen']:'';
		$this->data['produsenlama'] = isset($_POST['produsenlama']) ? $_POST['produsenlama']:'';
		$this->data['logo_tmp']   = $_FILES['filelogo']['tmp_name'];
		$this->data['logo_name']  = isset($_FILES['filelogo']['name']) ? $_FILES['filelogo']['name']:'';
		$this->data['logo_size']  = isset($_FILES['filelogo']['size']) ? $_FILES['filelogo']['size']:0;
		$this->data['logo_lama']  = isset($_POST['filelama']) ? $_POST['filelama']:'';
		$this->data['produsen_logo']  = 'produsen'.date('HisYmd').$this->data['logo_name'];
		$this->data['produsen_telp'] = isset($_POST['telp']) ? $_POST['telp']:'';
		$this->data['produsen_email'] = isset($_POST['email']) ? $_POST['email']:'';
		$this->data['produsen_alamat'] = isset($_POST['alamat']) ? $_POST['alamat']:'';
		$this->data['produsen_keterangan'] = isset($_POST['keterangan']) ? mysql_real_escape_string(htmlentities($_POST['keterangan'])):'';
		$this->data['produsen_web'] = isset($_POST['web']) ? $_POST['web']:'';
		$this->data['produsen_fb'] = isset($_POST['facebook']) ? mysql_real_escape_string(htmlentities($_POST['facebook'])):'';
		$this->data['produsen_ketgrosir'] = isset($_POST['ketgrosir']) ? mysql_real_escape_string(htmlentities($_POST['ketgrosir'])):'';
		$this->data['produsen_kapasitas'] = isset($_POST['kapasitas']) ? $_POST['kapasitas']:'';
		$this->data['aliasurl']        = isset($_POST['alias']) ? $_POST['alias']:'';

		$this->data['produk_image']    = isset($_FILES['produk_image']['name']) ?  $_FILES['produk_image']['name']:'';

		if($this->data['produk_image'] != '') $this->jmlgbr = count($this->data['produk_image']);
		else $this->jmlgbr = 0;

		if($this->data['aliasurl'] == '' ) $this->data['aliasurl'] = $this->Fungsi->friendlyURL($this->data['produsen_nama']);
		else $this->data['aliasurl'] = $this->Fungsi->friendlyURL($this->data['aliasurl']);

		if ($aksi=='simpan') $hasil = $this->adddata();
		else $hasil = $this->editdata();

		return $hasil;
	} 
   
	public function adddata() {
		$pesan='';
		$modulnya = 'input';
		$value = array();
		$valgbr = array();
		$cek = $this->Fungsi->cekHak(folder,"add",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menambah data ";
		} else {
			if($this->model->checkDataProdusen($this->data['produsen_nama'])){
				$pesan = "Produsen telah dipergunakan. Silahkan Masukkan Produsen lainnya";	
			} else {
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
				$idakhirprodusen = $this->Fungsi->fIdAkhir('_produsen','produsen_id');
				$idakhirprodusen = (int)$idakhirprodusen + 1;
				if($this->jmlgbr > 0) {
					 for($i=0 ; $i < $this->jmlgbr ; $i++){
						$gbr_tmp 	= isset($_FILES['produk_image']['tmp_name'][$i]) ? $_FILES['produk_image']['tmp_name'][$i]:'';;
	                    $gbr_name 	= isset($_FILES['produk_image']['name'][$i]) ? $_FILES['produk_image']['name'][$i]:'';
	                    $gbr_size 	= isset($_FILES['produk_image']['size'][$i]) ? $_FILES['produk_image']['size'][$i]:0;
						$gbr_nmpr = "sample".date('HisYmd').$i.$gbr_name;
						if($gbr_name != '') {
							if($gbr_size < 1000000) {
								$upload = $this->Fungsi->UploadImagebyUkuran($gbr_tmp,$gbr_name,$gbr_nmpr,200);
								if(!$upload) {
									$pesan = " Upload Tidak berhasil ";
									break;
								} else {
								     $valgbr[$i] = "('','".$idakhirprodusen."','".$gbr_nmpr."')";
								
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

				 if(!$this->model->simpanProdusen($this->data)) {
				    $pesan="<br>Gagal Menyimpan Data Produsen";
				 
				}
				if(count($valgbr) > 0) {
				    $datagbr = implode(",",$valgbr);
					if(!$this->model->simpanGambarProduk($datagbr)) $pesan = "<br> Gagal Menyimpan Gambar";
				}
			    //if(!$this->model->simpanDiskonProdusen($datadiskon)) $pesan = "<br> Gagal Menyimpan Diskon";
			}
		}
		$pesan = $this->Fungsi->pesandata($pesan,$modulnya);
		$pesan = "<script>parent.suksesdata('$pesan')</script>";
		return $pesan;
	}
   
	function editdata(){
		$modulnya = 'update';
		$pesan = '';
		$datadiskon = array();
		$delgbr = array();
		$valgbr = array();
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->checkDataProdusenByID($this->data['produsen_id'])){
				$pesan = " Ada kesalahan data ";
			} else {
				
				if($this->data['produsenlama'] != $this->data['produsen_nama']) {
				    if($this->model->checkDataProdusen($this->data['produsen_nama'])) $pesan = "<br>Produsen telah dipergunakan. Silahkan Masukkan Produsen lainnya";	
				}
				if($this->data['logo_lama'] != '' && $this->data['logo_name'] == '') $this->data['produsen_logo'] = $this->data['logo_lama'];
					
				if($this->data['logo_name'] != '') {
				    if($this->data['logo_size'] < 1000000) {
					   if(is_uploaded_file($this->data['logo_tmp'])) {
					       if(file_exists(DIR_IMAGE.'_other/other_'.$this->data['logo_lama'])) unlink(DIR_IMAGE.'_other/other_'.$this->data['logo_lama']);
						   
						   $upload = $this->Fungsi->UploadImagebyUkuran($this->data['logo_tmp'],$this->data['logo_name'],$this->data['produsen_logo'],100);
					       if(!$upload){
					         $pesan = "<br>Upload Tidak berhasil";
					       }
					   }
					}
					
				
				}
				if($this->jmlgbr > 0) {
					 for($i=0 ; $i < $this->jmlgbr ; $i++){
						$gbr_tmp 	= isset($_FILES['produk_image']['tmp_name'][$i]) ? $_FILES['produk_image']['tmp_name'][$i]:'';;
	                    $gbr_name 	= isset($_FILES['produk_image']['name'][$i]) ? $_FILES['produk_image']['name'][$i]:'';
	                    $gbr_size 	= isset($_FILES['produk_image']['size'][$i]) ? $_FILES['produk_image']['size'][$i]:0;
						$gbr_lama = isset($_POST['gbrlama'][$i]) ? $_POST['gbrlama'][$i]:'';
						$idgbr_lama = isset($_POST['idgbrlama'][$i]) ? $_POST['idgbrlama'][$i]:'';
						$gbr_nmpr = "sample".date('HisYmd').$i.$gbr_name;
						
						if($gbr_name != '') {
							if($gbr_size < 1000000) {
								if(file_exists(DIR_IMAGE.'_other/other_'.$gbr_lama)) unlink(DIR_IMAGE.'_other/other_'.$gbr_lama);
								$upload = $this->Fungsi->UploadImagebyUkuran($gbr_tmp,$gbr_name,$gbr_nmpr,100);
								if(!$upload) {
									$pesan = " Upload Tidak berhasil ";
									break;
								} else {
								     $valgbr[$i] = "('','".$this->data['produsen_id']."','".$gbr_nmpr."')";
									 $delgbr[$i] = "idprodgbr = '".$idgbr_lama."'";
								 }
							} else {
								$pesan = " Maksimal File 1MB ";
								break;
							}
						}
					 }
				}
				if($pesan == '') {
				 
				   //$jmldata = count($this->data['idrsgrup']);
				   if(!$this->model->editProdusen($this->data)) {
				   	   $pesan=" Proses ubah data gagal";
				   }
				   if(count($valgbr) > 0) {
						$datagbr = implode(",",$valgbr);
						if(!$this->model->simpanGambarProduk($datagbr)) $pesan = "<br> Gagal Menyimpan Gambar";
				   }
				   if(count($delgbr) > 0){
					     $datagbr = implode(" OR ",$delgbr);
                         $this->model->hapusGambars($datagbr);						 
				   }
				}
				
			}
		}
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
		if($caridata!='') $filter[] = " produsen_nama like '%".trim(strip_tags($caridata))."%'";
		if(!empty($filter))	$where = implode(" and ",$filter);

		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalProdusen($where);
		$result["rows"]    = $this->model->getProdusenLimit($this->offset,$this->rows,$where);
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
		$cek = $this->Fungsi->cekHak(folder,"del",1);
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
		} else {
			foreach($dataId as $data){
			   $produsen = $this->model->getProdusenByID($data);
			   $getGbrProdusen = $this->model->getProdusenGambarByID($data);
				if(!$this->model->checkRelasi($data)){
					if(!$this->model->hapusProdusen($data)) {
						 $pesan= "<br>Gagal Menghapus Data";
					} else {
						if(file_exists(DIR_IMAGE.'_other/other_'.$produsen['produsen_logo'])) unlink(DIR_IMAGE.'_other/other_'.$produsen['produsen_logo']);
						$pesan = '';
					}
					
					foreach($getGbrProdusen as $g){
						if(file_exists(DIR_IMAGE.'_other/other_'.$g['gbr'])) unlink(DIR_IMAGE.'_other/other_'.$g['gbr']);
					}
					$this->model->hapusGambarbyProdusen($data);
					
				} else {
					$dataError[] = $produsen['produsen_nama'];
				}
			}
		}
		if($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n",$dataError);
		if($pesan!='') $pesan="gagal|".$pesan;
		return $pesan;

	}
    
	function getProdusen(){
		return $this->model->getProdusen();
	}

	function dataProdusenByID($iddata){
		return $this->model->getProdusenByID($iddata);
	}
	function getResellerGrup(){
		$produsen = $this->model->getResellerGrup();
		return $produsen;
	}
	public function getGambarProduk($id){
		return $this->model->getGambarProduk($id);
	}
	public function hapusgambar(){
		$pesan = '';
		$id = isset($_POST['gid']) ? $_POST['gid']:'';

		if((int)$id > 0) {
			if(!$this->model->checkDataGambarProdukByID($id)) $pesan = 'Ada kesalahan data';

			if($pesan == '') {
				$gbr = $this->Fungsi->fcaridata('_produsen_gambar','gambar','idprodgbr',$id);
				 if(file_exists(DIR_IMAGE.'_other/other_'.$gbr)) unlink(DIR_IMAGE.'_other/other_'.$gbr);
				$this->model->hapusGambar($id);
			}
		}
		if($pesan!='') $pesan='gagal||'.$pesan;
		else $pesan = 'sukses';
		return $pesan;
	}
}
?>

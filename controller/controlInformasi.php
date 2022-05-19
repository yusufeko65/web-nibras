<?php
class controller_Informasi {
	private $dataModel;
	private $Fungsi;
   
      
	public function __construct(){
		$this->dataModel= new model_Informasi();
		$this->Fungsi	= new FungsiUmum();
	}
   
  
	public function GetMenuInformasi(){
		return $this->dataModel->GetMenuInformasi();
	}
  
	public function checkDataInformasiByID($pid,$alias) {
		return $this->dataModel->checkDataInformasiByID($pid,$alias);
	}
	public function getInformasiByID($pid) {
		$informasi = $this->dataModel->getInformasiByID($pid);
		$result = array();
		if($informasi) {
			$modelbank = new model_Bank();
			$databank  = $modelbank->getBankInRekening();
			$bankhtml = '';
			foreach($databank as $bank){
				$bankhtml .= '<div class="listbankinfo">';
				$bankhtml .= '<img src="'.URL_IMAGE.'_other/other_'.$bank['lgs'].'"><br>';
				$bankhtml .= 'No. Rek '.$bank['rek'].'<br>';
				$bankhtml .= 'A/n '.$bank['an'].'<br>';
				$bankhtml .= 'Cabang '.$bank['cabang'];
				$bankhtml .= '</div>';
			}
			$result['judul'] 	= strip_tags($informasi['info_judul']);
			$result['content']  = str_replace("[bank]",$bankhtml,$informasi['info_detail']);
		} else {
			$result['judul'] = 'Error';
			$result['content'] = 'Tidak ada data';
		}
		return $result;
	}
}
?>

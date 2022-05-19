<?php
//require_once DIR_MODEL."modelKategori.php";
class controller_Kategori {
   private $page;
   private $rows;
   private $offset;
   private $db;
   private $dataModel;
   private $Fungsi;
   private $data=array();
      
  public function __construct(){
		$this->dataModel= new model_Kategori();
		$this->Fungsi	= new FungsiUmum();
	}
   
  
  public function getKategori($induk=0){
	return $this->dataModel->getKategori($induk);
  }
  
  public function dataKategoriByID($iddata){
	return $this->dataModel->getKategoriByID($iddata);
  }
  public function getKategoriByIDAlias($pid,$j) {
    return $this->dataModel->getKategoriByIDAlias($pid,$j);
  }

}
?>

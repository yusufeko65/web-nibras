<?php
require_once DIR_MODEL."modelBanner.php";
class DataControlBanner {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $data=array();
   
   function __construct(){
		$this->dataModel= new DataModelBanner();
		$this->Fungsi= new FungsiUmum();
	}
   
       
  function getBannerBySlot($slot){
	return $this->dataModel->getBannerBySlot($slot);
  }
  
  function dataBannerByID($iddata){
	$Banner = $this->dataModel->getBannerByID($iddata);
	return $Banner;
  }
  
}
?>

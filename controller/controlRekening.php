<?php
class control_Rekening {
   
   private $dataModel;
   private $Fungsi;
         
   function __construct(){
		$this->dataModel= new model_Rekening();
		$this->Fungsi	= new FungsiUmum();
	}
   
  
  function getRekening(){
	$rekening = $this->dataModel->getRekening();
	return $rekening;
  }
  
  function dataRekeningByID($iddata){
	$rekening = $this->dataModel->getRekeningByID($iddata);
	return $rekening;
  }
 
}
?>

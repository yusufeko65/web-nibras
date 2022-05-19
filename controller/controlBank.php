<?php
class controller_Bank {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $data=array();
   
   function __construct(){
		$this->dataModel= new model_Bank();
		$this->Fungsi= new FungsiUmum();
	}
  
  function getBank(){
	return $this->dataModel->getBank();
  }
  
  function getBankInRekening(){
	return $this->dataModel->getBankInRekening();
  }
  
  function getBankByID($iddata){
	return $this->dataModel->getBankByID($iddata);
  }
  
  public function getRekening($bank) {
    return $this->dataModel->getRekening($bank);
  }
  
}
?>

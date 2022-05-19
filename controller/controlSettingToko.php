<?php
class controller_SettingToko {
   private $dataModel;
   private $Fungsi;
   
      
  public function __construct(){
		$this->dataModel= new model_SettingToko();
		$this->Fungsi	= new FungsiUmum();
	}
   
  
  public function getSettingToko(){
	return $this->dataModel->getSettingToko();
  }
  
}
?>

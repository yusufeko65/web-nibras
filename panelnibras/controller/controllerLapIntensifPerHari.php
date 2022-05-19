<?php
require_once DIR_INCLUDE."model/modelLapIntensifPerhari.php";


class controllerLapIntensifPerhari {
   private $model;
   private $Fungsi;
   private $data=array();
      
   function __construct(){
		$this->model= new modelLapIntensifPerhari();
		$this->Fungsi= new FungsiUmum();
   }
   
    public function tampilData(){

	
	$filter				= array();
	$where 				= '';
		
	$tgl 	= isset($_GET['tgl']) ? $_GET['tgl']:date('Y-m-d');
	
	
	
	if($tgl!='') $filter[] = " tgl_konfirm= '".trim(strip_tags(urlencode($tgl)))."'";
	
	if(!empty($filter))	$where = implode(" and ",$filter);
	
	return $this->model->getIntensif($where);
  }
   

}
?>

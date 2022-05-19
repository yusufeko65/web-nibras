<?php
require_once DIR_INCLUDE."model/modelVisitor.php";
class controllerVisitor{
   private $Fungsi;
   private $model;
   private $data=array();
   
   function __construct(){
		$this->model= new modelVisitor();
		$this->Fungsi	= new FungsiUmum();
	}
   function getVisitor($jenis,$tgl){
       return $this->model->getVisitor($jenis,$tgl);
   }
   function getVisitorGrafikBulan($thn){
       return $this->model->getVisitorGrafikBulan($thn);
   }

}
?>
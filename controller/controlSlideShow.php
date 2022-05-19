<?php
//require_once DIR_MODEL."modelTestimonial.php";
class controller_SlideShow {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $bank;
   private $data=array();
   private $error = array();
   private $kirim_email;
   
   function __construct(){
		$this->dataModel	= new model_SlideShow();
		$this->Fungsi		= new FungsiUmum();
   
   }
   
  function getSlideShow(){
	return $this->dataModel->getSlideShow();
  }

}
?>

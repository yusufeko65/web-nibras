<?php
class controller_Atribut {
	public function __construct(){
		$this->dataModel= new model_Atribut();
	}
	public function getWarna(){
		return $this->dataModel->getWarna();
	}
	
	public function getUkuran(){
		return $this->dataModel->getUkuran();
	}
	
	public function getWarnaByAlias($alias){
		return $this->dataModel->getWarnaByAlias($alias);
	}
	public function getUkuranByAlias($alias){
		return $this->dataModel->getUkuranByAlias($alias);
	}
}
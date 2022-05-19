<?php
class controller_Reseller {
	private $dataModel;
	private $Fungsi;
   
      
	public function __construct(){
		$this->dataModel= new model_Reseller();
		$this->Fungsi	= new FungsiUmum();
	}
	public function getResellerByID($id){
		return $this->dataModel->getResellerByID($id);
	}
	public function getGrupCustByID($tipemember){
		return $this->dataModel->getGrupCustByID($tipemember);
	}
	public function getGrupCustMulti($grup){
		return $this->dataModel->getGrupCustMulti($grup);
	}
	public function getGrupCusts($tipemember){
		return $this->dataModel->getGrupCusts($tipemember);
	}
	public function getGrupAllCusts(){
		return $this->dataModel->getGrupAllCusts();
	}
	
	public function getPoin($idmember) {
		return $this->dataModel->getPoin($idmember);
	}
	public function totalPoin($idmember) {
		return $this->dataModel->gettotalPoin($idmember);
	}
	public function getDeposito($idmember) {
		return $this->dataModel->getDeposito($idmember);
	}
	public function totalDeposito($idmember) {
		return $this->dataModel->gettotalDeposito($idmember);
	}
	public function getAlamatCustomer($idmember){
		return $this->dataModel->getAlamatCustomer($idmember);
	}
	public function getAlamatCustomerByID($id){
		return $this->dataModel->getAlamatCustomerByID($id);
	}
}
?>

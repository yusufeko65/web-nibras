<?php
class controllerLapCustomer
{
	private $model;
	private $Fungsi;
	private $data = array();

	function __construct()
	{
		$this->model = new modelLapCustomer();
		$this->Fungsi = new FungsiUmum();
	}

	public function tampilData()
	{

		$result 			= array();
		$filter				= array();
		$data 				= [];

		$data['bulan'] 	= isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
		$data['tahun'] 	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
		$data['grup'] = isset($_GET['grup']) ? $_GET['grup'] : '';


		return $this->model->getCustomer($data);
	}

	public function tampilCustomerDaily()
	{


		$data 				= [];

		$data['tanggal1'] 	= isset($_GET['tanggal1']) ? $_GET['tanggal1'] : date('Y-m-d');
		$data['tanggal2'] 	= isset($_GET['tanggal2']) ? $_GET['tanggal2'] : date('Y-m-d');
		$data['grup'] = isset($_GET['grup']) ? $_GET['grup'] : '';


		return $this->model->getCustomerDaily($data);
	}
}

<?php
class controllerLapProduk
{
	private $model;
	private $Fungsi;
	private $data = array();

	function __construct()
	{
		$this->model = new modelLapProduk();
		$this->Fungsi = new FungsiUmum();
	}

	public function tampilData()
	{
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		$result 			= array();
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page - 1) * $this->rows;
		$result["rows"] = $this->model->getResult($this->offset, $this->rows);
		$result["total"]   = $this->model->totalProduk();
		$result["page"]    = $this->page;
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));
		return $result;
	}

	public function getLapProdukByKategori($kategori = 0, $search, $baris = 10)
	{
		//$kategori = isset($_GET['kat']) ? $_GET['kat']  : 0;
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= $baris;
		$result 			= array();
		//$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page - 1) * $this->rows;

		$result["rows"] = $this->model->getProdukByKategori($kategori, $this->offset, $this->rows, $search);
		$result["total"]   = $this->model->totalProdukByKategori($kategori, $search);
		$result["page"]    = $this->page;
		$result["baris"]   = $this->rows;
		if ($baris > 0) {
			$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));
		}
		return $result;
	}
	public function getUkuranKategori($kategori)
	{
		return $this->model->getUkuranKategori($kategori);
	}

	public function getStokProdukPerKategoriPerWarnaUkuran($kategori)
	{
		return $this->model->getStokProdukPerKategoriPerWarnaUkuran($kategori);
	}
}

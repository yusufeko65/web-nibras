<?php
class controllerUkuran
{
	private $iddata;
	private $nmukuran;
	private $ukuranlama;
	private $urutan;
	private $alias;
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;

	function __construct()
	{
		$this->model = new modelUkuran();
		$this->Fungsi	= new FungsiUmum();
	}

	public function simpandata($aksi)
	{
		$hasil = '';
		$data = array();
		$this->iddata 	= isset($_POST['iddata']) ? $_POST['iddata'] : '';
		$this->nmukuran	= isset($_POST['ukuran']) ? $_POST['ukuran'] : '';
		$this->ukuranlama	= isset($_POST['ukuranlama']) ? $_POST['ukuranlama'] : '';
		$this->urutan	= isset($_POST['urutan']) ? $_POST['urutan'] : '';
		$this->alias   	= isset($_POST['alias']) ? $_POST['alias'] : '';

		if ($this->alias == '') $this->alias = $this->Fungsi->friendlyURL($this->nmukuran);
		else $this->alias = $this->Fungsi->friendlyURL($this->alias);

		if ($aksi == 'simpan') {
			$hasil = $this->adddata();
		} else {
			$hasil = $this->editdata();
		}
		return $hasil;
	}

	public function adddata()
	{
		$pesan = "";
		$modulnya = "input";
		$cek = $this->Fungsi->cekHak(folder, "add", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk menambah data ";
		} else {
			if ($this->model->checkDataUkuran($this->nmukuran)) {
				$pesan = "Ukuran telah dipergunakan. Silahkan Masukkan Ukuran lainnya";
			} else {
				$data['ukuran_id'] = $this->iddata;
				$data['ukuran_nama'] = $this->nmukuran;
				$data['urutan'] = $this->urutan;
				$data['ukuran_alias'] = $this->alias;
				if (!$this->model->simpanUkuran($data)) $pesan = "SQL Salah";
			}
		}
		return $this->Fungsi->pesandata($pesan, $modulnya);
	}

	function editdata()
	{
		$modulnya = "update";
		$pesan = '';

		$cek = $this->Fungsi->cekHak(folder, "edit", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if (!$this->model->checkDataUkuranByID($this->iddata)) {
				$pesan = ' <br>Ada kesalahan data ';
			} else {
				if ($this->ukuranlama != $this->nmukuran) {
					if ($this->model->checkDataUkuran($this->nmukuran)) {
						$pesan = '<br>Ukuran telah dipergunakan. Silahkan Masukkan Ukuran lainnya';
					}
				}
			}
		}
		if ($pesan == '') {
			$data['ukuran_id'] = $this->iddata;
			$data['ukuran_nama'] = $this->nmukuran;
			$data['ukuran_alias'] = $this->alias;
			$data['urutan'] = $this->urutan;
			if (!$this->model->editUkuran($data)) $pesan = "SQL Salah";
		}
		return $this->Fungsi->pesandata($pesan, $modulnya);
	}


	public function tampildata()
	{
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();

		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari'] : '';


		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page - 1) * $this->rows;

		$result["total"]   = $this->model->totalUkuran($data);
		$result["rows"]    = $this->model->getUkuranLimit($this->offset, $this->rows, $data);
		$result["page"]    = $this->page;
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));
		return $result;
	}

	function hapusdata()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$dataId = explode(":", $id);
		$dataError = array();
		$modul = "hapus";
		$pesan = '';
		$cek = $this->Fungsi->cekHak(folder, "del", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk menghapus ";
		} else {
			foreach ($dataId as $data) {
				if (!$this->model->checkRelasi($data)) {
					if (!$this->model->hapusUkuran($data)) $pesan = "SQL Salah";
				} else {
					$ukuran = $this->model->getUkuranByID($data);
					$dataError[] = $ukuran['ukuran_nama'];
				}
			}
		}
		if ($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n", $dataError);
		if ($pesan != '') $pesan = "gagal|" . $pesan;
		return $pesan;
	}

	function getUkuran()
	{
		$ukuran = $this->model->getUkuran();
		return $ukuran;
	}

	function dataUkuranByID($iddata)
	{
		$ukuran = $this->model->getUkuranByID($iddata);
		return $ukuran;
	}
	function getAutocompleteUkuran()
	{
		$data['caridata']	= isset($_GET['cari']) ? $_GET['cari'] : '';


		$json = array();
		$results = $this->model->getUkuranLimit(0, 0, $data);
		foreach ($results as $result) {
			$json[] = array(
				'ukuran_id' => $result['idukuran'],
				'name'        => strip_tags(html_entity_decode($result['ukuran'], ENT_QUOTES, 'UTF-8'))
			);
		}
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);
		echo json_encode($json);
	}
}
 
<?php
class controllerKategori
{
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data = array();

	public function __construct()
	{
		$this->model = new modelKategori();
		$this->Fungsi	= new FungsiUmum();
	}

	public function simpandata($aksi)
	{
		$hasil = '';
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach ($_POST as $key => $value) {
				$this->data["{$key}"] = $value;
			}
			$this->data['spesial'] = isset($this->data['spesial']) ? $this->data['spesial'] : '0';
			$this->data['tgl'] = date('Y-m-d H:i:s');
			if ($this->data['kategori_alias'] == '') $this->data['kategori_alias'] = $this->Fungsi->friendlyURL($this->data['kategori_nama']);
			else $this->data['kategori_alias'] = $this->Fungsi->friendlyURL($this->data['kategori_alias']);

			$ext = pathinfo($_FILES['filelogo']['name'], PATHINFO_EXTENSION);
			$this->data['kategori_logo'] = 'kate' . trim(strip_tags(trim(date('YmdHis')))) . "." . $ext;

			if ($aksi == 'simpan') $hasil = $this->adddata();
			else $hasil = $this->editdata();

			return $hasil;
		}
	}

	public function adddata()
	{
		$pesan = "";
		$modulnya = "input";
		$cek = $this->Fungsi->cekHak(folder, "add", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if ($this->model->checkDataKategori($this->data['kategori_nama'])) {
				$pesan = "Kategori telah dipergunakan. Silahkan Masukkan Kategori lainnya";
				$status = 'error';
			} else {
				if (!empty($_FILES['filelogo']['name'])) {
					if (is_uploaded_file($_FILES['filelogo']['tmp_name'])) {
						if ($_FILES['filelogo']['size'] < 1000000) {
							$modelsetting = new modelSetting();
							$fieldsetting = array(
								'config_kategorithumbnail_p',
								'config_kategorithumbnail_l',
								'config_kategorismall_p', 'config_kategorismall_l'
							);
							$setting = $modelsetting->getSettingByKeys($fieldsetting);
							foreach ($setting as $st) {
								$key 	= $st['setting_key'];
								$value 	= $st['setting_value'];
								${$key} = $value;
							}
							$logothumb = 'thumb' . $this->data['kategori_logo'];
							$logosmall = 'small' . $this->data['kategori_logo'];
							$datalogo[] = array(
								"nama_image" => $logothumb,
								"panjang" => $config_kategorithumbnail_p,
								"lebar" => $config_kategorithumbnail_l
							);
							$datalogo[] = array(
								"nama_image" => $logosmall,
								"panjang" => $config_kategorismall_p,
								"lebar" => $config_kategorismall_l
							);

							$upload = $this->Fungsi->UploadImagebyUkuranMulti($_FILES['filelogo']['tmp_name'], $_FILES['filelogo']['name'], $datalogo);
						} else {
							$pesan = " Maksimal File 1 MB ";
							$status = 'error';
						}
					}
				}
			}

			if ($pesan == '') {
				$this->data['pathcategory'] = $this->model->getPathInduk($this->data['kategori_induk']);
				$simpan = $this->model->simpanKategori($this->data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$pesan = 'Berhasil Menyimpan Kategori';
				} else {
					$status = 'error';
					$pesan = 'Gagal proses menyimpan kategori';
					$this->Fungsi->hapusfilegambar('thumb' . $this->data['kategori_logo']);
					$this->Fungsi->hapusfilegambar('small' . $this->data['kategori_logo']);
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan));
	}

	public function editdata()
	{
		$modulnya = "update";
		$pesan = "";
		$cek = $this->Fungsi->cekHak(folder, "edit", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if (!$this->model->checkDataKategoriByID($this->data['iddata'])) {
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {

				if (!empty($_FILES['filelogo']['name'])) {

					if (is_uploaded_file($_FILES['filelogo']['tmp_name'])) {
						if ($_FILES['filelogo']['size'] < 1000000) {
							$modelsetting = new modelSetting();
							$fieldsetting = array(
								'config_kategorithumbnail_p',
								'config_kategorithumbnail_l',
								'config_kategorismall_p', 'config_kategorismall_l'
							);
							$setting = $modelsetting->getSettingByKeys($fieldsetting);
							foreach ($setting as $st) {
								$key 	= $st['setting_key'];
								$value 	= $st['setting_value'];
								${$key} = $value;
							}
							$logothumb = 'thumb' . $this->data['kategori_logo'];
							$logosmall = 'small' . $this->data['kategori_logo'];

							$datalogo[] = array(
								"nama_image" => $logothumb,
								"panjang" => $config_kategorithumbnail_p,
								"lebar" => $config_kategorithumbnail_l
							);

							$datalogo[] = array(
								"nama_image" => $logosmall,
								"panjang" => $config_kategorismall_p,
								"lebar" => $config_kategorismall_l
							);

							$upload = $this->Fungsi->UploadImagebyUkuranMulti($_FILES['filelogo']['tmp_name'], $_FILES['filelogo']['name'], $datalogo);
						} else {
							$pesan = " Maksimal File 1 MB ";
							$status = 'error';
						}
					}
				} else {
					if ($this->data['filelama'] != '') {
						$this->data['kategori_logo'] = $this->data['filelama'];
					} else {
						$this->data['kategori_logo'] = '';
					}
					//print_r($this->data['kategori_logo'])'
				}

				if ($pesan == '') {
					$this->data['pathcategory'] = $this->model->getPathInduk($this->data['kategori_induk']);

					$simpan = $this->model->editKategori($this->data);
					$image = $this->data['kategori_logo'];
					if ($simpan['status'] == 'success') {

						$status = 'success';
						$pesan 	= 'Berhasil Menyimpan Kategori';

						$this->Fungsi->hapusfilegambar('small' . $this->data['filelama'], 'other');
						$this->Fungsi->hapusfilegambar('thumb' . $this->data['filelama'], 'other');
					} else {

						$status = 'error';
						$pesan 	= 'Gagal proses menyimpan kategori';
					}
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "image" => $image));
	}

	public function tampildata()
	{
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;

		$this->rows		= 10;
		$result 			= array();
		$data = array();
		$data["datacari"]	= isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$data["spesial"]	= isset($_GET['spesial']) ? $_GET['spesial'] : '';

		$result["rows"] = '';

		$result["rows"]    = $this->model->getKategoriLimit($data, 0);
		return $result;
	}

	public function hapusdata()
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
				$kategori = $this->model->getKategoriByID($data);
				if (!$this->model->checkRelasi($data)) {
					if (!$this->model->hapusKategori($data)) {
						$pesan = "SQL Salah";
					} else {
						$pesan = '';
						if (file_exists(DIR_IMAGE . '_other/other_' . $kategori['image'])) unlink(DIR_IMAGE . '_other/other_' . $kategori['image']);
					}
				} else {

					$dataError[] = $kategori['name'];
				}
			}
		}
		if ($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n", $dataError);
		if ($pesan != '') $pesan = "gagal|" . $pesan;
		return $pesan;
	}

	public function getKategori()
	{
		$data['datacari']	= isset($_GET['cari']) ? $_GET['cari'] : '';
		$data['spesial']	= isset($_GET['spesial']) ? $_GET['spesial'] : '0';
		$kategori = $this->model->getKategoriLimit($data, 0);
		return $kategori;
	}
	public function getAutoCompleteKategori($headproduk = 0)
	{
		$data['datacari']	= isset($_GET['cari']) ? $_GET['cari'] : '';
		$data['id']			= isset($_GET['id']) ? $_GET['id'] : 0;

		if ($headproduk == '1') {
			$data['spesial']	= isset($_GET['spesial']) ? $_GET['spesial'] : '1';
		} else {
			$data['spesial']	= isset($_GET['spesial']) ? $_GET['spesial'] : '0';
		}

		$json = array();
		$results = $this->model->getKategoriLimit($data, 0);
		foreach ($results as $result) {
			$json[] = array(
				'category_id' => $result['kategori_id'],
				'name'        => strip_tags(html_entity_decode($result['kategori_nama'], ENT_QUOTES, 'UTF-8'))
			);
		}
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);
		echo json_encode($json);
	}
	public function dataKategoriByID($iddata)
	{
		$kategori = $this->model->getKategoriByID($iddata);
		return $kategori;
	}
	public function dataKategoriByIDs($iddata)
	{
		return $this->model->getKategoriByIDs($iddata);
	}
	public function getWarnaKategoriByKategori($id)
	{
		return $this->model->getWarnaKategoriByKategori($id);
	}

	public function getKategoriUkuran($id)
	{
		return $this->model->getKategoriUkuran($id);
	}
	public function getListKategori($id)
	{
		return $this->model->getListKategori($id);
	}
}

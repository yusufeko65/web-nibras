<?php
class controllerProduk
{
	private $page;
	private $rows;
	private $offset;
	private $model;
	private $jmlkat;
	private $jmlwarna;
	private $jmlwarnagbr;
	private $jmlgrup;
	private $jmlgbr;
	private $jmlstokop;
	private $Fungsi;
	private $data = array();

	function __construct()
	{
		$this->model = new modelProduk();
		$this->Fungsi = new FungsiUmum();
	}

	public function simpandata($aksi)
	{
		$hasil = '';

		foreach ($_POST as $key => $value) {

			$this->data["{$key}"] = $value;
		}
		$this->data['jml_stok'] = isset($this->data['jml_stok']) ? $this->data['jml_stok'] : 0;
		$this->data['hrg_diskon'] = isset($this->data['hrg_diskon']) ? $this->data['hrg_diskon'] : 0;
		$this->data['persen_diskon'] = isset($this->data['persen_diskon']) ? $this->data['persen_diskon'] : 0;
		$this->data['tglupdate'] = date('Y-m-d H:i:s');

		$ext = pathinfo($_FILES['gbr_produk']['name'], PATHINFO_EXTENSION);
		$this->data['produk_logo'] = 'cover' . trim(strip_tags(trim(date('YmdHis')))) . "." . $ext;


		if ($this->data['alias_url'] == '') $this->data['alias_url'] = $this->Fungsi->friendlyURL($this->data['nama_produk']);
		else $this->data['alias_url'] = $this->Fungsi->friendlyURL($this->data['alias_url']);

		if ($aksi == 'simpan') $hasil = $this->adddata();
		else $hasil = $this->editdata();

		return $hasil;
	}

	public function adddata()
	{
		$pesan = '';
		$modulnya = 'input';

		$error     = false;
		$produk_id = 0;
		$cek = $this->Fungsi->cekHak(folder, "add", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if ($this->model->checkDataProduk($this->data['kode_produk'])) {
				$pesan = "Kode Produk telah dipergunakan. Silahkan Masukkan Kode Produk lainnya";
				$status = 'error';
			} else {

				if (!empty($_FILES['gbr_produk']['name'])) {
					if (is_uploaded_file($_FILES['gbr_produk']['tmp_name'])) {
						if ($_FILES['gbr_produk']['size'] < 1000000) {
							$upload = $this->Fungsi->UploadProduk($_FILES['gbr_produk']['tmp_name'], $_FILES['gbr_produk']['name'], $this->data['produk_logo']);
						} else {
							$pesan = " Maksimal File 1 MB ";
							$status = 'error';
						}
					}
				}
			}
			if ($pesan == '') {
				$simpan = $this->model->simpanProduk($this->data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$pesan = 'Berhasil Menyimpan Produk';
					$produk_id = $simpan['idproduk'];
				} else {
					$status = 'error';
					$pesan = 'Gagal proses menyimpan produk';
					$produk_id = '';
					$this->Fungsi->hapusfilegambar($this->data['produk_logo']);
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "produk_id" => $produk_id));
	}

	function editdata()
	{
		$modulnya = 'update';
		$pesan = '';
		$status = '';

		$cek = $this->Fungsi->cekHak(folder, "edit", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if (!$this->model->checkDataProdukByID($this->data['idproduk'])) {
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
				if ($this->data['produklama'] != '' && $_FILES['gbr_produk']['name'] == '') $this->data['produk_logo'] = $this->data['gbr_produk_lama'];


				if (!empty($_FILES['gbr_produk']['name'])) {
					if ($_FILES['gbr_produk']['size'] < 1000000) {

						if (is_uploaded_file($_FILES['gbr_produk']['tmp_name'])) {


							$upload = $this->Fungsi->UploadProduk($_FILES['gbr_produk']['tmp_name'], $_FILES['gbr_produk']['name'], $this->data['produk_logo']);
							if (!$upload) {
								$pesan = "Upload Tidak berhasil";
								$status = 'error';
							}
						}
					} else {
						$pesan = " Maksimal File 1 MB ";
						$status = 'error';
					}
				}

				if ($status != 'error') {
					if ($this->data['kodelama'] != $this->data['kode_produk']) {
						if ($this->model->checkDataProduk($this->data['kode_produk'])) {
							$pesan = "Kode Produk telah dipergunakan. Silahkan Masukkan Kode Produk lainnya ";
							$status = 'error';
						}
					}
					if ($status != 'error') {

						$simpan = $this->model->editProduk($this->data);
						if ($simpan['status'] == 'success') {
							$status = 'success';
							$pesan = 'Berhasil Menyimpan Produk';
							$this->Fungsi->hapusfilegambar($this->data['gbr_produk_lama']);
						} else {
							$status = 'error';
							$pesan = 'Gagal proses menyimpan produk';

							$this->Fungsi->hapusfilegambar($this->data['produk_logo']);
						}
					}
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "produk_id" => $this->data['idproduk']));
	}

	public function simpanheadproduk($aksi)
	{
		$hasil = '';

		foreach ($_POST as $key => $value) {
			$this->data["{$key}"] = $value;
		}
		$ext = pathinfo($_FILES['gbr_produk']['name'], PATHINFO_EXTENSION);
		$this->data['produk_logo'] = 'cover' . trim(strip_tags(trim(date('YmdHis')))) . "." . $ext;

		$this->data['tglupdate'] = date('Y-m-d H:i:s');

		if ($this->data['alias_url'] == '') $this->data['alias_url'] = $this->Fungsi->friendlyURL($this->data['nama_produk']);
		else $this->data['alias_url'] = $this->Fungsi->friendlyURL($this->data['alias_url']);

		if ($aksi == 'simpan') $hasil = $this->addheadproduk();
		else $hasil = $this->editheadproduk();

		return $hasil;
	}

	public function addheadproduk()
	{
		$pesan = '';
		$modulnya = 'input';

		$error     = false;

		$cek = $this->Fungsi->cekHak(folder, "add", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk menambah data ";
			$status = 'error';
		} else {
			if ($this->model->checkDataProdukHead($this->data['kode_produk'])) {
				$pesan = "Kode Produk telah dipergunakan. Silahkan Masukkan Kode Produk lainnya";
				$status = 'error';
			} else {

				if (!empty($_FILES['gbr_produk']['name'])) {
					if (is_uploaded_file($_FILES['gbr_produk']['tmp_name'])) {
						if ($_FILES['gbr_produk']['size'] < 1000000) {
							$upload = $this->Fungsi->UploadProduk($_FILES['gbr_produk']['tmp_name'], $_FILES['gbr_produk']['name'], $this->data['produk_logo']);
						} else {
							$pesan = " Maksimal File 1 MB ";
							$status = 'error';
						}
					}
				}
			}
			if ($pesan == '') {
				$simpan = $this->model->simpanHeadProduk($this->data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$pesan = 'Berhasil Menyimpan Produk';
					$produk_id = $simpan['idproduk'];
				} else {
					$status = 'error';
					$pesan = 'Gagal proses menyimpan produk';
					$produk_id = '';
					$this->Fungsi->hapusfilegambar($this->data['produk_logo']);
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "produk_id" => $produk_id));
	}

	public function editheadproduk()
	{
		$modulnya = 'update';
		$pesan = '';
		$status = '';

		$cek = $this->Fungsi->cekHak(folder, "edit", 1);
		if ($cek) {
			$pesan = " Anda tidak mempunyai Akses untuk mengubah data ";
			$status = 'error';
		} else {
			if (!$this->model->checkDataProdukHeadByID($this->data['idproduk'])) {
				$pesan = " Ada kesalahan data ";
				$status = 'error';
			} else {
				if ($this->data['produklama'] != '' && $_FILES['gbr_produk']['name'] == '') $this->data['produk_logo'] = $this->data['gbr_produk_lama'];


				if (!empty($_FILES['gbr_produk']['name'])) {
					if ($_FILES['gbr_produk']['size'] < 1000000) {

						if (is_uploaded_file($_FILES['gbr_produk']['tmp_name'])) {


							$upload = $this->Fungsi->UploadProduk($_FILES['gbr_produk']['tmp_name'], $_FILES['gbr_produk']['name'], $this->data['produk_logo']);
							if (!$upload) {
								$pesan = "Upload Tidak berhasil";
								$status = 'error';
							}
						}
					} else {
						$pesan = " Maksimal File 1 MB ";
						$status = 'error';
					}
				}

				if ($status != 'error') {
					if ($this->data['kodelama'] != $this->data['kode_produk']) {
						if ($this->model->checkDataProdukHead($this->data['kode_produk'])) {
							$pesan = "Kode Produk telah dipergunakan. Silahkan Masukkan Kode Produk lainnya ";
							$status = 'error';
						}
					}
					if ($status != 'error') {

						$simpan = $this->model->editProdukHead($this->data);
						if ($simpan['status'] == 'success') {
							$status = 'success';
							$pesan = 'Berhasil Menyimpan Produk';
							$this->Fungsi->hapusfilegambar('gproduk' . $this->data['gbr_produk_lama']);
						} else {
							$status = 'error';
							$pesan = 'Gagal proses menyimpan produk';

							$this->Fungsi->hapusfilegambar('gproduk' . $this->data['produk_logo']);
						}
					}
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "produk_id" => $this->data['idproduk'], "produk_gbr" => 'gproduk' . $this->data['produk_logo']));
	}

	function uploadWarna()
	{
		$data = [];
		$pesan = '';
		$status = '';
		$namawarna = '';
		$datawarna = [];
		$data['imagewarna']  = '';
		foreach ($_POST as $key => $value) {
			if ($key == 'actiondata' || $key == 'idwarna' || $key == 'produk_image' || $key == 'idproduk') {
				$data["{$key}"] = $value;
			}
		}

		if ($data['idwarna'] == '' && $data['idwarna'] == '0') {
			$pesan = 'Masukkan Pilihan Warna';
			$status = 'error';
		}

		if (empty($_FILES['produk_image']['name'])) {
			$pesan = 'Masukkan File Gambar untuk Warna';
			$status = 'error';
		}

		if ($status != 'error') {
			$ext = pathinfo($_FILES['produk_image']['name'], PATHINFO_EXTENSION);
			$data['imagewarna'] = date('YmdHis') . '.' . $ext;
			$cekwarnaproduk = $this->model->getWarnaProdukByProdukWarna($data);
			$cekwarnaproduk['gbr'] = isset($cekwarnaproduk['gbr']) ? $cekwarnaproduk['gbr'] : '';

			if ($_FILES['produk_image']['size'] < 1000000) {

				if (is_uploaded_file($_FILES['produk_image']['tmp_name'])) {

					$upload = $this->Fungsi->UploadProduk($_FILES['produk_image']['tmp_name'], $_FILES['produk_image']['name'], $data['imagewarna']);
					if (!$upload) {
						$pesan = "Upload Tidak berhasil";
						$status = 'error';
					}
				}
			} else {
				$pesan = " Maksimal File 1 MB ";
				$status = 'error';
			}

			if ($status != 'error') {
				$simpan = $this->model->simpanWarnaGambar($data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$pesan = 'Berhasil Mengupload Gambar Warna';
					/*
					$modelwarna = new modelWarna();
					$getwarna = $modelwarna->getWarnaByID($data['idwarna']);
					$namawarna = $getwarna['warna'];
					*/
					$this->Fungsi->hapusfilegambar('gproduk' . $cekwarnaproduk['gbr']);
					$datawarna = $this->model->getWarnaProdukByProduk($data['idproduk']);
				} else {
					$status = 'error';
					$pesan = 'Gagal proses Mengupload Gambar Warna';
				}
			}
		}
		//echo json_encode(array("status"=>$status,"result"=>$pesan,"warna"=>$namawarna,"idwarna"=>$data['idwarna'],"image"=>$data['imagewarna']));
		echo json_encode(array("status" => $status, "result" => $pesan, "datawarna" => $datawarna));
	}

	function hapusWarna()
	{
		$status = '';
		$pesan = '';
		$data = array();
		foreach ($_POST as $key => $value) {
			$data["{$key}"] = $value;
		}
		$getWarnaProduk = $this->model->getWarnaProdukByProdukWarna($data);
		if ($getWarnaProduk) {
			$hapuswarna = $this->model->hapusWarna($data);
			if ($hapuswarna) {
				$status = 'success';
				$pesan = 'Berhasil menghapus warna dan gambarnya';
				$this->Fungsi->hapusfilegambar('gproduk' . $getWarnaProduk['gbr']);
			} else {
				$status = 'error';
				$pesan = 'Gagal menghapus gambar warna';
			}
		} else {
			$status = 'error';
			$pesan = 'Data warna produk tidak valid';
		}
		echo json_encode(array("status" => $status, "result" => $pesan));
	}
	function uploadWarnaHeadProduk()
	{
		$data = [];
		$pesan = '';
		$status = '';
		$namawarna = '';
		$datawarna = [];
		$data['imagewarna']  = '';
		foreach ($_POST as $key => $value) {
			if ($key == 'actiondata' || $key == 'idwarna' || $key == 'produk_image' || $key == 'idproduk') {
				$data["{$key}"] = $value;
			}
		}

		if ($data['idwarna'] == '' && $data['idwarna'] == '0') {
			$pesan = 'Masukkan Pilihan Warna';
			$status = 'error';
		}

		if (empty($_FILES['produk_image']['name'])) {
			$pesan = 'Masukkan File Gambar untuk Warna';
			$status = 'error';
		}

		if ($status != 'error') {
			$ext = pathinfo($_FILES['produk_image']['name'], PATHINFO_EXTENSION);
			$data['imagewarna'] = date('YmdHis') . '.' . $ext;
			$cekwarnaproduk = $this->model->getWarnaProdukHeadByProdukWarna($data);
			if ($cekwarnaproduk) {
				$image =  $cekwarnaproduk['image_head'];
			} else {
				$image = '';
			}
			if ($_FILES['produk_image']['size'] < 1000000) {

				if (is_uploaded_file($_FILES['produk_image']['tmp_name'])) {

					$upload = $this->Fungsi->UploadProduk($_FILES['produk_image']['tmp_name'], $_FILES['produk_image']['name'], $data['imagewarna']);
					if (!$upload) {
						$pesan = "Upload Tidak berhasil";
						$status = 'error';
					}
				}
			} else {
				$pesan = " Maksimal File 1 MB ";
				$status = 'error';
			}

			if ($status != 'error') {
				$simpan = $this->model->simpanWarnaGambarHeadProduk($data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$pesan = 'Berhasil Mengupload Gambar Warna';

					$this->Fungsi->hapusfilegambar('gproduk' . $image);
					$datawarna = $this->model->getWarnaProdukHeadByProduk($data['idproduk']);
				} else {
					$status = 'error';
					$pesan = 'Gagal proses Mengupload Gambar Warna';
				}
			}
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "datawarna" => $datawarna));
	}
	function hapusWarnaHeadProduk()
	{
		$status = '';
		$pesan = '';
		$data = array();
		foreach ($_POST as $key => $value) {
			$data["{$key}"] = $value;
		}
		$getWarnaProduk = $this->model->getWarnaProdukHeadByProdukWarna($data);
		if ($getWarnaProduk) {
			$hapuswarna = $this->model->hapusWarnaHeadProduk($data);
			if ($hapuswarna) {
				$status = 'success';
				$pesan = 'Berhasil menghapus warna dan gambarnya';
				$this->Fungsi->hapusfilegambar('gproduk' . $getWarnaProduk['image_head']);
			} else {
				$status = 'error';
				$pesan = 'Gagal menghapus gambar warna';
			}
		} else {
			$status = 'error';
			$pesan = 'Data warna produk tidak valid';
		}
		echo json_encode(array("status" => $status, "result" => $pesan));
	}

	function getWarnaProdukByProduk($idproduk)
	{
		return $this->model->getWarnaProdukByProduk($idproduk);
	}
	function getWarnaProdukByProdukAndUkuran()
	{
		$idproduk = isset($_GET['idproduk']) ? $_GET['idproduk'] : 0;
		$idukuran = isset($_GET['idukuran']) ? $_GET['idukuran'] : 0;
		$datawarna = $this->model->getWarnaProdukByProdukAndUkuran($idproduk, $idukuran);
		echo json_encode($datawarna);
	}

	function getWarnaProdukHeadByProduk($idproduk)
	{
		return $this->model->getWarnaProdukHeadByProduk($idproduk);
	}
	function uploadGambarDetail()
	{
		$data = [];
		$pesan = '';
		$status = '';
		$namawarna = '';
		$data['imagedetail']  = '';
		$datagambardetail = [];
		foreach ($_POST as $key => $value) {
			if ($key == 'actiondata' || $key == 'produk_image_detail' || $key == 'idproduk') {
				$data["{$key}"] = $value;
			}
		}


		if (empty($_FILES['produk_image_detail']['name'])) {
			$pesan = 'Masukkan File Gambar Detail';
			$status = 'error';
		}

		if ($status != 'error') {
			$ext = pathinfo($_FILES['produk_image_detail']['name'], PATHINFO_EXTENSION);
			$data['imagedetail'] = date('YmdHis') . '.' . $ext;

			if ($_FILES['produk_image_detail']['size'] < 1000000) {

				if (is_uploaded_file($_FILES['produk_image_detail']['tmp_name'])) {

					$upload = $this->Fungsi->UploadProduk($_FILES['produk_image_detail']['tmp_name'], $_FILES['produk_image_detail']['name'], $data['imagedetail']);
					if (!$upload) {
						$pesan = "Upload Tidak berhasil";
						$status = 'error';
					}
				}
			} else {
				$pesan = " Maksimal File 1 MB ";
				$status = 'error';
			}

			if ($status != 'error') {
				$simpan = $this->model->simpanGambarDetail($data);
				if ($simpan['status'] == 'success') {
					$status = 'success';
					$pesan = 'Berhasil Mengupload Gambar Warna';
					$datagambardetail = $this->model->getGambarDetailByProduk($data['idproduk']);
				} else {
					$status = 'error';
					$pesan = 'Gagal proses Mengupload Gambar Warna';
				}
			}
		}
		//echo json_encode(array("status"=>$status,"result"=>$pesan,"image"=>$data['imagedetail']));
		echo json_encode(array("status" => $status, "result" => $pesan, "dataimage" => $datagambardetail));
	}


	function getGambarDetailByProduk($idproduk)
	{
		return $this->model->getGambarDetailByProduk($idproduk);
	}

	function hapusGambarDetail()
	{
		$status = '';
		$pesan = '';
		$data = array();
		foreach ($_POST as $key => $value) {
			$data["{$key}"] = $value;
		}

		$hapusgambar = $this->model->hapusGambarDetail($data);
		if ($hapusgambar) {
			$status = 'success';
			$pesan = 'Berhasil menghapus gambar detail';
			$this->Fungsi->hapusfilegambar('gproduk' . $data['image_detail']);
		} else {
			$status = 'error';
			$pesan = 'Gagal menghapus gambar detail';
		}

		echo json_encode(array("status" => $status, "result" => $pesan));
	}


	function saveStokOption()
	{
		$datastok = [];
		$data = [];

		foreach ($_POST as $key => $value) {
			if ($key == 'actiondata' || $key == 'stok_option' || $key == 'idproduk' || $key == 'idukuran' || $key == 'idwarna' || $key == 'tambahan_harga') {
				$data["{$key}"] = $value;
			}
		}

		$data['tglinsert'] = date('Y-m-d H:i:s');
		$check = $this->model->checkStokOption($data);
		if ($check) {

			$pesan = 'Untuk stok warna / ukuran produk ini sudah tercantum';
			$status = 'error';
		} else {

			$simpan = $this->model->saveStokOption($data);
			if ($simpan['status'] == 'success') {
				$status = 'success';
				$pesan = 'Berhasil Menambah stok';
				$datastok = $this->model->getAllStokOptionByProduk($data['idproduk']);
			} else {
				$status = 'error';
				$pesan = 'Gagal proses Menambah Stok';
			}
		}

		echo json_encode(array("status" => $status, "result" => $pesan, "datastok" => $datastok));
	}
	function editstokoption()
	{
		$data = [];
		$pesan = '';
		foreach ($_POST as $key => $value) {
			$data["{$key}"] = $value;
		}

		$stok_option = $this->model->getStokOptionByProduk($data['idproduk']);
		$data['stok_option'] = $stok_option + $data['stok'];

		$simpan = $this->model->updateStokOption($data);

		if ($simpan['status'] == 'success') {
			$status = 'success';
			$pesan = 'Berhasil Mengubah stok <b class="text-danger">' . $data['nmwarna'] . ' ' . $data['nmukuran'] . '</b>';
		} else {
			$status = 'error';

			$pesan = 'Gagal proses Mengubah Stok <b class="text-danger">' . $data['nmwarna'] . ' ' . $data['nmukuran'] . '</b>';
		}

		echo json_encode(array("status" => $status, "result" => $pesan, "datastok" => $data['stok']));
	}
	function getAllStokOptionByProduk($idproduk)
	{
		return $this->model->getAllStokOptionByProduk($idproduk);
	}
	function hapusstokoption()
	{
		$status = '';
		$pesan = '';
		$data = array();
		foreach ($_POST as $key => $value) {
			$data["{$key}"] = $value;
		}

		$hapusstok = $this->model->hapusStokOption($data);
		if ($hapusstok) {
			$status = 'success';
			$pesan = 'Berhasil menghapus stok per warna/ukuran';
			//$this->Fungsi->hapusfilegambar('gproduk'.$data['image_detail']);
		} else {
			$status = 'error';
			$pesan = 'Gagal menghapus stok per warna/ukuran';
		}

		echo json_encode(array("status" => $status, "result" => $pesan));
	}

	function savehargatambahan()
	{
		$dataharga = [];
		$data = [];

		foreach ($_POST as $key => $value) {
			if ($key == 'actiondata' || $key == 'tambahan_harga' || $key == 'idproduk' || $key == 'idukuran' || $key == 'idwarna') {
				$data["{$key}"] = $value;
			}
		}
		$data['tglinsert'] = date('Y-m-d H:i:s');
		$simpan = $this->model->savehargatambahan($data);
		if ($simpan['status'] == 'success') {
			$status = 'success';
			$pesan = 'Berhasil Menyimpan harga tambahan produk';

			$dataharga = $this->model->getAllHargaTambahanByProduk($data['idproduk']);
		} else {
			$status = 'error';
			$pesan = 'Gagal proses Menyimpan harga tambahan produk';
		}
		echo json_encode(array("status" => $status, "result" => $pesan, "dataharga" => $dataharga));
	}

	function getAllHargaTambahanByProduk($idproduk)
	{
		return $this->model->getAllHargaTambahanByProduk($idproduk);
	}

	function hapustambahharga()
	{
		$status = '';
		$pesan = '';
		$data = array();
		foreach ($_POST as $key => $value) {
			$data["{$key}"] = $value;
		}

		$hapusharga = $this->model->hapustambahharga($data);
		if ($hapusharga) {
			$status = 'success';
			$pesan = 'Berhasil menghapus tambah harga';
		} else {
			$status = 'error';
			$pesan = 'Gagal menghapus tambah harga';
		}

		echo json_encode(array("status" => $status, "result" => $pesan));
	}

	public function tampildata()
	{
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		//$this->rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		$where = '';
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$data['kat']	= isset($_GET['k']) ? $_GET['k'] : '';
		$data['sts']	= isset($_GET['sts']) ? $_GET['sts'] : '';
		
		$stsP = trim(strip_tags($data['sts']));
		if($stsP=="1"){
			$data['sts'] = "0";
		}else if($stsP=="2"){
			$data['sts'] = "1";
		}else{
			$data['sts'] = "";
		}

		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page - 1) * $this->rows;

		$result["total"]   = $this->model->totalProduk($data);
		$result["rows"]    = $this->model->getProdukLimit($this->offset, $this->rows, $data);
		$result["page"]    = $this->page;
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));
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

				if (!$this->model->checkRelasi($data)) {
					$produk = $this->model->getProdukByID($data);

					/* hapus warna & gambar */
					$getGbrProduk = $this->model->getWarnaProdukByProduk($data);
					if ($getGbrProduk) {
						foreach ($getGbrProduk as $g) {
							$this->Fungsi->hapusfilegambar('gproduk' . $g['gbr']);
						}
						//$this->model->hapusWarnaPerProduk($data);
					}

					/* hapus gambar detail */
					$getgbrdetail = $this->model->getGambarDetailByProduk($data);
					if ($getgbrdetail) {
						foreach ($getgbrdetail as $gb) {
							$this->Fungsi->hapusfilegambar('gproduk' . $gb['gbr_detail']);
						}
						//$this->model->hapusGambarDetailPerProduk($data);
					}

					/* hapus tambahan harga per warna */
					//$this->model->hapustambahhargaperproduk($data);

					/* hapus kategori per produk */
					//$this->model->hapusKategoriProduk($data);

					//* hapus stok warna per produk */
					//$this->model->hapusStokOptionPerProduk($data);

					//$this->model->hapusOptionProduk($data);


					if (!$this->model->hapusProduk($data)) {
						$pesan = "Gagal Menghapus Data";
						$status = 'error';
					} else {
						$this->Fungsi->hapusfilegambar('cover' . $produk['gbr_produk']);
						$pesan = '';
					}
				} else {
					$produk = $this->model->getProdukByID($data);
					$dataError[] = $produk['produk_nama'];
				}
			}
		}
		if ($dataError) $pesan = "Data dibawah ini tidak dapat dihapus, karena sedang dipakai di sistem : \n" . implode("\n", $dataError);
		if ($pesan != '') $pesan = "gagal|" . $pesan;
		return $pesan;
	}


	public function dataProdukByID($iddata)
	{
		return $this->model->getProdukByID($iddata);
	}
	public function dataProdukHeadByID($iddata)
	{
		return $this->model->getProdukHeadByID($iddata);
	}
	public function produkAutocomplete()
	{
		$json 	= array();

		$grupmember = isset($_GET['grupreseller']) ? $_GET['grupreseller'] : '';
		$cari		= isset($_GET['cariproduk']) ? $_GET['cariproduk'] : '';
		$produk 	= $this->model->getProduksBy($cari);
		$modelGrup 	= new modelCustomerGrup();
		$datagrup	= $modelGrup->getResellerGrupByID($grupmember);
		if ($produk && $datagrup) {
			foreach ($produk as $prod) {
				$dataukuran = $this->model->getProdukOption($prod['idproduk'], 'ukuran');

				$json[] = array(
					'product_id' 	 => $prod['idproduk'],
					'kode'		 	 => $prod['kode_produk'],
					'nama_produk'	 => strip_tags(html_entity_decode($prod['nama_produk'], ENT_QUOTES, 'UTF-8')),
					'ukuran'     	 => $dataukuran,
					'stok'		 	 => $prod['jml_stok'],
					'berat'		 	 => $prod['berat_produk'],
					'satuan'	 	 => $prod['hrg_jual'],
					'diskon_satuan'  => $prod['hrg_diskon'],
					'min_beli'		 => $datagrup['cg_min_beli'],
					'min_beli_syarat' => $datagrup['cg_min_beli_syarat'],
					'diskon_member'	 =>	$datagrup['cg_diskon'],
					'grup_nama'		 => $datagrup['cg_nm'],
					'grup_id'		 => $datagrup['cg_id']
				);
			}
			$status = 'success';
		} else {
			$status = 'error';
		}
		echo json_encode($json);
	}
	function getProdukKategori($idproduk)
	{
		$modelkategori = new modelKategori();
		$getkategoriproduk = $this->model->getProdukKategori($idproduk);
		if ($getkategoriproduk) {
			$data = [];
			foreach ($getkategoriproduk as $kategoriproduk) {
				$kategori = $modelkategori->getKategoriByID($kategoriproduk['idkategori']);
				$data[] = array(
					"idkategori" => $kategori['category_id'],
					"nama_kategori" => ($kategori['path']) ? $kategori['path'] . ' &gt; ' . $kategori['name'] : $kategori['name']
				);
			}
			return $data;
		}
		return false;
	}
	function getHeadProdukListAll()
	{
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		$result 			= array();

		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari'] : '';
		$data['kat']		= isset($_GET['k']) ? $_GET['k'] : '';


		$result["total"] 	= 0;
		$result["rows"]		= '';
		$this->offset 		= ($this->page - 1) * $this->rows;

		$result["total"]   = $this->model->totalHeadProduk($data);
		$result["rows"]    = $this->model->getHeadProdukListAll($this->offset, $this->rows, $data);
		$result["page"]    = $this->page;
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"]) / intval($result["baris"]));
		return $result;
	}
	function getAutoCompleteProdukHead()
	{
		$data['caridata']	= isset($_GET['cari']) ? $_GET['cari'] : '';
		$data['kat']		= isset($_GET['k']) ? $_GET['k'] : '';
		$json = array();
		$results = $this->model->getHeadProdukListAll('', '', $data, '1');
		foreach ($results as $result) {
			$json[] = array(
				'head_idproduk' => $result['head_idproduk'],
				'nama_produk'   => strip_tags(html_entity_decode($result['nama_produk'], ENT_QUOTES, 'UTF-8'))
			);
		}
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['nama_produk'];
		}

		array_multisort($sort_order, SORT_ASC, $json);
		echo json_encode($json);
	}
	function getStokWarnaUkuranJson()
	{

		$idproduk 	= isset($_GET['idproduk']) ? $_GET['idproduk'] : 0;
		$idukuran 	= isset($_GET['idukuran']) ? $_GET['idukuran'] : 0;
		$idwarna 	= isset($_GET['idwarna']) ? $_GET['idwarna'] : 0;
		$datastok = $this->model->getStokWarnaUkuran($idproduk, $idukuran, $idwarna);
		echo json_encode(array("stok" => $datastok));
	}
	function getStokWarnaUkuran($idproduk, $idukuran, $idwarna)
	{

		return $this->model->getStokWarnaUkuran($idproduk, $idukuran, $idwarna);
	}
}

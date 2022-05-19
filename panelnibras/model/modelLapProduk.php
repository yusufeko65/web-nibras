<?php
class modelLapProduk
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->db 		= new Database();
		$this->db->connect();
	}

	public function getResult($batas, $baris)
	{


		$sql = "select `po`.`idproduk` AS `idproduk`,
						`p`.`kode_produk` AS `kode_produk`,
						`pd`.`nama_produk` AS `nama_produk`,
						`u`.`ukuran` AS `ukuran`,
						`w`.`warna` AS `warna`,
						`po`.`stok` AS `stok` 
				from `_produk_options` `po` 
				left join `_produk` `p` on `p`.`idproduk` = `po`.`idproduk`
				left join `_produk_deskripsi` `pd` on `po`.`idproduk` = `pd`.`idproduk`
				left join `_ukuran` `u` on `po`.`ukuran` = `u`.`idukuran`
				left join `_warna` `w` on `po`.`warna` = `w`.`idwarna` 
				order by `pd`.`nama_produk`,`po`.`ukuran` limit $batas,$baris";

		$sql = $this->db->query($sql);

		if ($sql) {
			$arkab = array();
			foreach ($sql->rows as $rsa) {
				$arkab[] = $rsa;
			}
			return $arkab;
		}
		return false;
	}

	function totalProduk()
	{


		$sql = "select `po`.`idproduk` AS `idproduk`,
						`p`.`kode_produk` AS `kode_produk`,
						`pd`.`nama_produk` AS `nama_produk`,
						`u`.`ukuran` AS `ukuran`,
						`w`.`warna` AS `warna`,
						`po`.`stok` AS `stok` 
				from `_produk_options` `po` 
				left join `_produk` `p` on `p`.`idproduk` = `po`.`idproduk`
				left join `_produk_deskripsi` `pd` on `po`.`idproduk` = `pd`.`idproduk`
				left join `_ukuran` `u` on `po`.`ukuran` = `u`.`idukuran`
				left join `_warna` `w` on `po`.`warna` = `w`.`idwarna` ";


		$sql = $this->db->query($sql);
		return $sql->num_rows;
	}
	function getProdukByKategori($kategori, $batas, $limit, $search)
	{
		$filter = [];
		$search_by = '';
		$sql = "SELECT p.idproduk,kode_produk,nama_produk,
				w.idwarna,w.warna,c.name as nama_kategori,c.category_id
				FROM _produk p 
				INNER JOIN _produk_deskripsi pd
				ON p.idproduk = pd.idproduk
				INNER JOIN _produk_options po 
				ON p.idproduk = po.idproduk
				LEFT JOIN _produk_kategori pk 
				ON p.idproduk  = pk.idproduk
				LEFT JOIN _category_description c 
				ON pk.idkategori = c.category_id
				INNER JOIN  _warna w
				ON  po.warna = w.idwarna";



		if ($search != '') {
			$filter[] = "kode_produk='" . $this->db->escape(trim($search)) . "'";
			if ($kategori != '' && $kategori != '0') {
				$filter[] =	"idkategori = '" . $kategori . "'";
			}
		} else {
			$filter[] =	"idkategori = '" . $kategori . "'";
		}
		if (count($filter > 0)) {
			$search_by = implode(" AND ", $filter);
		}
		if ($search_by != '') {
			$sql .= " WHERE " . $search_by;
		}
		$sql .= " GROUP BY kode_produk,w.warna
				 ORDER BY kode_produk,nama_produk,category_id ASC ";

		if ($limit > 0) {
			$sql .= " limit $batas,$limit ";
		}

		$sql = $this->db->query($sql);

		if ($sql) {
			$data = array();
			foreach ($sql->rows as $rsa) {
				$kat = isset($rsa['category_id']) ? $rsa['category_id'] : 0;
				//$data["{$kat}"][] = $rsa;
				$data[] = $rsa;
			}
			return $data;
		}

		return false;
	}

	function totalProdukByKategori($kategori, $search)
	{
		$filter = [];
		$search_by = '';

		$sql = "SELECT p.idproduk,kode_produk,nama_produk,
				w.idwarna,w.warna,c.name as nama_kategori,c.category_id
				FROM _produk p 
				INNER JOIN _produk_deskripsi pd
				ON p.idproduk = pd.idproduk
				INNER JOIN _produk_options po 
				ON p.idproduk = po.idproduk
				LEFT JOIN _produk_kategori pk 
				ON p.idproduk  = pk.idproduk
				LEFT JOIN _category_description c 
				ON pk.idkategori = c.category_id
				INNER JOIN  _warna w
				ON  po.warna = w.idwarna";



		if ($search != '') {
			$filter[] = "kode_produk='" . $this->db->escape(trim($search)) . "'";
			if ($kategori != '' && $kategori != '0') {
				$filter[] =	"idkategori = '" . $kategori . "'";
			}
		} else {
			$filter[] =	"idkategori = '" . $kategori . "'";
		}
		if (count($filter > 0)) {
			$search_by = implode(" AND ", $filter);
		}
		if ($search_by != '') {
			$sql .= " WHERE " . $search_by;
		}
		$sql .= " GROUP BY kode_produk,w.warna ";
		$sql = $this->db->query($sql);
		return $sql->num_rows;
	}
	function getUkuranKategori($kategori = 0)
	{
		$sql = "select cu.idukuran,cu.category_id,u.ukuran
				from _category_ukuran cu 
				left join _ukuran u
				on cu.idukuran = u.idukuran";
		if ($kategori != '0') {
			$sql .= " where cu.category_id = '" . $kategori . "'";
		}
		$sql .= " ORDER BY u.order_by ASC";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = array();
			foreach ($strsql->rows as $rs) {
				$data[] = $rs;
			}
			return $data;
		}
		return false;
	}

	function getStokProdukPerKategoriPerWarnaUkuran($kategori = 0)
	{
		$sql = "SELECT po.idproduk, stok, ukuran, warna
				FROM _produk_options po
				left join _produk_kategori pk on po.idproduk = pk.idproduk ";
		if ($kategori != '0') {
			$sql .= " where idkategori = '" . $kategori . "'";
		}
		//echo $sql;
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = array();
			foreach ($strsql->rows as $rs) {
				$idproduk 	= $rs['idproduk'];
				$idwarna 	= $rs['warna'];
				$idukuran 	= $rs['ukuran'];
				$stok 		= $rs['stok'];
				$id 		= $idproduk . ':' . $idwarna . ':' . $idukuran;

				$data["{$id}"] = $stok;
				//$data[] = $rs;
			}
			//print_r($data);
			return $data;
		}
		return false;
	}
}

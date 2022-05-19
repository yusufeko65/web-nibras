<?php
class model_Produk
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->tabelnya = '_produk';
		$this->db 		= new Database();
		$this->db->connect();
	}

	public function getProduk()
	{
		$arproduk = array();
		$strsql = $this->db->query("select * from " . $this->tabelnya . " order by produk_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arproduk[] = array(
				'ids' => $rsa['produk_id'],
				'nms' => $rsa['produk_nama']
			);
		}

		return $arproduk;
	}

	function getProduksBy($cari='',$limit='')
	{
		$sql = "select * from " . $this->tabelnya . " LEFT JOIN _produk_deskripsi ON 
				_produk.idproduk = _produk_deskripsi.idproduk
				LEFT JOIN _produk_kategori ON 
				_produk.idproduk = _produk_kategori.idproduk
				WHERE 
				(_produk_deskripsi.nama_produk like '%" . $this->db->escape($cari) . "%') AND _produk.jml_stok > 0 AND status_produk='1'
				 ". $limit;

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = array();
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	public function getProdukOption($id, $tipe)
	{

		if ($tipe == 'warna') {
			$sql = "SELECT idwarna as id,warna as nm FROM _warna WHERE idwarna in (SELECT warna FROM _produk_options WHERE idproduk='" . $id . "')";
		} else {

			$sql = "SELECT DISTINCT(idukuran) as id,_ukuran.ukuran as nm 
				   FROM _produk_options 
		           INNER JOIN _ukuran ON _produk_options.ukuran = _ukuran.idukuran
				   WHERE idproduk = '" . $id . "' AND _produk_options.ukuran > 0 AND stok > 0
                   ORDER BY _ukuran.ukuran asc";
		}

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$arproduk = array();
			foreach ($strsql->rows as $rsa) {
				$arproduk[] = array(
					'id' => $rsa['id'],
					'nm' => $rsa['nm'],
					//'tambahan_harga'=>$rsa['tambahan_harga']
				);
			}
			return $arproduk;
		} else {
			return false;
		}
	}

	public function getProdukWarna($idproduk)
	{
		$sql = "SELECT _produk_options.warna as id,_warna.warna as nm,gbr
				FROM _produk_options 
				inner join _warna ON _produk_options.warna = _warna.idwarna 
				inner join _produk_img on _produk_options.warna = _produk_img.idwarna AND _produk_options.idproduk = _produk_img.idproduk
				WHERE _produk_options.idproduk='" . $idproduk . "' AND stok > 0 group by _produk_options.warna ORDER BY _warna.warna asc";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$arproduk = array();
			foreach ($strsql->rows as $rsa) {
				$arproduk[] = $rsa;
			}
			return $arproduk;
		} else {
			return false;
		}
	}

	public function getProdukSemuaWarna($idproduk)
	{

		$sql = "SELECT _warna.warna as nm,_produk_options.warna as id,
				_produk_img.gbr
				FROM _produk_options 
				LEFT JOIN _warna ON _produk_options.warna = _warna.idwarna
				LEFT JOIN _produk_img ON _produk_options.idproduk = _produk_img.idproduk AND _produk_options.warna = _produk_img.idwarna
				WHERE _produk_options.idproduk = '" . $idproduk . "' AND stok > 0 
				GROUP BY _warna.warna,_produk_options.warna 
				ORDER BY _warna.warna ASC";

		$strsql = $this->db->query($sql);

		if ($strsql) {
			$arproduk = array();
			foreach ($strsql->rows as $rsa) {
				$arproduk[] = $rsa;
			}
			return $arproduk;
		}
		return false;
	}

	public function getProdukWarnaByUkuran($idproduk, $ukuran)
	{

		$query = "SELECT DISTINCT(_produk_options.warna) as id,_warna.warna FROM _produk_options 
		          LEFT JOIN _warna ON _produk_options.warna = _warna.idwarna WHERE idproduk='" . $idproduk . "'
				  AND ukuran = '" . $ukuran . "' AND stok > 0 ORDER BY _warna.warna asc";
		$strsql = $this->db->query($query);
		if ($strsql) {
			$arproduk = array();
			foreach ($strsql->rows as $rsa) {
				$arproduk[] = array(
					'id' => $rsa['id'],
					'nm' => $rsa['warna'],
				);
			}
			return $arproduk;
		} else {
			return false;
		}
	}
	function getStokWarnaUkuran($idproduk, $idukuran, $idwarna)
	{
		$sql = "select stok 
				from _produk_options 
				where idproduk='" . $idproduk . "' 
				and ukuran='" . $idukuran . "'
				and warna='" . $idwarna . "'";

		$strsql = $this->db->query($sql);
		return isset($strsql->row['stok']) ? $strsql->row['stok'] : 0;
	}
	public function getProdukImages($idproduk)
	{
		$arproduk = array();

		$strsql = $this->db->query("SELECT _produk_warna.idwarna,_warna.warna,_produk_gambar.produk_gbr FROM _produk_warna 
							   LEFT JOIN _warna ON _produk_warna.idwarna = _warna.idwarna LEFT JOIN
							   _produk_gambar ON _produk_warna.idgambar = _produk_gambar.idpgambar
								WHERE _produk_warna.idproduk='" . $idproduk . "' AND _produk_warna.idwarna 
								IN (SELECT warna  FROM _produk_option WHERE idproduk='" . $idproduk . "' AND stok > 0) ORDER BY _warna.warna asc");

		foreach ($strsql->rows as $rsa) {
			$arproduk[] = array(
				'id' => $rsa['idwarna'],
				'gbr' => $rsa['produk_gbr'],

			);
		}
		return $arproduk;
	}
	public function getProdukImagesDetail($idproduk)
	{
		$arproduk = array();

		$strsql = $this->db->query("SELECT * from _produk_gbr_detail WHERE idproduk='" . $idproduk . "'");

		foreach ($strsql->rows as $rsa) {
			$arproduk[] = array(
				'id' => $rsa['idproduk'],
				'gbr' => $rsa['gbr_detail']
			);
		}
		return $arproduk;
	}
	public function getProdukImagesbyWarna($produk, $warna, $ukuran)
	{
		/*
		$strsql=$this->db->query("SELECT _produk_warna.*, _produk_gambar.produk_gbr from _produk_warna 
							 LEFT JOIN _produk_gambar ON _produk_warna.idgambar = _produk_gambar.idpgambar 
							 LEFT JOIN _produk_option ON _produk_warna.idwarna = _produk_options.warna 
							 WHERE _produk_warna.idproduk='".$produk."' AND _produk_warna.idwarna = '".$warna."' AND 
							 _produk_options.ukuran = '".$ukuran."'"); */

		//return $strsql->rows;
	}

	public function getProdukStokOption($produk, $warna, $ukuran)
	{
		$strsql = $this->db->query("select stok from _produk_options WHERE idproduk='" . $produk . "' AND ukuran='" . $ukuran . "' AND warna='" . $warna . "'");
		return $strsql->row;
	}

	public function checkDataProdukByID($produk_id)
	{
		$check = $this->db->query("select kode_produk from " . $this->tabelnya . " where idproduk='$produk_id'");
		if ($check->num_rows) {
			return true;
		} else {
			return false;
		}
	}

	public function checkDataKategori($id, $alias)
	{
		$check = $this->db->query("select kategori_id,kategori_alias from _kategori where kategori_id='$id' AND kategori_alias='$alias'");
		if ($check->num_rows) {
			return true;
		} else {
			return false;
		}
	}
	public function getProdukRelateByKategori($idkat)
	{
		$arproduk = array();
		$strsql = $this->db->query("select _produk.idproduk,_produk.kode_produk,_produk_deskripsi.nama_produk,_produk.jml_stok,_produk.gbr_produk,
		             _produk.hrg_jual,_produk_deskripsi.alias_url,_category.alias_url as kat_alias from " . $this->tabelnya . " 
					 LEFT JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk
					 LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk
					 LEFT JOIN _category ON _produk_kategori.idkategori = _category.category_id
					 WHERE _produk_kategori.idkategori = '" . $idkat . "' ORDER BY RAND() asc limit 6");


		foreach ($strsql->rows as $rsa) {
			$arproduk[] = $rsa;
		}

		return $arproduk;
	}
	public function getProdukLimit($batas, $baris, $jenis, $data)
	{
		$filter = [];
		$multifilter = [];
		$where = '';

		$tabelnya = $this->tabelnya . " LEFT JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk ";
		$tabelnya .= " LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk";
		$tabelnya .= " LEFT JOIN _category ON _produk_kategori.idkategori = _category.category_id";

		if ($jenis == 'kategori' && ($data['idkategori'] != '' && $data['idkategori'] != '0')) {

			$filter[] = "  _produk_kategori.idkategori = '" . $data['idkategori'] . "'";
		}
		if ($jenis == 'sale') {
			$filter[] = "  _produk.sale = '1'";
		}
		$tabelnya .= " LEFT JOIN _produk_options ON _produk.idproduk = _produk_options.idproduk";
		if ($data['warna'] != '' && $data['warna'] != '0') {
			$filter[] = "  _warna.alias = '" . $this->db->escape($data['warna']) . "' AND stok > 0";

			$tabelnya .= ' left join _warna on _produk_options.warna = _warna.idwarna';
		}
		if ($data['ukuran'] != '' && $data['ukuran'] != '0') {
			$filter[] = "  _ukuran.alias = '" . $this->db->escape($data['ukuran']) . "' AND stok > 0";

			$tabelnya .= ' left join _ukuran on _produk_options.ukuran = _ukuran.idukuran';
		}



		//if(trim($data['caridata']) != '') $filter[] = " _produk_deskripsi.nama_produk like '%".trim($this->db->escape($data['caridata']))."%'";
		if ($data['caridata'] != '') {
			$kw1 = str_replace(' ', '', $data['caridata']);
			$kw2 = $data['caridata'];

			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim($this->db->escape($kw1)) . "%'";
			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim($this->db->escape($kw2)) . "%'";
			$multifilter[] = " _produk.kode_produk like '%" . trim($this->db->escape($kw1)) . "%'";
			$multifilter[] = " _produk.kode_produk like '%" . trim($this->db->escape($kw2)) . "%'";
			if (count($multifilter) > 0) {
				$filter[] = '(' . implode(" OR ", $multifilter) . ')';
			}
		}



		switch ($data['sortir']) {
			default:
			case "upd":
				$sort = "_produk.date_updated desc";
				break;
			case "new";
				$sort = "_produk.idproduk desc";
				break;
			case "old";
				$sort = "_produk.idproduk asc";
				break;
			case "hrgdesc":
				$sort = "_produk.hrg_jual desc";
				break;
			case "hrgasc":
				$sort = "_produk.hrg_jual asc";
				break;
			case "namaasc":
				$sort = "_produk_deskripsi.nama_produk asc";
				break;
			case "namadesc":
				$sort = "_produk_deskripsi.nama_produk desc";
				break;
		}

		$rows     = "_produk.idproduk,_produk.kode_produk,_produk_deskripsi.nama_produk,_produk.jml_stok,_produk.gbr_produk,
		             _produk.hrg_jual,_produk_deskripsi.alias_url,_category.alias_url as kat_alias,sale,hrg_diskon,persen_diskon";
		$orderby  = $sort . " limit $batas,$baris";

		if (!empty($filter))	$where = implode(" and ", $filter);

		//if($jenis == 'kategori') {
		//   $tabelnya .= " LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk";
		//}
		/*
		if($jenis == 'warna') {
		   $tabelnya .= " LEFT JOIN _produk_options ON _produk.idproduk = _produk_options.idproduk";
		}
		if($jenis == 'ukuran') {
		   $tabelnya .= " LEFT JOIN _produk_options ON _produk.idproduk = _produk_options.idproduk";
		}
		*/
		$tabelnya .= " WHERE _produk.jml_stok > 0 and status_produk='1'";
		if ($where != '') $where = " AND " . $where;
		$sql = 'SELECT ' . $rows . ' FROM ' . $tabelnya . $where . ' group by _produk.idproduk ORDER BY ' . $orderby;



		$strsql = $this->db->query($sql);
		if ($strsql) {
			$hasil = [];
			foreach ($strsql->rows as $row) {
				$hasil[] = $row;
			}
			return $hasil;
		} else {
			return false;
		}
	}

	public function totalProduk($data, $jenis)
	{
		$where  	= '';
		$filter = [];
		$multifilter = [];
		$rows     = "_produk.idproduk";
		$tabelnya = $this->tabelnya . " LEFT JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk ";
		$tabelnya .= " LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk";
		$tabelnya .= " LEFT JOIN _category ON _produk_kategori.idkategori = _category.category_id";

		if ($jenis == 'kategori' && ($data['idkategori'] != '' && $data['idkategori'] != '0')) {

			$filter[] = "  _produk_kategori.idkategori = '" . $data['idkategori'] . "'";
		}
		if ($jenis == 'sale') {
			$filter[] = "  _produk.sale = '1'";
		}
		$tabelnya .= " LEFT JOIN _produk_options ON _produk.idproduk = _produk_options.idproduk";
		if ($data['warna'] != '' && $data['warna'] != '0') {
			$filter[] = "  _warna.alias = '" . $this->db->escape($data['warna']) . "' AND stok > 0";

			$tabelnya .= ' left join _warna on _produk_options.warna = _warna.idwarna';
		}
		if ($data['ukuran'] != '' && $data['ukuran'] != '0') {
			$filter[] = "  _ukuran.alias = '" . $this->db->escape($data['ukuran']) . "' AND stok > 0";

			$tabelnya .= ' left join _ukuran on _produk_options.ukuran = _ukuran.idukuran';
		}
		if ($data['caridata'] != '') {
			$kw1 = str_replace(' ', '', $data['caridata']);
			$kw2 = $data['caridata'];

			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim($this->db->escape($kw1)) . "%'";
			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim($this->db->escape($kw2)) . "%'";
			if (count($multifilter) > 0) {
				$filter[] = '(' . implode(" OR ", $multifilter) . ')';
			}
		}

		$tabelnya .= " WHERE _produk.jml_stok > 0 and status_produk='1'";
		if (!empty($filter))	$where = implode(" and ", $filter);
		if ($where != '') $where = " AND " . $where;
		$sql = 'SELECT ' . $rows . ' FROM ' . $tabelnya . $where . " group by _produk.idproduk ";

		$strsql = $this->db->query($sql);
		$jml = $strsql->num_rows;
		return isset($jml) ? $jml : 0;
	}
	public function getProdukByID($iddata)
	{
		$sql = "select _produk.idproduk,kode_produk,
				jml_stok,gbr_produk,berat_produk,
				hrg_jual,hrg_diskon,persen_diskon,
				sale,nama_produk,keterangan_produk,
				metatag_deskripsi,metatag_keyword,
				_produk_deskripsi.alias_url,poin,head_produk
				from " . $this->tabelnya . " LEFT JOIN _produk_deskripsi 
				ON _produk.idproduk = _produk_deskripsi.idproduk 
				where _produk.idproduk='" . $iddata . "'";

		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}

	public function getHeadProdukByID($id)
	{
		$sql = "select head_idproduk,kode_produk,nama_produk,kategori_produk,_category_description.name,
				deskripsi_head,tag_deskripsi,tag_keyword,url_alias,
				gbr_produk from _produk_head left join _category_description on _produk_head.kategori_produk = _category_description.category_id 
				where head_idproduk='" . $id . "' and status_produk='1'";
		$strsql = $this->db->query($sql);
		if (isset($strsql->row)) {
			$dataproduk = $strsql->row;
			$sql = "select * from _produk_head_warna where idhead_produk='" . $id . "'";
			$strsql = $this->db->query($sql);

			if ($strsql) {
				$datawarna = [];
				foreach ($strsql->rows as $row) {
					$datawarna[] = $row;
				}
			} else {
				$datawarna = false;
			}
			return array("dataproduk" => $dataproduk, "warna" => $datawarna);
		} else {
			return false;
		}
		//return isset($strsql->row) ? $strsql->row : false;
	}

	public function getProdukByHeadproduk($head)
	{


		$sql = "SELECT p.idproduk,p.kode_produk,pd.nama_produk,p.gbr_produk,pd.alias_url,pk.idkategori,cg.name as nama_kategori,w.idwarna,w.warna
				from _produk p
				left join _produk_deskripsi pd on p.idproduk = pd.idproduk
				left join _produk_kategori pk on p.idproduk = pk.idproduk
				left join _produk_options po ON p.idproduk = po.idproduk
				left join _warna w ON  po.warna = w.idwarna
				left join _category_description cg on pk.idkategori = cg.category_id
				where head_produk='" . $head . "' and status_produk = '1' order by pk.idkategori asc, kode_produk asc";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}

	public function getWarnaHeadProduk($head)
	{

		$sql = "select w.warna,phw.idwarna,phw.image_head
				from _produk_head_warna phw left join _warna w
				on phw.idwarna = w.idwarna
				where idhead_produk='" . $head . "'";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}

	public function stokProdukWarnaUkuran($data)
	{
		$filter = [];
		$w = '';
		if (isset($data['ukuran'])) {
			if ($data['ukuran'] != '' && $data['ukuran'] != '0') {
				$filter[] = "ukuran='" . $data['ukuran'] . "'";
			}
		}
		if (isset($data['warna']) && ($data['warna'] != '' && $data['warna'] != '0')) {
			$filter[] = "warna='" . $data['warna'] . "'";
		}
		if (count($filter) > 0) {
			$w = ' and ' . implode(" and ", $filter);
		}
		$sql = "select stok from _produk_options 
				where idproduk='" . $data['product_id'] . "'" . $w;

		$strsql = $this->db->query($sql);
		return isset($strsql->row['stok']) ? $strsql->row['stok'] : 0;
	}

	public function stokHargaProdukWarnaUkuran($data)
	{
		$filter = [];
		$w = '';
		if (isset($data['ukuran'])) {
			if ($data['ukuran'] != '' && $data['ukuran'] != '0') {
				$filter[] = "ukuran='" . $data['ukuran'] . "'";
			}
		}
		if (isset($data['warna']) && ($data['warna'] != '' && $data['warna'] != '0')) {
			$filter[] = "warna='" . $data['warna'] . "'";
		}
		if (count($filter) > 0) {
			$w = ' and ' . implode(" and ", $filter);
		}
		$sql = "select stok,tambahan_harga from _produk_options 
				where idproduk='" . $data['product_id'] . "'" . $w;

		$strsql = $this->db->query($sql);
		return $strsql->row;
	}

	public function getPoinProdukByPIDgCust($pid, $tipemember)
	{
		$sql = "SELECT points FROM _produk_reward WHERE product_id='" . $pid . "' AND customer_group_id='" . $tipemember . "'";
		$query = $this->db->query($sql);
		return isset($query->row['points']) ? $query->row['points'] : 0;
	}
	public function getHargaProdukGrupCustomerByID($pid, $tipemember, $config_memberdefault)
	{
		//$w = '';
		//if($tipemember == $config_memberdefault) {
		$w = " AND customer_group_id = '" . $tipemember . "' ";
		//}
		$arkab = array();
		$sql = "SELECT customer_group_id,_customer_grup.cg_nm,harga,poin,
               cg_min_beli,cg_total_awal,cg_min_beli_syarat, 
			   cg_min_beli_wajib,cg_ket 
			   FROM _produk_harga 
	           LEFT JOIN _customer_grup ON _produk_harga.customer_group_id = _customer_grup.cg_id
			   WHERE product_id='" . $pid . "' $w order by _customer_grup.cg_id asc";
		$query = $this->db->query($sql);
		foreach ($query->rows as $rsa) {
			$arkab[] = array(
				'grup_id' => $rsa['customer_group_id'],
				'grup_nm' => $rsa['cg_nm'],
				'harga' => $rsa['harga'],
				'poin' => $rsa['poin'],
				'min_beli' => $rsa['cg_min_beli'],
				'min_beli_syarat' => $rsa['cg_min_beli_syarat'],
				'min_beli_wajib' => $rsa['cg_min_beli_wajib'],
				'keterangan' => $rsa['cg_ket'],
				'total_awal' => $rsa['cg_total_awal']
			);
		}
		return $arkab;
	}
	public function getDiskonProdukGrupCustomerByID($pid)
	{
		$arkab = array();

		$sql = "SELECT customer_group_id,harga
	           FROM _produk_diskons
			   WHERE product_id='" . $pid . "' order by customer_group_id asc";
		$query = $this->db->query($sql);
		foreach ($query->rows as $rsa) {
			$arkab[] = array(
				'grup_id' => $rsa['customer_group_id'],
				'harga' => $rsa['harga']

			);
		}
		return $arkab;
	}
	public function getPoinProdukGrupCustomerByID($pid)
	{
		$data = array();
		$sql = "SELECT customer_group_id,_customer_grup.cg_nm,points
	           FROM _produk_reward
			   LEFT JOIN _customer_grup ON _produk_reward.customer_group_id = _customer_grup.cg_id
			   WHERE product_id='" . $pid . "'";
		$query = $this->db->query($sql);

		foreach ($query->rows as $rd) {
			$data[] = array(
				'grup_id' => $rd['customer_group_id'],
				'grup_nm' => $rd['cg_nm'],
				'poin' => $rd['points']
			);
		}
		return $data;
	}
	public function getProdukDiskon($idproduk, $idgrup)
	{
		$strsql = $this->db->query("select * from _produk_diskon where idproduk='" . $idproduk . "' AND idreseller_grup='" . $idgrup . "'select * from _produk_diskon where idproduk='" . $idproduk . "' AND idreseller_grup='" . $idgrup . "'");

		if ($strsql) {
			$rsa = $this->db->fetch_array($strsql);
			return $rsa;
		} else {
			return false;
		}
	}

	public function getProdukDiskons($idproduk, $idgrup)
	{
		$arkab = array();
		$idgrup = (int) $idgrup;
		if ($idgrup < 0) {
			//$z = explode("-",$idgrup);
			//$idgrups = $z[1];
			$idgrups = abs($idgrup);
			$idgrup = " AND idreseller_grup<>'" . $idgrups . "'";
		} else {
			$idgrup = " AND idreseller_grup='" . $idgrup . "'";
		}
		$strsql = $this->db->query("select * from _produk_diskon where idproduk='" . $idproduk . "'" . $idgrup . " ORDER by min_beli asc");
		while ($rsa = $this->db->fetch_array($strsql)) {
			$arkab[] = array(
				'idprod' => $rsa['idproduk'],
				'reseller' => $rsa['idreseller_grup'],
				'minimal' => $rsa['min_beli'],
				'harga' => $rsa['harga']
			);
		}
		return $arkab;
	}


	public function getKategoriProduk($id)
	{
		$arkab = array();
		$strsql = $this->db->query("SELECT _produk_kategori.idkategori,_category_description.name as kategori_nama,_category.alias_url as kategori_alias
							 FROM _produk_kategori LEFT JOIN 
							 _category_description ON _produk_kategori.idkategori = _category_description.category_id 
							 LEFT JOIN _category ON _produk_kategori.idkategori = _category.category_id where idproduk='" . $id . "'");

		foreach ($strsql->rows as $rsa) {
			$arkab[] = array(
				'idkategori' => $rsa['idkategori'],
				'kategori_nama' => $rsa['kategori_nama'],
				'kategori_alias' => $rsa['kategori_alias']
			);
		}
		return $arkab;
	}
	public function getGambarProduk($id)
	{
		$arkab = array();
		$strsql = $this->db->query("select * from _produk_gambar where idproduk='" . $id . "'");
		while ($rsa = $this->db->fetch_array($strsql)) {
			$arkab[] = array(
				'id' => $rsa['idpgambar'],
				'pr' => $rsa['idproduk'],
				'gb' => $rsa['produk_gbr']
			);
		}
		return $arkab;
	}
	public function getOptionProduk($id)
	{
		$arkab = array();
		$strsql = $this->db->query("select * from _produk_option where idproduk='" . $id . "'");
		while ($rsa = $this->db->fetch_array($strsql)) {
			$arkab[] = array(
				'id' => $rsa['idoptionprod'],
				'pr' => $rsa['idproduk'],
				'st' => $rsa['stok'],
				'uk' => $rsa['ukuran'],
				'wn' => $rsa['warna'],
				'gb' => $rsa['idgambar']
			);
		}
		return $arkab;
	}

	public function getOption($id, $warna, $ukuran)
	{
		$strsql = $this->db->query("select * from _produk_options 
								  LEFT JOIN _produk_img ON _produk_options.idproduk = _produk_img.idproduk AND _produk_options.warna = _produk_img.idwarna
								  where _produk_options.idproduk='" . $id . "' AND warna='" . $warna . "' AND ukuran='" . $ukuran . "'");
		return isset($strsql->row) ? $strsql->row : false;
	}

	public function getHarga($pid, $tipe)
	{
		$strsql = $this->db->query("SELECT min_beli,harga FROM _produk_diskon WHERE idreseller_grup='" . $tipe . "' AND idproduk='" . $pid . "'");
		return $this->db->fetch_array($strsql);
	}

	public function getHargaByGrupCustomer($pid, $tipe)
	{ }

	public function getCover($pid)
	{
		$strsql = $this->db->query("SELECT _produk_warna.idwarna,produk_gbr,_produk_gambar.idproduk 
							 FROM _produk_gambar LEFT JOIN _produk_warna ON 
							 _produk_gambar.idpgambar = _produk_warna.idgambar 
							 WHERE _produk_gambar.idproduk = '" . $pid . "' AND
							 _produk_warna.idwarna IN (SELECT warna FROM _produk_option WHERE 
							 idproduk='" . $pid . "' AND stok > 0) limit 1");
		return $this->db->fetch_array($strsql);
	}

	public function getCoverByWarna($pid, $warna)
	{
		$strsql = $this->db->query("SELECT _produk_warna.idwarna,produk_gbr,_produk_gambar.idproduk 
							 FROM _produk_gambar LEFT JOIN _produk_warna ON 
							 _produk_gambar.idpgambar = _produk_warna.idgambar 
							 WHERE _produk_gambar.idproduk = '" . $pid . "' AND
							 _produk_warna.idwarna IN (SELECT warna FROM _produk_option WHERE 
							 idproduk='" . $pid . "' AND warna='" . $warna . "'AND stok > 0) limit 1");

		return $this->db->fetch_array($strsql);
	}


	public function getProdukOrder($nopesan, $produkid, $ukuranid, $warnaid, $tipe)
	{
		$arproduk = array();
		if ($tipe == 'warna') {
			$sql = "SELECT idwarna as id,warna as nm FROM _warna WHERE idwarna in (SELECT warna FROM _produk_option WHERE idproduk='" . $id . "')";
		} else {
			$sql = "SELECT * FROM (SELECT idukuran AS id,ukuran AS nm FROM _ukuran WHERE idukuran IN (SELECT ukuran FROM _produk_option WHERE idproduk='$produkid' AND ukuran > 0 AND stok > 0)) AS tbl
				   WHERE tbl.id NOT IN (SELECT _order_detail_option.ukuranid FROM _order_detail LEFT JOIN _order_detail_option
                   ON _order_detail.iddetail = _order_detail_option.iddetail WHERE pesanan_no='$nopesan' AND produk_id='$produkid' AND ukuranid<>'$ukuranid')";
		}
		//echo $sql;
		$strsql = $this->db->query($sql);
		while ($rsa = $this->db->fetch_array($strsql)) {
			$arproduk[] = array(
				'id' => $rsa['id'],
				'nm' => $rsa['nm'],

			);
		}
		return $arproduk;
	}

	public function getProdukOrderWarnaByUkuran($idproduk, $ukuran, $warna, $nopesan)
	{
		$arproduk = array();
		$strsql = $this->db->query("SELECT * FROM (SELECT _produk_warna.idwarna,_warna.warna FROM _produk_warna LEFT JOIN _warna ON _produk_warna.idwarna = _warna.idwarna 
						     WHERE idproduk='$idproduk' AND _produk_warna.idwarna IN (SELECT warna  FROM _produk_option WHERE idproduk='$idproduk' AND stok > 0 AND ukuran='$ukuran')) AS tbl
							 WHERE tbl.idwarna NOT IN (SELECT _order_detail_option.warnaid FROM _order_detail LEFT JOIN _order_detail_option
							 ON _order_detail.iddetail = _order_detail_option.iddetail WHERE pesanan_no='$nopesan' AND produk_id='$idproduk' AND ukuranid='$ukuran' AND warnaid<>'$warna')");

		while ($rsa = $this->db->fetch_array($strsql)) {
			$arproduk[] = array(
				'id' => $rsa['idwarna'],
				'nm' => $rsa['warna'],

			);
		}
		return $arproduk;
	}
	public function getModuleProdukSale($jmlproduk)
	{
		$rows     = "_produk.idproduk,_produk.kode_produk,_produk_deskripsi.nama_produk,_produk.jml_stok,_produk.gbr_produk,
		             _produk.hrg_jual,_produk_deskripsi.alias_url,_category.alias_url as kat_alias,sale,hrg_diskon,persen_diskon";

		$tabelnya = $this->tabelnya . " LEFT JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk ";
		$tabelnya .= " LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk";
		$tabelnya .= " LEFT JOIN _category ON _produk_kategori.idkategori = _category.category_id";
		$tabelnya .= " WHERE _produk.jml_stok > 0 and status_produk='1' and sale='1'";
		$sql = "select " . $rows . " FROM " . $tabelnya . ' group by _produk.idproduk ORDER BY _produk.idproduk asc limit ' . $jmlproduk;

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
	public function getProdukWarnaUkuran($idproduk, $ukuran)
	{
		$filter = "  AND stok > 0";
		$w = '';
		if ($ukuran != '' && $ukuran != '0') {
			$w = " AND po.ukuran='" . $ukuran . "'";
		}
		$str  = "SELECT w.warna,po.warna as idwarna, pw.gbr, u.ukuran, po.tambahan_harga ";
		$str .= "FROM _produk_options po ";
		$str .= "LEFT JOIN _warna w ON po.warna = w.idwarna ";
		$str .= "LEFT JOIN _ukuran u ON po.ukuran = u.idukuran ";
		$str .= "LEFT JOIN _produk_img pw ON po.idproduk = pw.idproduk AND po.warna = pw.idwarna ";
		$str .= "WHERE po.idproduk = '" . $idproduk . "'" . $w . $filter . " GROUP BY w.warna,po.warna ";
		$str .= "ORDER BY w.warna ASC";

		$strsql = $this->db->query($str);
		if ($strsql) {
			$arproduk = [];
			foreach ($strsql->rows as $rsa) {
				$arproduk[] = $rsa;
			}
			return $arproduk;
		} else {
			return false;
		}
	}
	public function jumlahStokByUkuran($idproduk, $idukuran)
	{
		$w = '';
		if ($idukuran != '' && $idukuran != '0') {
			$w = " AND ukuran='" . $idukuran . "'";
		}
		$str = "SELECT SUM(stok) as jml FROM _produk_options WHERE idproduk='" . $idproduk . "'" . $w;

		$sql = $this->db->query($str);
		return isset($sql->row['jml']) ? $sql->row['jml'] : 0;
	}

	public function getWarna()
	{
		$sql = "select * from _warna";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
		} else {
			$data = false;
		}
		return $data;
	}
	function getProdukByKategori($kategori, $batas, $limit, $search, $whr="status_produk='1'")
	{
		$filter = [];
		$search_by = '';
		$sql = "SELECT p.idproduk,kode_produk,nama_produk,
				w.idwarna,w.warna,c.name as nama_kategori,c.category_id,
				pd.alias_url
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
		if(!empty($whr)){
			$filter[] = $whr;
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

	function totalProdukByKategori($kategori, $search, $whr="status_produk='1'")
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
		if(!empty($whr)){
			$filter[] = $whr;
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
}

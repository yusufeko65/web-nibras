<?php
class modelLapStok {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
	}
    
	public function getLapStok($where,$status){
	    $arkab = array();
		
		if($where != '') $where = " AND $where";
		
		
		/*$sql = mysql_query("SELECT _produk.idproduk, kode_produk,nama_produk,jml_stok,
						   (SELECT SUM(jml) FROM _order_detail inner JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no 
							WHERE _order_detail.produk_id = _produk.idproduk AND status_id <> '$status'  GROUP BY produk_id) AS booking,
							(SELECT SUM(jml) FROM _order_detail inner JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no 
							WHERE _order_detail.produk_id = _produk.idproduk AND status_id <> '$status'  GROUP BY produk_id) AS terjual
							FROM _produk INNER JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk");
	    */
		
		/*
		$sql = mysql_query("SELECT *,
						    (SELECT SUM(jml) FROM _order_detail 
						     INNER JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no 
							 WHERE _order_detail.produk_id = tp.idproduk AND status_id <> '$status'  GROUP BY produk_id) as booking 
						    FROM 
							 (SELECT _produk.idproduk, kode_produk,nama_produk,jml_stok
							  FROM _produk INNER JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk) AS tp");
		
		*/
		$sql = mysql_query("SELECT *,
						    (SELECT SUM(jml) FROM _order_detail 
						     INNER JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no 
							 WHERE _order_detail.produk_id = tp.idproduk AND status_id <> '$status'  GROUP BY produk_id) as booking 
						    FROM 
							 (SELECT _produk.idproduk, kode_produk,nama_produk,jml_stok
							  FROM _produk INNER JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk) AS tp
							WHERE jml_stok > 0 $where");
		
		while ($rsa=mysql_fetch_array($sql)){
			$arkab[] = array(
				'idprod' => $rsa['idproduk'],
				'kd_produk' => $rsa['kode_produk'],
				'nm_produk' => $rsa['nama_produk'],
				'sisa' => $rsa['jml_stok'],
				'booking' => $rsa['booking']
			);
		}
		return $arkab;
	}
	
	public function getLapStokDetail($iddata,$status){
	   $arkab = array();
	   /* $sql = "SELECT *,(SELECT stok FROM _produk_option WHERE idproduk='$iddata' AND ukuran = lapstokdetail.idukuran AND warna = lapstokdetail.idwarna) as stoks FROM lapstokdetail 
				WHERE produk_id = '$iddata' and status_id <> '$status'"; */
	   /* $sql = "SELECT *,
			  (SELECT stok FROM _produk_option WHERE idproduk = '$iddata' AND ukuran = tb.ukuranid AND warna = tb.warnaid) as stoks FROM 
			  (SELECT status_id,produk_id,kode_produk,nama_produk,SUM(jml) as booking,warna,ukuran,warnaid,ukuranid FROM _order 
			   INNER JOIN _order_detail ON _order.pesanan_no = _order_detail.pesanan_no
			   INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail
			   INNER JOIN _warna ON _order_detail_option.warnaid = _warna.idwarna
			   INNER JOIN _ukuran ON _order_detail_option.ukuranid = _ukuran.idukuran
			   INNER JOIN _produk ON _order_detail.produk_id = _produk.idproduk
			   INNER JOIN _produk_deskripsi ON _order_detail.produk_id = _produk_deskripsi.idproduk
			   WHERE status_id <> '$status' AND produk_id = '$iddata'
			   GROUP BY produk_id,warnaid,ukuranid) AS tb";
	  */
       $sql = "SELECT *
			   FROM
				(SELECT _produk_option.idproduk,kode_produk,nama_produk,_warna.warna,
				 _ukuran.ukuran,_produk_option.warna AS warnaid,_produk_option.ukuran AS ukuranid,stok 
				 FROM _produk_option
				 INNER JOIN _produk ON _produk_option.idproduk = _produk.idproduk
				 INNER JOIN _produk_deskripsi ON  _produk_deskripsi.idproduk = _produk.idproduk
				 LEFT JOIN _warna ON _produk_option.warna = _warna.idwarna
				 LEFT JOIN _ukuran ON _produk_option.ukuran = _ukuran.idukuran
				 WHERE _produk.idproduk = '$iddata' ) AS tp
			   left JOIN 
				(SELECT SUM(jml)  AS booking ,produk_id,warnaid,ukuranid FROM _order 
				 left JOIN _order_detail ON _order.pesanan_no = _order_detail.pesanan_no
				 left JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail
				 WHERE status_id <> '$status' AND produk_id = '$iddata'
			     GROUP BY produk_id,warnaid,ukuranid) AS ja 
			  ON ja.warnaid = tp.warnaid AND ja.ukuranid = tp.ukuranid";  
	   // ECHO $sql;
	   $sql = mysql_query($sql);
	   while ($rsa=mysql_fetch_array($sql)){
			$arkab[] = array(
				'idprod' => $rsa['produk_id'],
				'nmprod' => $rsa['nama_produk'],
				'kdprod' => $rsa['kode_produk'],
				'booking' => $rsa['booking'],
				'sisa' => $rsa['stok'],
				'warna' => $rsa['warna'],
				'ukuran' => $rsa['ukuran']
			);
		}
		return $arkab;
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>
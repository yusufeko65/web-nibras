<?php
class modelLapInBooking
{
    private $db;
    private $tabelnya;
    private $user;
    private $where_pengiriman;
    private $where_caridata;

    public function __construct()
    {
        $this->tabelnya = '_order';
        $this->db = new Database();
        $this->db->connect();
        $this->user = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"] : '';
        $this->status = "(_order.status_id = 9 or _order.status_id = 15)";
    }

    public function getTotalOrder($data)
    {
        $where = '';
        $filter = array();

        if ($data['status'] != '' && $data['status'] != 0) {
            $filter[] = "(_order.status_id = " . trim($this->db->escape(
                $data['status'])) . ")";
        } else {
            $filter[] = $this->status;
        }

        if ($data['caridata'] != '') {
            $filter[] = "(_produk.kode_produk like '%" . trim($this->db->escape($data['caridata'])) . "%' 
                or _order.pesanan_no like '%" . trim($this->db->escape($data['caridata'])) . "%'
                or _order_penerima.nama_penerima like '%" . trim($this->db->escape($data['caridata'])) . "%'
                or _order_pengirim.nama_pengirim like '%" . trim($this->db->escape($data['caridata'])) . "%'  
                or _customer.cust_nama like '%" . trim($this->db->escape($data['caridata'])) . "%')";
        }

        if (!empty($filter)) {
            $where = implode(" and ", $filter);
        }

        if ($where != '') {
            $where = " where " . $where;
        }

        $strsql = $this->db->query(

                                    "SELECT count(*) as total 
                                    FROM _order_detail as _order_detail
                                    INNER JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no
                                    INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id
                                    INNER JOIN _produk ON _order_detail.produk_id = _produk.idproduk
                                    INNER JOIN _warna ON _order_detail.warnaid = _warna.idwarna
                                    INNER JOIN _ukuran ON _order_detail.ukuranid = _ukuran.idukuran
                                    INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
                                    INNER JOIN _order_penerima ON _order.pesanan_no = _order_penerima.pesanan_no
                                    INNER JOIN _order_pengirim ON _order.pesanan_no = _order_pengirim.pesanan_no"
            . $where);
        return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
    }

    public function getOrderLimit($batas, $baris, $data)
    {
        $where = '';
        $filter = array();
        $order = '';

        if ($data['status'] != '' && $data['status'] != 0) {
            $filter[] = "(_order.status_id = " . trim($this->db->escape(
                $data['status'])) . ")";
        } else {
            $filter[] = $this->status;
        }

        if ($data['caridata'] != '') {
            $filter[] = "(_produk.kode_produk like '%" . trim($this->db->escape($data['caridata'])) . "%' 
                or _order.pesanan_no like '%" . trim($this->db->escape($data['caridata'])) . "%'
                or _order_penerima.nama_penerima like '%" . trim($this->db->escape($data['caridata'])) . "%'
                or _order_pengirim.nama_pengirim like '%" . trim($this->db->escape($data['caridata'])) . "%'  
                or _customer.cust_nama like '%" . trim($this->db->escape($data['caridata'])) . "%')";
        }

        if (!empty($filter)) {
            $where = implode(" and ", $filter);
        }

        if ($where != '') {
            $where = " where " . $where;
        }

        $sql = "SELECT _order.pesanan_no as order_id, _customer.cust_nama as customer, _produk.kode_produk as produk, 
                       _warna.warna as warna, _ukuran.ukuran as ukuran, _order_detail.jml as jumlah, _status_order.status_nama as status 
                FROM _order_detail as _order_detail
                INNER JOIN _order ON _order_detail.pesanan_no = _order.pesanan_no
                INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id
                INNER JOIN _produk ON _order_detail.produk_id = _produk.idproduk
                INNER JOIN _warna ON _order_detail.warnaid = _warna.idwarna
                INNER JOIN _ukuran ON _order_detail.ukuranid = _ukuran.idukuran
                INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
                INNER JOIN _order_penerima ON _order.pesanan_no = _order_penerima.pesanan_no
                INNER JOIN _order_pengirim ON _order.pesanan_no = _order_pengirim.pesanan_no

                " . $where . "

                LIMIT $batas,$baris";

        $strsql = $this->db->query($sql);
        if ($strsql) {
            $hasil = [];
            foreach ($strsql->rows as $rs) {
                $hasil[] = $rs;
            }
            return $hasil;
        }
        return false;
    }
}

<?php
class modelPengiriman
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
        $this->status = "_order.status_id = 11";
        $this->status_resi ="(_order.no_awb = '' or _order.no_awb = '-' ) and (_order.kurir <> 'undefined' or _order.kurir <> '') and (_order.status_id = 11 or _order.status_id = 18)";
        $this->today = "_order.tgl_kirim = '" . date('Y-m-d') . "'";
    }

    public function getTotalOrder($data, $resi = false, $today = true)
    {
        $where = '';
        $filter = array();
        $search = false;

        if ($data['caridata'] != '') {
            $filter[] = "(_order.pesanan_no like '%" . trim($this->db->escape($data['caridata'])) . "%'
            or _order_penerima.nama_penerima like '%" . $this->db->escape($data['caridata']) . "%'
            or _order_pengirim.nama_pengirim like '%" . $this->db->escape($data['caridata']) . "%'
            or _customer.cust_nama like '%" . $this->db->escape($data['caridata']) . "%')";
            $search = true;
        }

        if ($resi) {
            if(!$search) {
                $filter[] = $this->status_resi;
            }
        } else {
            $filter[] = $this->status;
        }

        if ($today) {
            $filter[] = $this->today;
        }

        if ($data['nama_kurir'] != '' && $data['nama_kurir'] != '0') {
            $filter[] = "_order.kurir = '" . trim($this->db->escape($data['nama_kurir'])) . "' and _servis.servis_id = '" . trim($this->db->escape($data['servis'])) . "'";
        }

        if (!empty($filter)) {
            $where = implode(" and ", $filter);
        }

        if ($where != '') {
            $where = " where " . $where;
        }

        $strsql = $this->db->query(

                                           "select count(*) as total from _order
                                    inner join _status_order on _order.status_id = _status_order.status_id
                                    inner join _order_penerima on _order.pesanan_no = _order_penerima.pesanan_no
                                    inner join _order_pengirim on _order.pesanan_no = _order_pengirim.pesanan_no
                                    inner join _customer on _order.pelanggan_id = _customer.cust_id
                                    inner join _servis on _order.servis_kurir = _servis.servis_id
                                    left join _login on _order.input_by = _login.login_id "
            . $where);
        return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
    }

    public function checkSentOrder($idpesanan)
    {
        $sql = $this->db->query("select status_id from " . $this->tabelnya . "
                                         where pesanan_no=$idpesanan limit 1");
        // $jml = $check->num_rows;
        // $result =
        // if ($jml > 0) return true;
        // else return false;
        $result = $sql->row;
        return $result;
    }

    public function getOrderLimit($batas, $baris, $data, $resi = false, $today
        = true) {
        $where = '';
        $filter = array();
        $order = '';
        $search = false;

        if ($data['caridata'] != '') {
            $filter[] = "(_order.pesanan_no like '%" . trim($this->db->escape($data['caridata'])) . "%'
            or _order_penerima.nama_penerima like '%" . $this->db->escape($data['caridata']) . "%'
            or _order_pengirim.nama_pengirim like '%" . $this->db->escape($data['caridata']) . "%'
            or _customer.cust_nama like '%" . $this->db->escape($data['caridata']) . "%')";
            $search = true;
        }

        if ($resi) {
            if(!$search) {
                $filter[] = $this->status_resi;
            }
            $order = " _order.pesanan_no desc ";
        } else {
            $filter[] = $this->status;
            $order = "pesanan_tgl desc ";
        }

        if ($today) {
            $filter[] = $this->today;
        }

        if ($data['nama_kurir'] != '' && $data['nama_kurir'] != '0') {
            $filter[] = "_order.kurir = '" . trim($this->db->escape($data['nama_kurir'])) . "'and _servis.servis_id = '" . trim($this->db->escape($data['servis'])) . "'";
        }

        if (!empty($filter)) {
            $where = implode(" and ", $filter);
        }

        if ($where != '') {
            $where = " where " . $where;
        }

        $sql = "select idpesanan,_order.pesanan_no,

                cust_nama, nama_penerima, nama_pengirim,

                status_nama as status, _status_order.status_id as status_id, _order.no_awb as no_resi,

                kurir, login_username, _servis.servis_code as service, _order.pesanan_kurir as ongkir

                from _order

                inner join _status_order on _order.status_id = _status_order.status_id

                inner join _order_penerima on _order.pesanan_no = _order_penerima.pesanan_no

                inner join _order_pengirim on _order.pesanan_no = _order_pengirim.pesanan_no

                inner join _customer on _order.pelanggan_id = _customer.cust_id

                inner join _servis on _order.servis_kurir = _servis.servis_id

                left join _login on _order.input_by = _login.login_id

                " . $where . "

                order by

                " . $order . "

                limit $batas,$baris";

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

    public function editStatus($order_id)
    {
        $sql = $this->db->query("update " . $this->tabelnya . " set input_by='". $this->user . "', tgl_kirim = '" . date('Y-m-d') . "', status_id = 11 where pesanan_no='$order_id'");
        if ($sql) {
            return true;
        } else {
            return false;
        }

    }

    public function inputResi($order_id, $no_resi)
    {
        $sql = $this->db->query("update " . $this->tabelnya . " set input_by='". $this->user . "', tgl_kirim = '" . date('Y-m-d') . "', no_awb = '$no_resi', status_id = 18 where pesanan_no='$order_id'");
        if ($sql) {
            return true;
        } else {
            return false;
        }

    }

    public function insertStatusHistory($order_id, $is_done = false)
    {
        $status_order = $is_done ? 18 : 11;

        $sql = "insert into _order_status values (null,'" . $order_id . "','" . date('Y-m-d H:i:s') . "', $status_order, '', '" . $this->user . "')";

        $strsql = $this->db->query($sql);
        return $strsql;
    }

    public function getKurir($today = true)
    {
        $where = '';
        $filter = array();
        if ($today) {
            $filter[] = "tgl_kirim = '" . date('Y-m-d') . "'";
        }

        if (!empty($filter)) {
            $where = implode(" and ", $filter);
        }

        if ($where != '') {
            $where = " where " . $where;
        }

        $sql = "select kurir, _servis.servis_id as service_id, servis_code as service
                  from `_order`
                  join _servis on _order.servis_kurir = _servis.servis_id
                  " . $where . "
                  group by kurir, servis_code";

        $strsql = $this->db->query($sql);
        return $strsql->rows;
    }
}

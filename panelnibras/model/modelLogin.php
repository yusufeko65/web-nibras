<?php
class modelLogin{
	var $db_mysql;
	var $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_login';
		$this->db 		= new Database();
		$this->db->connect();
	}

	function getLogin($data){
                $user = $data['username'];
                $pass = $data['password'];

                $user = $this->escapeLogin($user);
                $pass = $this->escapeLogin($pass);

		$sql = "SELECT login_nama,login_username,login_pwd,login_status,_login.lg_id,_login_group.lg_nama,DATE_FORMAT(last_login,'%Y-%m-%d %H:%i:%s') as last_login,login_id FROM 
							  _login INNER JOIN _login_group ON _login.lg_id = _login_group.lg_id WHERE 
							  login_username='".$user."' and login_pwd='".$pass."' AND login_status='1'";
		$strsql=$this->db->query($sql);
		
		return isset($strsql->row) ? $strsql->row : array();
	}
	
	function hakAkses($grupuser){
		$data = array();
		$sql = "SELECT _menu.menu_name,_menu.menu_folder,lg_id,ha_add,ha_edit,ha_delete,ha_view FROM _hak_akses INNER JOIN _menu ON _hak_akses.ha_menu = _menu.menu_id WHERE lg_id='".$grupuser."'";
		$strsql = $this->db->query($sql);
		if($strsql) {
			foreach ($strsql->rows as $row) {
				$data[] = array(
					'menu_name' => $row['menu_name'],
					'menu_folder' => $row['menu_folder'],
					'grup_id' => $row['lg_id'],
					'add' => $row['ha_add'],
					'edit' => $row['ha_edit'],
					'del' => $row['ha_delete'],
					'view' => $row['ha_view']
				);
			}
		}
		return $data;
	}
	function updateLogin($user,$jam){
		$str = $this->db->query("update _login set last_login='$jam' WHERE login_username='".$user."'");
		if($str) return true;
	    else return false;
	}

function escapeLogin($inp) {
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
} 

	function __destruct() {
		$this->db->disconnect();
	}
}
?>
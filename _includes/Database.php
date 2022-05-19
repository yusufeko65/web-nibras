<?php

class Database {
   
    private $koneksi;

    /*
	private $server	= 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'goetnik'; 
	*/
   
	
	private $server   = 'localhost';     // Database Host
    private $username = 'goetnik1_usr903t';          // Username
    private $password  = 'B7+@FsX%,33d';          // Password
    private $database  = 'goetnik1_nibras';
	
	public function connect() {
	   $this->koneksi = new mysqli($this->server, $this->username, $this->password, $this->database);
	   /* check connection */
       if (mysqli_connect_errno()) {
        // printf("Connect failed: %s\n", mysqli_connect_error());
         exit();
       }
	   
	}
	
	public function autocommit($flag){
	  $this->koneksi->autocommit($flag);
	}
	
	public function commit() {
	  $this->koneksi->commit();
	}
	
	public function rollback() {
	  $this->koneksi->rollback();
	}
	
	public function query($sql){
	    $query = $this->koneksi->query($sql);

		if (!$this->koneksi->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}
				
				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;
				
				$query->close();
				
				return $result;
			} else {
			    
				return true;
			}
		} else {
			//print_r('Error: ' . $this->koneksi->error  . '<br />Error No: ' . $this->koneksi->errno . '<br />' . $sql);
		}
	}
	public function escape($value) {
		return $this->koneksi->real_escape_string($value);
	}
	public function disconnect() {
		$this->koneksi->close();
	}
	
   
   
}



?>
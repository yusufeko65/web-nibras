<?php
class Database {
   
   
	//public $database;

	private $server   = 'localhost';     // Database Host
    private $username = 'nibrasc01_usergrobogan';          // Username
    private $password  = '6Rm7hZ%5RcdE';          // Password
    private $database  = 'nibrasc01_dbgrobogan';
	
//	private $username = 'usrbusanainspire';          // Username
  //	private $password  = 'vkAQWVfnf$';          // Password
  //	private $database  = 'devbusanainspire';
	
	private static $dbInstance = false;
	
	public function connect() {
		
		if(!(self::$dbInstance))
        {
			self::$dbInstance = new mysqli($this->server, $this->username, $this->password, $this->database);
			
			/* check connection */
			if (mysqli_connect_errno()) {
				 printf("Connect failed: %s\n", mysqli_connect_error());
				 exit();
			
			}
			
		} 
		
		//return self::$dbInstance;
		return true;
	}
	
	public function beginTransaction(){
		if(!self::$dbInstance) {
			
			//self::$dbInstance = $this->connect();
			$this->connect();
		}
		self::$dbInstance->begin_transaction();
	}
	
	public function autocommit($flag){
		if(!self::$dbInstance) {
			
			//self::$dbInstance = $this->connect();
			$this->connect();
		}
		self::$dbInstance->autocommit($flag);
	}
	
	public function commit() {
		
		if(!(self::$dbInstance)){
			//self::$dbInstance = $this->connect();
			$this->connect();
		} 
		self::$dbInstance->commit();
	}
	
	public function rollback() {
		
		if(!self::$dbInstance) {
			//self::$dbInstance = $this->connect();
			$this->connect();
		}
		self::$dbInstance->rollback();
	}
	
	public function lastid()
	{
		if(!self::$dbInstance) {
			//self::$dbInstance = $this->connect();
			$this->connect();
		}
		return self::$dbInstance->insert_id;
	}
	
	public function query($sql){
		
		if(!(self::$dbInstance)){
			//self::$dbInstance = $this->connect();
			$this->connect();
			
		} 
		
	    $query = self::$dbInstance->query($sql);

		if (!self::$dbInstance->errno) {
		
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}
				
				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;
				
				//$query->close();
				
				//echo "udah ditutup <br>";
				return $result;
			} else {
			    
				return true;
			}
		} else {
			print_r('Error: ' . self::$dbInstance->error  . '<br />Error No: ' . self::$dbInstance->errno . '<br />' . $sql);
			//print_r($sql);
		}
	}
	public function escape($value) {
		
		if(!self::$dbInstance) {
			
			$this->connect();
		}
		return self::$dbInstance->real_escape_string($value);
	}
	
	public function affected_rows() {
		
		if(!self::$dbInstance) {
			$this->connect();
		}
		return self::$dbInstance->affected_rows;
	}
	
	public function disconnect() {
		
		if(self::$dbInstance){
			self::$dbInstance->close();
			self::$dbInstance = false;
			
		}
	}
	public function __destruct()
    {
		self::disconnect();
    }  
}

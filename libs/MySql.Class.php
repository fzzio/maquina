<?php
/*************************************************************************************************************
 * Class  		: MySql (Singleton)
 * 
 * Version		: 1.0.0
 * 
 * Author 		: Rahul Kate ( rahul@rahulkate.com | Www.RahulKate.Com )
 *  
 * Created on 	: 07 Aug 2011
 * 
 * Description	: A PHP MySql Wrapper Class. I am sure you will like this class.
 *  
 * Copyrights	: Please use it freely as and where you like. 
 * 				  The Author does not provide any gurantee or warrranty of any sorts.
 *************************************************************************************************************/
class Mysql{
	
	private static $instance;
	private static $connection;
	//private static $result;
	
	private function __construct($db = array()){

		if(empty($db)){ global $db; }
		
		// CONNECT TO THE MYSQL SERVER
		self::$connection = mysql_connect($db['host'], $db['user'], $db['pass']);
		if(!self::$connection){
			// HANDLE ERROR HERE
			die('Unable to connect to MySql server : '.mysql_error(self::$connection));
		}
		
		// SELECT THE DATABASE SCHEMA
		if(!mysql_select_db($db['name'],self::$connection)){
			// HANDLE ERRORS HERE
			die('Unable to connect to database : '.mysql_error(self::$connection));
		}
	}
	
	public static function getInstance($chain = true){
		if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
	}
	
	public function executeQuery($query){
		
		$result = mysql_query($query, self::$connection);
		if(!$result){
			// HANDLE ERROR HERE
			die('Query Failed : '.mysql_error()."\n\r<br />".$query);
		}
		
		if(is_resource($result))
			return $result;		
		elseif($rs = mysql_insert_id(self::$connection))
			return $rs;
		elseif($rs = mysql_affected_rows(self::$connection)) 
			return $rs;
		else
			return true;
	} 
	
	public function getResultSet($query, $type='ASSOC'){
		
		// RUN THE QUERY AND GET THE RESULT
		$result = $this->executeQuery($query);
		$records = array();

		// RETURN AN ASSOCIATIVE RESULT SET
		if($result){
			switch($type){
				// FETCH AN INDEXED ARRAY
				case 'ARRAY':
						while($row = mysql_fetch_array($result,MYSQL_NUM))
						 	$records[] = $row;
					break;
				// FETCH AN ARRAY OF OBJECTS
				case 'OBJECT':
						while($row = mysql_fetch_object($result))
						 	$records[] = $row;
					break;
				// FETCH AN ASSOCIATIVE ARRAY
				default:
					while($row = mysql_fetch_assoc($result)){
						$records[] = $row;
					}
					break;
					
			}
			mysql_free_result($result);
		}else
			return false;
		
		// RETURN THE RECORD SET
		return $records;
		
	} // END GET RESULT SET
	
	public function getSingleRow($query, $type='ASSOC'){
		
		$result = $this->executeQuery($query);
		if($result && mysql_num_rows($result)>0){
			// RETURN AN ASSOCIATIVE RESULT SET
			switch($type){
				// FETCH AN INDEXED ARRAY
				case 'ARRAY':
						$row = mysql_fetch_array($result, MYSQL_NUM);
					break;
				// FETCH AN ARRAY OF OBJECTS
				case 'OBJECT':
						$row = mysql_fetch_object($result);
					break;
				// FETCH AN ASSOCIATIVE ARRAY
				default:
						$row = mysql_fetch_assoc($result);
					break;
			}
			mysql_free_result($result);
			return $row;
		}else
			return false;
	}
	
	public function getSingleRecord($query){
		
		// RUN THE QUERY AND GET THE RESULT
		$result = $this->executeQuery($query);
		
		if(!$result) return false;
		
		$row = mysql_fetch_array($result, MYSQL_NUM);
		
		mysql_free_result($result);
		return $row[0];
	}
	
	public function getCount($query){

		$query = preg_replace("/SELECT([\sa-zA-Z0-9._,\(\)\*]+)FROM/", "SELECT COUNT(1) FROM ", $query);
		$query = preg_replace("/LIMIT ([0-9]+)/",'',$query);
		$query = preg_replace("/OFFSET ([0-9]+)/",'',$query);
		$query = preg_replace("/ORDER BY ([a-zA-Z0-9._,]+) (ASC|DESC)?/",'',$query);
		return $this->getSingleRecord($query);
	}
	
	
	public function insertRecord($query, $sequence = false){
		return $this->executeQuery($query);
	}
	
	public function updateRecord($query){
		return $this->executeQuery($query);	
	}

	public function deleteRecord($query){
		return $this->executeQuery($query);		
	}
		
	public function getFieldNames($table = ''){
		if(trim($table)=='') return false;
		return $this->getResultSet("SELECT DISTINCT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table'", 'ARRAY'); 
	}
	
	public function getTableMeta($table = ''){
		if(trim($table)=='') return false;
		$res = $this->getResultSet("SELECT COLUMN_NAME AS NAME, COLUMN_TYPE AS TYPE FROM information_schema.columns WHERE TABLE_NAME='$table'");
		if(!$res) return false;

		$data = array();
		foreach($res as $row){
				$vars = preg_split('/\(|\)/',$row['TYPE']);
				$data[$row['NAME']] = array('type'=>$vars[0], 'length'=>$vars[1]);
		} 
		return $data;
	}
	
	public function getPrimaryKey($table = ''){
		if(trim($table)=='') return false;
		return $this->getSingleRecord("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_KEY = 'PRI' ");	
	}
	
	

	// THE NEW FUNCTIONS
	public function beginTransaction(){
		return mysql_query("BEGIN"); 	
	}
	
	public function rollbackTransaction(){
		return mysql_query("ROLLBACK");	
	}
	
	public function commitTransaction(){
		return mysql_query("COMMIT");
	}
		
	public function __destruct(){
		if(is_resource(self::$connection)) 
			mysql_close(self::$connection);
	}
}
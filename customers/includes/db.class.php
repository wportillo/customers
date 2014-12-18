<?php
/**
 * Database Class
 *
 * @author William
 * @package Core
 */
class db{
	
	/**
	 * Open a connection to MySQL Server
	 *
	 * @return resource Returns a MySQL link identifier on success, or FALSE on failure. 
	 */
		public static function connectdb(){
			
			global $db;
			
			if(!$db = new mysqli(DB_HOST, DB_USER, DB_PASS)){
				return false;
			}
			
			if(!$db->select_db(DB_NAME)){
				return false;
			}
			
			$db->query("SET NAMES 'utf8'");
			
			return true;		
		}
	
	/**
	 * Num Rows
	 * 
	 * @return integer
	 */
	public static function num_rows($rst){
		
		return $rst->num_rows;
	
	}
	
	/**
	 * Affected Rows
	 * 
	 * @return integer
	 */
	public static function affected_rows(){

		global $db;
		
		return $db->affected_rows;
	}
	
	/**
	 * Last Inserted Id
	 * 
	 * @return integer
	 */
	public static function insert_id(){
		
		global $db;
		
		return $db->insert_id;
	}
	
	/**
	 * Fetch Assoc
	 * 
	 * @return array
	 */
	public static function fetch_assoc($rst){
		
		return $rst->fetch_assoc();
	}
	
	/**
	 * Fetch Aarray
	 * 
	 * @return array
	 */
	public static function fetch_array($rst){
		return $rst->fetch_array();
	}	
	
	/**
	 * Quote String
	 *
	 * @param string $value
	 * @return string
	 */
	public static function quote($value){
		global $db;
	
		if(get_magic_quotes_gpc()){
			$value = stripslashes($value);
		}
		
		if(!is_numeric($value) || $value[0] == '0'){
			$value = "'" . $db->real_escape_string($value) . "'";
		}
		
		return $value;
		
	}	
	
	/**
	 * Field Or Table Quote String
	 *
	 * @param string $value
	 * @return string
	 */
	public static function ftquote($value){
		global $db;
	
		if(get_magic_quotes_gpc()){
			$value = stripslashes($value);
		}
		
		if(!is_numeric($value) || $value[0] == '0'){
			$value = "`" . $db->real_escape_string($value) . "`";
		}
		
		return $value;
		
	}

	/**
	 * Escape String
	 *
	 * @param string $value
	 * @return string
	 */
		public static function escapestring($value){
			global $db;
		
			$value = $db->real_escape_string($value);
			
			return $value;
		}
	
	/**
	 * Run MySQL Query
	 * 
	 * @param string $sql
	 * @return mysql_resource or false
	 */
	public static function query($sql){
		
		global $db;
		
		$rst = $db->query($sql);
		
		if(!$rst){
			return false;
		}
		
		return $rst;
	}
	
    public static function begin_transaction(){
		
    	global $db;
		
		$db->query('begin');
	}
    
	public static function end_transaction(){
			
	    	global $db;
					
			if($db->error){
				
				$db->query('rollback');
	            
				return false;
	        }else{
	            
	        	$db->query('commit');
	            
	            return true;
	        }
	}
}
?>
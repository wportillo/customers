<?php
/**
 *  Session Manager
 */
class SessionManager {

	public $life_time;
  	 
  	 public $db_object;
  	 
	/**
	 * Initialize Session Manager
	 * 
	 * @param string $session_id
	 */
	 public function __construct(){

	 		$this->life_time 			 =  18000000;
	 		
	 		$this->db_object 			 = new Sessions();
	 	
	 	/**
		 * BEGIN SESSION
		 */
		 	session_set_save_handler(
		 			array( $this, 'open' ),
		 			array( $this, 'close' ),
		 			array( $this, 'read'),
		 			array( $this, 'write'),
		 			array( $this, 'destroy'),
		 			array( $this, 'gc' )
		 	);
		 	
		 	session_start();
		 
	}
	
	/**
	 * Open Session Path
	 * @param $save_path
	 * @param $session_name
	 */
	public function open($save_path,$session_name ) {
		
		$sess_save_path = $save_path;
		
		return true;
	}

	/**
	 * Set Life Time
	 */
	public function set_life_time(){

		$value = $_COOKIE[SESSION_NAME];
		
		setcookie(SESSION_NAME,$value,time() + $this->life_time);
	}
	/**
	 * Close Session
	 */
	public function close() {
		return true;
	}
	/**
	 * Read Session Values
	 * @param $id
	 */
		public function read($id){
		
			$data=null;
			
			$read_assoc = $this->db_object->get_list(1,1,'i_session='.db::quote($id).'  AND `expires` > '.time());
			
			$count = db::num_rows($read_assoc);
			
			if($count > 0) {

				$row_session = db::fetch_assoc($read_assoc);
			
				/**
				 * Preguntar por los valores
				 */
					$data = $row_session['session'];
					
				}
				return $data;
		}
	
	
	/**
	 * Create Session data
	 * 
	 * @param $id
	 * @param $data
	 */
		public function write( $id, $data ) {
			
				global $db;
				
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS);
				
				$db->select_db(DB_NAME);
				
				$db->query("SET NAMES 'utf8'");

				$found = $this->db_object->get($id);

				$sessdata = unserialize_session($data);
				
				$data_mysql=array(
						'i_session'    =>$id,
						'session'      => $data,
						'ip'		   => get_real_ip(),
						'expires'	   =>  time() + $this->life_time,
				);
				
		  		if($found){
		  			
		  			$this->db_object->update($data_mysql,$id);
		     	
		     	}else{
					
		     		 $this->db_object->add($data_mysql);
		     	}
		     	
		     	/*
		     	 * Delete Clean Session
		     	 */
		     		$session_sql='DELETE FROM `sessions` WHERE `i_user`=0 AND `i_customer`=0 AND MINUTE(TIMEDIFF(CURRENT_TIMESTAMP(),last_update)) >=15';
		     		
		     		db::query($session_sql);
		     	
		     		$db->close();
				
					return true;
		}
	
	/**
	 * Destroy Session
	 * @param $id
	 */
		public function destroy( $id ) {
			
			$this->db_object->delete($id);
			
			return true;
		}
	
		public function gc() {
			
			$session_sql='DELETE FROM `sessions` WHERE `expires <'.time();
			 
			db::query($session_sql);
			
			return true;
		}
}
?>
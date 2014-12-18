<?php

/**
 * Security Admin
 * @author William
 * @package admin
 */
	class Security extends Rewrite{
		
			public $iurl;
			
			public $dbuser;
			
			public $data;
			
			public $debug = false;
			
			public $dbroll;
			
			public $permissions;
			
			public $acceslog;
			
			public $reseller;
			
			public $store;
			
			/**
			 * Security Params
			 * 
			 * Construct
			 */
				public function __construct(){
					
					$this->dbuser 		 = new Users();
					
					$this->dbroll		 = new Roll();
					
					$this->acceslog		 = new Crm_access_log();

					$this->reseller		 = new Crm_reseller();

					$this->store		 = new Crm_store();
					
					$this->store->pivot_tables = false;
					
					$this->dbuser->debug = $this->debug;
					
					$this->dbuser->debug = $this->debug;
					
					$this->data			 = _session('user',false);
					
					$this->iurl			 = array('forget','login','batchaccount');
					
					$this->update();
					
					$this->getrolles();
					
					$this->checkuser();
				}
			
			/**
			 * Login
			 *
			 * @param string $username
			 * @param string $password 
			 * @return boolean
			 */
				 public function login($username, $password){
					
				 	$rs_user=$this->dbuser->get_list(1,1,'(user='.db::quote($username).' OR email='.db::quote($username).') AND password='.db::quote(encrypt($password, ENCRYPT_KEY)));
					
					$user = db::fetch_assoc($rs_user);
					
					$_SESSION['user']  = $user;
					
					$this->data		   = _session('user',false);
					
					if($user){
							
							$data_mysql['i_user'] 	 = $user['i_user'];
							
							$data_mysql['public_ip'] = get_real_ip();
							
							$data_mysql['useragent'] = $_SERVER['HTTP_USER_AGENT'];
						
							$this->acceslog->add($data_mysql);
						return true;
					}else{
						return false;
					}
				 }
		
				 
			
			/**
			 * User is Logged
			 * 
			 * @return boolean
			 */
				public function logged(){
					
					if($this->data['user']){
						 return true;
					}else{
						return false;
					}	
				}
			/**
			 * Get Balance
			 *
			 * @param ID
			 * @param string $type reseller or store
			 */
				public function getbalance($id,$type){
					
					switch($type){
						case 'reseller':
						 
							$row = $this->reseller->get($id);
						 
							if($row['type']=='debit'){

								return array('type'=>$row['type'],'balance'=>$row['amount']);
							
							}else{
								
								return array('type'=>$row['type'],'balance'=>$row['balance']);
							}
						
						break;
						case 'store':
							$row = $this->store->get($id);
								
							return $row['balance'];
						break;
					}
				}
			
			/**
			 * Get Exiprate User
			 * 
			 * @return boolean
			 */
				public function expirate(){
					
					if($this->data['expiration_flag']=='1'){
						
						if(mysql_date_to_timestamp($this->data['expiration_date']) < time()){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}
				
			/**
			 * Get Inactive User
			 *
			 * @return boolean
			 */
				public function active(){
					
					if($this->data['active']=='1'){
						return true;
					}else{
						return true;
					}
				}
			
			/**
			 * 
			 * Update session
			 * 
			 */
				public function update(){
	
					if($this->logged()){

						$rs_user=$this->dbuser->get_list(1,1,'(user='.db::quote($this->data['user']).' OR email='.db::quote($this->data['user']).') AND password='.db::quote($this->data['password']));
							
						$user = db::fetch_assoc($rs_user);
							
						$_SESSION['user']  = $user;
							
						$this->data		   = _session('user',false);
							
						if($user){
							
							return true;
						}else{
							return false;
						}
					}
		
				}
			
			/**
			 * 
			 * Get Roll
			 */
				public function getrolles(){
					
					if($this->logged()){
		
						$rol=$this->dbroll->get($this->data['i_roll']);
	
						foreach($rol as $key=>$value){
	
							if(preg_match('/^[a-z]+_perm/', $key)){
									
								 $this->permissions[$key] = unserialize($value);
							
							}
						}
					}
				}
			
			/**
			 * 
			 * Check User
			 * 
			 */
				public function checkuser(){
					 
					switch(true){
						case (!$this->logged()):
						case ($this->expirate()):
						case (!$this->active()):
	
							if(!search_in_array($this->iurl, $this->get_uri_position(1))){
								header('location:/login');
							}
						break;
					}
				}
			
			/**
			 * 
			 * Redirect Roll
			 * 
			 */
				public function redirectroll($moduleroll){
					
					if($this->logged()){
					
						$roll = $this->permissions;
			
						if(isset($roll[$moduleroll['roll'].'_perm'])){
							
							if(is_array($roll[$moduleroll['roll'].'_perm'])){
								if(!search_in_array($roll[$moduleroll['roll'].'_perm'], $moduleroll['permissions'])){
									header('location:/deny');
								}
							}else{
								  header('location:/deny');
							}
						}
					}
				}

			/**
			 *
			 * Redirect Roll
			 *
			 */
				public function checkroll($moduleroll){
						
					if($this->logged()){
							
						$roll = $this->permissions;
							
						if(isset($roll[$moduleroll['roll'].'_perm'])){
								
							if(is_array($roll[$moduleroll['roll'].'_perm'])){
								if(!search_in_array($roll[$moduleroll['roll'].'_perm'], $moduleroll['permissions'])){
									header('location:/deny');
								}
							}else{
								header('location:/deny');
							}
						}
					}
				}
			/**
			 *
			 * Get Roll
			 *
			 */
				public function getroll($rolname){
					
					if($this->logged()){
						
						$roll = $this->permissions;

						if(isset($roll[$rolname.'_perm'])){

							return  $roll[$rolname.'_perm'];
						
						}else{
							
							return false;
						
						}
					}
				}
			/**
			 *
			 * Get Perm
			 *
			 */
				public function getperm($rolname,$perm = 'show'){
						
					if($this->logged()){
				
						$roll = $this->permissions;
				
						if(isset($roll[$rolname.'_perm'])){
				
							
							if(search_in_array($roll[$rolname.'_perm'],$perm)){
								return true;
							}else{
								return false;
							}
						}else{
								
							return false;
				
						}
					}
				
				}
				
	}
?>
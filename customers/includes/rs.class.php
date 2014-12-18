<?php
/**
 * RS Class
 *
 * @author William
 * @package Core
 */

/**
 * RS Class
 *
 * @package Core
 */
class rs{
	
	/**
	 * Table
	 */
	public $table;
	
	/**
	 * Primary Key
	 */
	public $primary_key;
	
	/**
	 * Pivot Tables
	 */
	public $pivot_tables;
	
	/**
	 * Debug
	 *
	 * @var booelan
	 */
	public $debug = false;

    /**
	 *	Unique
	 *
	 * @var booelan
	 */
	public $unique= false;
	/**
	 *	Table Fields
	 *
	 * @var array
	 */
	public $table_fields=false;
	/**
	 * Condicion Default
	 * 
	 * @var string
	 */
	public $condicion=false;
	
	/**
	 * Constructor
	 *
	 * @return rs
	 */
	function __construct() {
		if(DEBUG_DB){
			$this->debug = true;
		}
        $condicion='';
	}
	
	/**
	 * Get Pivot SQL
	 *
	 * @return string
	 */
	private function get_pivot(){
		
		$sql = '';
		
		if(is_array($this->pivot_tables)){

			$last_table = $this->table;
			
			foreach($this->pivot_tables as $pivot){
				
				if(isset($pivot['type'])){
					$pivot_type = $pivot['type'];
				}else {
					$pivot_type = ' INNER JOIN ';
				}	
				/*
				 * Reload same table
				 */
				if(isset($pivot['similar'])){
					$similar = $pivot['similar'];
				}else {
					$similar = $pivot['table'];
				}	
				if($last_table != $this->table){
					$sql .= ' ' . $pivot_type . ' ' . db::ftquote($pivot['table']) . ' ';
					$sql .= db::ftquote($pivot['table']) . ' ON ' . db::ftquote($pivot['table']) . '.' . db::ftquote($pivot['link_a']) . ' = ' . db::ftquote($last_table) . '.' . db::ftquote($pivot['link_b']);
				}else {
					$sql .= ' ' . db::ftquote($this->table) . ' ' . $pivot_type . ' ' . db::ftquote($pivot['table']) . ' ';
					$sql .= db::ftquote($similar) . ' ON ' . db::ftquote($this->table) . '.' . db::ftquote($pivot['link_a']) . ' = ' . db::ftquote($similar) . '.' . db::ftquote($pivot['link_b']);					
				}
				
				$last_table = $pivot['table'];
				
			}	
					
		}
		
		return $sql;		
		
	}
		
	/**
	 * Add
	 * 
	 * @param array $data
	 * @return integer 
	 */
	public function add($data){
		
		foreach($data as $key => $value){
			$arr_fields[] = db::ftquote($key);
			
			if($value!='NULL'){

				$arr_values[] = db::quote($value);
			
			}else{
				$arr_values[] = $value;
			}
		
		}
		
		$fields = implode(", ", $arr_fields);
		$values = implode(", ", $arr_values);
				
		$sql = "INSERT INTO " . db::ftquote($this->table) . " (" . $fields . ") VALUES (" . $values .  ")";
		
		if($this->debug){ $this->show_debug($sql); }
		
		$rst = db::query($sql);
		
		if(!$rst){
			return false;
		}
		
		if(db::affected_rows() != 1){
			return false;
		}
		
		$id = db::insert_id();
		
		return $id;
		
	}
	/**
	 * Get Last Insert Id
	 * @return integer
	 */
	public function get_last_insert_id(){

		$sql=' SELECT MAX('.$this->primary_key.') AS last FROM '.db::ftquote($this->table);
	
		$rst 	= db::query($sql);
		
		$result = db::fetch_assoc($rst);

		if($this->debug){
			$this->show_debug($sql);
		}
		
		return $result['last'];
	}
	/**
	 * Show SQL
	 * 
	 * @params string $sql
	 */
	public function show_debug($sql){
		
		echo '<div class="sql_debug">' . $sql . '</div>';		
		
	}
	/**
	 * Update batch - prototype
	 * 
	 * @param array $data
	 * @param integer $id
	 * @return boolean
	 */
	public function batch_update($data, $initial_id, $final_id){
		
		$sql = "UPDATE " . db::ftquote($this->table) . " SET ";
		$update = array();
		
		foreach($data as $key => $value){
			
			if($value!='NULL'){
				$update[] = db::ftquote($key) . " = " . db::quote($value);
			}else{
				$update[] = db::ftquote($key) . " = " . db::escapestring($value);
			}	
		
		}
		
		$sql .= implode(", ", $update);
		$sql .= " WHERE " . db::ftquote($this->primary_key)  . " >= " . db::quote($initial_id) .  " AND ". db::ftquote($this->primary_key). " <= ". db::quote($final_id);
		
		if($this->debug){ $this->show_debug($sql); }
		
		$rst = db::query($sql);

		return $rst;
		
	}
	
	/**
	 * Update
	 * 
	 * @param array $data
	 * @param integer $id
	 * @return boolean
	 */
	public function update($data, $id){
		
		$sql = "UPDATE " . db::ftquote($this->table) . " SET ";
		$update = array();
		
		foreach($data as $key => $value){
			
			if($value!='NULL'){
				$update[] = db::ftquote($key) . " = " . db::quote($value);
			}else{
				$update[] = db::ftquote($key) . " = " . db::escapestring($value);
			}	
		
		}
		
		$sql .= implode(", ", $update);
		$sql .= " WHERE " . db::ftquote($this->primary_key)  . " = " . db::quote($id) .  " LIMIT 1";
		
		if($this->debug){ $this->show_debug($sql); }
		
		$rst = db::query($sql);

		return $rst;
		
	}
	
	/**
	 * Delete
	 * 
	 * @param integer $id
	 * @param bollean $column
	 * @param bollean $limit
	 *  
	 * @return boolean
	 */
	public function delete($id,$column=false,$limit=true,$operator=false){
		if($column==false){

			if($limit==true){
				if($operator==false){
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($this->primary_key) . " = " . db::quote($id) . " LIMIT 1";
				}else{
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($this->primary_key) ." ". $operator  ." ". db::quote($id) . " LIMIT 1";
				}
			}else{
				if($operator==false){
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($this->primary_key) . " = " . db::quote($id);
				}else{
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($this->primary_key) ." ". $operator  ." ". db::quote($id);
				}
			}
		}else{
			if($limit==true){
				if($operator==false){
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($column) . " = " . db::quote($id) . " LIMIT 1";
				}else{
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($column) ." ".  $operator  ." ". db::quote($id) . " LIMIT 1";
				}
			}else{
				if($operator==false){
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($column) . " = " . db::quote($id);
				}else{
					$sql = "DELETE FROM " . db::ftquote($this->table) . " WHERE " . db::ftquote($column) ." ". $operator  ." ". db::quote($id);
				}
			}
		}
		if($this->debug){ $this->show_debug($sql); }
		
		$rst = db::query($sql);
		
		return $rst;
				
	}
	
	/**
	 * Get
	 * 
	 * @param integer $id
	 * @return array 
	 */
	public function get($id){
		
		
			if(is_array($this->table_fields)){
				$sql = "SELECT ".$this->get_table_fields();
			}else{
				$sql = "SELECT " . db::ftquote($this->table) . ".* ";
			}
	
			if(!is_array($this->table_fields)){
				if(is_array($this->pivot_tables)){
					foreach($this->pivot_tables as $pivot){
							if($this->unique==false){
								$sql .= ", " . db::ftquote($pivot['table']) . ".*";
							}else{
								$sql .= "*";
							}
						}
				}
			}
		$sql .= ' FROM ' . db::ftquote($this->table) . ' ';
		
		$sql .= $this->get_pivot();
		
		$sql .= " WHERE " . db::ftquote($this->table) . '.' . db::ftquote($this->primary_key) . " = " . db::quote($id) . " LIMIT 1";
		
		if($this->debug){ $this->show_debug($sql); }
		
		$rst = db::query($sql);
				
		if(!$rst){
			return false;
		}
		
		if(db::num_rows($rst) != 1){
			return false;
		}
		
		$data = db::fetch_assoc($rst);
		
		return $data;
		
	}
	
	/**
	 * Get List
	 * 
	 * @param integer $max
	 * @param integer $pag
	 * @param string $where
	 * @param string $order
	 * @return mysql_resource
	 */
	public function get_list($max = 0, $pag = 1, $where = '', $order = ''){
        $max = (int) $max;
		if(is_numeric($pag)){
            $pag = ((int) $pag) - 1;
        }else{
            $pag = 0;
        }
        if($where!=''){
            if($this->condicion !=''){
                $where = $this->condicion."AND ".$where;
            }
        }else{
            $where = $this->condicion;
        }
		if($this->unique==false){
			if(is_array($this->table_fields)){
				$sql = "SELECT ".$this->get_table_fields();
			}else{
				$sql = "SELECT " . db::ftquote($this->table) . ".* ";
			}
		}else{
			$sql = " SELECT *";	
		
		}
		if(!is_array($this->table_fields)){
			if(is_array($this->pivot_tables)){
				foreach($this->pivot_tables as $pivot){
				 $sql .= ", " . db::ftquote($pivot['table']) . ".*";
				}
			}
		}
		$sql .= ' FROM ' . db::ftquote($this->table) . ' ';
		
		$sql .= $this->get_pivot();
		
		if($where != ''){
			$sql .= " WHERE " . $where;
		}
		
		if($order != ''){
			$sql .= " ORDER BY " . $order;
		}
		
		if($max != 0){
			$from = $max * $pag;
			$sql .= " LIMIT " . $from . ", " . $max;
		}

		if($this->debug){ $this->show_debug($sql); }	
		
		$rst = db::query($sql);
		
		if(!$rst){
			return false;
		}
	
		return $rst;	
		
	}
		
	/**
	 * Count
	 * 
	 * @param string $where
	 * @return integer
	 */
	public function count($where = ''){

		$sql = "SELECT COUNT(*) AS `count` FROM " . db::ftquote($this->table) . ' ';
		$sql .= $this->get_pivot();
		
       if($where!=''){
            if($this->condicion !=''){
                $where = $this->condicion."AND ".$where;
            }
        }else{
            $where = $this->condicion;
        }
        
		if($where != ''){
			$sql .= " WHERE " . $where; 
		}

		if($this->debug){ $this->show_debug($sql); }
		
		$rst = db::query($sql);
		
		if(!$rst){
			return false;
		}
		
		$count = db::fetch_assoc($rst);
		
		return $count['count'];
		
	}
	/**
	 * Get Table Fields
	 *
	 * @return string
	 */
	private function get_table_fields(){
	
		$sql='';
		
		$table_fields = $this->table_fields;
		
		foreach ($table_fields as $index=>$value){
		 $sql.=db::ftquote($value).',';	
		}
		
		return substr($sql,0,-1);
	}
}
?>
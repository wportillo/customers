<?php
class Grid{
	
	public $where;
	
	public $db;
	
	public $page;
	
	public $tpl;
	
	public $security;
	
	public $rolname;
	
	/**
	 * Construct
	 */
		public function __construct($db,$tpl){
			
			$this->security = new Security();
			
			$this->where	= '';
			
			$this->db       = $db;
		
			$this->tpl		= $tpl;
		}
		/**
		 * Delete elemts Grid
		 * @param $i_element
		 */
		public function delete($i_element,$default='trash'){

			if($this->security->getperm($this->rolname,'delete')){
					
				if(!is_array($i_element)){
						
					$element_info = $this->db->get($i_element);
			
					if($element_info[$default]=='1'){
							
				   		$this->db->delete($i_element);
							
						return array('deleted'=>$i_element);
							
					}else{
							
						/**
						 * Trash
						 */
						$data[$default]=1;
							
						$this->db->update($data, $i_element);
							
						return array('trash'=>$i_element);
					}
				}else{
					foreach($i_element as $i_element_arr){
			
						$element_info = $this->db->get($i_element_arr);
			
						if($element_info[$default]=='1'){
								
							$this->db->delete($i_element_arr);
								
						}else{
								
							/**
							 * Trash
							 */
							$data[$default]=1;
								
							$this->db->update($data, $i_element_arr);
								
						}
					}
				}
			}
		}
		
		/**
		 * Active elemts Grid
		 * @param integer or array $i_element
		 * @param string $element_name
		 */
		public function active($i_element,$element_name='active'){
		
			if($this->security->getperm($this->rolname,'edit')){
			
				if(!is_array($i_element)){
					/**
					 * Restore
					 */
					$data[$element_name]=1;
			
					$this->db->update($data, $i_element);
			
					return array('active'=>$i_element);
			
				}else{
						
					foreach($i_element as $i_element_arr){
						/**
						 *  Desactive
						 */
						$data[$element_name]=1;
							
						$this->db->update($data, $i_element_arr);
							
					}
						
					return array('active'=>$i_element_arr);
				}
			}
		}
		/**
		 * Desctive elemts Grid
		 * @param integer or array $i_element
		 * @param string $element_name
		 */
			public function desactive($i_element,$element_name='active'){
			
				if($this->security->getperm($this->rolname,'edit')){
					
					if(!is_array($i_element)){
						/**
						 * Restore
						 */
						$data[$element_name]=0;
				
						$this->db->update($data, $i_element);
				
						return array('desactive'=>$i_element);
				
					}else{
							
						foreach($i_element as $i_element_arr){
							/**
							 *  Desactive
							 */
								
							$data[$element_name]=0;
								
							$this->db->update($data, $i_element_arr);
						}
							
						return array('desactive'=>$i_element_arr);
					}
				}
			}
		
	/**
	 * Restore elemts Grid
	 * @param $i_element
	 */
		public function restore($i_element,$default='trash'){
			
			if($this->security->getperm($this->rolname,'delete')){
				
				if(!is_array($i_element)){
					/**
					 * Restore
					 */
					$data[$default]=0;
			
					$this->db->update($data, $i_element);
			
					return array('restore'=>$i_element);
			
				}else{
					foreach($i_element as $i_element_arr){
						/**
						 * Restore
						 */
						$data[$default]=0;
							
						$this->db->debug=true;
							
						$this->db->update($data, $i_element_arr);
					}
						
					return array('restore'=>$i_element_arr);
				}
			}
		}
	
	/** 
	 * Generate Pagination
	 * 
	 * @return $pag
	 */
	public function generate_pagination($page,$max,$go_to=''){
	
			$tpl = new HTML_Template_Sigma(TEMPLATES_CLASS_PATH, TEMPLATES_CLASS_CACHE_PATH);
			
			$tpl->loadTemplatefile('pagination.tpl.html');
			
			$tpl->touchBlock('pagination');

			$amount = $this->db->count($this->where);
			
			$pages = ceil($amount / $max);
			
			if($pages != 0){
				
				$pag=1;
					
				if($page){
					$pag=$page;
				}
				
				if(is_array($go_to)){
					if($go_to[0]!=''){
						$pag = (int) $go_to[0];
					}
					if($go_to[1]!=''){
						$pag = (int) $go_to[1];
					}
				}
				
				if($pag <= 1){
					$pag=1;
					$down_pag  = 1;
				}else{
					
					$down_pag  = $pag - 1;
				}
				
				if($pag >= $pages){
					$pag = $pages;
				}		
				
		
			
				if($pag >= 1){
					
					$tpl->setCurrentBlock('previous');
						
						$tpl->setVariable('back', '?pagenumber='.$down_pag.'&sent_form=1');
						
						$tpl->setVariable('previous_direction',$this->page);
						
						$tpl->setVariable('page', $pag);	
					
					$tpl->parse('previous');
				}
		
				$up_pag = $pag + 1;
				
				if($pag <= $pages){
					
					$tpl->setCurrentBlock('following');
					
						$tpl->setVariable('next','?pagenumber='.$up_pag.'&sent_form=1');
						
						$tpl->setVariable('following_direction',$this->page);
						
						$tpl->setVariable('page',$pages);
					
					$tpl->parse('following');
				}
					/*
					 * Paginado Numeros
					 */
					
					$i=1;

					$tpl->setVariable('amount_results',$amount);
					
					$tpl->touchBlock('records');
					
				}else{
					$this->tpl->touchBlock('norecords');	
					$pag=1;
					$tpl->setVariable('amount_results',0);
				}
				/*
				 * Generate Html Pagination
				 */
				$this->tpl->setVariable('pagination',$tpl->get());
				
				return $pag;
		}
		
		/**
		 * Generate order by Fieldname
		 * @param $page
		 * @param $page_number
		 * @param $elements
		 * @param $field_value
		 * @param $order_type
		 */
			public function Orderby($page,$elements,$order,$table){
				
					foreach($elements as $db_name=>$label_name){
						
							if($table==$db_name){
								    
								   if($order=='asc'){
								    	$html="<th><a href='{$page}/?table={$db_name}&order=desc&sent_form=1' style='text-decoration:underline;'>{$label_name} DESC</a></th>";
								   }else{
								   		$html="<th><a href='{$page}/?table={$db_name}&order=asc&sent_form=1' style='text-decoration:underline;'>{$label_name} ASC</a></th>";
								   }
							
								 
							}else{
								$html="<th><a href='{$page}/?table={$db_name}&order=asc&sent_form=1' style='text-decoration:underline;'>{$label_name}</a></th>";
							}
					
							$this->tpl->setVariable($db_name.'_order_by',$html);
					}
			}
}
?>
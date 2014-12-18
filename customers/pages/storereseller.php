<?php
		$moduleroll = array('roll'=>'storeresellers','permissions'=>'edit');
	
		$page_title       = 'Tiendas';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_store			  = $apache_index->get_uri_position(2);
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Crm_store = new Crm_store();
			
			$Crm_store->pivot_tables=false;
			
			$Elements = new Elements($Crm_store, $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_store!=''){
				
				$rows=$Elements->db->get($i_store);
				
				$tpl->setVariable('title','Editar Tienda');
				
				/**
				 * If relation users
				 */
					$Users = new Users();
				
					$Users->primary_key='i_store';
				
					$users = $Users->get($i_store);
				
			}else{

				$tpl->setVariable('title','Agregar Tienda');	

				$rows=false;
				
				$users=false;
			}

			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'input','label'=>'*Telefono','size'=>'3','name'=>'phone'),
					array('value'=>'','type'=>'input','label'=>'*Direccion','name'=>'address','size'=>'3'),
					array('value'=>'','type'=>'input','label'=>'*Ciudad','name'=>'city','size'=>'3'),
					array('value'=>'','type'=>'input','label'=>'Hashtag','name'=>'hashtag','size'=>'3'),
					array('value'=>'','type'=>'input','label'=>'Balance','name'=>'balance','size'=>'1'),
					(!$users) ? array('value'=>'0','type'=>'radio','label'=>'Activo','name'=>'active','size'=>'3') : null ,
					($session_vars['i_reseller']=='0')? array('value'=>'0',
							'type' 	    =>'dropmenudb',
							'label'	    =>'Reseller',
							'table'     => new Crm_reseller(),
							'where'	    =>'trash!=1',
							'drop_value'=>'i_reseller',
							'drop_label'=>'name',
							'name'	    =>'i_reseller'
					) : array('value'=>$session_vars['i_reseller'],'name'=>'i_reseller'),
			);
		
			
			/**
			 * Charge from values
			 */
				foreach($elements as $value){
		
					 $key = $value['name'];
					
					 if(isset($key)){
								
									$form_val[$key] 	= _post($key,$value['value']);
									
									switch($key){
										default:
											$data_mysql[$key]	= $form_val[$key];
										break;
									}
						}
					}

					if($form_val['sent_form']!=''){
						
						try {
	
							if($form_val['name']==''){
	
								throw new Exception('Debe ingresar nombre de reseller');
							}
							
							if($form_val['address']==''){
									
								throw new Exception('Debe ingresar una Direccion');
							}
							
							if($form_val['city']==''){
									
								throw new Exception('Debe ingresar una Ciudad');
							}
							
							if($form_val['i_reseller']=='0'){
							
								throw new Exception('Debe asignar esta tienda a un Reseller');
							}
							
							if($form_val['phone']==''){
							
								throw new Exception('Debe ingresar un numero telefonico correcto.');
							}
							
							if($i_store!=''){

									$Elements->db->update($data_mysql, $i_store);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/storeresellers');
	
								}else{
								
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/storeresellers');
							}
								
						}catch (Exception $e){
							
							/**
							 * Show Notification event
							 */
								$Elements->notification($e->getMessage(),2);
							
							/**
							 * Autocomplete send values
							 */
								foreach($form_val as $key=>$val){
									switch($key){
										default:
											$rows[$key] = $form_val[$key];
										break;
									}
								}
						}
					}

					$Elements->LoadElements($elements, $rows);
		
					$module_content = $tpl->get();
			
				/*
				 * Display File
				 */
				require_once('display.inc.php');
?>
<?php
		$moduleroll = array('roll'=>'roles','permissions'=>'edit');
	
		$page_title       = 'Rol';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_roll			  = $apache_index->get_uri_position(2);
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
			
			$Elements = new Elements(new Roll(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_roll!=''){
				
					$rows=$Elements->db->get($i_roll);
				
					$rows['show'] = unserialize($rows['show']);
				
					$tpl->setVariable('title','Editar Roll');

				/**
				 * If relation users
				 */
					$Users = new Users();
				
					$Users->primary_key='i_roll';
				
					$users = $Users->get($i_roll);
				
			}else{

				$tpl->setVariable('title','Agregar Roll');	

				$rows=false;
				
				$users=false;
			}

			$elements = array(
					
					array('value'=>'0','name'=>'trash'),
					
					array('value'=>'0','name'=>'customers_perm'),
					
					array('value'=>'0','name'=>'users_perm'),
					
					array('value'=>'0','name'=>'roles_perm'),
					
					array('value'=>'0','name'=>'promocodes_perm'),

					array('value'=>'0','name'=>'mailing_perm'),

					array('value'=>'0','name'=>'categories_perm'),

					array('value'=>'0','name'=>'contents_perm'),

					array('value'=>'0','name'=>'resellers_perm'),

					array('value'=>'0','name'=>'storeresellers_perm'),

					array('value'=>'0','name'=>'sales_perm'),

					array('value'=>'0','name'=>'freeregistry_perm'),
					
					array('value'=>'0','name'=>'tvmiaapi_perm'),

					array('value'=>'0','name'=>'commissions_perm'),

					array('value'=>'0','name'=>'products_perm'),
					
					array('value'=>'','type'=>'input','label'=>'*Nombre Roll','size'=>'3','name'=>'name'),
					
					array('value'=>'0',
							'type' 	      =>'dropmenudb',
							'label'	      =>'*Rol',
							'table'       => new Roll(),
							'where'	  	  =>'trash!=1',
							'drop_value'  =>'i_roll',
							'drop_label'  =>'name',
							'name'	  	  =>'show',
							'multiselect' =>true,
					),
					(!$users) ? array('value'=>'0','type'=>'radio','label'=>'*Activo','name'=>'active') :null ,
			);
		
			
				/**
				 * Charge from values
				 */
					foreach($elements as $value){
			
						$key = $value['name'];
							
						if(isset($key)){
		
							$form_val[$key] 	= _post($key,$value['value']);
							
							switch($key){
								case 'name':
								case 'active':
								case 'trash':
									$data_mysql[$key]	= $form_val[$key];
								break;
								default :
									$data_mysql[$key] 	= serialize($form_val[$key]);
								break;
							}
						}
					}

					if($form_val['sent_form']!=''){
						
						try {
	
							if($form_val['name']==''){
	
								throw new Exception('Debe ingresar un nombre para este roll');
							}
	
							if($i_roll!=''){
								
									$Elements->db->update($data_mysql, $i_roll);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/roles');
								
							}else{
									
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/roles');
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
										case 'name':
										case 'active':
										case 'trash':
											$rows[$key] = $form_val[$key];
										break;
										default:
											$rows[$key] = serialize($form_val[$key]);
										break;
									}
								}
						}
					}
					
					$Elements->permission_grid($rows);
					
					$Elements->LoadElements($elements, $rows);
		
					$module_content = $tpl->get();
			
				/*
				 * Display File
				 */
				require_once('display.inc.php');
?>
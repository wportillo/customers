<?php
		$moduleroll = array('roll'=>'resellers','permissions'=>'edit');
	
		$page_title       = 'Usuario';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_reseller			  = $apache_index->get_uri_position(2);
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(new Crm_reseller(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_reseller!=''){
				
				$rows=$Elements->db->get($i_reseller);
				
				$tpl->setVariable('title','Editar Reseller');
				
				/**
				 * If relation users
				 */
					$Users = new Users();
				
					$Users->primary_key='i_reseller';
				
					$users = $Users->get($i_reseller);
				
			}else{

				$tpl->setVariable('title','Agregar Reseller');	

				$rows  = false;
				
				$users = false;
			}

			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'input','label'=>'*Contacto','size'=>'3','name'=>'contact'),
					array('value'=>'','type'=>'input','label'=>'*Email','size'=>'3','name'=>'email','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Telefono','size'=>'3','name'=>'phone'),
					array('value'=>'','type'=>'input','label'=>'*Direccion','name'=>'address','size'=>'3'),
					array('value'=>'','type'=>'input','label'=>'*Ciudad','name'=>'city','size'=>'3'),
					array('value'=>'','type'=>'input','label'=>'*Zip','name'=>'zipcode','size'=>'3'),
					array('value'=>'0',
			 		 	   'dropvalues'=> array('50'=>'50','100'=>'100','150'=>'150','200'=>'200','250'=>'250'),
			 		 	   'type' 	   =>'dropmenu',
			 		 	   'label'=>'Minimo Residual',
			 		 	   'name' =>'residual'),
					(!$users) ? array('value'=>'0','type'=>'radio','label'=>'Activo','name'=>'active','size'=>'3') : null ,
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
	
							if($form_val['contact']==''){
							
								throw new Exception('Debe ingresar nombre de contacto');
							}
							
							if(!Check::email($form_val['email'])){
								
								throw new Exception('Debe ingresar un email correcto.');
							}
							
							if($form_val['phone']==''){
							
								throw new Exception('Debe ingresar un numero telefonico correcto.');
							}
							
							if($form_val['address']==''){
									
								throw new Exception('Debe ingresar una Direccion');
							}
							
							if($form_val['city']==''){
									
								throw new Exception('Debe ingresar una Ciudad');
							}
							
							if($form_val['zipcode']==''){
									
								throw new Exception('Debe ingresar una Codigo de area');
							}
							
							if($i_reseller!=''){
									
									$Elements->db->update($data_mysql, $i_reseller);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/resellers');
	
								}else{
								
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/resellers');
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
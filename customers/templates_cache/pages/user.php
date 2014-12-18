<?php
		$moduleroll = array('roll'=>'users','permissions'=>'edit');
	
		$page_title       = 'Usuario';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_user			  = $apache_index->get_uri_position(2);
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Crm_store = new Crm_store();
			
			$Crm_store->table_fields= array(
					'crm_store`.`name',
					'crm_store`.`i_reseller',
					'crm_store`.`i_store',
			);
			
			$Elements = new Elements(new Users(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_user!=''){
				
				$rows=$Elements->db->get($i_user);
				
				if(!date_null($rows['expiration_date'])){
					$rows['expiration_date'] = mysql_date_to_spanish($rows['expiration_date']);
				}else{
					$rows['expiration_date'] ='';
				}
				
				$rows['password']		 = '';
				
				$tpl->setVariable('title','Editar Usuario');
				
			}else{

				$tpl->setVariable('title','Agregar Usuario');	

				$rows=false;
			}

			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre Completo','size'=>'3','name'=>'fullname'),
					array('value'=>'','type'=>'input','label'=>'*Email','size'=>'3','name'=>'email','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Usuario','size'=>'3','name'=>'user'),
					array('value'=>'','type'=>'input','label'=>'*Contraseña','size'=>'3','name'=>'password','inputtype'=>'password'),
					array('value'=>'0','type'=>'radio','label'=>'*Expira','name'=>'expiration_flag'),
					array('value'=>'','type'=>'calendar','label'=>'*Expiracion','name'=>'expiration_date'),
					array('value'=>'0','type'=>'radio','label'=>'Usuario Activo','name'=>'active'),
					
					array('value'=>'0',
						  'type' 	  =>'dropmenurol',
						  'label'	  =>'*Rol',
						  'table'     => new Roll(),
						  'where'	  =>'trash!=1',
						  'drop_value'=>'i_roll',
						  'drop_label'=>'name',
						  'name'	  =>'i_roll',
						  'i_roll'	  => $session_vars['i_roll']
					),
					
					($session_vars['i_reseller'] =='0') ?
					
					array('value'=>'0',
							'type' 	    =>'dropmenudb',
							'label'	    =>'*Reseller',
							'table'     => new Crm_reseller(),
							'where'	    =>'trash!=1',
							'drop_value'=>'i_reseller',
							'drop_label'=>'name',
							'name'	    =>'i_reseller'
					): array('value'=>$session_vars['i_reseller'],'name'=>'i_reseller'),
					
					($session_vars['i_reseller']!='0') ?
					
					array('value'=>'0',
							'type' 	    =>'dropmenudb',
							'label'	    =>'*Tienda',
							'table'     => $Crm_store,
							'where'	    =>'`crm_store`.`trash`!= 1 AND `crm_store`.`i_reseller`='.$session_vars['i_reseller'],
							'drop_value'=>'i_store',
							'drop_label'=>'name',
							'name'	    =>'i_store'
					) :
					
					null ,
			);
	
			/**
			 * Charge from values
			 */
				foreach($elements as $value){
		
					 $key = $value['name'];
					
					 if(isset($key)){
								
									$form_val[$key] 	= _post($key,$value['value']);
									
									switch($key){
										case 'expiration_date':
											if($form_val[$key]!=''){	
												$data_mysql[$key]	= spanish_date_to_mysql($form_val[$key]);
											}else{
												$data_mysql[$key]	= '0000-00-00 00:00:00';
											}
										break;
										case 'password':
											if($form_val[$key]!=''){
												$data_mysql[$key] = encrypt($form_val[$key], ENCRYPT_KEY);
											}
										break;
										case 'i_reseller':
										case 'i_roll':
										case 'i_store':
											if($form_val[$key]=='0'){
												$data_mysql[$key] = 'NULL';
											}else{
												$data_mysql[$key] = $form_val[$key];
											}
										break;
										default:
											$data_mysql[$key]	= $form_val[$key];
										break;
									}
								}
						}
					
					if($form_val['sent_form']!=''){
						
						try {
	
							if($form_val['fullname']==''){
	
								throw new Exception('Debe ingresar Nombre compledo del usuario a registrar');
							}
	
							if(!Check::email($form_val['email'])){
								
								throw new Exception('Debe ingresar un email correcto.');
							}
							
							if(!Check::email($form_val['email'])){
	
								throw new Exception('Debe ingresar un usuario para ingresar a la plataforma.');
							
							}
						
							if($i_user!=''){
								
								if($Elements->db->count('`email`='.db::quote($form_val['email']).' AND `i_user`!='.db::quote($i_user))>0){
									
									throw new Exception('El Email ingresado ya se encuentra en nuestro sistema');
								}
								
								if($Elements->db->count('`user`='.db::quote($form_val['user']).' AND `i_user`!='.db::quote($i_user))>0){
	
									throw new Exception('El Usuario ingresado ya se encuentra en nuestro sistema');
								
								}

							}else{
							
								if($Elements->db->count('`email`='.db::quote($form_val['email']))>0){
	
									throw new Exception('El Email ingresado ya se encuentra en nuestro sistema');
								
								}
								
								if($Elements->db->count('`user`='.db::quote($form_val['user']))>0){
	
									throw new Exception('El Usuario ingresado ya se encuentra en nuestro sistema');
								
								}
								
								if($form_val['password']==''){
									throw new Exception('Debe ingresar una Contraseña');
								}
							
							}
							
							if($form_val['expiration_flag']=='1'){
							
								if($form_val['expiration_date']==''){
									throw new Exception('Debe ingresar una fecha de Expiracion');
								}
							}
							
							if($session_vars['i_roll']=='12'){
								
								if($form_val['i_roll']=='0'){
									
									throw new Exception('Debe ingresar un Rol para asignarle permisos a este usuario');
								}	
							}
						
							if($i_user!=''){
									
									$Elements->db->update($data_mysql, $i_user);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/users');
	
								}else{
								
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/users');
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
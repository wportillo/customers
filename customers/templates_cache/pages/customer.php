<?php
		$moduleroll = array('roll'=>'customers','permissions'=>'edit');
	
		$page_title       				= 'Informacion cliente';
		
		$layout           				= true;
		
		$error			  				= false;
		
		$page	  		  				= $apache_index->get_uri_position(1);
		
		$i_customer		  				= $apache_index->get_uri_position(2);
		
		$Customer_date_info 			= new Customer_date_info();
		
		$Customer_date_info->primary_key= 'i_customer';
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(new Customer_info(), $tpl);
	
			$Elements->Main_bar(array(
					'customer/'.$i_customer	=>'Editar informacion',
					'credit/'				=>'Datos de credito',
					'paymentdetail/'		=>'Detalle de Transacciones',
					'deviceregistration/'	=>'Registro de Dispositivos',
				
			));
			
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_customer!=''){
				
				$rows  = $Elements->db->get($i_customer);
				
				$valid = $Customer_date_info->get($i_customer);
				
				if(!date_null($valid['valid'])){
					$rows['valid'] = mysql_date_to_spanish($valid['valid']);
				}else{
					$rows['valid'] ='';
				}
				
				$rows['password']		 = '';
				
				$tpl->setVariable('title','Editar Informacion');
				
				$_SESSION['i_customer_property'] = $i_customer;
				
			}else{
				
				header('location:/customers');
			
			}
			
			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'input','label'=>'*Apellido','size'=>'3','name'=>'surname'),
					array('value'=>'','type'=>'input','label'=>'*Email','size'=>'3','name'=>'email','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Contraseña','size'=>'3','name'=>'password','inputtype'=>'password'),
					array('value'=>'','type'=>'input','label'=>'*Direccion','size'=>'3','name'=>'address'),
					array('value'=>'','type'=>'input','label'=>'*Cod pais','size'=>'1','name'=>'areacode'),
					array('value'=>'','type'=>'input','label'=>'*Telefono','size'=>'1','name'=>'phone'),
					array('value'=>'','type'=>'input','label'=>'Zip','size'=>'3','name'=>'zip'),
					array('value'=>'','type'=>'input','label'=>'Ciudad','size'=>'3','name'=>'city'),
					array('value'=>'','type'=>'input','label'=>'Estado','size'=>'3','name'=>'state'),
					array('value'=>'','type'=>'calendar','label'=>'*Valido hasta','name'=>'valid'),
					array('value'=>'0','type'=>'radio','label'=>'Activo','name'=>'active'),
			);
	
			/**
			 * Charge from values
			 */
				foreach($elements as $value){
		
					 $key = $value['name'];
					
					 if(isset($key)){
								
									$form_val[$key] 	= _post($key,$value['value']);
									
									switch($key){
										case 'valid':
											
											if($form_val[$key]!=''){
												$data_valid[$key]	= spanish_date_to_mysql($form_val[$key]);
											}else{
												$data_valid[$key]	= '0000-00-00 00:00:00';
											}
											
										break;
										case 'password':
											if($form_val[$key]!=''){
												$data_mysql[$key] = encrypt($form_val[$key], ENCRYPT_KEY);
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
	
							if($form_val['name']==''){
	
								throw new Exception('Debe ingresar Nombre compledo del cliente');
							}
	
							if($form_val['surname']==''){
							
								throw new Exception('Debe ingresar apellido del cliente');
							}
							
							if(!Check::email($form_val['email'])){
								
								throw new Exception('Debe ingresar un email correcto.');
							}
							
							if($Elements->db->count(' `customer_info`.`email`='.db::quote($form_val['email']).' AND `customer_info`.`i_customer`!='.db::quote($i_customer))>0){
								
								throw new Exception('El Email ingresado ya se encuentra en nuestro sistema');
							}

							if($form_val['areacode']==''){
									
								throw new Exception('Debe ingresar el codigo de pais');
							}
							
							if($form_val['phone']==''){
									
								throw new Exception('Debe ingresar el numero telefonico');
							}
							
							if($form_val['address']==''){
							
								throw new Exception('Debe ingresar una direccion.');
							}
							
							if($form_val['valid']==''){
								throw new Exception('Debe ingresar la fecha hasta cuando estará activo el cliente');
							}
									
							$Elements->db->update($data_mysql, $i_customer);

							$Customer_date_info->update($data_valid, $i_customer);
							
							$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
							$_SESSION['label_notification']['type'] = 1;
									
							header('location:/customers');
								
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
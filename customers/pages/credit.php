<?php
		$moduleroll = array('roll'=>'customers','permissions'=>'edit');
	
		$page_title       = 'Datos de credito';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$Credit_validator = new Credit_card_validator();
		
		$i_customer		  = _session('i_customer_property');
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Customer_credit_info = new Customer_credit_info();
			
			$Customer_credit_info->primary_key='i_customer';
			
			$Elements = new Elements($Customer_credit_info, $tpl);
	
			$Elements->Main_bar(array(
					'customer/'.$i_customer	=>'Editar informacion',
					'credit/'				=>'Datos de credito',
					'paymentdetail/'		=>'Detalle de Transacciones',
					'deviceregistration/'	=>'Registro de Dispositivos',
				
			));
			
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_customer!=''){
				
				$rows=$Elements->db->get($i_customer);
				
				$rows['number'] = '****'.substr(decrypt($rows['number'],ENCRYPT_KEY),-4);
				
				$rows['cvv']='';
				
				$tpl->setVariable('title','Datos de credito');
				
				$_SESSION['i_customer_property'] = $i_customer;
				
			}else{
				
				header('location:/customers');
			
			}
			
			$elements = array(
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'c_name'),
					array('value'=>'','type'=>'input','label'=>'*Apellido','size'=>'3','name'=>'c_surname'),
					array('value'=>'','type'=>'input','label'=>'*Zip','size'=>'3','name'=>'c_zip'),
					array('value'=>'0',
						 'dropvalues'=> 	
						    array('01'=>'Enero',
								  '02'=>'Febrero',
								  '03'=>'Marzo',
								  '04'=>'Abril',
								  '05'=>'Mayo',
								  '06'=>'Junio',
								  '07'=>'Julio',
								  '08'=>'Agosto',
								  '09'=>'Setiembre',
								  '10'=>'Octubre',
								  '11'=>'Noviembre',
								  '12'=>'Diciembre'),
							'type'		=>'dropmenu',
							'label'		=>'*Mes',
							'name'		=>'month'
					),
					array('value'=>'0',
							'dropvalues'=>
							array('2012'=>'2012',
									'2013'=>'2013',
									'2014'=>'2014',
									'2015'=>'2015',
									'2016'=>'2016',
									'2017'=>'2017',
									'2018'=>'2018',
									'2019'=>'2019',
									'2020'=>'2020'),
							'type'		=>'dropmenu',
							'label'		=>'*Año',
							'name'		=>'year'
					),
					array('value'=>'','type'=>'input','label'=>'*Direccion','size'=>'3','name'=>'c_address'),
					array('value'=>'','type'=>'input','label'=>'*Numero tarjeta','size'=>'3','name'=>'number'),
					array('value'=>'','type'=>'input','label'=>'*Cod seguridad','size'=>'1','name'=>'cvv','inputtype'=>'password'),
			);
	
			/**
			 * Charge from values
			 */
				foreach($elements as $value){
		
					 $key = $value['name'];
					
					 if(isset($key)){
								
									$form_val[$key] 	= _post($key,$value['value']);
									
									switch($key){
										case 'number':
										case 'cvv':
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
	
							if($form_val['c_name']==''){
	
								throw new Exception('Debe ingresar Nombre compledo del cliente');
							}
	
							if($form_val['c_surname']==''){
							
								throw new Exception('Debe ingresar apellido del cliente');
							}
							
							if($form_val['c_address']==''){
									
								throw new Exception('Debe ingresar una direccion');
							}
							
							if($form_val['c_zip']==''){
								throw new Exception('Debe ingresar una direccion');
							}
							
							if($form_val['month']=='0'){
								throw new Exception('Debe ingresar el mes de vencimiento de la tarjeta');
							}
							
							if($form_val['year']=='0'){
								throw new Exception('Debe ingresar el ano de vencimiento de la tarjeta');
							}
							
							if(!$Credit_validator->Card_Expiration_Check($form_val['month'], $form_val['year'])){
								throw new Exception('La tarjeta ingresada se encuentra expirada');
							}
							
							if(!$Credit_validator->LuhnCheck($form_val['number'])){
								throw new Exception('La tarjeta de Credito Ingresado no es valida');
							}
							
							if($form_val['cvv']==''){
								throw new Exception('Debe ingresar el codigo de seguridad de la parte posterior de la tarjeta');
							}
							
							if($form_val['cvv']!=''){
								
								$Elements->db->update($data_mysql, $i_customer);
								
								$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
								$_SESSION['label_notification']['type'] = 1;
							}
							
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
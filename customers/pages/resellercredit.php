<?php
		$moduleroll = array('roll'=>'resellers','permissions'=>'show');
	
		$page_title       = 'Datos de credito';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$Credit_validator = new Credit_card_validator();
		

		if($session_vars['i_reseller']!='0'){
			
			$i_reseller 		 = $session_vars['i_reseller'];
		
		}else{

			$i_reseller 		 = _session('i_reseller_property');
		}
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Crm_Reseller_credit_info = new Crm_reseller_credit_info();
			
			$Crm_Reseller_credit_info->primary_key='i_reseller';
			
			$Elements = new Elements($Crm_Reseller_credit_info, $tpl);
	
			
			if($session_vars['i_reseller']=='0'){
				$Elements->Main_bar(array(
						'reseller/'.$i_reseller	=>'Editar informacion',
						'resellercredit/'		=>'Datos de credito',
						'resellerpaymentdetail/'=>'Detalle de Transacciones',
				));
			}
			
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_reseller!=''){
				
				$rows=$Elements->db->get($i_reseller);
				
				if(!$rows){
					
					$data['i_reseller']=$i_reseller;
					
					$Elements->db->add($data);	
				}
				
				$rows=$Elements->db->get($i_reseller);
				
				
				$rows['number'] = '****'.substr(decrypt($rows['number'],ENCRYPT_KEY),-4);
				
				$rows['cvv']='';
				
				$tpl->setVariable('title','Datos de credito');
				
			}else{
				
				header('location:/resellers');
			
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
	
								throw new Exception('Debe ingresar nombre completo');
							}
	
							if($form_val['c_surname']==''){
							
								throw new Exception('Debe ingresar apellido');
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
								throw new Exception('La tarjeta de credito Ingresada no es valida');
							}
							
							if($form_val['cvv']==''){
								throw new Exception('Debe ingresar el codigo de seguridad de la parte posterior de la tarjeta');
							}
							
							if($form_val['cvv']!=''){
								
								$Elements->db->update($data_mysql, $i_reseller);
								
								$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
								$_SESSION['label_notification']['type'] = 1;
							}
							
							header('location:/resellers');
								
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
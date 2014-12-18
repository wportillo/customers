<?php
		$moduleroll = array('roll'=>'sales','permissions'=>'edit');
	
		$page_title       = 'Ventas';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);

		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Customer = new Customer();

			$Elements = new Elements(false, $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			$tpl->setVariable('title','Crear Cuenta');	

			$rows=false;
			
			$elements = array(
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'input','label'=>'*Apellido','size'=>'3','name'=>'surname'),
					array('value'=>'','type'=>'input','label'=>'*Email','size'=>'3','name'=>'email','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Repetir Email','size'=>'3','name'=>'email_repeat','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Contraseña','size'=>'3','name'=>'password','inputtype'=>'password'),
					array('value'=>'','type'=>'input','label'=>'*Repetir Contraseña','size'=>'3','name'=>'password_repeat','inputtype'=>'password'),
					
					array('value'=>'0',
							'type' 	    =>'dropmenudb',
							'label'	    =>'*Producto',
							'table'     => New Product_info(),
							'where'		=>' amount!=0',
							'drop_value'=>'key_product',
							'drop_label'=>'name_product',
							'name'	    =>'key_product'
					),
					
					array('value'=>'0',
							'type' 	    =>'dropmenudb',
							'label'	    =>'*Pais',
							'table'     => New Country(),
							'where'		=>'',
							'drop_value'=>'value',
							'drop_label'=>'label',
							'name'	    =>'country'
					),
					array('value'=>'','type'=>'input','label'=>'*Cod pais','size'=>'1','name'=>'areacode'),
					array('value'=>'','type'=>'input','label'=>'*Telefono','size'=>'2','name'=>'phone'),
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'c_name'),
					array('value'=>'','type'=>'input','label'=>'*Apellido','size'=>'3','name'=>'c_surname'),
					array('value'=>'','type'=>'input','label'=>'*Zip','size'=>'1','name'=>'c_zip'),
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
					array('value'=>'0',
							'dropvalues'=>
							array(  'credit'=>'Credito',
									'debit'=>'Debito',
									  ),
							'type'		=>'dropmenu',
							'label'		=>'*Metodo de pago',
							'name'		=>'payment_method',
							'onchange'	=> 'showcredit(this.value);'
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
										default:
											$data_mysql[$key]	= $form_val[$key];
										break;
									}
								}
						}
					
					if($form_val['sent_form']!=''){
						
						$message = $Customer->addpremium($data_mysql,$session_vars);
						
							if($message){	
								
								/**
								 * Show Notification event
								 */
									$Elements->notification($message->error->es,2);
									
									/**
									 * Autocomplete send values
									 */
										foreach($form_val as $key=>$val){
											
											switch($key){
												case 'payment_method':

													$rows[$key] = $form_val[$key];
													
													if($rows[$key]=='credit'){
														$tpl->setVariable('display_credit','display:block;');	
													}
													
												break;
												default:
													$rows[$key] = $form_val[$key];
												break;
											}
										}
						
						     }else{
						     	
						 
						     	

						     	/**
						     	 * Autocomplete send values
						     	 */
						     	foreach($form_val as $key=>$val){
						     			
						     		switch($key){
						     			case 'payment_method':
						     	
						     				$rows[$key] = $form_val[$key];
						     					
						     				if($rows[$key]=='credit'){
						     					$tpl->setVariable('display_credit','display:block;');
						     				}
						     					
						     			break;
						     			default:
						     				$rows[$key] = $form_val[$key];
						     			break;
						     		}
						     	}
						     	
						     	$_SESSION['label_notification']['label'] = $page_title.' creado correctamente';
						     	
						     	$_SESSION['label_notification']['type'] = 1;

						     	//header('location:/home');
						     }
					  } 

					$Elements->LoadElements($elements, $rows);
		
					$module_content = $tpl->get();
			
				/*
				 * Display File
				 */
				require_once('display.inc.php');
?>
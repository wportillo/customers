<?php
		$moduleroll = array('roll'=>'resellers','permissions'=>'edit');
	
		$page_title       = 'Usuario';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_reseller		  = $apache_index->get_uri_position(2);
		
		$Store			  = new Crm_store();
		 
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements 			= new Elements(new Crm_reseller(), $tpl);
			
			
			
			$Elements->rolname	= $moduleroll['roll'];
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			
			if($i_reseller!=''){
				
				$Elements->Main_bar(array(
						'reseller/'.$i_reseller	=>'Editar informacion',
						'resellercredit/'		=>'Datos de credito',
						'resellerpaymentdetail/'		=>'Detalle de Transacciones',
				));
				
				$rows=$Elements->db->get($i_reseller);
				
				$tpl->setVariable('title','Editar Reseller');
				
				/**
				 * If relation users
				 */
					$Users = new Users();
				
					$Users->primary_key='i_reseller';
				
					$users = $Users->get($i_reseller);
					
					$_SESSION['i_reseller_property'] = $i_reseller;
				
					if($rows['residual']!='0'){
						$tpl->setVariable('display_residual','display:block;');
					}
					
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
					array('value'=>'','type'=>'input','label'=>'Hashtag','name'=>'hashtag','size'=>'3'),
					array('value'=>'','type'=>'input','label'=>'Balance','name'=>'balance','size'=>'2'),
					array('value'=>'','type'=>'input','label'=>'Comisión por recarga %','name'=>'commission','size'=>'1'),
					array('value'=>'','type'=>'input','label'=>'Comisión nuevo cliente %','name'=>'create_commission','size'=>'1'),
					array('value'=>'','type'=>'input','label'=>'Comisión Residual %','name'=>'residual_commission','size'=>'1'),
					array('value'=>'0',
							'dropvalues'=> array('debit'=>'Debito','credit'=>'Credito'),
							'type' 	    =>'dropmenu',
							'label'		=>'Tipo',
							'name' 		=>'type',
					),
					array('value'=>'0',
			 		 	   'dropvalues'=> array('50'=>'50 Clientes','100'=>'100 Clientes','150'=>'150 Clientes','200'=>'200 Clientes','250'=>'250 Clientes'),
			 		 	   'type' 	   =>'dropmenu',
			 		 	   'label'=>'Comision Residuales',
			 		 	   'name' =>'residual',
						   'onchange'	=> 'showresidual(this.value);'
					),
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
							
							if($form_val['type']=='0'){
									
								throw new Exception('Debe seleccionar el tipo de pago del Reseller');
							}
							
							if($form_val['hashtag']!=''){
									
								if($Elements->db->count('`hashtag`='.db::quote($form_val['hashtag']).' AND `i_reseller`!='.db::quote($i_reseller))>0){
																		
									throw new Exception('Hasthag ya registrado a otro reseller intente otro');
								}
								
								if($Store->count('`hashtag`='.db::quote($form_val['hashtag']))>0){
								
									throw new Exception('Hasthag ya registrado en una tienda intente otro');
								}
							}
							
							if($form_val['residual']!='0'){

								if($form_val['residual_commission']=='0'){
									
									throw new Exception('Es necesario definir la comission que tendra este reseller');
								}
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
										case 'residual':
										
											$rows[$key] = $form_val[$key];
												
											if($rows[$key]!='0'){
												$tpl->setVariable('display_residual','display:block;');
											}
												
										break;
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
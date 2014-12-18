<?php
		$moduleroll = array('roll'=>'customers','permissions'=>'edit');
	
		$page_title       = 'Dispositivo';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_device		  = $apache_index->get_uri_position(2);
			
		$i_customer		  = _session('i_customer_property');
		
		if($i_customer==''){
			header('location:/customers');
		}
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(new Customer_device_info(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_device!=''){
				
				$rows=$Elements->db->get($i_device);
				
				$tpl->setVariable('title','Editar Dispositivo');
				
			}else{

				$tpl->setVariable('title','Agregar Dispositivo');	

				$rows=false;
			}

			$elements = array(
					array('value'=>$i_customer,'name'=>'i_customer'),
					array(
							'value'=>'0',
							'dropvalues'	=>  array('roku'=>'Roku','android'=>'Android','netgear'=>'Netgear'),
							'type'			=> 'dropmenu',
							'label'			=> 'Tipo',
							'name'			=> 'type'
					),
					array('value'=>'','type'=>'input','label'=>'*Serial','size'=>'3','name'=>'i_serial'),
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
	
							if($form_val['i_serial']==''){
	
								throw new Exception('Debe ingresar Serial del dispositivo');
							}
	
							switch($form_val['type']){
								case 'Roku':
									if(!Check::lenght($form_val['i_serial'],12,12)){
										throw new Exception('El serial de Roku debe contener 12 caracteres');
									}
								break;
								case 'Android':
									if(!Check::mac(string_to_mac($form_val['i_serial']))){
										throw new Exception('El Mac Address ingresado es incorrecto');
									}
								break;
								case 'Netgear':
									if(!Check::mac(string_to_mac($form_val['i_serial']))){
										throw new Exception('El Mac Address ingresado es incorrecto');
									}
								break;
							}
						
							if($i_device!=''){
									
									$Elements->db->update($data_mysql, $i_device);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/deviceregistration');
	
								}else{
								
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/deviceregistration');
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
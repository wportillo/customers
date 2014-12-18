<?php
		$moduleroll = array('roll'=>'products','permissions'=>'edit');
	
		$page_title       = 'Producto';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_product			  = $apache_index->get_uri_position(2);
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
			
			$Customer_product_info = new Customer_product_info();
			
			$Elements = new Elements(new Product_info(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_product!=''){
				
					$rows=$Elements->db->get($i_product);
				
					$tpl->setVariable('title','Editar Producto');

					$Customer_product_info->primary_key='key_product';
				
					$product = $Customer_product_info->get($rows['key_product']);
			
			}else{

					$tpl->setVariable('title','Agregar Producto');	

				$rows=false;
				
				$product=false;
			}

				$elements = array(
						
						array('value'=>'0','name'=>'trash'),
						
						array('value'=>'','type'=>'input','label'=>'*Identificador','size'=>'3','name'=>'key_product'),
							
						array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name_product'),
						
						array('value'=>'','type'=>'textarea','label'=>'*Descripcion PT','width'=>'30','height'=>'30','name'=>'description_product_pt'),
	
						array('value'=>'','type'=>'textarea','label'=>'*Descripcion ES','width'=>'30','height'=>'30','name'=>'description_product_es'),
						
						array('value'=>'','type'=>'input','label'=>'*Precio','size'=>'3','name'=>'amount'),
					
						array('value'=>'','type'=>'input','label'=>'*Subscripcion','size'=>'3','name'=>'subscription'),
						
						array(
								'value'=>'0',
								'dropvalues'	=>  array('0'=> 'No','1'=> 'Si'),
								'type'			=> 'dropmenu',
								'label'			=> '*Envio',
								'name'			=>  'shipping'
						),
						array(
								'value'=>'0',
								'dropvalues'	=>  array('2'=> '2 Días','5'=> '5 Días','8'=> '8 Días','16'=> '16 Días','30'=> '30 Días','60' => '60 Días'),
								'type'			=> 'dropmenu',
								'label'			=> '*Facturacion',
								'name'			=> 'cycle'
						),
						array(
								'value'=>'0',
								'dropvalues'	=>  array('1'=> 'Si','0'=> 'No'),
								'type'			=> 'dropmenu',
								'label'			=> '*Dispositivo',
								'name'			=> 'device'
						),
						(!$product) ? array('value'=>'0','type'=>'radio','label'=>'*Activo','name'=>'active') :null ,
				);
		
			
				/**
				 * Charge from values
				 */
					foreach($elements as $value){
			
						$key = $value['name'];
							
						if(isset($key)){
		
							$form_val[$key] 	= _post($key,$value['value']);
							
							switch($key){
								default :
									$data_mysql[$key] 	= $form_val[$key];
								break;
							}
						}
					}

					if($form_val['sent_form']!=''){
						
						try {
	
							if($form_val['key_product']==''){
								throw new Exception('Debe ingresar un identificador para este producto');
							}
							
							if($form_val['name_product']==''){
								throw new Exception('Debe ingresar un nombre para este producto');
							}
							
							if($form_val['amount']==''){
								throw new Exception('Debe ingresar un precio para este producto');
							}	
							
							if($form_val['subscription']==''){
								throw new Exception('Debe ingresar una subscripcion para este producto');
							}
							
							if($form_val['shipping']==''){
								throw new Exception('Debe ingresar precio de envio para este producto');
							}
							
							if($form_val['cycle']=='0'){
								throw new Exception('Debe ingresar ciclo de facturacion para este producto');
							}
							
							if($form_val['description_product_pt']==''){
								throw new Exception('Debe ingresar descripcion PT para este producto');
							}
							
							if($form_val['description_product_es']==''){
								throw new Exception('Debe ingresar descripcion ES para este producto');
							}
							
							if($i_product!=''){
								
									$Elements->db->update($data_mysql, $i_product);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/products');
								
							}else{
									
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/products');
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
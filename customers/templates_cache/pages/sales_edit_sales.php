<?php
		require_once('main.inc.php');
	
		$page_title       =  'Crear Cliente';
		
		$layout           =  true;
		
		$Form_elements    =  new Form_elements();
		
		$error		 	  =	 false;
		
		$info			  =  $apache_index->get_uri_position(3);
		
		$pagina	  		  =  $apache_index->get_uri_position(1);

		$Create_customer  =  new TVmia_Customer();
		
		$Create_customer  -> debug=false;
		
		$Product 		  =  new Products();
		
		$Product		  -> debug=false;
		
	/**
	 * Get Store User & Reseller Id
	 */	
		$id_reseller	=	_session('id_reseller');
		
		$id_store		=	_session('id_store');
		
		$id_user		=	_session('user_id');
		
		$id_rol			=   _session('rol_id');
		
		$id_product		=	_post('id_product');
		
	/**
	 * Load template
	 */
		$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
		
		$tpl->loadTemplatefile($pagina.'.tpl.html'); 
		
		$Form_elements->object_template=$tpl;
	
		
		$tpl->touchBlock($pagina);
		 
	/**
	 * Sent form load value
 	 */
		
			$form_val['sent_form']					  =		_post('sent_form');
		
			$form_val['product']					  =		_post('product','0');

			$tpl->setVariable('title','Agregar Cliente');	

			$rows=false;
			
			
			$client=array(
					'name'				=> array('','input','*Nombre','3','text'),
					'surname'	  	  	=> array('','input','*Apellido','3','text'),
					'email'   	  	  	=> array('','input','*Email','3','email'),
					'address'   	  	=> array('','input','*Direccion','3','text'),
					'city'	  	  	 	=> array('','input','*Ciudad','3','text'),
					'zip_code'	  	 	=> array('','input','*Codigo Postal','1','text'),
					'state'	 	  		=> array('','dropmenu_bd','*Estado',array('drop_value'=>'codigo_estado','drop_name'=>'nombre_estado'),new State(),false,false,false,false),
					'phone'	 			=> array('','input','*Phone','3','text'),
					'password'	 		=> array('','input','*Password','3','password'),
					'product'	 		=> array('','','')
		);
		$credit=array(
				'security_code'		=> array('','input','*Cod Seguridad','1','text'),
				'credit_zip_code'	=> array('','input','*Cod Postal de Tarjeta','1','text'),
				'credit_name'		=> array('','input','*Nombre en tarjeta','3','text'),
				'month'				=> array('','dropmenu','*Fecha de vencimiento Mes',array('01'=>'Enero',
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
						'12'=>'Diciembre')
						,false,false),
				'year'				=> array('','dropmenu','*Fecha de vencimiento Año',array('2012'=>'2012',
						'2013'=>'2013',
						'2014'=>'2014',
						'2015'=>'2015',
						'2016'=>'2016',
						'2017'=>'2017',
						'2018'=>'2018',
						'2019'=>'2019',
						'2020'=>'2020')
						,false,false),
				'credit_address'	=> array('','input','*Direccion en Tarjeta','3','text'),
				'credit_number'		=> array('','input','*Numero en Tarjeta (Visa,Master,American Express,Discover)','3','text')
		);
			
				$post_elements = array_merge_recursive($client,$credit);
			
				$product = $Product->get($form_val['product']);
				
				if($product['monthly']==0 && $product['price']==0 && $form_val['product']!='0'){
					$tpl->setVariable('credit_hidden','display:none;');
				}
			
			
		/**
		 * Charge from values
		 */
			foreach($post_elements as $value=>$default_value){

				if($default_value[1]!='label'){	
					
					$form_val[$value] 	= _post($value,$default_value[0]);
					
					switch($value){
						default:
							$data_mysql[$value]	= $form_val[$value];
						break;
					}
				}
			}
			
			/**
			 *
			 * If sent form
			 * 
			 */		
			if($form_val['sent_form']!=''){
				
				  $customer_info=array(
				 		'customer'=>array(
				 				'name'				 =>  $form_val['name'],
				 				'surname'	  	  	 =>  $form_val['surname'],
				 				'email'   	  	  	 =>  $form_val['email'],
				 				'address'   	  	 =>  $form_val['address'],
				 				'city'	  	  	 	 =>  $form_val['city'],
				 				'zip_code'	  	 	 =>  $form_val['zip_code'],
				 				'state'	 	  		 =>  $form_val['state'],
				 				'phone'	 			 =>  $form_val['phone'],
				 				'password'	 		 =>  $form_val['password'],
				 				'product'	 		 =>  $form_val['product'],
				 				'id_reseller'		 =>  $id_reseller,
				 				'id_store'		 	 =>  $id_store,
				 				'id_user'		 	 =>  $id_user, 
				 		)
				 );
				$credit_info=array(
			 			'credit'=> array(
			 					'security_code'		=> $form_val['security_code'],
			 					'credit_zip_code'	=> $form_val['credit_zip_code'],
			 					'credit_name'		=> $form_val['credit_name'],
			 					'month'				=> $form_val['month'],
			 					'year'				=> $form_val['year'],
			 					'credit_address'	=> $form_val['credit_address'],
			 					'credit_number'		=> $form_val['credit_number']
			 			),
			 	);
				
				$customer_data = array_merge_recursive($customer_info,$credit_info);
				
			 	
				$customer  = $Create_customer->Add($customer_data);
				
				if(!isset($customer['i_customer'])){	
				 	$error[] = $customer['error'];
				}

				if($error!=false){
					/**
					 * Show Notification event
					 */
						$Form_elements->notification($error[0],2);
					
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
				}else{
					
			
						$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
						
						$_SESSION['label_notification']['type'] = 1;
						
						header('location:/customers');
				}	
			}	 		
			
			/**
			 * Rol Id
			 */
				$Form_elements->Dropmenu_bd_product('*Producto','product',$rows['product'],array('drop_value'=>'i_product','drop_name'=>'name_product'),new Products(),'trash!=1 and active=1',false,false,'get_product();',$id_rol);
			
			if($id_product==''){
				
				$Form_elements->Generate_Form($post_elements, $rows);
				
				$module_content = $tpl->get();
			
				/*
				 * Display File
				 */
				require_once('display.inc.php');
			}else{
				
				$product = $Product->get($id_product);
				
				if($product['monthly']==0 && $product['price']==0){
						print 'free';
				}
			}
?>
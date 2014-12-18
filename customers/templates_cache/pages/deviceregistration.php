<?php
	 		$moduleroll = array('roll'=>'customers','permissions'=>'show');
	 		
			$page	  		    = $apache_index->get_uri_position(1);
				
			$sent_form			= _request('sent_form','0');
			
			$i_customer_property =_session('i_customer_property');
			
			
			if($i_customer_property==''){
				header('location:/customers');
			}
			
		/*
		 * Page configurations
		 */
			$page_title       = 'Registro de dispositivos';
			
			$layout           = true;
			
			$max			  = 10;
			
			$order			  = '';
			
			$where 			  = 'i_customer='.$i_customer_property;
			
		/*
		 * Levantar template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			$tpl->loadTemplatefile($page.'.tpl.html'); 
			$tpl->touchBlock($page);
	
		/*
		 * New Search Button
		 */
			$tpl->setVariable('page_title',$page_title);
		
			
			$customer_device_info   					= new Customer_device_info();
		
			$customer_device_info->debug				= false;
		
			$Elements 	   				= new Elements($customer_device_info,$tpl);
			
			
			$Elements->Main_bar(array(
					'customer/'.$i_customer_property	=>'Editar informacion',
					'credit/'			    			=>'Datos de credito',
					'paymentdetail/'					=>'Detalle de Transacciones',
					'deviceregistration/'				=>'Registro de Dispositivos',
			));
			
			$Elements->rolname			= $moduleroll['roll'];
			
			$Elements->page				= $page;	

		

				$label_notification	= _session('label_notification','');	
				
				if($label_notification!=''){
					
					$Elements->notification($label_notification['label'],$label_notification['type']);
					
					unset($_SESSION['label_notification']);
				}
			
			   $filter=array(
			 		
			   		 array('value'=>'0','name'=>'go_to'),
			 			
			 		 array('value'=>'0','name'=>'action'),
			 			 
			 		 array('value'=>'','name'=>'order'),
			 		
			 		 array('value'=>'','name'=>'table'),
			 				 			 
			 		 array('value'=>'0','name'=>'sent_form'),
			 			
			 		 array('value'=>'0','name'=>'pagenumber'),
			 	
			   		array(
			 		 	  'value'=>'0',
			 		 	  'dropvalues'	=> array('50'=>'50','100'=>'100','150'=>'150','200'=>'200','250'=>'250'),
			 		 	  'type'		=>'dropmenu',
			 		 	  'label'		=>'Cantidad por pag',
			 		 	  'onchange'	=>'javascript:document.grilla.submit();',
			 		 	  'name'		=>'quantity'
			 		  ),
			   	);
			 
				foreach($filter as $value){
				
					$key = $value['name'];
					
					$form_val[$key] 	= _request($key,$value['value']);
	
					if($sent_form!='0'){

							if(isset($_REQUEST[$key])){
								
								$_SESSION[$key.'_paymentdetail']  	   = $form_val[$key];
							}
					}
					
					$session[$key]				   =	_session($key.'_paymentdetail',$value['value']);
				}
			
				if($session['quantity']!='0'){

					$max	=	$session['quantity'];	
				
				}
			
				
				
				switch($form_val['action']){
					case 'reset':
						foreach ($filter as $value){

							unset($_SESSION[$value['name'].'_paymentdetail']);

							header('location:/paymentdetail');
						}
					break;
				}

				$tpl->touchBlock('adddevice');
				
				$Elements->where = $where;
				
				if($session['table']!='' && $session['order']!=''){
					$order.=  $session['table'].' '.$session['order'];
				}
				
				$pag   = $Elements->generate_pagination($session['pagenumber'],$max,$form_val['go_to']);

				$grid = $Elements->db->get_list($max,$pag,$where,$order);
			
				while($row=db::fetch_assoc($grid)){
					
					$tpl->setCurrentBlock('list');
				
						$tpl->setVariable('i_serial',$row['i_serial']);
						
						$tpl->setVariable('type',$row['type']);
						
						$tpl->setVariable('last_update',mysql_date_to_spanish($row['last_update']));
						
						$Elements->icondelete('delete',$row['i_serial'],'1');
							
						$Elements->iconedit('edit',$row['i_serial'],'device');
					
					$tpl->parse('list');
				}
				
				$Elements->LoadElements($filter, $session);
				
				$order_elements = array(
						'i_serial'      	=>	'Serial',
						'type'  			=>	'Tipo',
						'last_update'		=>  'Fecha',
				);
					
				$Elements->Orderby($page,$order_elements, $session['order'],$session['table']);
		
				
				$module_content = $tpl->get();
			 /*
			  * Display File
			  */
			  require_once('display.inc.php');
<?php
	 		$moduleroll = array('roll'=>'customers','permissions'=>'show');
	 		
			$page	  		    = $apache_index->get_uri_position(1);
				
			$sent_form			= _request('sent_form','0');
		/*
		 * Page configurations
		 */
			$page_title       = 'Clientes';
			
			$layout           = true;
			
			$max			  = 10;
			
			$order			  = '';
			
			$where 			  = '1=1';
			
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
		
			
			$Customers   				= new Customer_info();
			
			$Customers->debug			= false;
		
			$Elements 	   				= new Elements($Customers,$tpl);
			
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
			 				
			 		 array('value'=>'0','name'=>'id'),
			 			 
			 		 array('value'=>'0','name'=>'sent_form'),
			 			
			 		 array('value'=>'0','name'=>'pagenumber'),
			 			
			 		array('value'=>'','type'=>'input','label'=>'Email','size'=>'3','name'=>'email'),

			   		array('value'=>'','type'=>'input','label'=>'ID','size'=>'3','name'=>'i_customer'),
			   		
			   		array('value'=>'','type'=>'calendar','label'=>'Desde','name'=>'begin'),
			   		
			   		array('value'=>'','type'=>'calendar','label'=>'Hasta','name'=>'end'),
			   		
			 		 array('value'=>'0',
			 		 	   'dropvalues'=> array('1'=>'Eliminados'),
			 		 	   'type' =>'dropmenu',
			 		 	   'label'=>'Papelera',
			 		 	   'name' =>'trash'),
			   		array('value'=>'0',
			   				'dropvalues'=> array('A'=>'Activo','I'=>'Inactivo'),
			   				'type' =>'dropmenu',
			   				'label'=>'Estatus',
			   				'name' =>'active'),
			   		array(
			 		 	  'value'=>'0',
			 		 	  'dropvalues'	=> array('50'=>'50','100'=>'100','150'=>'150','200'=>'200','250'=>'250'),
			 		 	  'type'		=>'dropmenu',
			 		 	  'label'		=>'Cantidad por pag',
			 		 	  'onchange'	=>'javascript:document.grilla.submit();',
			 		 	  'name'		=>'quantity'
			 		  ),
			   		array('value'=>'0',
			   				'type' 	    =>'dropmenudb',
			   				'label'	    =>'Producto',
			   				'table'     => new Product_info(),
			   				'where'	    =>'trash!=1',
			   				'drop_value'=>'key_product',
			   				'drop_label'=>'key_product',
			   				'name'	    =>'key_product'
			   		),
                                        
                                 ($session_vars['i_reseller']==0)?
			   					 array('value'=>'0',
					   				'type' 	    =>'dropmenudb',
					   				'label'	    =>'Reseller',
					   				'table'     => new Crm_reseller(),
					   				'where'	    =>'trash!=1',
					   				'drop_value'=>'i_reseller',
					   				'drop_label'=>'name',
					   				'name'	    =>'i_reseller'
                                  ): null
			   	);
			 
				foreach($filter as $value){
				
					$key = $value['name'];
					
					$form_val[$key] 	= _request($key,$value['value']);
	
					if($sent_form!='0'){

							if(isset($_REQUEST[$key])){
								
								$_SESSION[$key.'_customers']  	   = $form_val[$key];
							}
					}
					
					$session[$key]				   =	_session($key.'_customers',$value['value']);
				}
			
				if($session['quantity']!='0'){

					$max	=	$session['quantity'];	
				
				}
			
				if($session['trash']!='0'){
					$where.=' AND `customer_info`.`trash`='.db::quote($session['trash']);
				}
				
				if($session['i_customer']!=''){
					$where.=' AND `customer_info`.`i_customer`='.db::quote($session['i_customer']);
				}
				
				if($session['key_product']!='0'){
					$where.=' AND `customer_product_info`.`key_product`='.db::quote($session['key_product']);
				}
				
				if($session['email']!=''){
					$where.=' AND `customer_info`.`email`  LIKE '.db::quote('%'.$session['email'].'%');
				}
				
				if($session_vars['i_reseller']!='0'){
					$where.=' AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']);
				}
				
				if($session_vars['i_store']!='0'){
					$where.=' AND `customer_info`.`i_store`='.db::quote($session_vars['i_store']);
				}

				if(isset($session['i_reseller'])){
	                if($session['i_reseller']!='0'){
						$where.=' AND `customer_info`.`i_reseller`='.db::quote($session['i_reseller']);
					}
				}
				
				if($session['begin']!='' && $session['end']!=''){
					if($session['begin']!=$session['end']){
						$where.=' AND `customer_date_info`.`creation_date` BETWEEN '.db::quote(spanish_date_to_mysql($session['begin']).' 00:00:00').' AND '.db::quote(spanish_date_to_mysql($session['end']).' 11:59:59 ');
					}else{
						$where.=' AND `customer_date_info`.`creation_date` LIKE '.db::quote(spanish_date_to_mysql($session['begin']).'%');
					}
				}
				
				if($session['active']!='0'){
					if($session['active']=='A'){
						$where.=' AND `customer_info`.`active`=1';
					}else{
						$where.=' AND `customer_info`.`active`=0';
					}
				}
				
				$_SESSION['where_customer'] = $where;
				
				switch($form_val['action']){
					case 'active':
						$Elements->active($form_val['id']);
					break;
					case 'desactive':
						$Elements->desactive($form_val['id']);
					break;
					case 'delete':
						$Elements->delete($form_val['id']);
					break;
					case 'restore':
						$Elements->restore($form_val['id']);
					break;
					case 'reset':
						foreach ($filter as $value){

							unset($_SESSION[$value['name'].'_customers']);

							header('location:/customers');
						}
					break;
				}
				
				
				if($Elements->security->getperm($moduleroll['roll'],'delete')){
					$tpl->touchBlock('export_customers');
				}
				
				$Elements->where = $where;
				
				if($session['table']!='' && $session['order']!=''){
					$order.=  $session['table'].' '.$session['order'];
				}
				
				$pag   = $Elements->generate_pagination($session['pagenumber'],$max,$form_val['go_to']);

				$grid = $Elements->db->get_list($max,$pag,$where,$order);
			
				while($row=db::fetch_assoc($grid)){
					
					$tpl->setCurrentBlock('list');
				
						$tpl->setVariable('i_customer',$row['i_customer']);
						
						$tpl->setVariable('name',ucfirst($row['name']).' '.ucfirst($row['surname']));
						
						$tpl->setVariable('email',$row['email']);
						
						$tpl->setVariable('creation_date',mysql_date_to_spanish(($row['creation_date'])));
						
						if(!date_null($row['last_payment'])){
							
							$tpl->setVariable('last_payment',mysql_date_to_spanish(($row['last_payment'])));
						
							$tpl->touchBlock('makepayment');
								
						}else{
							
							$tpl->touchBlock('upgrade');
							
							$tpl->setVariable('last_payment','-----');
						
						}
						
						$tpl->setVariable('key_product',ucfirst($row['key_product']));
						
						$Elements->icondelete('delete',$row['i_customer'],$row['trash']);
							
						$Elements->iconedit('edit',$row['i_customer'],'customer');
							
						if($session_vars['i_reseller']==0){

							$Elements->iconactive('active_action',$row['i_customer'],$row['active']);
						
						}else{
							
							$Elements->iconactiveshow('active_action',$row['active']);
						}
						
						$Elements->iconrestore('restore',$row['i_customer'],$row['trash']);
							
						$Elements->log_icon('log', $row['i_customer'], 'accesslog');
							
					$tpl->parse('list');
				}
			/*
			 * Generate dialog box combobox  
			 */
				if($session_vars['i_reseller']=='0'){
					$Elements->ajax_combobox('combo');
				}
			
				$Elements->LoadElements($filter, $session);
		
				$order_elements = array(
						'i_customer'      	=>	'ID',
						'name'      		=>	'Nombre',
						'email'				=>  'Email',
						'creation_date'		=>  'Creado',
						'key_product'		=>  'Producto',
						'last_payment'		=>  'Pago',
						'active'			=>  'Activo',
				);
					
				$Elements->Orderby($page,$order_elements, $session['order'],$session['table']);
		
				
				$module_content = $tpl->get();
			 /*
			  * Display File
			  */
			  require_once('display.inc.php');
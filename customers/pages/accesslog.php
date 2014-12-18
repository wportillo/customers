<?php
	 		$page	  		    = $apache_index->get_uri_position(1);
	 		
	 		$i_user				= $apache_index->get_uri_position(2);
				
			$sent_form			= _request('sent_form','0');
			
			/*
			 * Page configurations
			 */
				$page_title       = 'Historial de Accesos';
				
				$layout           = true;
				
				$max			  = 10;
				
				$order			  = '';

				
				
				
				
				if($i_user!='' && is_int($i_user)){
					
					$where=' i_user='.$i_user;
					
				}else{

					$where=' i_user='.$session_vars['i_user'];
				}
				
				
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
			
				
				$Crm_access_log   			= new Crm_access_log();
				
				$Crm_access_log->debug		= false;
			
				$Elements 	   				= new Elements($Crm_access_log,$tpl);
				
				$Elements->page				= $page;	

	

				$label_notification	= _session('label_notification','');	
				
				if($label_notification!=''){
					
					$Elements->notification($label_notification['label'],$label_notification['type']);
					
					unset($_SESSION['label_notification']);
				}
			
			
			   $filter=array(
			 		
			   		 array('value'=>'0','name'=>'go_to'),
			 			
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
								
								$_SESSION[$key.'_accesslog']  	   = $form_val[$key];
							}
					}
					
					$session[$key]				   =	_session($key.'_accesslog',$value['value']);
				}
			
				if($session['quantity']!='0'){

					$max	=	$session['quantity'];	
				
				}
				
				$Elements->where = $where;
				
				if($session['table']!='' && $session['order']!=''){
					$order.=  $session['table'].' '.$session['order'];
				}
				
				$pag   = $Elements->generate_pagination($session['pagenumber'],$max,$form_val['go_to']);

				$grid = $Elements->db->get_list($max,$pag,$where,$order);
			
				while($row=db::fetch_assoc($grid)){
					
				
					$tpl->setCurrentBlock('list');
				
						$tpl->setVariable('public_ip',$row['public_ip']);
					
						$tpl->setVariable('useragent',$row['useragent']);
						
						$tpl->setVariable('date',mysql_date_to_spanish($row['last_access']));
							
					$tpl->parse('list');
				}
				
			/*
			 * Generate dialog box combobox  
			 */
				$Elements->ajax_combobox('combo');
			
				$Elements->LoadElements($filter, $session);
		
				$order_elements = array(
						'public_ip'			=>  'IP publica',
						'useragent'			=>  'Agente',
						'last_access'		=>  'fecha',
				);
					
				$Elements->Orderby($page,$order_elements, $session['order'],$session['table']);
		
				$module_content = $tpl->get();
			 /*
			  * Display File
			  */
			  require_once('display.inc.php');
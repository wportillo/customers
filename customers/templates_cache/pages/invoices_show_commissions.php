<?php
	require_once('main.inc.php');
	$page	  		    = $apache_index->get_uri_position(1);
	$page_number	    = $apache_index->get_uri_position(2);
	$instruction 	    = $apache_index->get_uri_position(3);
	$instruction_value  = $apache_index->get_uri_position(4);
	
	$page_title       = 'Invoices';
	$layout           = true;
	$max			  = 50;
	$order			  = '';
	$where 			  = 'i_invoice!=0 AND trash = 0 ';
	
	$session_reseller = _session('id_reseller');
	
	$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
	$tpl->loadTemplatefile($page.'.tpl.html');
	$tpl->touchBlock($page);
	$tpl->setVariable('page_title',$page_title);
	
	$invoices = new Invoices();
	//$invoices->debug = true;
	
	$gridElements 	   				= new Grid_elements();
	$gridElements->db_object		= $invoices;
	$gridElements->page				= $page;
	$gridElements->rol_name			= $page_security_attr['rol_name'];
	$gridElements->security			= $Security;
	$gridElements->object_template	= $tpl;
	
	$label_notification	= _session('label_notification','');
	if($label_notification!=''){
		$gridElements->notification($label_notification['label'],$label_notification['type']);
		unset($_SESSION['label_notification']);
	}
	
	$pr=new Products();
	
	$postElements=array(
		'i_reseller'	=>		array('0','dropmenu_bd','Agente reseller',array('drop_value'=>'id_reseller','drop_name'=>'name_reseller'),new Business_Reseller(),'id_reseller!=0',false,false,false),
		'generation_date_desde'			=>		array('','calendar','Desde:'),
		'generation_date_hasta'			=>		array('','calendar','Hasta:'),
		'payment_date_desde'			=>		array('','calendar','Desde:'),
		'payment_date_hasta'			=>		array('','calendar','Hasta:'),
		'ir_a'			=>		array('','',''),
		'payment'		=>		array('0','dropmenu','Balance',array('sp'=>'Sin procesar','pending'=>'Pendiente','ok'=>'Pagado'),false,false),
			
		'action'		=>		array('','',''),
		'cantidad'		=>		array('0','dropmenu','Cantidad por pag',array('50'=>'50','100'=>'100','150'=>'150','200'=>'200','250'=>'250'),false,'javascript:document.grilla.submit();',false)
	);
	
	$form_val['send_form'] 			=  _post('send_form');
	if($instruction=='reset'){
		foreach ($postElements as $value=>$atrrib){
			unset($_SESSION[$value.'_invoices']);
		}
	}
	foreach($postElements as $value=>$default_value){
		$form_val[$value] = _post($value,$default_value[0]);
		if($form_val['send_form']!=''){
			$_SESSION[$value.'_invoices']   = $form_val[$value];
		}
		$session[$value]				   =	_session($value.'_invoices',$default_value[0]);
	}
	if($gridElements->check_rol_security_delete()){
		if($instruction=='delete'){
			$invoices->Delete($instruction_value);
			header('location:/invoices');
		}
	}
	if($form_val['send_form']!='' && $form_val['action']!='0'){
		switch($form_val['action']){
			case'delete':
				if($gridElements->check_rol_security_delete()){
					$gridElements->Delete($form_val['selected']);
				}
			break;
			case'generate':
				if($gridElements->check_rol_security_delete()){
					$result = $gridElements->generateInvoice($form_val['selected']);
					if($result!='OK'){
						$_SESSION['label_notification']['label'] = $result;
						$_SESSION['label_notification']['type'] = 2;
					}
				}
			break;
		}
	}
	
	foreach($postElements as $value=>$default_value){
		$form_val[$value] = _post($value,$default_value[0]);
		if($form_val['send_form']!=''){
			$_SESSION[$value.'_invoices']   = $form_val[$value];
		}
		$session[$value]				   =	_session($value.'_invoices',$default_value[0]);
	}
	
	$orderByElements=array(
		'generation_date'	=>	'Fecha de generación',
		'payment_date'		=>	'Fecha de pago',
		'ammount'			=>	'Cantidad',
		'i_invoice'			=>	'Invoice',
		'payment'			=>	'Estado',
		'i_reseller'		=>	'Reseller',
	);
	
	if($instruction!='' && $instruction_value!=''){
		$order= $instruction.' '.$instruction_value;
	}
	
	if($session['cantidad']!='0'){
		$max=$session['cantidad'];
	}
	
	if($session['generation_date_desde']!='' && $session['generation_date_hasta']!=''){
		if($session['generation_date_desde']!=$session['generation_date_hasta']){
			$where.=' AND `generation_date` BETWEEN '.db::quote(spanish_date_to_mysql($session['generation_date_desde']).' 00:00:00').' AND '.db::quote(spanish_date_to_mysql($session['generation_date_hasta']).' 11:59:59 ');
		}else{
			$where.=' AND `generation_date` LIKE '.db::quote(spanish_date_to_mysql($session['generation_date_desde']).'%');
		}
	}
	
	if($session['payment_date_desde']!='' && $session['payment_date_hasta']!=''){
		if($session['payment_date_desde']!=$session['payment_date_hasta']){
			$where.=' AND `payment_date` BETWEEN '.db::quote(spanish_date_to_mysql($session['payment_date_desde']).' 00:00:00').' AND '.db::quote(spanish_date_to_mysql($session['payment_date_hasta']).' 11:59:59 ');
		}else{
			$where.=' AND `payment_date` LIKE '.db::quote(spanish_date_to_mysql($session['payment_date_desde']).'%');
		}
	}
	
	if($session['payment']!='0'){
		$pay = 0;
		switch($session['payment']){
			case 'sp':
				$pay = 0;
			break;
			case 'pending':
				$pay = 1;
			break;
			case 'ok':
				$pay = 2;
			break;
		}
		$where.="AND `payment`=".$pay;
	}
	if($session['i_reseller']!='0'){
		$where.="AND `i_reseller`=".$session['i_reseller'];
	}
	
	$gridElements->where=$where;
	$pag = $gridElements->generate_pagination($page_number,$max,$form_val['ir_a'],$instruction,$instruction_value);
	$grilla=$invoices->get_list($max,$pag,$where,$order);
	while($row=db::fetch_assoc($grilla)){
		$tpl->setCurrentBlock('list');
			$tpl->setVariable('id',$row['i_invoice']);
			
			$tempreseller = new Business_Reseller();
			$rowreseller = $tempreseller->get($row['i_reseller']);
			$tpl->setVariable('name_reseller', $rowreseller['name_reseller']);
			
			$tpl->setVariable('ammount', $row['ammount']);
			$tpl->setVariable('generation_date', $row['generation_date']);
			$tpl->setVariable('payment_date', ($row['payment_date']!='0000-00-00 00:00:00')?$row['payment_date']:'Por definir');
			switch($row['payment']){
				case 2:
					$tpl->setVariable('payment', 'Pagado');
				break;
				case 1:
					$tpl->setVariable('payment', 'Pendiente');
				break;
				case 0;
					$tpl->setVariable('payment', 'Sin procesar');
				break;
			}
			$tpl->setVariable('i_invoice', '<a href="/invoice/'.$row['i_invoice'].'/pdf">'.$row['i_invoice'].'</a>');
		$tpl->parse('list');
	}
	$tpl->setVariable('new_search','/'.$page.'/pag-1/reset');
	$gridElements->Generate_Form($postElements, $session);
	$gridElements->Generate_OrderBy($page, $page_number,$orderByElements,$instruction,$instruction_value);
	
	$module_content = $tpl->get();
	require_once('display.inc.php');
?>
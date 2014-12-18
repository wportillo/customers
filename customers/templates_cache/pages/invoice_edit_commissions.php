<?php
	require_once('main.inc.php');
	$page_title       = 'Ver invoice';
	$layout           = false;
	$invoice 		  = new Invoices();
	//$invoice-> debug=true;

	$quan = 1;
	$registros=35;
	
	$display	 	  = $apache_index->get_uri_position(3);
	$i_invoice	 	  = $apache_index->get_uri_position(2);
	$pagina	  		  = $apache_index->get_uri_position(1);

	$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
	$tpl->loadTemplatefile($pagina.'.tpl.html');
	$tpl->touchBlock($pagina);
	$tpl->setVariable('page_title',$page_title);
	
	$invoice = new Invoices();
	$rowinvoice = $invoice->get($i_invoice);
	
	$tpl->setVariable('i_invoice',$rowinvoice['i_invoice']);
	$tpl->setVariable('generation_date',$rowinvoice['generation_date']);
	$tpl->setVariable('totalammount',$rowinvoice['ammount']);
	$res = new Business_Reseller();
	$rowreseller = $res->get($rowinvoice['i_reseller']);
	$tpl->setVariable('nombre_reseller', $rowreseller['name_reseller']);
	
	$cantidad=0;
	$paginas=0;
	$commission = new Commissions();
	//$commission->debug=true;
	$client=new Customer();
	
	
	//while($paginas<($commission->count('1')/$registros)){
	while($paginas<($commission->count('i_invoice='.$i_invoice)/$registros)){
		$tpl->setCurrentBlock('table_page');
		$tpl->touchBlock('tableheader');
		$paginas++;
		$comm = $commission->get_list($registros,$paginas,'i_invoice='.$i_invoice, 'generation_date asc');
		//$comm = $commission->get_list('','','i_invoice='.$i_invoice, '');
		
		while($row=db::fetch_assoc($comm)){
			$tpl->setCurrentBlock('list');
			$tpl->setVariable('quantity',$quan);
			$rowcustomer=$client->get($row['i_client']);
			$tpl->setVariable('id_telefonica',$rowcustomer['id_telefonica']);
			$tpl->setVariable('id',$row['i_client']);
			$tpl->setVariable('product',$row['description'].' - ('.$row['product'].')');
			$tpl->setVariable('date', date('Y-m-d', strtotime($row['generation_date'])));
			$tpl->setVariable('ammount',$row['ammount']);
			$tpl->setVariable('subtotal',$row['ammount']*$quan);
			$tpl->parse('list');
			$cantidad++;
		}
		$tpl->setVariable('pagina', $paginas);
		
		if($paginas==floor($commission->count('1')/$registros)){
			$tpl->setCurrentBlock('total');
			$tpl->setVariable('totalammount2',$rowinvoice['ammount']);
			$tpl->parse('total');
		}
		$tpl->parse('table_page');
	}
		
	if($display=='pdf'){
		
		$dompdf = new DOMPDF();
		
		$dompdf->load_html($tpl->get());
			
		ini_set('memory_limit','800M');
			
		$dompdf->render();
	
		$dompdf->stream(basename('invoice_'.$i_invoice.'_'.date('m-d-y')), array('Attachment'=>'0'));
	}else{
		$module_content = $tpl->get();
	}
	require_once('display.inc.php');
?>
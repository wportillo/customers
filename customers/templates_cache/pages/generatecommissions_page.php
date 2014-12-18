<?php
	require_once('main.inc.php');
	$customers			=	new Customer();
	$where_customers	=	"id_reseller!=0 AND (producto='TVMIA00200' OR producto='TVMIA00500' OR producto='TVMIA00041')";
	$commissions 		= 	new Commissions();
	$where_commissions	=	"i_commission!=0";
	$rows_customer		=	$customers->get_list('', '', $where_customers, '');
	while($rows = db::fetch_assoc($rows_customer)){
		$row_commissions	=	$commissions->get_list('','','i_client = '.$rows['id_cliente'],'');
		if(db::num_rows($row_commissions)==0){
			$data['i_client']		=	$rows['id_cliente'];
			$data['generation_date']		=	$rows['fecha_creacion'];
			$prod	=	new Products();
			$prod_row	=	$prod->get($rows['producto']);
			$data['ammount']		=	$prod_row['commissions'];
			$data['i_reseller']		=	$rows['id_reseller'];
			$data['description']	=	$prod_row['name_product'];
			$data['product']	=	$prod_row['i_product'];
			$commissions->add($data);
		}
	}
	
?>
<?php
/**
 * Db classes
 * @author william
 * @package core
 */
class Product_info extends rs{
	function __construct(){
		$this->table ='product_info';
		$this->primary_key='i_product';
	}
	
}

class Mailling extends rs{
	function __construct(){
		$this->table ='crm_emails';
		$this->primary_key='i_email';
	}

}

class Country extends rs{
	function __construct(){
		$this->table ='country';
		$this->primary_key='i_country';
	}

}

class Customer_info extends rs{
	function __construct(){
		$this->table ='customer_info';
		$this->primary_key='i_customer';
		$this->pivot_tables=array(
				array(
							
						'table'  => 'customer_date_info',
							
						'link_a' => 'i_customer',
							
						'link_b' => 'i_customer'),
				array(
							
						'table'  => 'customer_product_info',
							
						'link_a' => 'i_customer',
							
						'link_b' => 'i_customer'),
		);
	}
}

class Customer_favorites extends rs{
	function __construct(){
		$this->table ='customer_favorites';
		$this->primary_key='i_fav';
	}
}

class Customer_date_info extends rs{
	function __construct(){
		$this->table ='customer_date_info';
		$this->primary_key='i_date';
	}
}

class Customer_device_info extends rs{
	function __construct(){
		$this->table ='customer_device_info';
		$this->primary_key='i_serial';
	}
}

class Customer_credit_info extends rs{
	function __construct(){
		$this->table ='customer_credit_info';
		$this->primary_key='i_credit';
	}
}

class Crm_reseller_credit_info extends rs{
	function __construct(){
		$this->table ='crm_reseller_credit_info';
		$this->primary_key='i_credit';
	}
}

class Customer_product_info extends rs{
	function __construct(){
		$this->table ='customer_product_info';
		$this->primary_key='i_product';
	}
}
class Customer_payment_history extends rs{
	function __construct(){
		$this->table ='customer_payment_history';
		$this->primary_key='i_payment';
	}
}
class Crm_reseller_payment_history extends rs{
	function __construct(){
		$this->table ='crm_reseller_payment_history';
		$this->primary_key='i_payment';
	}
}


class Crm_access_log extends rs{
	function __construct(){
		$this->table ='crm_access_log';
		$this->primary_key='i_access_log';
	}
}

class Roll extends rs{
	function __construct(){
		$this->table ='crm_roll';
		$this->primary_key='i_roll';
	}

}

class Users extends rs{
	function __construct(){
		$this->table ='crm_user';
		$this->primary_key='i_user';
		$this->pivot_tables=array(
				  array('table'=>'crm_roll',
						'link_a'=>'i_roll',
						'link_b'=>'i_roll'));
	}

}

class Sessions extends rs{
	function __construct(){
		$this->table ='sessions';
		$this->primary_key='i_session';
	}

}

class Promo_Codes extends rs{
	function __construct(){
		$this->table ='promo_codes';
		$this->primary_key='id_code';
	}

}

class Category_Pictures extends rs{
	function __construct(){
		$this->table ='fotos_categorias';
		$this->primary_key='id_foto';
	}

}

class Category extends rs{
	function __construct(){
		$this->table ='category';
		$this->primary_key='i_category';
	}

}

class Contents extends rs{
	function __construct(){
		$this->table ='channels_info';
		$this->primary_key='i_channel';
	}

}

class Crm_reseller extends rs{
	function __construct(){
		$this->table='crm_reseller';
		$this->primary_key ='i_reseller';
	}
}

class Crm_store extends rs{
	function __construct(){
		$this->table ='crm_store';
		$this->primary_key='i_store';
		$this->pivot_tables=array(
				array('table'=>'crm_reseller',
				'link_a'=>'i_reseller',
				'link_b'=>'i_reseller')
		);
	}
}

class Tickets extends rs{
	function __construct(){
		$this->table ='tickets';
		$this->primary_key='i_ticket';
		$this->pivot_tables=array(array('table'=>'clientes',
										'link_a'=>'customer_id',
										'link_b'=>'id_cliente')
		);
	}

}

class Commissions extends rs{
	function __construct(){
		$this->table ='subscription_commission';
		$this->primary_key='i_commission';
	}
}

class Invoices extends rs{
	function __construct(){
		$this->table ='invoices';
		$this->primary_key='i_invoice';
	}
}
?>
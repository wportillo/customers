<?php
		/**
		 * Main File
		 */
			require_once('main.inc.php');
		
			$layout            = false;
		
			$notification      = $apache_index->get_uri_position(2);

			$error 		  	   = false;
					
		/**
		 * POST Var
		 */
			$sent_form 		= _post('sent_form');
				
			$username   	= _post('usuario');
				
			$password  		= _post('password');
			
			$persistent  	= _post('persistent','0');

		/**
		 * if logged Redirect to home
		 */		
		 	 if($Security->logged()){
				header('location:/home');
			 }
			
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			$tpl->loadTemplatefile('login.tpl.html'); 
			$tpl->touchBlock('login');
		
			
			$tpl->setVariable('base',BASE);
				
			/*
			 * Send form
			 */
			if($sent_form!=''){
			
				if(!$Security->login($username, $password)){
					$error[]='Usuario o password Incorrecto';
				}
				
				if(!$Security->active()){
					$error[]='Usuario inactivo';
				}
				
				if($Security->expirate()){
					$error[]='Usuario Expirado';
				}
				
				/**
				 * Login
				 */
				if(!$error){
					
					/**
					 * Set Limited cookie
					 */
						if($persistent=='0'){
							$Sessions_Val->life_time=14400;
						}
						
						$Sessions_Val->set_life_time();
						
						header('location:/home');
					
				}else{
					
						$tpl->setCurrentBlock('error');
							$tpl->setVariable('error',$error[0]);
						$tpl->parse('error');
				}
			}
				
			$module_content = $tpl->get();    
		   
		   /**
			* Display File
			*/
			require_once('display.inc.php');
?>
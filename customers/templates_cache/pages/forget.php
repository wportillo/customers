<?php
		/**
		 * Forget password
		 */
			require_once('main.inc.php');

			$Users		 = new Users();
		
			$error       = false;
		
			$email		= _request('user');
			
			try {

				if(!Check::email($email)){

					throw  new Exception('Ingrese correctamente su correo electronico');
				}
				
				if($Users->count('email='.db::quote($email))==0){
					throw  new Exception('Este correo electronico no pertenece a una cuenta de tvmia');
				}	
			
				print json_encode(array('error'=>false,'message'=>'La Contrasena se a mandado a su correo electronico'));
					
			}catch (Exception $e){
				
				print json_encode(array('error'=>true,'message'=>$e->getMessage()));
			}
		

			/*
			if($error==false){
			
				 	 $user_assoc = $Users->get_list(1,1,'email='.db::quote($email));			
					 
				 	 $users=db::fetch_assoc($user_assoc);
				 	 
				 	 $email_params=array(
							'template'=>array(
					
									'config'    => array('template'=>'forget_email.tpl.html'),
					
									'print_vars'=>array(
					
											array('label'=>'fullname','value'=>  $users['fullname']),
											
											array('label'=>'user','value'=>  $users['user']),
											
											array('label'=>'password','value'=>  decrypt($users['password'], ENCRYPT_KEY)),
							 		 ),
					
							),
					
							'subject' => 'Recuperacion de contraseña TVmia',
								
							'from'	  => 'soporte@tvmia.com',
								
							'fromname'=> 'Recuperacion de contraseña TVmia',
								
							'address' =>  array('email'=>$email,'name'=>'Usuario'),
					);
						
					/**
					 * Send Email
					 *
					$Tvmia_email->Send_Email($email_params);
				
					print 'ok';
			}else{
					print $error[0];
			}
			*/
?>
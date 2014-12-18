<?php
	/**
	 * 
	 * Class Sysmenu
	 * 
	 * @author william
	 * @package admin
	 * 
	 */
	
	class Sysmenu{

 	 public $tpl;
	
 	 public $db_object;
 	 
 	 public $security;
	
 	 /**
 	  * 
 	  * Layout Construct
 	  */
	 	 public function __construct($tpl){
	 	 	
	 	 	$this->security = new Security();
	 	 	
	 	 	$this->tpl		= $tpl;
	 	 }
 	
		/**
		 * Generate Sidebar Buttons
		 *
		 * @param array $pages
		 *
		 * return mixed
		 *
		 */
			public function sidebar($pages){
			
				$html='';
				
				foreach($pages as $key=>$value){
					
					if(isset($this->security->permissions[$key.'_perm'])){		
	
						$roll = $this->security->permissions[$key.'_perm'];
						
						if(is_array($roll)){
		
							if(isset($value['perm'])){
								$show = $value['perm'];
							}else{
								$show='show';
							}
							
							if(search_in_array($roll,$show)!=null){
		
								$html.="<li><a href='/{$value['url']}' class='nav-top-item no-submenu'>{$value['title']}</a></li>";
							}
							
						}
					
					}else{
						$html.="<li><a href='/{$value['url']}' class='nav-top-item no-submenu'>{$value['title']}</a></li>";
					}
				}
				
				$this->tpl->setVariable('sidebar_menu',$html);
			}
		
			
		/**
		 * Generate Module Icons
		 *
		 * @param array $pages
		 *
		 * return mixed
		 *
		 */
			public function home($pages){
				
				$html='<ul class="shortcut-buttons-set" style="margin-left: 50px;">';
				
				foreach($pages as $key=>$value){
						
						$roll = $this->security->permissions[$key.'_perm'];
					
						if(is_array($roll)){
						
							if(search_in_array($roll,'show')!=null){
								
								$html.="<li>
										<a rel='modal' href='/{$value['url']}' class='shortcut-button'>
											<span>
												<img alt='{$value['title']}' src='{$value['icon']}' width='90' height='90'><br/>
												{$value['title']}
											</span>
										</a>
									</li>";
							}
							
						}
				}
				
				$html.='</ul>';
				
				$this->tpl->setVariable('menu_home',$html);
			}
			
			/**
			 * Generate Sidebar Buttons
			 *
			 * @param array $pages
			 *
			 * return mixed
			 *
			 */
			public function menubar($pages){
					
				$html='';
			
				foreach($pages as $key=>$value){
						
					if(isset($this->security->permissions[$key.'_perm'])){
						$roll = $this->security->permissions[$key.'_perm'];
						
						if(is_array($roll)){
				
							if(isset($value['perm'])){
								$show = $value['perm'];
							}else{
								$show='show';
							}
							
							if(search_in_array($roll,$show)!=null){
				
								$html.="<li><a href='/{$value['url']}'>{$value['title']}</a></li>";
							}
						}
					}else{
							$html.="<li><a href='/{$value['url']}'>{$value['title']}</a></li>";
					}
				}
				
				$this->tpl->setVariable('menu_button',$html);
			}
	}
?>
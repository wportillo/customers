<?php
/**
 * 
 * Generate tag Elements
 * 
 * @author william
 * @package admin
 * 
 */
class Elements extends Grid{
	
		private $disabled;
	/**
	 * Constructor
	 * 
	 */
	public function __construct($db,$tpl){
		
		parent::__construct($db, $tpl);

		$this->disabledelement();
	}
		
	private function disabledelement(){
		
		if($this->security->getperm($this->rolname,'edit')){
			$this->disabled=false;
		}else{
			$this->disabled='disabled';
		}
	}
	
	/**
	 *
	 * Generate Main Bar
	 *
	 * @param array $attrib
	 *
	 */
	public function Main_bar($attrib){
	
		$html='<table><thead><tr><td>';
		
		foreach($attrib as $direction=>$label){

			if(isset($this->security->rol_values[$this->page])){
				$security = $this->security->rol_values[$this->page];
			}else{
				$security = array('edit');
			}
			
			if(is_array($security)){
				if(search_in_array($security,'edit')!=null){
					$html.='<a class="button" href="'.$direction.'">'.$label.'</a>';
				}
			}else{
				$html.='';
			}
		}
	
		$html.='</td></tr></thead></table>';

		$this->tpl->setVariable('main_bar',$html);
	}
	
	/**
	 * Upload
	 */
		public function upload($elementattrib,$request){

			$html="<p><span style='display: block;  margin-right: 5px; margin-top: 4px;'>{$elementattrib['label']}</span>";
			
			if($request!=''){

				$html.="<img src='{$elementattrib['folder']}{$request}' height='120px' width='120px' style='display:block;' id='image-{$elementattrib['name']}'/>";
			
			}else{

				$html.="<img  height='120px' width='120px' style='display:block;' id='image-{$elementattrib['name']}'/>";
					
			}
			
			
			$html.="<input type='hidden' name='{$elementattrib['name']}' id='imagename-{$elementattrib['name']}' value='{$request}'/>";
			
			$html.="<a class='{$elementattrib['name']} button' id='{$elementattrib['name']}' style='margin-left: 33px; top: 13px;'>Subir</a>
			
			<div id='info-{$elementattrib['name']}'></div>";
		
			$html.="<script>
			$('#{$elementattrib['name']}').ajaxUpload({
				url : 'upload',
				name: 'fileupload',
				onSubmit: function() {
					$('#info-{$elementattrib['name']}').html('Cargando ... ');
				},
				onComplete: function(result) {
					
					var fileinfo = $.parseJSON(result);

					$('#info-{$elementattrib['name']}').html('');
					
					$('#image-{$elementattrib['name']}').attr('src',fileinfo.folder+fileinfo.name);
					
					$('#imagename-{$elementattrib['name']}').val(fileinfo.name);
				}
			});
			</script></p>";
				
			$this->tpl->setVariable($elementattrib['name'].'_upload',$html);
		}
		
	/**
	 * Generate Permission Grid
	 *
	 * @param string $rows
	 *
	 * return mixed
	 */
		
		public function permission_grid($rows){
		
			$elementattrib;	
			
			$getgrid = $this->db->get_list('1','1','trash!=1');
			
			while($row = db::fetch_assoc($getgrid)){
			
				foreach($row as $key => $value) {
						
					if(preg_match('/^[a-z]+_perm/', $key)){
							
						$text = str_replace('_perm', '', $key);
			
						$elementattrib[] = array('name'=>$key,'label'=>ucfirst($text),'value'=>$row[$key]);
					}
				}
			}

			$html='<p><table class="permissions">';
		
			$html.='<tr>
						<th>Modulos</th>
						<th>Mostrar Modulo</th>
						<th>Crear/Editar</th>
						<th>Listar</th>
						<th>Borrar</th>
					</tr>';
		
			foreach ($elementattrib as $value){
					
				$html.='<tr><td>'.$value['label'].'</td>';
		
				$key = $value['name'];
			
				if(is_array(unserialize($rows[$key]))){
					$check = unserialize($rows[$key]);
				}else{
					$check = array('default');
				}
				
				if(array_key_exists('1',$check)){
					$html.='<td><input class="check-all-permissions" type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[1]" value="show" checked="checked" /></td>';
				}else{
					$html.='<td><input class="check-all-permissions" type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[1]" value="show" /></td>';
				}
				
				if(array_key_exists('2',$check)){
					$html.='<td><input class="edit-permissions" 	 type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[2]" value="edit" checked="checked" /></td>';
				}else{
					$html.='<td><input class="edit-permissions" 	 type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[2]" value="edit" /></td>';
				}
				
				if(array_key_exists('3',$check)){
					$html.='<td><input class="view-permissions" 	 type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[3]" value="list" checked="checked" /></td>';
				}else{
					$html.='<td><input class="view-permissions" 	 type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[3]" value="list" /></td>';
				}
				
				if(array_key_exists('4',$check)){
					$html.='<td><input class="delete-permissions" 	 type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[4]" value="delete" checked="checked" /></td>';
				}else{
					$html.='<td><input class="delete-permissions" 	 type="checkbox"  id="col_'.$value['name'].'"  name="'.$value['name'].'[4]" value="delete" /></td>';
				}
		
				$html.='</tr>';
			}
		
			$html.='</table></p>';
		
			$this->tpl->setVariable('grid_permission',$html);
		}
		
	/**
	 * Generate Input
	 * @param string  $label
	 * @param string  $input_name
	 * @param integer $size
	 * @param string  $value
	 * 
	 * @return mixed input
	 */
		public function input($elementattrib,$request){
		
			switch($elementattrib['size']){
				case 1:
					$style_size = 'minim';
				break;
				case 2:
					$style_size = 'small';
				break;
				case 3:
					$style_size = 'medium';
				break;
				case 4:
					$style_size = 'large';
				break;
			}
			
						
			
			if(!isset($elementattrib['inputtype'])){
				
				$html="<span style='display: block;  margin-right: 5px; margin-top: 4px;'> {$elementattrib['label']} : </span><input name='{$elementattrib['name']}' class='text-input {$style_size}-input'  value='{$request}' type='text'/>";	
			
			}else{
				$html="<span style='display: block;  margin-right: 5px; margin-top: 4px;'> {$elementattrib['label']} : </span><input name='{$elementattrib['name']}' class='text-input {$style_size}-input'  value='{$request}' type='{$elementattrib['inputtype']}'/>";	
			}
		
			$this->tpl->setVariable($elementattrib['name'].'_input',$html);
		}
	/**
	 * Generate Label
	 *
	 * @param string $label_name
	 * @param string $input_name
	 * @param string $value
	 *
	 * return mixed
	 */
	public function label($elementattrib,$request){
		$this->tpl->setVariable($elementattrib['name'].'_label',"<p><span>{$elementattrib['label']}</span><span style='margin-left: 15px;'>{$request}</span></p>");
	}
	
	/**
	 * Generate Radio
	 *
	 * @param string $label_name
	 * @param string $input_name
	 * @param string $value
	 *
	 */
	public function radio($elementattrib,$request){
	
		$html="<p><span style='display: block;  margin-right: 5px; margin-top: 4px;'>{$elementattrib['label']}</span>";
	
		if($request=='0'){

			$html.="<input type='radio' name='{$elementattrib['name']}'  value='0' checked='checked' id='{$elementattrib['name']}_inactive'/> No<br /> <input type='radio' name='{$elementattrib['name']}'  value='1'  id='{$elementattrib['name']}_active' /> Si</p>";
		
		}else{
			
			$html.="<input type='radio'  name='{$elementattrib['name']}'  value='0'  id='{$elementattrib['name']}_inactive' /> No<br /><input type='radio' name='{$elementattrib['name']}'  value='1' checked='checked' id='{$elementattrib['name']}_active' /> Si</p>";
	
		}
		
		$this->tpl->setVariable($elementattrib['name'].'_radio',$html);
	}
	
	/**
	 * Generate Notification Event
	 * @param String $label
	 * @param integer $type_event
	 *
	 * return mixed
	 */
	public function notification($label,$type_event=1){
	
		switch($type_event){
			case 1:
				$type='success';
			break;
			case 2:
				$type='error';
			break;
			case 3:
				$type='attention';
			break;
		}
	
		$html='<div id="notification">
		<div class="notification '.$type.' png_bg">
		<a onclick="closes();" class="close">
		<img alt="close" title="Cerrar Esta notificacion" src="resources/images/icons/cross_grey_small.png">
		</a>
		<div>'.$label.'</div></div></div>';
	
		$this->tpl->setVariable('notification_event',$html);
	}
	
	/**
	 * Generate Drop Menu
	 * @param array   $elementattrib
	 * @param integer $request
	 *
	 */
	public function dropmenu($elementattrib,$request){
		
		$html='<span style="display: block;  margin-right: 5px; margin-top: 4px;">'.$elementattrib['label']. ':</span>';
		
		if(!isset($elementattrib['multiselect'])){

			
			if(isset($elementattrib['onchange'])){

				$html.="<select name='{$elementattrib['name']}' id='{$elementattrib['name']}' onchange='{$elementattrib['onchange']}'>";
			
			}else{
				$html.="<select name='{$elementattrib['name']}' id='{$elementattrib['name']}'>";
			}
			
		
			
			$html.='<option value="0">seleccione..</option>';
		
		}else{

			if(isset($elementattrib['onchange'])){

				$html.="<select name='{$elementattrib['name']}[]' id='{$elementattrib['name']}' multiple='true' onchange='{$elementattrib['onchange']}'>";
			}else{
				$html.="<select name='{$elementattrib['name']}[]' id='{$elementattrib['name']}' multiple='true'>";
			}
		}
		
		foreach ($elementattrib['dropvalues'] as $key=>$value){

			if(!is_array($request)){
			
					if($request==$key){
						$html.='<option value="'.$key.'" selected>'.$value.'</option>';
					}else{
						$html.='<option value="'.$key.'">'.$value.'</option>';
					}
			}else{
					if(search_in_array($request,$key)!=null){
						$html.='<option value="'.$key.'" selected>'.$value.'</option>';
					}else{
						$html.='<option value="'.$key.'">'.$value.'</option>';
					}
			}
		}
		
		$html.='</select>';
		
		$this->tpl->setVariable($elementattrib['name'].'_dropmenu',$html);
	}
	
		/**
		 *
		 * Generate dropmenu from Bd
		 *
		 * @param array  $elementattrib
		 * @param mixed  $request
		 *
		 */
		public function dropmenudb($elementattrib,$request){
		
			$rs_dropmenu = $elementattrib['table']->get_list('','',$elementattrib['where']);
			
			$html="<span style='display: block;  margin-right: 5px; margin-top: 4px;'>{$elementattrib['label']}:</span>";
		
		
			if(!isset($elementattrib['multiselect'])){

				
				if(isset($elementattrib['onchange'])){

					$html.="<select name='{$elementattrib['name']}' id='{$elementattrib['name']}' onchange='{$elementattrib['onchange']}'>";
				
				}else{
					
					$html.="<select name='{$elementattrib['name']}'  id='{$elementattrib['name']}'>";
				
				}
				$html.='<option value="0">seleccione..</option>';
			
			}else{
				
				
				if(isset($elementattrib['onchange'])){

					$html.="<select name='{$elementattrib['name']}[]' id='{$elementattrib['name']}' multiple='true' onchange='{$elementattrib['onchange']}'>";
				
				}else{
					
					$html.="<select name='{$elementattrib['name']}[]' id='{$elementattrib['name']}' multiple='true' >";
				
				}
				
			}
			
			while($row=db::fetch_assoc($rs_dropmenu)){
				
					$label = $elementattrib['drop_label'];
					
					$value = $elementattrib['drop_value'];
				
				if(!is_array($request)){


					if($request==$row[$value]){

						$html.='<option value="'.$row[$value].'" selected >'.$row[$label].'</option>';
					
					}else{
						
						$html.='<option value="'.$row[$value].'">'.$row[$label].'</option>';
					
					}
				}else{
					
					if(search_in_array($request,$row[$value])!=null){
						$html.='<option value="'.$row[$value].'" selected>'.$row[$label].'</option>';
					}else{
						$html.='<option value="'.$row[$value].'">'.$row[$label].'</option>';
					}
				}
			}
			
			$html.='</select>';
		
			$this->tpl->setVariable($elementattrib['name'].'_dropmenu',$html);
		
		}
		/**
		 *
		 * Generate dropmenu from Bd
		 *
		 * @param array  $elementattrib
		 * @param mixed  $request
		 *
		 */
		public function dropmenurol($elementattrib,$request){
		
			$rs_dropmenu = $elementattrib['table']->get_list('','',$elementattrib['where']);
				
			$html="<span style='display: block;  margin-right: 5px; margin-top: 4px;'>{$elementattrib['label']}:</span>";
		
		
			if(!isset($elementattrib['multiselect'])){
		
		
				if(isset($elementattrib['onchange'])){
		
					$html.="<select name='{$elementattrib['name']}' id='{$elementattrib['name']}' onchange='{$elementattrib['onchange']}'>";
		
				}else{
						
					$html.="<select name='{$elementattrib['name']}'  id='{$elementattrib['name']}'>";
		
				}
				$html.='<option value="0">seleccione..</option>';
					
			}else{
		
		
				if(isset($elementattrib['onchange'])){
		
					$html.="<select name='{$elementattrib['name']}[]' id='{$elementattrib['name']}' multiple='true' onchange='{$elementattrib['onchange']}'>";
		
				}else{
						
					$html.="<select name='{$elementattrib['name']}[]' id='{$elementattrib['name']}' multiple='true' >";
		
				}
		
			}
				
			while($row=db::fetch_assoc($rs_dropmenu)){
		
				$label = $elementattrib['drop_label'];
					
				$value = $elementattrib['drop_value'];
		
				$show  = unserialize($row['show']);
				
				
				if(!is_array($request)){
		
		
					if($request==$row[$value]){
		
						
						if(is_array($show)){
								
							if(search_in_array($show, $elementattrib['i_roll'])){
								$html.='<option value="'.$row[$value].'" selected >'.$row[$label].'</option>';
							}
						}
							
					}else{
						
						if(is_array($show)){
						
							if(search_in_array($show, $elementattrib['i_roll'])){
								$html.='<option value="'.$row[$value].'">'.$row[$label].'</option>';
							}
						}
					}
				}else{
						
					if(search_in_array($request,$row[$value])!=null){
						
						if(is_array($show)){
						
							if(search_in_array($show, $elementattrib['i_roll'])){
								$html.='<option value="'.$row[$value].'" selected>'.$row[$label].'</option>';
							}
						}
						
					}else{
						if(is_array($show)){
						
							if(search_in_array($show, $elementattrib['i_roll'])){
									$html.='<option value="'.$row[$value].'">'.$row[$label].'</option>';
							}
						}
					}
				}
			}
				
			$html.='</select>';
		
			$this->tpl->setVariable($elementattrib['name'].'_dropmenu',$html);
		
		}
		
		/**
		 * Generate Calendar
		 *
		 * @param array  $elementattrib
		 * @param mixed  $request
		 *
		 */
		public function calendar($elementattrib,$request){
		
				$html='';
		
				$html.="<span style='display: block;  margin-right: 5px; margin-top: 9px;'>{$elementattrib['label']}:</span>";
					
				$html.="<input name='{$elementattrib['name']}' size='30' id='{$elementattrib['name']}' value='{$request}' class='text-input small-input' readonly='readonly'/>";
			
				$html.="<a id='{$elementattrib['name']}_btn'><img src='resources/icons/calendar.gif' style='margin-bottom:-16px;'/></a>";
				
				$html.="
				<script type='text/javascript'>
					
					var {$elementattrib['name']} = Calendar.setup({
						onSelect: function({$elementattrib['name']}) {
						{$elementattrib['name']}.hide();
					},
					showtime:false
				 });";
						
				$html.="{$elementattrib['name']}.manageFields('{$elementattrib['name']}_btn','{$elementattrib['name']}','%e/%o/%Y');</script>";
	
				$this->tpl->setVariable($elementattrib['name'].'_calendar',$html);
		}
			
		/**
		 * Generate Delete icon
		 * 
		 * @param $input_name
		 * @param $id
		 * 
		 */
			public function icondelete($input_name,$id,$trash){
				
				$html='';	
				
				if($this->security->getperm($this->rolname,'delete')){
					
					if($trash=='0'){
						$html.="<a onclick='void(0);' title='Enviar el elemento a la paelera?' class='icon-event' id='/{$this->page}/?action=delete&id={$id}' ><img alt='Send to trash' src='/resources/images/trash.png'/></a>";
					}else{
						$html.="<a onclick='void(0);' title='Desea borrar permanentemente el elemento?' class='icon-event' id='/{$this->page}/?action=delete&id={$id}' ><img alt='Send to trash' src='/resources/images/icons/cross.png'/></a>";
					}
				}
				
				$this->tpl->setVariable($input_name.'_delete',$html);
			}
	
			/**
			 * Generate Checbox
		     *
			 */
				public function generate_checkbox(){
					
					$security=false;
					
					if(isset($this->security->rol_values[$this->rolname])){
						$security=$this->security->rol_values[$this->rolname];
					}
					
					if(is_array($security)){
						if(search_in_array($security,'delete')!=null){
							if($trash=='0'){
								$html="<a onclick='void(0);' title='Enviar el elemento a la paelera?' class='icon-event' id='/{$this->page}/pag-1/delete/{$id}' ><img alt='Send to trash' src='/resources/images/trash.png'/></a>";
							}else{
								$html="<a onclick='void(0);' title='Desea borrar permanentemente el elemento?' class='icon-event' id='/{$this->page}/pag-1/delete/{$id}' ><img alt='Send to trash' src='/resources/images/icons/cross.png'/></a>";
							}
						}else{
							$html='';
						}
					}else{
						$html='';
					}
					
					$this->tpl->setVariable($input_name.'_delete',$html);
				}
	
			/**
			 * Generate Activate icon
			 *
			 * @param $input_name
			 * @param $id
			 *
			 */
				public function iconactive($input_name,$id,$active,$field=''){
						
						if($this->security->getperm($this->rolname,'edit')){
							
							if($active=='0'){
								
									$html="<a  title='Activar' class='active-event' href='/{$this->page}/?action=active&id={$id}&sent_form=1&field={$field}'><img alt='Active' src='/resources/images/icons/desactivo.png'/></a>";
							}else{
			
									$html="<a  title='Desactivar' class='active-event' href='/{$this->page}/?action=desactive&id={$id}&sent_form=1&field={$field}'><img alt='Desactive' src='/resources/images/icons/activo.png'/></a>";
								
							}
							
						}else{
			
							if($active=='0'){
									$html="<img alt='Desactivo' src='/resources/images/icons/desactivo.png'/>";
							}else{
									$html="<img alt='Activo' src='/resources/images/icons/activo.png'/>";
							}
							
						}
						
						$this->tpl->setVariable($input_name.'_active',$html);
				}
			
			/**
			 * Generate Activate iconshow
			 *
			 * @param $input_name
			 * @param $id
			 *
			 */
				public function iconactiveshow($input_name,$active){
				
					if($active=='0'){
						$html="<img alt='Desactivo' src='/resources/images/icons/desactivo.png'/>";
					}else{
						$html="<img alt='Activo' src='/resources/images/icons/activo.png'/>";
					}
						
					$this->tpl->setVariable($input_name.'_active',$html);
				}	
		/**
		 * Generate Icon restore
		 *
		 * @param $input_name
		 * @param $id
		 *
		 */
			public function iconrestore($input_name,$id,$trash){
					
				$html='';
				
				if($this->security->getperm($this->rolname,'edit')){
	
						if($trash=='1'){
							$html="<a onclick='void(0);' title='Desea restaurar el elemento?' class='icon-event' id='/{$this->page}/?action=restore&id={$id}' ><img alt='Delete' src='/resources/images/action_refresh.gif'/></a>";
						}
					
				}
				
				$this->tpl->setVariable($input_name.'_restore',$html);
			
			}
			
		/**
		 * Generate IconEdit
		 *
		 * @param $input_name
		 * @param $id
		 *
		 */
			public function iconedit($input_name,$id,$page_edit){
				
				$html='';
				
				if($this->security->getperm($this->rolname,'edit')){
					$html.="<a href='/{$page_edit}/{$id}' title='Edit'><img alt='Edit' src='/resources/images/icons/pencil.png'/></a>";
				}
				
				$this->tpl->setVariable($input_name.'_edit',$html);
			}
		
	/**
	 * Generate Log icon
	 *
	 * @param $input_name
	 * @param $id
	 *
	 */
		public function log_icon($input_name,$id,$log_direction){
				
			$html='';
			
			if($this->security->getperm($this->rolname,'edit')){
				$html.="<a href='/{$log_direction}/{$id}' title='Log'><img alt='Edit' src='/resources/icons/list_world.gif'/></a>";
			}
			
			$this->tpl->setVariable($input_name.'_log',$html);
		}
		
		/**
		 * Generate TextArea
		 * 
		 * @param array  $elementattrib
		 * @param mixed  $request
		 * 
		 */
			public function textarea($elementattrib,$request){
			
				$this->tpl->setVariable($elementattrib['name'].'_textarea',"<p><span style='display: block;  margin-right: 5px; margin-top: 4px;'>{$elementattrib['label']}</span><textarea name='{$elementattrib['name']}' id='{$elementattrib['name']}' width='{$elementattrib['width']}' height='{$elementattrib['height']}' >{$request}</textarea></p>");
			}
	/**
	 * Generate ajax Combobox
	 *
	 * @param $input_name
	 *
	 *
	 */
		public function ajax_combobox($input_name){
		
			$html='';
			
				
				$select_ini='<div id="combo_ajax" style="display:none;">Accion:<select name="accept_option" id="accept_option">';
					
				
				$select_edit='<option value="0">seleccione</option><option value="activate">Activar</option><option value="desactivate">Desactivar</option>';
					
				$select_delete= '<option value="delete">Eliminar</option><option value="restore">Restaurar</option>';
					
				$select_end='</select></div>';
				
				$html.=$select_ini;
				
				if($this->security->getperm($this->rolname,'edit')){
					$html.=$select_edit;
				}
				if($this->security->getperm($this->rolname,'delete')){
					$html.=$select_delete;
				}
	
				$html.=$select_end;
		
			$this->tpl->setVariable($input_name.'_ajax',$html);
		}
	
	/**
	 *
	 * Load Elements
	 * 
	 */
		public function LoadElements($elements,$request){
			
			foreach($elements as $elementattrib){
	
				if(isset($elementattrib['type'])){
					
					switch($elementattrib['type']){
						case 'calendar':
							$this->calendar($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'upload':
							$this->upload($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'input':
							$this->input($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'textarea':
							$this->textarea($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'radio':
							$this->radio($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'dropmenu':
							$this->dropmenu($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'label':
							$this->label($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'dropmenudb':
							$this->dropmenudb($elementattrib,$request[$elementattrib['name']]);
						break;
						case 'dropmenurol':
							$this->dropmenurol($elementattrib,$request[$elementattrib['name']]);
						break;
					}
				}
			}
		}
}
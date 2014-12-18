<?php
	/**
	 * Upload File
	 * 
	 * @author william
	 */
	class File{

		public $file;
		
		public $size;
		
		public $tmp;
		
		public $newfile;
		
		public $tmpdir;
		
		public $dir;
		
			
		/**
		 * Construct
		 * 
		 */	
			public function __construct(){
				
				$this->tmpdir     =  'files_cache/';
				
				$this->dir		  =  'files/';
			}
		
		/**
		 * Upload File
		 */
			public function upload(){
				
				$this->file    	  =  pathinfo($_FILES['fileupload']['name']);
			
				$this->newfile 	  =  uniqid().'.'.$this->file['extension'];
				
				$this->size    	  =  $_FILES['fileupload']['size'];
				
				$this->tmp     	  =  $_FILES['fileupload']['tmp_name'];
				
				move_uploaded_file($this->tmp,$this->tmpdir.$this->newfile);
			
				$fileinfo = array('name'=>$this->newfile,'folder'=>$this->tmpdir,'size'=>$this->size);
				
				return json_encode($fileinfo);
			}
		
		/**
		 * Move file
		 */
			public function move($name){
				
				if(file_exists($this->tmpdir.$name)){
					
					copy($this->tmpdir.$name, $this->dir.$name);
					
					unlink($this->tmpdir.$name);
				}	
			}
	
	}

?>
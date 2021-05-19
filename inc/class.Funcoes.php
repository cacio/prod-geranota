<?php
	
	class Funcoes{
		
		public function __construct(){
			
			
		}
		
		public	function getExtension($str){
			
			$i = strrpos($str,".");
			if (!$i){
				return "";
			}
			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		
		}
		
		public function create_zip($files = array(),$destination = '',$overwrite = false) {

			//Se o ficheiro existe, e overwrite é falso, return false

			if(file_exists($destination) && !$overwrite) { return false;}

			//variaveis

			$valid_files = array();

			//Se foi dado os files...

			if(is_array($files)) {

				//ciclo por cada ficheiro

				foreach($files as $file) {

					//certificar se ficheiro existe mesmo

					if(file_exists($file)) {

						$valid_files[] = $file;

					}

				}

			}

			//se temos então bons ficheiros...

			if(count($valid_files)) {

				//criar o arquivo ZIP

				$zip = new ZipArchive();

				if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {

					return false;

				}

				//adicionar os ditos finheiros

				foreach($valid_files as $file) {
					$files = explode('/',$file);
					$zip->addFile($file,$files[2]);

				}

				//debug

			   // echo 'O arquivo ZIP contem ',$zip->numFiles,' files com o estado de ',$zip->status;

				//fechar o zip -- done!

				$zip->close();

				//verificar se o ZIP foi bem criado

				return file_exists($destination);

			}

			else

			{

				return false;

			}

		}
		
	}


?>
<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'busca':

				$dao = new ProdutosDAO();
				$vet = $dao->BuscaProduto($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$pro = $vet[$i];
					
					$cod = $pro->getCodigo();
					$nom = $pro->getDescricao();
					$uni = $pro->getUnidade();
						
					array_push($results, array(
									'label' => ''.$cod.'-'.$nom.'',
									'value' => ''.$cod.'',
								   	'cod'=>''.$cod.'',
									'nom'=>''.$nom.'|('.$uni.')',
									'uni'=>''.$uni.'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;

			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>
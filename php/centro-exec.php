<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'busca':
				
				$term  = !empty($_REQUEST['term']) ? $_REQUEST['term'] : '';
				$cfor  = !empty($_REQUEST['cfor']) ? $_REQUEST['cfor'] : '';
				
				$dao = new CentroCustoDAO();
				$vet = $dao->BuscaCentroCustoPorFornecedor($cfor,$term);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$con = $vet[$i];
					
										
					$codigo     = $con->getCodigo();
					$descricao  = $con->getNome();
					$codfor 	= $con->getCodFornecedor();	
						
					array_push($results, array(
									'label' => ''.$codigo.'-'.$descricao.'',
									'value' => ''.$codigo.'',
								   	'cod'=>''.$codigo.'',
									'nom'=>''.$descricao.'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;

			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>
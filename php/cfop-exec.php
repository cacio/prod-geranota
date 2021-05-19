<?php

	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	
		switch($act){

			case 'busca':
					
				$dao = new CfopDAO();
				$vet = $dao->BuscaCfop($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$cfop = $vet[$i];
					
					$cod 	 = $cfop->getCodigo();
					$nom 	 = mb_convert_encoding($cfop->getDescricao(),'UTF-8');
					$codfisc = $cfop->getCodigoFiscal();	
					
					array_push($results, array(
									'label' => ''.$cod.'-'.$nom.'',
									'value' => ''.$cod.'',
								   	'cod'=>''.$cod.'',
									'nom'=>''.$nom.'',
								));		
					
				}
				
				echo (json_encode($results));
					
			
			break;
			case 'veridicacfop':
					
				$dao = new CfopDAO();
				$vet = $dao->VeridicaCfop($_REQUEST['cf']);
				$num = count($vet);
				$results = array();
				
				if($num == 0){
					
					array_push($results, array(
									'label' => '2',
								   	'cod'=>'',
									'nom'=>'',
									'msg'=>'CFOP NÃO EXISTE',
					));	
				}else{
									
					$cfop = $vet[0];
				
					$cod 	  = $cfop->getCodigo();
					$nom 	  = mb_convert_encoding($cfop->getDescricao(),'UTF-8');
					$baixaest = $cfop->getBaixaEst();
		
					array_push($results, array(
									'label' => '1',
								   	'cod'=>''.$cod.'',
									'nom'=>''.$nom.'',
									'msg'=>'',
								));		
				
				}
				
					
				
				
				echo (json_encode($results));
					
			
			break;
			
		}

	}

?>
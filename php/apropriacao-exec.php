<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':

				/*echo "<pre>";
				print_r($_REQUEST);*/
				
				$result  = array();
				
				$centros = !empty($_REQUEST['centros']) ? $_REQUEST['centros'] : '0';
				$contas  = !empty($_REQUEST['contas'])  ? $_REQUEST['contas']  : '0';
	
				$vl      = !empty(str_replace(',', '.', str_replace('.', '', $_REQUEST['vl'])))  ? str_replace(',', '.', str_replace('.', '', $_REQUEST['vl'])) : '';
				$notas   = !empty($_REQUEST['notas'])   ? $_REQUEST['notas']   : '';
				$emp     = !empty($_REQUEST['emp'])     ? $_REQUEST['emp']     : '';
				$dtemi   = !empty($_REQUEST['dtemi'])   ? $_REQUEST['dtemi']   : '';
				$cfor    = !empty($_REQUEST['cfors'])    ? $_REQUEST['cfors']  : '';
				
				$xcentros = !empty($_REQUEST['xcentros']) ? $_REQUEST['xcentros'] : '0';
				$xcontas  = !empty($_REQUEST['xcontas'])  ? $_REQUEST['xcontas']  : '0';
				
				
				$daof =  new FinancContasDAO();
				$vetf = $daof->VerificaFinancContasPorCentro($contas);	
				$numf = count($vetf);
				
				if($numf > 0){
				
					$apro = new ApropriacaoMovimentacao();
					
					$apro->setCentro($centros);	
					$apro->setConta($contas);			
					$apro->setValor($vl);
					$apro->setNota($notas);
					$apro->setFornecedor($cfor);
					$apro->setEmpresa($emp);
					$apro->setDataEmissao($dtemi);
					
					$dao = new ApropriacaoMovimentacaoDAO();
					$dao->inserir($apro);
					
					$proxid = $dao->ProximoId();			
					$prox   = $proxid[0];
					$cod    = $prox->getProximoId();
					
					array_push($result, array(
								'centros' => ''.$centros.'',
								'contas' => ''.$contas.'',
								'xcentros' => ''.$xcentros.'',
								'xcontas' => ''.$xcontas.'',
								'vl' => ''.$vl.'',
								'emp' => ''.$emp.'',
								'notas' => ''.$notas.'',
								'dtemi' => ''.$dtemi.'',
								'cfor' => ''.$cfor.'',
								'cod' => ''.$cod.'',
								'tipo' => '1',			
					));	
				
				}else{
				
					array_push($result, array(
							'centros' => '',
							'contas' => '',
							'xcentros' => '',
							'xcontas' => '',
							'vl' => '',
							'emp' => '',
							'notas' => '',
							'dtemi' => '',
							'cfor' => '',
							'cod' => '',
							'tipo' => '3',			
					));
					
				}
				
				echo (json_encode($result));
					
				break;
			case 'deletar':
				
			$id = $_REQUEST['id'];		
			$result  = array();
			
			 foreach($id as $key => $values){	
				
				$apro = new ApropriacaoMovimentacao();
				$apro->setCodigo($values);
				
				$dao = new ApropriacaoMovimentacaoDAO();
				$dao->deletar($apro);
				
				array_push($result, array(
						'cod' => ''.$values.'',					
				));	
								
			 }
			 
			 echo (json_encode($result));
			 
			break;
			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>
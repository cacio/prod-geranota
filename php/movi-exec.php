<?php

	require_once('../inc/inc.autoload.php');
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];
		switch($act){

		case 'inserir':
			
			$encoding = mb_internal_encoding(); 
			$val 	      = !empty($_REQUEST['valor']) 		  ? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor'])) : 0;
			$hist 		  = !empty($_REQUEST['hist'])  	      ? $_REQUEST['hist'] : "";
			$data 		  = !empty($_REQUEST['data'])         ? implode("-", array_reverse(explode(".", $_REQUEST['data']))) : date('Y-m-d');			
			$contacredito = !empty($_REQUEST['contacredito']) ? $_REQUEST['contacredito'] : '0';
			$contadebido  = !empty($_REQUEST['contadebido'])  ? $_REQUEST['contadebido'] : '0';
			//$idhist       = !empty($_REQUEST['idhist']) 	  ? $_REQUEST['idhist'] : '0';
			$idhistpadrao = !empty($_REQUEST['idhistpadrao']) ? $_REQUEST['idhistpadrao'] :'0';
					
			$mov = new Movimentacao();

			$mov->setData($data);
			$mov->setIdContaCredito(trim($contacredito));
			$mov->setIdContaDebito(trim($contadebido));		
			$mov->setHistorico(mb_strtoupper($hist, $encoding));
			$mov->setIdHistorico(trim($idhistpadrao));
			$mov->setValor($val);

			$dao = new MovimentacaoDAO();
			$dao->inserir($mov);

			echo 'Adicionado com sucesso!';
		
		break;
		case 'alterar':
					
			$id			  = !empty($_REQUEST['id'])  	      ? $_REQUEST['id'] : "";
			$val 	      = !empty($_REQUEST['valor']) 		  ? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor'])) : 0;
			$hist 		  = !empty($_REQUEST['hist'])  	      ? $_REQUEST['hist'] : "";
			$data 		  = !empty($_REQUEST['data'])         ? implode("-", array_reverse(explode(".", $_REQUEST['data']))) : date('Y-m-d');			
			$contacredito = !empty($_REQUEST['contacredito']) ? $_REQUEST['contacredito'] : '0';
			$contadebido  = !empty($_REQUEST['contadebido'])  ? $_REQUEST['contadebido'] : '0';
			//$idhist       = !empty($_REQUEST['idhist']) 	  ? $_REQUEST['idhist'] : '0';
			$idhistpadrao = !empty($_REQUEST['idhistpadrao']) ? $_REQUEST['idhistpadrao'] :'';
			
			$mov = new Movimentacao();

			$mov->setCodigo($id);
			$mov->setData($data);
			$mov->setIdContaCredito($contacredito);
			$mov->setIdContaDebito($contadebido);		
			$mov->setHistorico($hist);
			$mov->setIdHistorico($idhistpadrao);
			$mov->setValor($val);

			$dao = new MovimentacaoDAO();
			$dao->alterar($mov);

			echo 'Alterado com sucesso!';
		
		break;
		case 'delete':

			($_REQUEST['id'])  ? $idm   = $_REQUEST['id']  :false;

			$mov = new Movimentacao();
			$mov->setCodigo($idm);

			$dao = new MovimentacaoDAO();
			$dao->deletar($mov);
		break;
		
		case 'lista':

			$dao = new MovimentacaoDAO();
			$vet = $dao->ListaFinancContas();
			$num = count($vet);
			$arr = array();

			for ($i=0; $i < $num; $i++) { 
				$mov			 = $vet[$i];
				$codigo 		 = $mov->getCodigo();
				$data   		 = $mov->getData();
				$mov_idconta_c   = utf8_encode($mov->getIdContaCredito());
				$mov_idconta_d   = utf8_encode($mov->getIdContaDebito());		
				$mov_historico   = utf8_encode($mov->getHistorico());
				$mov_idhistorico = $mov->getIdHistorico();
				$mov_valor       = $mov->getValor();
				$descpadrao      = utf8_encode($mov->getDescricao());

				array_push($arr,array(
					'id'=>$codigo,
					'data'=>date('d/m/Y',strtotime($data)),
					'mov_idconta_c'=>$mov_idconta_c,
					'mov_idconta_d'=>$mov_idconta_d,
					'mov_historico'	=>$descpadrao.' '.$mov_historico,
					'mov_valor'=>number_format($mov_valor,2,',','.') 					
				));
			}

			echo json_encode($arr);


		break;
		case 'listaum':
			$id = $_REQUEST['id'];
			
			$dao = new MovimentacaoDAO();
			$vet = $dao->ListaFinancContasUm($id);
			$num = count($vet);
			$arr = array();

			for ($i=0; $i < $num; $i++) { 
				$mov			 = $vet[$i];

				$codigo 		 = $mov->getCodigo();
				$data   		 = $mov->getData();
				$mov_idconta_c   = $mov->getIdContaCredito();
				$mov_idconta_d   = $mov->getIdContaDebito();		
				$mov_historico   = $mov->getHistorico();
				$mov_idhistorico = $mov->getIdHistorico();
				$mov_valor       = $mov->getValor();				

				array_push($arr,array(
					'id'=>$codigo,
					'data'=>date('d.m.Y',strtotime($data)),
					'mov_idconta_c'=>$mov_idconta_c,
					'mov_idconta_d'=>$mov_idconta_d,
					'mov_historico'	=>$mov_historico,
					'mov_idhistorico'=>trim($mov_idhistorico),
					'mov_valor'=>number_format($mov_valor,2,',','.') 					
				));
			}

			echo json_encode($arr);

		break;
		}	

	}

	

?>
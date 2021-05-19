<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		


		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':
				
				
				
				$dados  = $_REQUEST['dados'];
				$result =  array();
				
				foreach($dados as $key=>$value){
					
					
					$empresa    = $value['empresa'];
					$cedente    = $value['cedente'];
					$emissao    = $value['emissao'];
					$tipo	    = $value['tipo'];
					$numeronota = $value['numeronota'];
					$totalnota  = str_replace(',', '.', str_replace('.', '', $value['totalnota']));
					$arquivo    = $value['arquivo'];
					$cfop		= $value['cfop'];
					
					$dao = new CfopDAO();
					$vet = $dao->ListaCfopUm($cfop);
					$num = count($vet);
					
					if($num > 0){
					
						$nt = $vet[0];					
						$baixaest = $nt->getBaixaEst();
						
						if($baixaest == 'S'){
							
							
							$xml =  simplexml_load_file($arquivo);
							$dp = !empty($xml->NFe->infNFe->cobr->dup) ? $xml->NFe->infNFe->cobr->dup : '1';
				
						if($dp != '1'){ 
							foreach($xml->NFe->infNFe->cobr->dup as $dups){
								
									$nDup  = $dups->nDup;
									if(strlen($nDup) > 15){
										$nDup = $numeronota;
									}
									
									if(count($xml->NFe->infNFe->cobr->dup) == 1){
										$nDup = $numeronota;
									}else{
										$nDup = $numeronota.'/'.(int)$nDup;
									}
									
									$dVenc = implode(".", array_reverse(explode("-", "".$dups->dVenc."")));
									$vDup  = $dups->vDup;
								
									$dup = new Duplic();											
									
									$dup->setEmpresa($empresa);
									$dup->setCodFornecedor($cedente);
									$dup->setEmissao($emissao);
									$dup->setNumero($nDup);
									$dup->setTipo($tipo);
									$dup->setVencimento($dVenc);
									$dup->setValorDoc($vDup);
									$dup->setNumeroNota($numeronota);
									$dup->setTotalNota($totalnota);								
									
									$daod = new DuplicDAO();
									$daod->inserir($dup);	
									
							}
							
							array_push($result, array(
									'tipo' => 'ok',	
									'msg'=>'Ok tudo certo, Vamos para a proxima!',				
							));	
												
							}else{
							
								$nDup  = $_REQUEST['nDup'];
								if(strlen($nDup) > 15){
									$nDup = $numeronota;
								}
								
								//if(strlen($nDup) == 1){
									$nDup = $numeronota.'/'.$nDup;
								//}
								
								$dVenc = $_REQUEST['dVenc'];
								$vDup  = str_replace(',', '.', str_replace('.', '', $_REQUEST['vDup']));
								
								if($vDup != $totalnota){																
									$nDup = $numeronota."/".$_REQUEST['parcela'];									
								}
								
								$dup = new Duplic();											
								
								$dup->setEmpresa($empresa);
								$dup->setCodFornecedor($cedente);
								$dup->setEmissao($emissao);
								$dup->setNumero($nDup);
								$dup->setTipo($tipo);
								$dup->setVencimento($dVenc);
								$dup->setValorDoc($vDup);
								$dup->setNumeroNota($numeronota);
								$dup->setTotalNota($totalnota);								
								
								$daod = new DuplicDAO();
								$daod->inserir($dup);	
								
								array_push($result, array(
									'tipo' => 'ok',	
									'msg'=>'Ok tudo certo, Vamos para a proxima!',															
								));	
								
							}
								
								
						}else{
							
							array_push($result, array(
									'tipo' => 'not',	
									'msg'=>'Ops, CFOP não tem direito a inserir no duplic',					
							));
							
						}// if do baixaest												
					
					}else{
						array_push($result, array(
										'tipo' => 'not',	
										'msg'=>'Ops, Não acho a CFOP informada',					
								));
					
					} // if do num
					
				} // foreach dos dados
					
				 echo (json_encode($result));
				
			break;
			case 'mostra':
				
				$arquivo = $_REQUEST['arquivo'];
				$result  =  array();
				
				$xml	 =  simplexml_load_file($arquivo);
				$dp = !empty($xml->NFe->infNFe->cobr->dup) ? $xml->NFe->infNFe->cobr->dup : '1';				
				if($dp != '1'){ 
				
					foreach($xml->NFe->infNFe->cobr->dup as $dups){
						
							$nDup  = $dups->nDup;
														
							if(count($xml->NFe->infNFe->cobr->dup) == 1){
								$nDup = $xml->NFe->infNFe->ide->nNF;
							}else{
								$nDup = $xml->NFe->infNFe->ide->nNF.'/'.(int)$nDup;
							}
							
							$dVenc = implode(".", array_reverse(explode("-", "".$dups->dVenc."")));
							$vDup  = $dups->vDup;
	
							array_push($result, array(
									'nDup' => ''.$nDup.'',	
									'dVenc'=>''.$dVenc.'',
									'vDup'=>''.$vDup.'',
									'msg'=>'1',					
							));
							
					}
				}else{
						
					array_push($result, array(
							'nDup' => '',	
							'dVenc'=>'',
							'vDup'=>'',	
							'msg'=>'0',				
					));
				}
			 echo (json_encode($result));
				 
			break;
			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>
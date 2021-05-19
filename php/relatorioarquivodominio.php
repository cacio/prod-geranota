<?php
	
	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/relatorioarquivodominio.htm');
	//$tpl->assignInclude('conteudo','../tpl/relatorioarquivodominio.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.menu.php');
    	$tpl->assign('log',$_SESSION['login']);
		
		$dataini     =  $_REQUEST['dataini'];
		$datafin     =  $_REQUEST['datafin'];

		$daof = new FinancContasDAO();
		$vetf = $daof->ValidaFincHistoricoPadrao();
		$numf = count($vetf);
		$msg = "";
		if($numf > 0){

			for ($i=0; $i < $numf; $i++) { 
					
				$fct    = $vetf[$i];
				
				$codigo = $fct->getCodigo();
				$desc   = utf8_encode($fct->getDescricao());

				$msg .= "{$codigo} - {$desc} <br>";
			}

			$tpl->newBlock('listaralert');
			$tpl->assign('msg',"{$msg}");

		}

		$daomov = new MovimentacaoDAO();

		$vetvincpag = $daomov->InconcistenciaPagamentos($dataini,$datafin);
		$numvincpag = count($vetvincpag);
		$msg2 = "";
		if($numvincpag > 0){

			for ($i=0; $i < $numvincpag; $i++) { 
				
				$movpag	      = $movpag[$i];
				$CONTACREDITO = $movpag->getIdContaCredito();
				$CONTADEBIDO  = $movpag->getIdContaDebito();	

				$msg2 .= "{$CONTACREDITO} <br>";
				$msg2 .= "{$CONTADEBIDO} <br>";


			}
			
			$tpl->newBlock('listaralert2');
			$tpl->assign('msg2',"{$msg2}");
		}

		$vetvincprov = $daomov->InconcistenciaProvisionamento($dataini,$datafin);
		$numvincprov = count($vetvincprov);
		$msg3 = "";	

		if($numvincprov > 0){

			for ($i=0; $i < $numvincprov; $i++) { 

				$movprov	   = $vetvincprov[$i];

				$CONTACREDITO2 =  mb_convert_encoding($movprov->getIdContaCredito(),"UTF-8");
				$CONTADEBIDO2  = mb_convert_encoding($movprov->getIdContaDebito(),"UTF-8");						
				$hispadrao     = $movprov->getIdHistorico();

				if(substr(trim($CONTACREDITO2), 0, 3) == 'SEM'){
					$msg3 .= "{$CONTACREDITO2} <br>";
				}
				if(substr(trim($CONTADEBIDO2), 0, 3) == 'SEM'){
					$msg3 .= "{$CONTADEBIDO2} <br>";
				}
				if(substr(trim($hispadrao), 0, 3) == 'SEM'){
					$msg3 .= "{$hispadrao} <br>";
				}

			}
			$tpl->newBlock('listaralert3');
			$tpl->assign('msg3',"{$msg3}");
		}


		$vetvincrecb = $daomov->InconcistenciaRecebimentos($dataini,$datafin);
		$numvincrecb = count($vetvincrecb);
		$msg4 = "";	

		if($numvincrecb > 0){

			for ($i=0; $i < $numvincrecb; $i++) { 

				$movrecb	   = $vetvincrecb[$i];

				$CONTACREDITO3 = $movrecb->getIdContaCredito();
				$CONTADEBIDO3  = $movrecb->getIdContaDebito();						
			
				if(substr(trim($CONTACREDITO3), 0, 3) == 'SEM'){
					$msg4 .= "{$CONTACREDITO3} <br>";
				}
				if(substr(trim($CONTADEBIDO3), 0, 3) == 'SEM'){
					$msg4 .= "{$CONTADEBIDO3} <br>";
				}
				

			}
			$tpl->newBlock('listaralert4');
			$tpl->assign('msg4',"{$msg4}");
		}


		$vetmov = $daomov->DadosArquivoDominioRel($dataini,$datafin);
		$nummov = count($vetmov);

		
		for ($i=0; $i < $nummov; $i++) { 

			$mov             = $vetmov[$i];
			$data            = date('d/m/Y',strtotime($mov->getData()));
			$mov_idconta_c   = $mov->getIdContaCredito();
			$mov_idconta_d   = $mov->getIdContaDebito();		
			$mov_historico   = $mov->getHistorico();
			$mov_idhistorico = $mov->getIdHistorico();
			$mov_valor       = number_format($mov->getValor(),2,',','.');
			$idusuario       = $mov->getIdUsuario();
			$CODIGOFM        = $mov->getCodigoFM();

			$tpl->newBlock('listar');

			$tpl->assign('data',$data);
			$tpl->assign('mov_idconta_c',utf8_encode($mov_idconta_c));
			$tpl->assign('mov_idconta_d',utf8_encode($mov_idconta_d));
			$tpl->assign('mov_historico',utf8_encode($mov_historico));
			$tpl->assign('mov_idhistorico',$mov_idhistorico);
			$tpl->assign('mov_valor',$mov_valor);
			$tpl->assign('idusuario',$idusuario);
			$tpl->assign('CODIGOFM',$CODIGOFM);

			
		}
				
		
	

	/**************************************************************/
	$tpl->printToScreen();

?>
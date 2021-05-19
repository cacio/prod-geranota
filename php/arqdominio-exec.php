<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){
		
		$act = $_REQUEST['act'];	
		switch($act){

			case 'gerar':
                error_reporting(E_ALL);
	            ini_set('display_errors', 'On');
                $dataini = $_REQUEST['dataini'];
                $datafim = $_REQUEST['datafin'];

                $daof = new FinancContasDAO();
                $vetf = $daof->ValidaFincHistoricoPadrao();
                $numf = count($vetf);
                
                $daomovs = new MovimentacaoDAO();
                $vetvincpag = $daomovs->InconcistenciaPagamentos($dataini,$datafim);
		        $numvincpag = count($vetvincpag);

                $vetvincprov = $daomovs->InconcistenciaProvisionamento($dataini,$datafim);
		        $numvincprov = count($vetvincprov);

                $vetvincrecb = $daomovs->InconcistenciaRecebimentos($dataini,$datafim);
		        $numvincrecb = count($vetvincrecb);

                $numvalida = $numf + $numvincpag + $numvincprov + $numvincrecb;

                if($numvalida == 0){

                $daoem = new Nf_EDAO();
                $vetem = $daoem->DadosEmitente();
                $nemem = count($vetem);

                if($nemem>0){
                    $emit = $vetem[0];
                    $codigo = $emit->getCodigo();
			        $nome   = $emit->getNome();
			        $cnpj   = $emit->getCnpjCpf();
                }


                $dao = new ArqDominio();

                $dadosHeader      = array();
                $dadosLancamento  = array();
                $dadosGerais      = array();
                $dadosCentroCusto = array();
                $dadosfinalizador = array();

                array_push($dadosHeader,array(
                    'REG'=>"01",
                    'codemp'=>"505",
                    'cgc'=>"{$cnpj}",
                    'dtini'=>"".str_replace('.','/',$dataini)."",
                    'dtfim'=>"".str_replace('.','/',$datafim)."",
                    'tipo'=>"N",
                    'tipoNota'=>"01",
                    'constante'=>"00000",
                    'sistema'=>"1",
                    'valorfix'=>"9"
                ));


                $daomov = new MovimentacaoDAO();
                $vetmov = $daomov->DadosArquivoDominio($dataini,$datafim);
                $nummov = count($vetmov);

                
                for ($i=0; $i < $nummov; $i++) { 

                    $mov             = $vetmov[$i];
                    $data            = date('d/m/Y',strtotime($mov->getData()));
                    $mov_idconta_c   = $mov->getIdContaCredito();
                    $mov_idconta_d   = $mov->getIdContaDebito();		
                    $mov_historico   = $mov->getHistorico();
                    $mov_idhistorico = trim($mov->getIdHistorico());
                    $mov_valor       = str_replace('.','',$mov->getValor());
                    $idusuario       = $mov->getIdUsuario();
                    $CODIGOFM        = $mov->getCodigoFM();
                    //echo "{$mov->getValor()}\n";
                    array_push($dadosLancamento,array(
                        'REG'=>'02',
                        'data'=>"{$data}", //Data do lançamento
                        'valorlancamento'=>"{$mov_valor}", // Valor do lançamento
                        'contadebito'=>"{$mov_idconta_d}", // Conta Débito
                        'contacredito'=>"{$mov_idconta_c}", // Conta Crédito
                        'historico'=>"{$mov_historico}", //Histórico (Complemento)
                        'origem'=>"12", // Origem (Valor Fixo "12"=Cliente)
                        'usuario'=>"{$idusuario}", //Usuário
                        'codfm'=>"{$CODIGOFM}", // Código da Filial ou Matriz
                        'codhistorico'=>"{$mov_idhistorico}", //Código do Histórico
                        'brancos'=>""    //Brancos
                    ));
                   
                }

                $contador = count($dadosHeader) + count($dadosLancamento) + count($dadosGerais) + count($dadosCentroCusto) + 1;

                array_push($dadosfinalizador,array(
                    'final'=>$contador
                ));

                $dados = array(
                    'dadosheader'=>$dadosHeader,
                    'dadoslancamento'=>$dadosLancamento,
                    'dadoslancamentogeral'=>$dadosGerais,
                    'dadoslancamentocentrocusto'=>$dadosCentroCusto,
                    'dadosfinalizador'=>$dadosfinalizador
                );

                $dao->__init($dados);

                $dao->save('../arquivo/dominio.txt');
			
                $result = array();

                array_push($result,array(
                    'arquivo'=>'../arquivo/dominio.txt',
                    'tipo'=>'1'
                ));
            }else{
                $msg = "";
                if($numf > 0){
                    $msg .= "<strong style='font-size: 16px;'>Inconsistência no vínculo entre Histórico Padrão e Contas</strong><br>";
                    for ($i=0; $i < $numf; $i++) { 
                        
                        $fct    = $vetf[$i];
                        
                        $codigo = $fct->getCodigo();
                        $desc   = utf8_encode($fct->getDescricao());

                        $msg .= "{$codigo} - {$desc} <br>";
                    }
                }
                $msg2 = "";
                if($numvincpag > 0){
                    $msg2 .= "<strong style='font-size: 16px;'>Inconsistência de vínculo de cadastro nos pagamentos</strong> <br/>";
                    for ($i=0; $i < $numvincpag; $i++) { 
                        
                        $movpag	      = $movpag[$i];
                        $CONTACREDITO = utf8_encode($movpag->getIdContaCredito());
                        $CONTADEBIDO  = utf8_encode($movpag->getIdContaDebito());	

                        $msg2 .= "{$CONTACREDITO} <br>";
                        $msg2 .= "{$CONTADEBIDO} <br>";


                    }                                        
                }
                $msg3 = "";	
                if($numvincprov > 0){
                    $msg3 .= "<strong style='font-size: 16px;'>Inconsistência de vínculo de cadastro nos provisionamentos</strong> <br/>";	
                    for ($i=0; $i < $numvincprov; $i++) { 
        
                        $movprov	   = $vetvincprov[$i];
        
                        $CONTACREDITO2 = mb_convert_encoding($movprov->getIdContaCredito(),"UTF-8");
                        $CONTADEBIDO2  = mb_convert_encoding($movprov->getIdContaDebito(),"UTF-8");						
                        $hispadrao     = mb_convert_encoding($movprov->getIdHistorico(),"UTF-8");
        
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
                }

                $msg4 = "";	

                if($numvincrecb > 0){
                    $msg4 .= "<strong style='font-size: 16px;'>Inconsistência de vínculo de cadastro nos recebimentos</strong> <br/>";
                    for ($i=0; $i < $numvincrecb; $i++) { 

                        $movrecb	   = $vetvincrecb[$i];

                        $CONTACREDITO3 = mb_convert_encoding($movrecb->getIdContaCredito(),"UTF-8");
                        $CONTADEBIDO3  = mb_convert_encoding($movrecb->getIdContaDebito(),"UTF-8");						
                    
                        if(substr(trim($CONTACREDITO3), 0, 3) == 'SEM'){
                            $msg4 .= "{$CONTACREDITO3} <br>";
                        }
                        if(substr(trim($CONTADEBIDO3), 0, 3) == 'SEM'){
                            $msg4 .= "{$CONTADEBIDO3} <br>";
                        }
                        

                    }
                    
                }

                $result = array();
                array_push($result,array(
                    'arquivo'=>'../arquivo/dominio.txt',
                    'tipo'=>'2',
                    'msg'=>"{$msg}",
                    'msg2'=>"{$msg2}",
                    'msg3'=>"{$msg3}",
                    'msg4'=>"{$msg4}",
                ));
            }
                echo json_encode($result);
			
			break;
			
		}

	}	

?>
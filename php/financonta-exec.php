<?php

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){	

		$act = $_REQUEST['act'];			

		switch($act){

			case 'busca':

				$dao = new FinancContasDAO();
				$vet = $dao->BuscaFinancContas($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$conta = $vet[$i];
					
					$cod	  = $conta->getCodigo();
					$nom	  = utf8_encode($conta->getDescricao());
					$reduzido = $conta->getReduzido();	

					array_push($results, array(
									'label' => ''.$cod.'-'.utf8_decode($nom).'',
									'value' => ''.$cod.'',
								   	'cod'=>''.$cod.'',
									'nom'=>''.utf8_decode($nom).'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;
			
			case 'busca2':

				$dao = new FinancContasDAO();
				$vet = $dao->BuscaFinancContas($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$conta = $vet[$i];
					
					$cod 		 = $conta->getCodigo();
					$nom 		 = $conta->getDescricao();
					$centrocusto = $conta->getCentroCusto();
					$codconclas  = $conta->getCodConClass();
						
					array_push($results, array(
									'label' => ''.$codconclas.'-'.utf8_encode($nom).'',
									'value' => ''.$codconclas.'',
								   	'centrocusto'=>''.$centrocusto.'',
									'nom'=>''.$nom.'',
									'codconclas'=>''.$codconclas.'',
									'cod'=>''.$cod.'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;

			case 'buscaconta':

				$dao = new FinancContasDAO();
				$vet = $dao->BuscaFinancContas($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$conta = $vet[$i];
					
					$cod	  = $conta->getCodigo();
					$nom	  = $conta->getDescricao();
					$reduzido = $conta->getReduzido();	

					array_push($results, array(
									'label' => ''.$reduzido.'-'.utf8_encode($nom).'',
									'value' => ''.$reduzido.'',
								   	'cod'=>''.$cod.'',
									'nom'=>''.utf8_decode($nom).'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;
			case 'updatehist':

				$codhist   = !empty($_REQUEST['codhist']) ? $_REQUEST['codhist'] : '';
				$codfinanc = !empty($_REQUEST['codfinanc']) ? $_REQUEST['codfinanc'] : '';

				$daoh = new HistoricoPadraoDAO();
				$veth = $daoh->ListaHistoricoPadraoAlter($codhist);
				$numh = count($veth);

				if($numh > 0){

					$finc = new FinancContas();

					$finc->setCodigo($codfinanc);		
					$finc->setIdHistorico($codhist);

					$dao = new FinancContasDAO();
					$dao->alterarhist($finc);

					$res = array();
                    array_push($res,array(
                        'msg'=>"Alterado com sucesso!",
                        'idfinanc'=>$codfinanc,
                        'tipo'=>1
					));
					
				}else{

					$res = array();
                    array_push($res,array(
                        'msg'=>"Codigo reduzido não existe!",
                        'idfinanc'=>$codfinanc,
                        'tipo'=>2
                    ));
				}

				echo json_encode($res);

			break;
			case 'buscaid':
				
				$dao = new FinancContasDAO();
				$vet = $dao->ProxIdRedu();
				$num = count($vet);
				$res = array();

				if($num > 0){

					$prox = $vet[0];

					$codigo  = $prox->getProxIdRedu();
					$proximo = $prox->getProxId();

					array_push($res,array(
						'idred'=>$codigo,
						'id'=>$proximo
					));
				}else{
					array_push($res,array(
						'idred'=>0,
						'id'=>0
					));
				}
				
				echo json_encode($res);

			break;
			case 'inserir':

				
				$id 	  = $_REQUEST['id'];
				$codreduz = $_REQUEST['codreduz'];
				$desc     = $_REQUEST['desc'];
				$codclass = $_REQUEST['codclass'];	
				$res 	  = array();

				$fc =  new FinancContas();

				$fc->setCodigo($id);		
				$fc->setReduzido($codreduz);
				$fc->setCodConClass($codclass);
				$fc->setDescricao($desc);

				$dao = new FinancContasDAO();
				$dao->inserirfinanc($fc);

			
				$daoh = new Helpers();
				echo $daoh->ajaxresponse("message",[
                    "type"=>"success",
					"message"=>"Caastrado con sucesso!",
					'dados'=>$_REQUEST
                ]);

			break;
			case 'gerar':

				
				$condicao   = array();
				if(isset($_REQUEST['ck'])){
					$codclassfica = $_REQUEST['codclassfica'];  
					
					$condicao[]    = " f.COD_CON_CLAS like '%{$codclassfica}%' ";

					
				}

				if(isset($_REQUEST['reduzini']) and !empty($_REQUEST['reduzini'])){

					$reduzini      =  $_REQUEST['reduzini'];	

					$condicao[]    = " f.REDUZIDO between '".$reduzini."' ";
					
				}
						
				if(isset($_REQUEST['reduzfim']) and !empty($_REQUEST['reduzfim'])){

					$reduzfim     =  $_REQUEST['reduzfim'];	

					$condicao[]    = " '".$reduzfim."' ";
				
				}

				$condicao[]    = " f.ANALI_SINTE = 'A' ";

				$where = '';
				if(count($condicao) > 0){		
					$where = ' where'.implode('AND',$condicao);			
				}

				$dao = new FinancContasDAO();				
				$vet = $dao->ListaFinancContasPsq($where); 
				$num = count($vet);				
				$string  = "";

				for($i = 0; $i < $num; $i++){
					
					$conta = $vet[$i];
					
					$cod 		 = $conta->getCodigo();
					$nom 		 = $conta->getDescricao();
					$centrocusto = $conta->getCentroCusto();
					$codconclas  = $conta->getCodConClass();
					$reduzido    = $conta->getReduzido();	
					if(!empty(trim($codconclas)) && !empty(trim($reduzido))){
						$string .= "{$reduzido};{$codconclas};A;{$nom}\n"; 
					}
				}
				
				$dao->save('../arquivo/contas.txt',$string);
				
				$result = array();

                array_push($result,array(
                    'arquivo'=>'../arquivo/contas.txt',
                    'tipo'=>'1'
                ));
				echo json_encode($result);
			break;
		}

	

	

	}

	

	//header('Location:'.$destino);

?>
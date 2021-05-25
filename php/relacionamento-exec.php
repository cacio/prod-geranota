<?php
	use NFePHP\NFe\Tools;
	use NFePHP\Common\Certificate;
	use NFePHP\NFe\Common\Standardize;
	require_once('../inc/inc.autoload.php');
	require_once('../php/geral_config.php');
	
	session_start();
	date_default_timezone_set('Etc/GMT+3');
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'box':
				
				$pathFile           = '../public/config.json';
				$configJson         = file_get_contents($pathFile);
				$installConfig      = json_decode($configJson);
				$arr                = file_get_contents($pathFile);

				$data 		= array();
				$condicao   = array();
				$condicao2  = array();
				$condicao3  = array();
				$tpEvento   = ''.$_REQUEST['manif'].'';
				$nSeqEvento = 1;	
				$xmsg		= "";
				if(isset($_GET['files']))
				{	
					$error = false;
					$files = array();
					$uploaddir = '../uploads/';
					foreach($_FILES as $file)
					{
						
						
						if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name']))){
							
							require_once '../sped-nfe/bootstrap.php';
							$pfxcontent = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
							$password   = "".$installConfig->senhacert."";
							
							$certificate = Certificate::readPfx($pfxcontent, $password);					

							$tools = new Tools($arr, $certificate);
							//só funciona para o modelo 55
							$tools->model('55');
							//este serviço somente opera em ambiente de produção
							$tools->setEnvironment(1);
							$arquivo = $uploaddir .$file['name'];
							
							if($xml =  simplexml_load_file($arquivo)){
								
								$chNFe = trim($xml->protNFe->infProt->chNFe);
								
								$responsemanifest = $tools->sefazManifesta($chNFe,$tpEvento,$xJust = '',$nSeqEvento = 1);
								$st 			  = new Standardize($responsemanifest);
								$stdRes 		  = $st->toStd();
								$arra 			  = $st->toArray();

								if($stdRes->cStat == 128){
									$files[] = $uploaddir .$file['name'];
								}else{
									$error = true;
									$xmsg  = $arra;
								}

							}

							
						}else{
							
							$error = true;
							$xmsg  = "Houve um erro ao carregar seus arquivos";
						}
						
											
						
					}
					$data = ($error) ? array('error' => $xmsg) : array('files' => $files);
				}else{
				
					foreach($_REQUEST['filenames'] as $arquivo){
						
					if($xml =  simplexml_load_file($arquivo)){
		
							$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);				
														
							$condicao2[]  = " m.numero_nota = '".$xml->NFe->infNFe->ide->nNF."' ";
							$condicao2[]  = " f.cnpj_cpf = '".$xml->NFe->infNFe->emit->CNPJ."' ";
							/*$condicao2[]  = " m.data_emissao = '".implode(".", array_reverse(explode("-", "".$dEmi[0]."")))."' ";*/
							
							$where2 = '';
							if(count($condicao2) > 0){							
								$where2 = ' where'.implode('AND',$condicao2);									
							}
													
							//verifica se existe a nota
							$daon = new NotasEntradaMDAO();
							$vetn = $daon->VerificaNotas($where2);
							$numn = count($vetn);
							
							if($numn == 0){
							
							//verificar se é para a empresa  
							$condicao3[]  = " n.cnpj = '".$xml->NFe->infNFe->dest->CNPJ."' ";
							$condicao3[]  = " n.ie = '".$xml->NFe->infNFe->dest->IE."' ";
													
							$where3 = '';
							if(count($condicao3) > 0){							
								$where3 = ' where'.implode('AND',$condicao3);									
							}
							
							$daonf = new Nf_EDAO();
							$vetnf = $daonf->VerificaEmitenteDaNota($where3);
							$numnf = count($vetnf);
							
							if($numnf >= 0){
							$cnpjcpfs	 = !empty($xml->NFe->infNFe->emit->CNPJ) ? $xml->NFe->infNFe->emit->CNPJ : $xml->NFe->infNFe->emit->CPF;
							$condicao[]  = " f.cnpj_cpf = '".$cnpjcpfs."' ";
							$condicao[]  = " f.inscrica_estadual = '".$xml->NFe->infNFe->emit->IE."' ";

							$where = '';
							if(count($condicao) > 0){							
								$where = ' where'.implode('AND',$condicao);									
							}
							
							$dao = new FornecedorDAO();
							$vet = $dao->VerificaFornecedor($where);
							$num = count($vet);
						
							
							if($num > 0){
								//existe -> pega os dados
										
								$for = $vet[0];
								
								$cod	  = $for->getCodigo();	
								$nome 	  = $dao->filter($for->getNome());
								$ende 	  = $dao->filter(str_replace(",", "", utf8_encode($for->getEndereco())));
								$esta 	  = $for->getEstado();
								$cid  	  = $dao->filter($for->getCidade());
								$barr 	  = $dao->filter($for->getBairro());
								$cep  	  = $for->getCep();
								$tel  	  = $for->getTelefone();
								$fax  	  = $for->getFax();
								$cont 	  = $for->getContato();
								$cnpjcpf  = $for->getCnpjCpf(); 
								$ins	  = $for->getIncricoesEstadual();
								$contactb = $for->getContaCtb();
								$placa	  = $for->getPlaca();
								$uf		  = $for->getUf();
								$antt	  = $for->getAntt();
								$pais	  = $for->getPais();
								$emil	  = $for->getEmail();
								$produtor = $for->getProdutor();
								$obs	  = $for->getObs();
								$tipo	  = $for->getTipo();
														
								array_push($data,array(
									'cod' => ''.$cod.'',
									'nome' => ''.$nome.'',
									'ende' => ''.$ende.'',
									'esta' => ''.$esta.'',
									'cid' => ''.$cid.'',
									'barr' => ''.$barr.'',
									'cep' => ''.$cep.'',
									'tel' => ''.$tel.'',
									'cnpjcpf' => ''.$cnpjcpf.'',
									'ins' => ''.$ins.'',
									'arquivo' => ''.$arquivo.'',
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'dtemis'=>''.implode(".", array_reverse(explode("-", "".$dEmi[0].""))).'',
									'cfop'=>''.$xml->NFe->infNFe->det->prod->CFOP.'',
									'dtentrada'=>''.implode(".", array_reverse(explode("-", "".$xml->NFe->infNFe->ide->dSaiEnt.""))).'',
									'vIPI'=>''.$xml->NFe->infNFe->total->ICMSTot->vIPI.'',
									'vFrete'=>''.$xml->NFe->infNFe->total->ICMSTot->vFrete.'',
									'vST'=>''.$xml->NFe->infNFe->total->ICMSTot->vST.'',
									'vOutro'=>''.$xml->NFe->infNFe->total->ICMSTot->vOutro.'',
									'vDesc'=>''.$xml->NFe->infNFe->total->ICMSTot->vDesc.'',
								));						
								
							foreach($xml->NFe->infNFe->det as $prod){	
								 
								$daor = new RelacionaProdutoDAO();
								//$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$cod);
								if(!empty($installConfig->relcomp)){
									if($installConfig->relcomp == 'N'){
										$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$cod);
									}else if($installConfig->relcomp == 'S'){
										$hash = "{$prod->prod->cProd}{$prod->prod->xProd}{$prod->prod->CFOP}";

										$vetr = $daor->verificaRelacionamentoHash(sha1($hash),$cod);

										if(count($vetr) == 0){
											$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$cod);	
										}

									}else{
										$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$cod);
									}
								}else{
									$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$cod);
								}

								$numr = count($vetr);
								
								if($numr > 0){
									$rel = $vetr[0];
									
									$idfornec  = $rel->getIdFornecedor();
									$idprod    = $rel->getIdProduto();
									$idprodrel = $rel->getIdProdutoRelacionado();
									$idrel     = $rel->getCodigo();
									//ProdutoFormulacao
									$daop =  new ProdutosDAO();
									$vetp = $daop->ProdutoFormulacao($idprodrel);
									$nump = count($vetp);
									
									if($nump > 0){
										$pro = $vetp[0];
										
										$prod_codigo    = $pro->getCodigo();
										$prod_descricao = $pro->getDescricao();
										
										
									}else{
										
										$prod_codigo 	= "";
										$prod_descricao = "";
										
									}
									
								 }else{
									
										$prod_codigo 	= "";
										$prod_descricao = "";
										$idrel			= "";
								 }
								 
								 $posicaoqtde = explode('Qtde:',$prod->infAdProd);
								 $ncabecaqtd  = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";	

								array_push($data,array(
									'cProd' => ''.$prod->prod->cProd.'',
									'xProd' => ''.$prod->prod->xProd.'',
									'NCM' => ''.$prod->prod->NCM.'',
									'CFOP' => ''.$prod->prod->CFOP.'',
									'uCom' => ''.$prod->prod->uCom.'',
									'qCom' => ''.$prod->prod->qCom.'',
									'vUnCom' => ''.$prod->prod->vUnCom.'',
									'vProd' => ''.$prod->prod->vProd.'',
									'uTrib' => ''.$prod->prod->uTrib.'',
									'qTrib' => ''.$prod->prod->qTrib.'',
									'vUnTrib' => ''.$prod->prod->vUnTrib.'',
									'indTot' => ''.$prod->prod->indTot.'',
									'relacionado' => ''.$prod_codigo.' '.$prod_descricao.'',
									'idrel' => ''.$idrel.'',
									'ncabecaqtd' => ''.$ncabecaqtd.'',
								));
								
								
							}
							
							}else{
								
								//não existe -> insere e pega os dados
								
								$proximo_id = $dao->ProximoId();
								$codprox    = $proximo_id[0];								
								$prox_id    = $codprox->getProximo_Id();
								$cnpjcpfs	 = !empty($xml->NFe->infNFe->emit->CNPJ) ? $xml->NFe->infNFe->emit->CNPJ : $xml->NFe->infNFe->emit->CPF;
								$for = new Fornecedor();
								
								$for->setCodigo($prox_id);
								$for->setNome(utf8_encode($xml->NFe->infNFe->emit->xNome));
								$for->setEndereco($xml->NFe->infNFe->emit->enderEmit->xLgr);
								$for->setEstado($xml->NFe->infNFe->emit->enderEmit->UF);
								$for->setCidade($xml->NFe->infNFe->emit->enderEmit->xMun);
								$for->setBairro($xml->NFe->infNFe->emit->enderEmit->xBairro);
								$for->setCep($xml->NFe->infNFe->emit->enderEmit->CEP);
								$for->setTelefone('');
								$for->setFax('');
								$for->setContato('');
								$for->setCnpjCpf($cnpjcpfs); 
								$for->setIncricoesEstadual($xml->NFe->infNFe->emit->IE);
								$for->setContaCtb('');
								$for->setPlaca('');
								$for->setUf('');
								$for->setAntt('');
								$for->setPais($xml->NFe->infNFe->emit->enderEmit->cPais);
								$for->setEmail($xml->NFe->infNFe->emit->email);
								$for->setProdutor('');
								$for->setObs('');
								$for->setTipo('1');
								
								$dao->inserir($for);
								
								array_push($data,array(
									'cod' => ''.$prox_id.'',
									'nome' => ''.$xml->NFe->infNFe->emit->xNome.'',
									'ende' => ''.$xml->NFe->infNFe->emit->enderEmit->xLgr.'',
									'esta' => ''.$xml->NFe->infNFe->emit->enderEmit->UF.'',
									'cid' => ''.$xml->NFe->infNFe->emit->enderEmit->xMun.'',
									'barr' => ''.$xml->NFe->infNFe->emit->enderEmit->xBairro.'',
									'cep' => ''.$xml->NFe->infNFe->emit->enderEmit->CEP.'',
									'tel' => '',
									'cnpjcpf' => ''.$cnpjcpfs.'',
									'ins' => ''.$xml->NFe->infNFe->emit->IE.'',
									'arquivo' => ''.$arquivo.'',
									'Numero'=>''.$xml->NFe->infNFe->ide->nNF.'',
									'dtemis'=>''.implode(".", array_reverse(explode("-", "".$dEmi[0].""))).'',
									'cfop'=>''.$xml->NFe->infNFe->det->prod->CFOP.'',
									'dtentrada'=>''.implode(".", array_reverse(explode("-", "".$xml->NFe->infNFe->ide->dSaiEnt.""))).'',
									'vIPI'=>''.$xml->NFe->infNFe->total->ICMSTot->vIPI.'',
									'vFrete'=>''.$xml->NFe->infNFe->total->ICMSTot->vFrete.'',
									'vST'=>''.$xml->NFe->infNFe->total->ICMSTot->vST.'',
									'vOutro'=>''.$xml->NFe->infNFe->total->ICMSTot->vOutro.'',
									'vDesc'=>''.$xml->NFe->infNFe->total->ICMSTot->vDesc.'',
								));						
								
							foreach($xml->NFe->infNFe->det as $prod){	
								
								$daor = new RelacionaProdutoDAO();
								if(!empty($installConfig->relcomp)){
									if($installConfig->relcomp == 'N'){
										$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$prox_id);
									}else if($installConfig->relcomp == 'S'){
										$hash = "{$prod->prod->cProd}{$prod->prod->xProd}{$prod->prod->CFOP}";

										$vetr = $daor->verificaRelacionamentoHash(sha1($hash),$prox_id);
										if(count($vetr) == 0){
											$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$prox_id);	
										}
									}else{
										$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$prox_id);
									}
								}else{
									$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$prox_id);
								}
								$numr = count($vetr);
								
								if($numr > 0){
									$rel = $vetr[0];
									
									$idfornec  = $rel->getIdFornecedor();
									$idprod    = $rel->getIdProduto();
									$idprodrel = $rel->getIdProdutoRelacionado();
									$idrel     = $rel->getCodigo();
									
									//ProdutoFormulacao
									$daop =  new ProdutosDAO();
									$vetp = $daop->ProdutoFormulacao($idprodrel);
									$nump = count($vetp);
									
									if($nump > 0){
										$pro = $vetp[0];
										
										$prod_codigo    = $pro->getCodigo();
										$prod_descricao = $pro->getDescricao();
										
										
									}else{
										
										$prod_codigo 	= "";
										$prod_descricao = "";
									
									}
									
								 }else{
									
										$prod_codigo 	= "";
										$prod_descricao = "";
										$idrel			= "";
								 }
								 
								 $posicaoqtde = explode('Qtde:',$prod->infAdProd);
								 $ncabecaqtd  = !empty($posicaoqtde[1])? preg_replace("/[^0-9]/", "", $posicaoqtde[1]) : "";

								array_push($data,array(
									'cProd' => ''.$prod->prod->cProd.'',
									'xProd' => ''.$prod->prod->xProd.'',
									'NCM' => ''.$prod->prod->NCM.'',
									'CFOP' => ''.$prod->prod->CFOP.'',
									'uCom' => ''.$prod->prod->uCom.'',
									'qCom' => ''.$prod->prod->qCom.'',
									'vUnCom' => ''.$prod->prod->vUnCom.'',
									'vProd' => ''.$prod->prod->vProd.'',
									'uTrib' => ''.$prod->prod->uTrib.'',
									'qTrib' => ''.$prod->prod->qTrib.'',
									'vUnTrib' => ''.$prod->prod->vUnTrib.'',
									'indTot' => ''.$prod->prod->indTot.'',									
									'relacionado' => ''.$prod_codigo.' '.$prod_descricao.'',
									'idrel' => ''.$idrel.'',
									'ncabecaqtd' => ''.$ncabecaqtd.'',
								));
								
								
							}
								
								
							
							}
						}else{
							
							array_push($data,array(
									'msgerro' => '2',									
								));
						
						}
						 }else{
							
							array_push($data,array(
									'msgerro' => '1',									
								));
								
							
						}//termino do if que verifica a nota
						 
						 }else{
							
							 $error = true;							 	
						}
					}
						
				}
				
				echo json_encode($data);
				
							
									
			break;
			case 'inserir':
				
				$item      = $_REQUEST['item'];										
				$arquivo   = $_REQUEST['arquivo'];
				$condicao  = array();
				$condicao2 = array();
				$qtdprod   = count($_REQUEST['item']);
				$resposta  = array();
				$condicao3 = array();
				
					
				$xml   =  simplexml_load_file($arquivo);				
				$dEmi  = explode('T',$xml->NFe->infNFe->ide->dhEmi);	
				
				$pathFile           = '../public/config.json';
				$configJson         = file_get_contents($pathFile);
				$installConfig      = json_decode($configJson);
				
				$pathFilebird       = '../public/configbird.json';
				$configJsonbird     = file_get_contents($pathFilebird);
				$installConfigbird  = json_decode($configJsonbird);

				$cnpjcpfs	  = !empty($xml->NFe->infNFe->emit->CNPJ) ? $xml->NFe->infNFe->emit->CNPJ : $xml->NFe->infNFe->emit->CPF;
				$condicao2[]  = " m.numero_nota = '".$xml->NFe->infNFe->ide->nNF."' ";
				$condicao2[]  = " f.cnpj_cpf = '".$cnpjcpfs."' ";
				$condicao2[]  = " m.data_emissao = '".implode(".", array_reverse(explode("-", "".$dEmi[0]."")))."' ";
				
				$where2 = '';
				if(count($condicao2) > 0){							
					$where2 = ' where'.implode('AND',$condicao2);									
				}
										
				//verifica se existe a nota
				$daon = new NotasEntradaMDAO();
				$vetn = $daon->VerificaNotas($where2);
				$numn = count($vetn);
				
				if($numn == 0){
				
				foreach($xml->NFe->infNFe->det as $det){
					
					$condicao3[]  = " f.cnpj_cpf = '".$cnpjcpfs."' ";
					$condicao3[]  = " f.inscrica_estadual = '".$xml->NFe->infNFe->emit->IE."' ";
					
					$where3 = '';
					if(count($condicao3) > 0){							
						$where3 = ' where'.implode('AND',$condicao3);									
					}
					
					$daof = new FornecedorDAO();
					$vetf = $daof->VerificaFornecedor($where3);
					$numf = count($vetf);
	
					if($numf > 0){
						
						$for	  = $vetf[0];
						$codf	  = $for->getCodigo();					
						
					}
					
					
					$daor = new RelacionaProdutoDAO();
					if(!empty($installConfig->relcomp)){
						if($installConfig->relcomp == 'N'){
							$vetr = $daor->verificaRelacionamento($det->prod->cProd,$codf);
						}else if($installConfig->relcomp == 'S'){
							$hash = "{$det->prod->cProd}{$det->prod->xProd}{$det->prod->CFOP}";
							$vetr = $daor->verificaRelacionamentoHash(sha1($hash),$codf);

							if(count($vetr) == 0){
								$vetr = $daor->verificaRelacionamento($det->prod->cProd,$codf);		
							}

						}else{
							$vetr = $daor->verificaRelacionamento($det->prod->cProd,$codf);	
						}
					}else{
						$vetr = $daor->verificaRelacionamento($det->prod->cProd,$codf);
					}
					
					$numr = count($vetr);
					if($numr > 0){
						$rel = $vetr[0];
												
						$QTDPUNS	   = $rel->getQtdPorUnidade();
						$vators        = $rel->getVator();
						
						$cProd 		   = $det->prod->cProd;
						$xProd 		   = $det->prod->xProd;
						
						if($vators == 1){
							$qCom  		   = floatval($det->prod->qCom) * $QTDPUNS;	
						}else{
							$qCom  		   = floatval($det->prod->qCom) / $QTDPUNS;
						}						
						
						$OrigqCom  	   = $det->prod->qCom;
						
						//$vProd		   = $det->prod->vProd;
						$vProd		   = !empty($xml->NFe->infNFe->total->ICMSTot->vProd) ? $xml->NFe->infNFe->total->ICMSTot->vProd : '0.00';
						if($vators == 1){
							$vUnCom		   = floatval($det->prod->vProd) / $qCom;
						}else{
							$vUnCom		   = floatval($det->prod->vProd) / $qCom;
						}
						
						
						$OrigvUnCom	   = floatval($det->prod->vUnCom);
						//$CFOP  		   = $item['CFOP'];
						$IDPROD_REL    = $rel->getIdProdutoRelacionado();
						$cfor		   = $rel->getIdFornecedor();
						$idcfop		   = $rel->getCfop();
						$NPEC_CX	   = !empty($rel->getNpcCx()) ? $rel->getNpcCx() :0;
												
						if($idcfop == ''){
							$formcfop   = $_REQUEST['cfop'];
						}else{
							$formcfop   = $idcfop;
						}
						
						$daonatu   = new CfopDAO();
						$vetnatu   = $daonatu->ListaCfopUm($formcfop);
						$numnatu   = count($vetnatu);
						if($numnatu > 0){
							$cfopnatu  	    = $vetnatu[0];
							$cfopcodigo 	= $cfopnatu->getCodigo();
							$cfopcodinteiro = $cfopnatu->getCodigoFiscal();
							
						}else{
							echo "Cfop não encontrada, verifique como esta no cadastro de cfop!";
							die();
						}
								
						
						$formdtentrada = $_REQUEST['dtentrada'];
					
					}else{
						echo "Não encontrei nem um relacionamento com esse codigo de fornecedor {$codf} e cnpj {$xml->NFe->infNFe->emit->CNPJ} verifique o relacionamento se não existe dois CNPJ cadastrado para o mesmo forncedor ou caso não resolva limpa os cache do navegador precionando o [CTRL + SHIFT + DEL] e  limpa os resentes e depois que limpar dar um F5 na pagina, caso nada disso funcionar acionar o suporte que junto com desenvolvimento irremos verificar o ocorrido, Obrigado! ";
						die();
					}
					   
					   
						
					$nNF     = $xml->NFe->infNFe->ide->nNF;		
					$serie   = $xml->NFe->infNFe->ide->serie;
					if(empty($_REQUEST['dtentrada'])){
						
						$dEmi         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
						$hS           = explode('-',$dEmi[1]);
								
						if(empty(strstr($dEmi[1],'-'))){
							$hS           = explode('+',$dEmi[1]);
						}	
						$hSaiEnt	  = $hS[0];
						$dEmi         = implode(".", array_reverse(explode("-", "".$dEmi[0]."")));
						
						
					}else{
						
						$dEmient	   = $_REQUEST['dtentrada'];
						
						$dEmi2         = explode('T',$xml->NFe->infNFe->ide->dhEmi);
						$dEmi          = $_REQUEST['dtentrada']; // Modificado para a data da Emissão : pedido jorge obs: para não precisar alterar relatorios do retaquardas "extrato do produto"
						$hS            = explode('-',$dEmi2[1]);	
						
						if(empty(strstr($dEmi2[1],'-'))){
							$hS           = explode('+',$dEmi2[1]);
						}
						
						$hSaiEnt	   = $hS[0];
					}
					
					
				
					
					
					
					//$dSaiEnt = implode(".", array_reverse(explode("-", "".$xml->NFe->infNFe->ide->dSaiEnt."")));
					//$hSaiEnt = $xml->NFe->infNFe->ide->hSaiEnt;	
					
					$vtotalFrete = !empty($xml->NFe->infNFe->total->ICMSTot->vFrete) ? $xml->NFe->infNFe->total->ICMSTot->vFrete : '0.00';
					$vFrete	 = !empty($det->prod->vFrete) ? $det->prod->vFrete : '0.00';										
					$vSeg    = !empty($det->prod->vSeg)   ? $det->prod->vSeg   : '0.00';
					$vOutro  = !empty($det->prod->vOutro) ? $det->prod->vOutro : '0.00';
					$uCom    = $det->prod->uCom;
					$infAdic = $xml->NFe->infNFe->infAdic->infCpl;
					
					$tFrete	 = !empty($xml->NFe->infNFe->transp->modFrete) ? $xml->NFe->infNFe->transp->modFrete : '0';
					
					$vIPI	 = !empty($det->imposto->IPI->IPITrib->vIPI) ? $det->imposto->IPI->IPITrib->vIPI : '0.00';
					$vBC	 = !empty($det->imposto->IPI->IPITrib->vBC) ? $det->imposto->IPI->IPITrib->vBC : '0.00';
					$pIPI	 = !empty($det->imposto->IPI->IPITrib->pIPI) ? $det->imposto->IPI->IPITrib->pIPI : '0.00';
															
					
					if(empty($_REQUEST['novovalortotnota'])){
						$vNF	 = !empty($xml->NFe->infNFe->total->ICMSTot->vNF) ? $xml->NFe->infNFe->total->ICMSTot->vNF : '0.00';
					
					}else{
						$vNF    = str_replace(',', '.', str_replace('.', '', $_REQUEST['novovalortotnota']));
					}
					
					
					
					
					//echo $vNF.'<br/>';
					$vBCs	  = !empty($xml->NFe->infNFe->total->ICMSTot->vBC) ? $xml->NFe->infNFe->total->ICMSTot->vBC : '0.00';
					$vICMS_to = !empty($xml->NFe->infNFe->total->ICMSTot->vICMS) ? $xml->NFe->infNFe->total->ICMSTot->vICMS : '0.00';
					$vBCST_to = !empty($xml->NFe->infNFe->total->ICMSTot->vBCST) ? $xml->NFe->infNFe->total->ICMSTot->vBCST : '0.00';
					$vST_to   = !empty($xml->NFe->infNFe->total->ICMSTot->vST) ? $xml->NFe->infNFe->total->ICMSTot->vST : '0.00';
					
					$qVol 	 = !empty($xml->NFe->infNFe->transp->vol->qVol) ? $xml->NFe->infNFe->transp->vol->qVol : 'NULL';	
					$esp     = $xml->NFe->infNFe->transp->vol->esp;	
					$pesoB	 = !empty($xml->NFe->infNFe->transp->vol->pesoB) ? $xml->NFe->infNFe->transp->vol->pesoB : 'NULL';
					$pesoL	 = !empty($xml->NFe->infNFe->transp->vol->pesoL) ? $xml->NFe->infNFe->transp->vol->pesoL : 'NULL';		
					
					
					$vDesc   = !empty($xml->NFe->infNFe->total->ICMSTot->vDesc) ? $xml->NFe->infNFe->total->ICMSTot->vDesc : '0.00';
					$vtotprod   = $det->prod->vProd;//!empty($xml->NFe->infNFe->total->ICMSTot->vProd) ? $xml->NFe->infNFe->total->ICMSTot->vProd : '0.00';
					
					$placa	 = $xml->NFe->infNFe->transp->veicTransp->placa;
					$UF		 = $xml->NFe->infNFe->transp->veicTransp->UF;
					
					$ICMS00    = $det->imposto->ICMS->ICMS00->CST;
					$ICMS10    = $det->imposto->ICMS->ICMS10->CST;
					$ICMS20    = $det->imposto->ICMS->ICMS20->CST;
					$ICMS30    = $det->imposto->ICMS->ICMS30->CST;
					$ICMS40    = $det->imposto->ICMS->ICMS40->CST;
					$ICMS41    = $det->imposto->ICMS->ICMS41->CST;
					$ICMS50    = $det->imposto->ICMS->ICMS50->CST;
					$ICMS51    = $det->imposto->ICMS->ICMS51->CST;
					$ICMS60    = $det->imposto->ICMS->ICMS60->CST;
					$ICMS70    = $det->imposto->ICMS->ICMS70->CST;
					$ICMS90    = $det->imposto->ICMS->ICMS90->CST;
					$ICMSST    = $det->imposto->ICMS->ICMSST;
					
					
					$ICMSSN101    = $det->imposto->ICMS->ICMSSN101->CSOSN;
					$ICMSSN102    = $det->imposto->ICMS->ICMSSN102->CSOSN;
					$ICMSSN202    = $det->imposto->ICMS->ICMSSN202->CSOSN;
					$ICMSSN201    = $det->imposto->ICMS->ICMSSN201->CSOSN;
					$ICMSSN500    = $det->imposto->ICMS->ICMSSN500->CSOSN;
					$ICMSSN900    = $det->imposto->ICMS->ICMSSN900->CSOSN;
					 
					if($ICMSSN101 == 101){
					
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN101->orig) ? $det->imposto->ICMS->ICMSSN101->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN101->CST) ? $det->imposto->ICMS->ICMSSN101->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN101->modBC) ? $det->imposto->ICMS->ICMSSN101->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN101->pRedBC) ? $det->imposto->ICMS->ICMSSN101->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN101->vBC) ? $det->imposto->ICMS->ICMSSN101->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN101->pICMS) ? $det->imposto->ICMS->ICMSSN101->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN101->vICMS) ? $det->imposto->ICMS->ICMSSN101->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN101->modBCST) ? $det->imposto->ICMS->ICMSSN101->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN101->pMVAST) ? $det->imposto->ICMS->ICMSSN101->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN101->pRedBCST) ? $det->imposto->ICMS->ICMSSN101->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN101->vBCST) ? $det->imposto->ICMS->ICMSSN101->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN101->pICMSST) ? $det->imposto->ICMS->ICMSSN101->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN101->vICMSST) ? $det->imposto->ICMS->ICMSSN101->vICMSST : 'NULL';	
						
					}
					if($ICMSSN201 == 201){
					
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN201->orig) ? $det->imposto->ICMS->ICMSSN201->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN201->CST) ? $det->imposto->ICMS->ICMSSN201->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN201->modBC) ? $det->imposto->ICMS->ICMSSN201->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN201->pRedBC) ? $det->imposto->ICMS->ICMSSN201->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN201->vBC) ? $det->imposto->ICMS->ICMSSN201->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN201->pICMS) ? $det->imposto->ICMS->ICMSSN201->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN201->vICMS) ? $det->imposto->ICMS->ICMSSN201->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN201->modBCST) ? $det->imposto->ICMS->ICMSSN201->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN201->pMVAST) ? $det->imposto->ICMS->ICMSSN201->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN201->pRedBCST) ? $det->imposto->ICMS->ICMSSN201->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN201->vBCST) ? $det->imposto->ICMS->ICMSSN201->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN201->pICMSST) ? $det->imposto->ICMS->ICMSSN201->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN201->vICMSST) ? $det->imposto->ICMS->ICMSSN201->vICMSST : 'NULL';	
						
					}
					
					if($ICMSSN102 == 102){
					
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN102->orig) ? $det->imposto->ICMS->ICMSSN102->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN102->CST) ? $det->imposto->ICMS->ICMSSN102->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN102->modBC) ? $det->imposto->ICMS->ICMSSN102->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN102->pRedBC) ? $det->imposto->ICMS->ICMSSN102->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN102->vBC) ? $det->imposto->ICMS->ICMSSN102->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN102->pICMS) ? $det->imposto->ICMS->ICMSSN102->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN102->vICMS) ? $det->imposto->ICMS->ICMSSN102->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN102->modBCST) ? $det->imposto->ICMS->ICMSSN102->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN102->pMVAST) ? $det->imposto->ICMS->ICMSSN102->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN102->pRedBCST) ? $det->imposto->ICMS->ICMSSN102->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN102->vBCST) ? $det->imposto->ICMS->ICMSSN102->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN102->pICMSST) ? $det->imposto->ICMS->ICMSSN102->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN102->vICMSST) ? $det->imposto->ICMS->ICMSSN102->vICMSST : 'NULL';	
						
					}else if($ICMSSN202 == 202){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN202->orig) ? $det->imposto->ICMS->ICMSSN202->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN202->CST) ? $det->imposto->ICMS->ICMSSN202->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN202->modBC) ? $det->imposto->ICMS->ICMSSN202->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN202->pRedBC) ? $det->imposto->ICMS->ICMSSN202->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN202->vBC) ? $det->imposto->ICMS->ICMSSN202->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN202->pICMS) ? $det->imposto->ICMS->ICMSSN202->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN202->vICMS) ? $det->imposto->ICMS->ICMSSN202->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN202->modBCST) ? $det->imposto->ICMS->ICMSSN202->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN202->pMVAST) ? $det->imposto->ICMS->ICMSSN202->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN202->pRedBCST) ? $det->imposto->ICMS->ICMSSN202->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN202->vBCST) ? $det->imposto->ICMS->ICMSSN202->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN202->pICMSST) ? $det->imposto->ICMS->ICMSSN202->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN202->vICMSST) ? $det->imposto->ICMS->ICMSSN202->vICMSST : 'NULL';
					
					}else if($ICMSSN202 == 203){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN203->orig) ? $det->imposto->ICMS->ICMSSN203->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN203->CST) ? $det->imposto->ICMS->ICMSSN203->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN203->modBC) ? $det->imposto->ICMS->ICMSSN203->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN203->pRedBC) ? $det->imposto->ICMS->ICMSSN203->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN203->vBC) ? $det->imposto->ICMS->ICMSSN203->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN203->pICMS) ? $det->imposto->ICMS->ICMSSN203->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN203->vICMS) ? $det->imposto->ICMS->ICMSSN203->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN203->modBCST) ? $det->imposto->ICMS->ICMSSN203->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN203->pMVAST) ? $det->imposto->ICMS->ICMSSN203->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN203->pRedBCST) ? $det->imposto->ICMS->ICMSSN203->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN203->vBCST) ? $det->imposto->ICMS->ICMSSN203->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN203->pICMSST) ? $det->imposto->ICMS->ICMSSN203->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN203->vICMSST) ? $det->imposto->ICMS->ICMSSN203->vICMSST : 'NULL';
					
					}else if($ICMSSN500 == 500){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN500->orig) ? $det->imposto->ICMS->ICMSSN500->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN500->CST) ? $det->imposto->ICMS->ICMSSN500->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN500->modBC) ? $det->imposto->ICMS->ICMSSN500->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN500->pRedBC) ? $det->imposto->ICMS->ICMSSN500->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN500->vBC) ? $det->imposto->ICMS->ICMSSN500->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN500->pICMS) ? $det->imposto->ICMS->ICMSSN500->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN500->vICMS) ? $det->imposto->ICMS->ICMSSN500->vICMS : 'NULL';	
						
						$modBCST    = !empty($det->imposto->ICMS->ICMSSN500->modBCST)    ? $det->imposto->ICMS->ICMSSN500->modBCST      : 'NULL';						
						$pMVAST	    = !empty($det->imposto->ICMS->ICMSSN500->pMVAST)     ? $det->imposto->ICMS->ICMSSN500->pMVAST 		: 'NULL';					
						$pRedBCST   = !empty($det->imposto->ICMS->ICMSSN500->pRedBCST)   ? $det->imposto->ICMS->ICMSSN500->pRedBCST 	: 'NULL';						
						$vBCSTRet   = !empty($det->imposto->ICMS->ICMSSN500->vBCSTRet)   ? $det->imposto->ICMS->ICMSSN500->vBCSTRet 	: 'NULL';
						$vBCST	    = !empty($det->imposto->ICMS->ICMSSN500->vBCST)      ? $det->imposto->ICMS->ICMSSN500->vBCST 		: $vBCSTRet;					
						$pICMSST    = !empty($det->imposto->ICMS->ICMSSN500->pICMSST)    ? $det->imposto->ICMS->ICMSSN500->pICMSST 		: 'NULL';						
						$vICMSSTRet = !empty($det->imposto->ICMS->ICMSSN500->vICMSSTRet) ? $det->imposto->ICMS->ICMSSN500->vICMSSTRet 	: 'NULL';
						$vICMSST    = !empty($det->imposto->ICMS->ICMSSN500->vICMSST)    ? $det->imposto->ICMS->ICMSSN500->vICMSST 		: $vICMSSTRet;

					}else if($ICMSSN900 == 900){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN900->orig) ? $det->imposto->ICMS->ICMSSN202->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN900->CST) ? $det->imposto->ICMS->ICMSSN900->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN900->modBC) ? $det->imposto->ICMS->ICMSSN900->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN900->pRedBC) ? $det->imposto->ICMS->ICMSSN900->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN900->vBC) ? $det->imposto->ICMS->ICMSSN900->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN900->pICMS) ? $det->imposto->ICMS->ICMSSN900->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN900->vICMS) ? $det->imposto->ICMS->ICMSSN900->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN900->modBCST) ? $det->imposto->ICMS->ICMSSN900->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN900->pMVAST) ? $det->imposto->ICMS->ICMSSN900->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN900->pRedBCST) ? $det->imposto->ICMS->ICMSSN900->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN900->vBCST) ? $det->imposto->ICMS->ICMSSN900->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN900->pICMSST) ? $det->imposto->ICMS->ICMSSN900->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN900->vICMSST) ? $det->imposto->ICMS->ICMSSN900->vICMSST : 'NULL';
						
					}else if($ICMSSN102 == 300){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN102->orig) ? $det->imposto->ICMS->ICMSSN102->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN102->CST) ? $det->imposto->ICMS->ICMSSN200->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN102->modBC) ? $det->imposto->ICMS->ICMSSN200->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN102->pRedBC) ? $det->imposto->ICMS->ICMSSN200->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN102->vBC) ? $det->imposto->ICMS->ICMSSN200->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN102->pICMS) ? $det->imposto->ICMS->ICMSSN200->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN102->vICMS) ? $det->imposto->ICMS->ICMSSN200->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN102->modBCST) ? $det->imposto->ICMS->ICMSSN200->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN102->pMVAST) ? $det->imposto->ICMS->ICMSSN200->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN102->pRedBCST) ? $det->imposto->ICMS->ICMSSN200->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN102->vBCST) ? $det->imposto->ICMS->ICMSSN200->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN102->pICMSST) ? $det->imposto->ICMS->ICMSSN200->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN102->vICMSST) ? $det->imposto->ICMS->ICMSSN200->vICMSST : 'NULL';
						
					}
					
					if($ICMS00 == "00"){
						
						//$det->imposto->ICMS->ICMS00->
						$orig 	   = !empty($det->imposto->ICMS->ICMS00->orig) ? $det->imposto->ICMS->ICMS00->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS00->CST) ? $det->imposto->ICMS->ICMS00->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS00->modBC) ? $det->imposto->ICMS->ICMS00->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS00->pRedBC) ? $det->imposto->ICMS->ICMS00->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS00->vBC) ? $det->imposto->ICMS->ICMS00->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS00->pICMS) ? $det->imposto->ICMS->ICMS00->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS00->vICMS) ? $det->imposto->ICMS->ICMS00->vICMS : 'NULL';	
										
						$modBCST   = !empty($det->imposto->ICMS->ICMS00->modBCST) ? $det->imposto->ICMS->ICMS00->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS00->pMVAST) ? $det->imposto->ICMS->ICMS00->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS00->pRedBCST) ? $det->imposto->ICMS->ICMS00->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS00->vBCST) ? $det->imposto->ICMS->ICMS00->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS00->pICMSST) ? $det->imposto->ICMS->ICMS00->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS00->vICMSST) ? $det->imposto->ICMS->ICMS00->vICMSST : 'NULL';						
						
					}else if($ICMS10 == "10"){
						
						//$det->imposto->ICMS->ICMS10->
						$orig 	   = !empty($det->imposto->ICMS->ICMS10->orig) ? $det->imposto->ICMS->ICMS10->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS10->CST) ? $det->imposto->ICMS->ICMS10->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS10->CST) ? $det->imposto->ICMS->ICMS10->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS10->pRedBC) ? $det->imposto->ICMS->ICMS10->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS10->vBC) ? $det->imposto->ICMS->ICMS10->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS10->pICMS) ? $det->imposto->ICMS->ICMS10->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS10->vICMS) ? $det->imposto->ICMS->ICMS10->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS10->modBCST) ? $det->imposto->ICMS->ICMS10->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS10->pMVAST) ? $det->imposto->ICMS->ICMS10->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS10->pRedBCST) ? $det->imposto->ICMS->ICMS10->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS10->vBCST) ? $det->imposto->ICMS->ICMS10->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS10->pICMSST) ? $det->imposto->ICMS->ICMS10->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS10->vICMSST) ? $det->imposto->ICMS->ICMS10->vICMSST : 'NULL';
					}else if($ICMS20 == "20"){
					
						//$det->imposto->ICMS->ICMS20->
						$orig 	   = !empty($det->imposto->ICMS->ICMS20->orig) ? $det->imposto->ICMS->ICMS20->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS20->CST) ? $det->imposto->ICMS->ICMS20->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS20->modBC) ? $det->imposto->ICMS->ICMS20->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS20->pRedBC) ? $det->imposto->ICMS->ICMS20->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS20->vBC) ? $det->imposto->ICMS->ICMS20->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS20->pICMS) ? $det->imposto->ICMS->ICMS20->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS20->vICMS) ? $det->imposto->ICMS->ICMS20->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS20->modBCST) ? $det->imposto->ICMS->ICMS20->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS20->pMVAST) ? $det->imposto->ICMS->ICMS20->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS20->pRedBCST) ? $det->imposto->ICMS->ICMS20->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS20->vBCST) ? $det->imposto->ICMS->ICMS20->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS20->pICMSST) ? $det->imposto->ICMS->ICMS20->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS20->vICMSST) ? $det->imposto->ICMS->ICMS20->vICMSST : 'NULL';
						
					}else if($ICMS30 == "30"){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMS30->orig) ? $det->imposto->ICMS->ICMS30->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS30->CST) ? $det->imposto->ICMS->ICMS30->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS30->modBC) ? $det->imposto->ICMS->ICMS30->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS30->pRedBC) ? $det->imposto->ICMS->ICMS30->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS30->vBC) ? $det->imposto->ICMS->ICMS30->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS30->pICMS) ? $det->imposto->ICMS->ICMS30->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS30->vICMS) ? $det->imposto->ICMS->ICMS30->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS30->modBCST) ? $det->imposto->ICMS->ICMS30->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS30->pMVAST) ? $det->imposto->ICMS->ICMS30->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS30->pRedBCST) ? $det->imposto->ICMS->ICMS30->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS30->vBCST) ? $det->imposto->ICMS->ICMS30->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS30->pICMSST) ? $det->imposto->ICMS->ICMS30->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS30->vICMSST) ? $det->imposto->ICMS->ICMS30->vICMSST : 'NULL';
						
					}else if($ICMS40 == "40"){
						
						//$det->imposto->ICMS->ICMS40->
											
						
						$orig 	   = !empty($det->imposto->ICMS->ICMS40->orig) ? $det->imposto->ICMS->ICMS40->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS40->CST) ? $det->imposto->ICMS->ICMS40->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS40->modBC) ? $det->imposto->ICMS->ICMS40->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS40->pRedBC) ? $det->imposto->ICMS->ICMS40->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS40->vBC) ? $det->imposto->ICMS->ICMS40->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS40->pICMS) ? $det->imposto->ICMS->ICMS40->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS40->vICMS) ? $det->imposto->ICMS->ICMS40->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS40->modBCST) ? $det->imposto->ICMS->ICMS40->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS40->pMVAST) ? $det->imposto->ICMS->ICMS40->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS40->pRedBCST) ? $det->imposto->ICMS->ICMS40->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS40->vBCST) ? $det->imposto->ICMS->ICMS40->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS40->pICMSST) ? $det->imposto->ICMS->ICMS40->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS40->vICMSST) ? $det->imposto->ICMS->ICMS40->vICMSST : 'NULL';
						
					}else if($ICMS51 == "51"){
						
						//$det->imposto->ICMS->ICMS51->
										
						
						$orig 	   = !empty($det->imposto->ICMS->ICMS51->orig) ? $det->imposto->ICMS->ICMS51->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS51->CST) ? $det->imposto->ICMS->ICMS51->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS51->modBC) ? $det->imposto->ICMS->ICMS51->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS51->pRedBC) ? $det->imposto->ICMS->ICMS51->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS51->vBC) ? $det->imposto->ICMS->ICMS51->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS51->pICMS) ? $det->imposto->ICMS->ICMS51->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS51->vICMS) ? $det->imposto->ICMS->ICMS51->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS51->modBCST) ? $det->imposto->ICMS->ICMS51->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS51->pMVAST) ? $det->imposto->ICMS->ICMS51->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS51->pRedBCST) ? $det->imposto->ICMS->ICMS51->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS51->vBCST) ? $det->imposto->ICMS->ICMS51->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS51->pICMSST) ? $det->imposto->ICMS->ICMS51->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS51->vICMSST) ? $det->imposto->ICMS->ICMS51->vICMSST : 'NULL';
						
						
					}else if($ICMS60 == "60"){
						
						//$det->imposto->ICMS->ICMS60->
						
						
						$orig 	  	 = !empty($det->imposto->ICMS->ICMS60->orig) 	   ? $det->imposto->ICMS->ICMS60->orig       : '0';
						$CST      	 = !empty($det->imposto->ICMS->ICMS60->CST) 	   ? $det->imposto->ICMS->ICMS60->CST        : '';						
						$modBC	  	 = !empty($det->imposto->ICMS->ICMS60->modBC) 	   ? $det->imposto->ICMS->ICMS60->modBC      : 'NULL';					
						$pRedBC	  	 = !empty($det->imposto->ICMS->ICMS60->pRedBC)	   ? $det->imposto->ICMS->ICMS60->pRedBC     : 'NULL';					
						$vBC	  	 = !empty($det->imposto->ICMS->ICMS60->vBC) 	   ? $det->imposto->ICMS->ICMS60->vBC 	     : 'NULL';					
						$pICMS	  	 = !empty($det->imposto->ICMS->ICMS60->pICMS) 	   ? $det->imposto->ICMS->ICMS60->pICMS      : 'NULL';					
						$vICMS	 	 = !empty($det->imposto->ICMS->ICMS60->vICMS) 	   ? $det->imposto->ICMS->ICMS60->vICMS      : 'NULL';					
						$modBCST 	 = !empty($det->imposto->ICMS->ICMS60->modBCST)    ? $det->imposto->ICMS->ICMS60->modBCST    : 'NULL';						
						$pMVAST	   	 = !empty($det->imposto->ICMS->ICMS60->pMVAST) 	   ? $det->imposto->ICMS->ICMS60->pMVAST     : 'NULL';					
						$pRedBCST  	 = !empty($det->imposto->ICMS->ICMS60->pRedBCST)   ? $det->imposto->ICMS->ICMS60->pRedBCST   : 'NULL';
						$vBCSTRet    = !empty($det->imposto->ICMS->ICMS60->vBCSTRet)   ? $det->imposto->ICMS->ICMS60->vBCSTRet   : 'NULL';						
						$vBCST	  	 = !empty($det->imposto->ICMS->ICMS60->vBCST) 	   ? $det->imposto->ICMS->ICMS60->vBCST      : $vBCSTRet;					
						$pICMSST   	 = !empty($det->imposto->ICMS->ICMS60->pICMSST)	   ? $det->imposto->ICMS->ICMS60->pICMSST    : 'NULL';						
						$vICMSSTRet  = !empty($det->imposto->ICMS->ICMS60->vICMSSTRet) ? $det->imposto->ICMS->ICMS60->vICMSSTRet : 'NULL';
						$vICMSST   	 = !empty($det->imposto->ICMS->ICMS60->vICMSST)    ? $det->imposto->ICMS->ICMS60->vICMSST    : $vICMSSTRet;						
						
							

					}else if($ICMS70 == "70"){						
						
						//$det->imposto->ICMS->ICMS70->vICMS						
												
						
						$orig 	   = !empty($det->imposto->ICMS->ICMS70->orig) ? $det->imposto->ICMS->ICMS70->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS70->CST) ? $det->imposto->ICMS->ICMS70->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS70->modBC) ? $det->imposto->ICMS->ICMS70->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS70->pRedBC) ? $det->imposto->ICMS->ICMS70->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS70->vBC) ? $det->imposto->ICMS->ICMS70->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS70->pICMS) ? $det->imposto->ICMS->ICMS70->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS70->vICMS) ? $det->imposto->ICMS->ICMS70->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS70->modBCST) ? $det->imposto->ICMS->ICMS70->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS70->pMVAST) ? $det->imposto->ICMS->ICMS70->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS70->pRedBCST) ? $det->imposto->ICMS->ICMS70->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS70->vBCST) ? $det->imposto->ICMS->ICMS70->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS70->pICMSST) ? $det->imposto->ICMS->ICMS70->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS70->vICMSST) ? $det->imposto->ICMS->ICMS70->vICMSST : 'NULL';
						
					}else if($ICMS90 == "90"){
				
						//$det->imposto->ICMS->ICMS90->
							
						$orig 	   = !empty($det->imposto->ICMS->ICMS90->orig) ? $det->imposto->ICMS->ICMS90->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS90->CST) ? $det->imposto->ICMS->ICMS90->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS90->modBC) ? $det->imposto->ICMS->ICMS90->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS90->pRedBC) ? $det->imposto->ICMS->ICMS90->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS90->vBC) ? $det->imposto->ICMS->ICMS90->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS90->pICMS) ? $det->imposto->ICMS->ICMS90->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS90->vICMS) ? $det->imposto->ICMS->ICMS90->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS90->modBCST) ? $det->imposto->ICMS->ICMS90->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS90->pMVAST) ? $det->imposto->ICMS->ICMS90->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS90->pRedBCST) ? $det->imposto->ICMS->ICMS90->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS90->vBCST) ? $det->imposto->ICMS->ICMS90->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS90->pICMSST) ? $det->imposto->ICMS->ICMS90->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS90->vICMSST) ? $det->imposto->ICMS->ICMS90->vICMSST : 'NULL';
						
					}else if($ICMS41 == "41"){
					
						$orig 	   = !empty($det->imposto->ICMS->ICMS41->orig) ? $det->imposto->ICMS->ICMS41->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS41->CST) ? $det->imposto->ICMS->ICMS41->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS41->modBC) ? $det->imposto->ICMS->ICMS41->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS41->pRedBC) ? $det->imposto->ICMS->ICMS41->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS41->vBC) ? $det->imposto->ICMS->ICMS41->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS41->pICMS) ? $det->imposto->ICMS->ICMS41->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS41->vICMS) ? $det->imposto->ICMS->ICMS41->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS41->modBCST) ? $det->imposto->ICMS->ICMS41->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS41->pMVAST) ? $det->imposto->ICMS->ICMS41->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS41->pRedBCST) ? $det->imposto->ICMS->ICMS41->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS41->vBCST) ? $det->imposto->ICMS->ICMS41->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS41->pICMSST) ? $det->imposto->ICMS->ICMS41->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS41->vICMSST) ? $det->imposto->ICMS->ICMS41->vICMSST : 'NULL';
					
					
					}else if($ICMS50 == "50"){
						
						$orig 	   = !empty($det->imposto->ICMS->ICMS50->orig) ? $det->imposto->ICMS->ICMS50->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS50->CST) ? $det->imposto->ICMS->ICMS50->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS50->modBC) ? $det->imposto->ICMS->ICMS50->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS50->pRedBC) ? $det->imposto->ICMS->ICMS50->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS50->vBC) ? $det->imposto->ICMS->ICMS50->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS50->pICMS) ? $det->imposto->ICMS->ICMS50->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS50->vICMS) ? $det->imposto->ICMS->ICMS50->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS50->modBCST) ? $det->imposto->ICMS->ICMS50->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS50->pMVAST) ? $det->imposto->ICMS->ICMS50->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS50->pRedBCST) ? $det->imposto->ICMS->ICMS50->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS50->vBCST) ? $det->imposto->ICMS->ICMS50->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS50->pICMSST) ? $det->imposto->ICMS->ICMS50->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS50->vICMSST) ? $det->imposto->ICMS->ICMS50->vICMSST : 'NULL';
						
					
					}
					
					if($ICMSSN102 == 103){
					
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN102->orig) ? $det->imposto->ICMS->ICMSSN102->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN102->CST) ? $det->imposto->ICMS->ICMSSN102->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN102->modBC) ? $det->imposto->ICMS->ICMSSN102->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN102->pRedBC) ? $det->imposto->ICMS->ICMSSN102->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN102->vBC) ? $det->imposto->ICMS->ICMSSN102->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN102->pICMS) ? $det->imposto->ICMS->ICMSSN102->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN102->vICMS) ? $det->imposto->ICMS->ICMSSN102->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN102->modBCST) ? $det->imposto->ICMS->ICMSSN102->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN102->pMVAST) ? $det->imposto->ICMS->ICMSSN102->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN102->pRedBCST) ? $det->imposto->ICMS->ICMSSN102->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN102->vBCST) ? $det->imposto->ICMS->ICMSSN102->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN102->pICMSST) ? $det->imposto->ICMS->ICMSSN102->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN102->vICMSST) ? $det->imposto->ICMS->ICMSSN102->vICMSST : 'NULL';	
						
					}
					
					if($ICMSSN102 == 400){
					
						$orig 	   = !empty($det->imposto->ICMS->ICMSSN102->orig) ? $det->imposto->ICMS->ICMSSN102->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMSSN102->CST) ? $det->imposto->ICMS->ICMSSN102->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMSSN102->modBC) ? $det->imposto->ICMS->ICMSSN102->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMSSN102->pRedBC) ? $det->imposto->ICMS->ICMSSN102->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMSSN102->vBC) ? $det->imposto->ICMS->ICMSSN102->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMSSN102->pICMS) ? $det->imposto->ICMS->ICMSSN102->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMSSN102->vICMS) ? $det->imposto->ICMS->ICMSSN102->vICMS : 'NULL';	
						
						$modBCST   = !empty($det->imposto->ICMS->ICMSSN102->modBCST) ? $det->imposto->ICMS->ICMSSN102->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMSSN102->pMVAST) ? $det->imposto->ICMS->ICMSSN102->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMSSN102->pRedBCST) ? $det->imposto->ICMS->ICMSSN102->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMSSN102->vBCST) ? $det->imposto->ICMS->ICMSSN102->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMSSN102->pICMSST) ? $det->imposto->ICMS->ICMSSN102->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMSSN102->vICMSST) ? $det->imposto->ICMS->ICMSSN102->vICMSST : 'NULL';	
						
					}
					
					
					if($ICMS40 == "41"){
						
						//$det->imposto->ICMS->ICMS40->
											
						
						$orig 	   = !empty($det->imposto->ICMS->ICMS40->orig) ? $det->imposto->ICMS->ICMS40->orig : '0';
						$CST       = !empty($det->imposto->ICMS->ICMS40->CST) ? $det->imposto->ICMS->ICMS40->CST : '';						
						$modBC	   = !empty($det->imposto->ICMS->ICMS40->modBC) ? $det->imposto->ICMS->ICMS40->modBC : 'NULL';					
						$pRedBC	   = !empty($det->imposto->ICMS->ICMS40->pRedBC) ? $det->imposto->ICMS->ICMS40->pRedBC : 'NULL';					
						$vBC	   = !empty($det->imposto->ICMS->ICMS40->vBC) ? $det->imposto->ICMS->ICMS40->vBC : 'NULL';					
						$pICMS	   = !empty($det->imposto->ICMS->ICMS40->pICMS) ? $det->imposto->ICMS->ICMS40->pICMS : 'NULL';					
						$vICMS	   = !empty($det->imposto->ICMS->ICMS40->vICMS) ? $det->imposto->ICMS->ICMS40->vICMS : 'NULL';					
						$modBCST   = !empty($det->imposto->ICMS->ICMS40->modBCST) ? $det->imposto->ICMS->ICMS40->modBCST : 'NULL';						
						$pMVAST	   = !empty($det->imposto->ICMS->ICMS40->pMVAST) ? $det->imposto->ICMS->ICMS40->pMVAST : 'NULL';					
						$pRedBCST  = !empty($det->imposto->ICMS->ICMS40->pRedBCST) ? $det->imposto->ICMS->ICMS40->pRedBCST : 'NULL';						
						$vBCST	   = !empty($det->imposto->ICMS->ICMS40->vBCST) ? $det->imposto->ICMS->ICMS40->vBCST : 'NULL';					
						$pICMSST   = !empty($det->imposto->ICMS->ICMS40->pICMSST) ? $det->imposto->ICMS->ICMS40->pICMSST : 'NULL';						
						$vICMSST   = !empty($det->imposto->ICMS->ICMS40->vICMSST) ? $det->imposto->ICMS->ICMS40->vICMSST : 'NULL';
						
					}
					
					
					if($ICMSST->CST == '60'){
						$orig 	  	 = !empty($ICMSST->orig) 	   ? $ICMSST->orig       : '0';
						$CST      	 = !empty($ICMSST->CST) 	   ? $ICMSST->CST        : '';						
						$modBC	  	 = !empty($ICMSST->modBC) 	   ? $ICMSST->modBC      : 'NULL';					
						$pRedBC	  	 = !empty($ICMSST->pRedBC)	   ? $ICMSST->pRedBC     : 'NULL';					
						$vBC	  	 = !empty($ICMSST->vBC) 	   ? $ICMSST->vBC 	     : 'NULL';					
						$pICMS	  	 = !empty($ICMSST->pICMS) 	   ? $ICMSST->pICMS      : 'NULL';					
						$vICMS	 	 = !empty($ICMSST->vICMS) 	   ? $ICMSST->vICMS      : 'NULL';					
						$modBCST 	 = !empty($ICMSST->modBCST)    ? $ICMSST->modBCST    : 'NULL';						
						$pMVAST	   	 = !empty($ICMSST->pMVAST) 	   ? $ICMSST->pMVAST     : 'NULL';					
						$pRedBCST  	 = !empty($ICMSST->pRedBCST)   ? $ICMSST->pRedBCST   : 'NULL';
						$vBCSTRet    = !empty($ICMSST->vBCSTRet)   ? $ICMSST->vBCSTRet   : 'NULL';						
						$vBCST	  	 = !empty($ICMSST->vBCST) 	   ? $ICMSST->vBCST      : $vBCSTRet;					
						$pICMSST   	 = !empty($ICMSST->pICMSST)	   ? $ICMSST->pICMSST    : 'NULL';						
						$vICMSSTRet  = !empty($ICMSST->vICMSSTRet) ? $ICMSST->vICMSSTRet : 'NULL';
						$vICMSST   	 = !empty($ICMSST->vICMSST)    ? $ICMSST->vICMSST    : $vICMSSTRet;
					}

					if(!empty($_REQUEST['vlguiast'])){
						if($cfopcodinteiro > 2000 and $cfopcodinteiro < 3000){

							if($CST == '10' or $CST == '30' or $CST == '60' or $CST == '70' or $CST == '20'){
								$vltotprod = 0;
								foreach($xml->NFe->infNFe->det as $detp){						
									$tag70 = "ICMS70";
									$tag60 = "ICMS60";
									$tag30 = "ICMS30";
									$tag10 = "ICMS10";
									$tag20 = "ICMS20";					
									
									if(!empty($detp->imposto->ICMS->{$tag70}) or !empty($detp->imposto->ICMS->{$tag60}) or !empty($detp->imposto->ICMS->{$tag30}) or !empty($detp->imposto->ICMS->{$tag10}) or !empty($detp->imposto->ICMS->{$tag20})){
										$vltotprod = $vltotprod + floatval($detp->prod->qCom);
									}
								}
																
								$vlguiast    = str_replace(',', '.', str_replace('.', '', $_REQUEST['vlguiast']));
								$fatorguiast = ($vlguiast / $vltotprod);								
								$vICMSST     = ($qCom * $fatorguiast);
								$vBCST       = ($vICMSST/0.12);

							}

						}
						
					}
							$daop =  new ProdutosDAO();
							$vetp = $daop->ProdutoFormulacao($IDPROD_REL);
							$nump = count($vetp);
							
							
							if($nump == 0){

								$msgr =  "Relacionamento de Produto - Vinculo anterior inválido<br/>";
								$daors = new RelacionaProdutoDAO();
								if(!empty($installConfig->relcomp)){
									if($installConfig->relcomp == 'N'){
										$vetreln = $daors->ListarRelacionamentoIncorretos($det->prod->cProd,$codf);
									}else if($installConfig->relcomp == 'S'){
										$hash = "{$det->prod->cProd}{$det->prod->xProd}{$det->prod->CFOP}";
										$vetreln = $daors->ListarRelacionamentoIncorretosHash(sha1($hash),$codf);
										if(count($vetreln) == 0){
											$vetreln = $daors->ListarRelacionamentoIncorretos($det->prod->cProd,$codf);	
										}
									}else{
										$vetreln = $daors->ListarRelacionamentoIncorretos($det->prod->cProd,$codf);
									}
								}else{
									$vetreln = $daors->ListarRelacionamentoIncorretos($det->prod->cProd,$codf);
								}
								


								$numreln = count($vetreln);
								if($numreln> 0){
									$msgr .= "Foram locaziados vinculos anteriores que devem ser limpos para novos relacionamento.<br/>";
									for ($r=0; $r < $numreln; $r++) { 
										$reln = $vetreln[$r];
										$id 	= $reln->getCodigo();
										$codigo = $reln->getIdProduto();
										$desc   = $reln->getProdDesc();	

										
										$msgr .= " (".($r+1).") - Codigo Relacionado:{$codigo} Descrição do produto: {$desc}<br/><br/>";
									}
									$msgr .= "<a href='#' class='btn-naousar btn btn-primary' data-id='".$det->prod->cProd."|".$codf."'> Clique AQUI!</a>, para limpar este(s) ralacionamento(s)<br/>";
								}
								echo "{$msgr}";
								die();	
							}else{
								$pro  = $vetp[0];
							}

							$precovenda = $pro->getPrecovenda();
							$promargem  = $pro->getMargem();			
							$ulticusto  = $pro->getUltimoCusto();
													
							if($installConfig->somtribcusto == 'S'){				
							
							$margem   = ($promargem / 100);												
							$vator    = ($vtotalFrete / $xml->NFe->infNFe->total->ICMSTot->vProd);
							
							$custo    = floatval($vUnCom * $vator) + $vUnCom;
							
							
							$vIPIunit    = (floatval($vIPI)    / floatval($det->prod->qCom));
							$vICMSSTunit = (floatval($vICMSST) / floatval($det->prod->qCom));							
						    $vCUSTO      = $custo + $vIPIunit + $vICMSSTunit ;	
							
							}else{							
								$vCUSTO   = 0;
							}
							
							if($installConfig->atualizaprecocusto == 'S'){
							
								$vvenda   =  round(($vCUSTO + ($vCUSTO * $margem)),1);
								
								$prv 	  =  round($vvenda - ($vvenda * ($pro->getPrecDescAv() / 100)),1) ;
								
								//echo 'idproduto :'.$IDPROD_REL.' Custo:'.$custo.' vcusto:'. $vCUSTO.' vvenda: '.$vvenda.' Precovenda:'.$prv.'\r';	
								
															
								$prod = new Produtos();
				
								$prod->setUltimoCusto(floatval($vCUSTO));																							
								$prod->setCodigo($IDPROD_REL);														
								$prod->setDataUltimoCusto($dEmient);		            
								$prod->setUltimoCodFornec($cfor);
								$prod->setPrecoMaximo($vvenda);
								$prod->setPrecovenda($vvenda);
								
								$daop->atualizacusto($prod);
							
							}
					
							if(!empty($xml->NFe->infNFe->transp->transporta->CNPJ)){

								$condicao[]  = " f.cnpj_cpf = '".$xml->NFe->infNFe->transp->transporta->CNPJ."' ";
								//$condicao[]  = " f.inscrica_estadual = '".$xml->NFe->infNFe->transp->transporta->IE."' ";

								$where = '';
								if(count($condicao) > 0){							
									$where = ' where'.implode('AND',$condicao);									
								}
								
								$dao = new FornecedorDAO();
								$vet = $dao->VerificaFornecedor($where);
								$num = count($vet);
								
								if($num > 0){
									//existe -> pega os dados
									
									$for = $vet[0];
									
									$cod	  = $for->getCodigo();	
									$nome 	  = $for->getNome();
									$ende 	  = $for->getEndereco();
									$esta 	  = $for->getEstado();
									$cid  	  = $for->getCidade();
									$barr 	  = $for->getBairro();
									$cep  	  = $for->getCep();
									$tel  	  = $for->getTelefone();
									$fax  	  = $for->getFax();
									$cont 	  = $for->getContato();
									$cnpjcpf  = $for->getCnpjCpf(); 
									$ins	  = $for->getIncricoesEstadual();
									$contactb = $for->getContaCtb();
									$placa	  = $for->getPlaca();
									$uf		  = $for->getUf();
									$antt	  = $for->getAntt();
									$pais	  = $for->getPais();
									$emil	  = $for->getEmail();
									$produtor = $for->getProdutor();
									$obs	  = $for->getObs();
									$tipo	  = $for->getTipo();
									
								}else{
									
									$proximo_id = $dao->ProximoId();
									$codprox    = $proximo_id[0];								
									$cod	    = $codprox->getProximo_Id();
									
									$for = new Fornecedor();
									
									$for->setCodigo($cod);
									$for->setNome(utf8_encode($xml->NFe->infNFe->transp->transporta->xNome));
									$for->setEndereco($xml->NFe->infNFe->transp->transporta->xEnder);
									$for->setEstado($xml->NFe->infNFe->transp->transporta->UF);
									$for->setCidade($xml->NFe->infNFe->transp->transporta->xMun);
									$for->setBairro('NULL');
									$for->setCep('NULL');
									$for->setTelefone('');
									$for->setFax('');
									$for->setContato('');
									$for->setCnpjCpf($xml->NFe->infNFe->transp->transporta->CNPJ); 
									$for->setIncricoesEstadual($xml->NFe->infNFe->transp->transporta->IE);
									$for->setContaCtb('');
									$for->setPlaca('');
									$for->setUf('');
									$for->setAntt('');
									$for->setPais('1058');
									$for->setEmail('NULL');
									$for->setProdutor('');
									$for->setObs('');
									$for->setTipo('1');
									
									$dao->inserir($for);
								
								
								}
								
							}else{
								$cod = $cfor;
							}
					// verifica se é formulacao boi casado							
					if($installConfigbird->FormulacaoBoiCasado == 'S'){

						$listformulacao = json_encode($installConfigbird->listformulacao,true);
						$lista          = json_decode($listformulacao);
							
						$daofun 		= new FuncoesDAO();
						$codboi 		= $daofun->pegaidboicasado('BOICASADO');
						
						if($IDPROD_REL == $codboi['ID']){
							foreach($lista as $key=>$val){

									$idp      = $val->ID;
									$nomep    = strtoupper(trim($val->DESC));
									$QTDPECAS =	$codboi['QTDPECAS'];
									$NPEC     = $NPEC_CX * $QTDPECAS;
									$PERC     = (floatval($val->PERC) / 100);
									$somperc  = ($PERC * floatval($det->prod->qCom));
									//echo "{$NPEC_CX} - ".$det->prod->qCom."<br/>";
									$entsai   = "E";
									//verificando a cfop se baixa estoque
									$daonat    = new CfopDAO();
									$vetnat   = $daonat->ListaCfopUm($formcfop);
									$cfopnat  = $vetnat[0];
									$baixaest = $cfopnat->getBaixaEst();
										
									if($baixaest == 'S'){
										if($nomep == 'BOICASADO'){
											//uma saida
											$entsai = 'S';
											$NPEC   = $NPEC_CX;
											$somperc= floatval($det->prod->qCom);
										}
									}else{
										if($nomep == 'BOICASADO'){
											//umaentrada
											$entsai = 'E';
										}	
									}

									if($entsai != 'E' or $nomep != 'BOICASADO'){
																				
										$req = new Requisicoes();
											
										$req->setData($dEmi);
										$req->setHora($hSaiEnt);
										$req->setProduto($idp);
										$req->setQuantidade($somperc);
										$req->setEntSai($entsai);
										$req->setTipoReq('WEB');
										$req->setNumero($nNF);
										$req->setValor($vNF);
										$req->setPecas($NPEC);
										$req->setJustificativa("Requisitado automatico pela entrada de notas");

										$daoreq = new RequisicoesDAO();
										$daoreq->inserir($req);
									}
								


							}
						}
					}
						

					$daon = new NotasEntradaMDAO();
					$vetn = $daon->ProximoId();
					$nem  = $vetn[0];
					$reg  = $nem->getProximoId();

					
					$ntem = new NotasEntradaM();
					
					$ntem->setEntradaSaida('E'); //ver
					$ntem->setNumeroNota($nNF);
					$ntem->setSerieNota($serie);
					$ntem->setDataEmissao($dEmi);
					$ntem->setCfop($cfopcodigo);//validar cfop com st e busca na tabela natura		
					$ntem->setDataEntrada($dEmient);
					$ntem->setCodigoFornecedor($cfor);
					$ntem->setHorario($hSaiEnt);
					$ntem->setValorFrete($vFrete); // FRETE COMPOS POR 
					$ntem->setValorSeguro($vSeg); // 
					$ntem->setOutrosDespecas($vOutro);
					$ntem->setValorIpi($vIPI); // PEGAR NO DETALHE 
					$ntem->setBaseCalculo($vBCs); // PEGAR NO DETALHE 
					$ntem->setValorIcm($vICMS_to);
					$ntem->setBaseCalculoSubs($vBCST_to); // PEGAR NO DETALHE 
					$ntem->setValorIcmSubs($vST_to); // PEGAR NO DETALHE 
					$ntem->setValorTotalProdutos($vProd); // PEGAR NO DETALHE 
					$ntem->setValortotalnota($vNF); //valor toral da nota pegar no total
					$ntem->setCodTransp($cod); // iqual fornecedor
					$ntem->setFrete($tFrete);// NO TOTAL
					$ntem->setPlaca($placa);
					$ntem->setUfPlaca($UF);
					$ntem->setQuantidadeRodape($qVol); //QVOL
					$ntem->setEspecie(substr($esp,0,10));//esp
					$ntem->setMarca('');//
					$ntem->setNumero('');
					$ntem->setPesoBruto($pesoB); //pesoB
					$ntem->setPesoLiquido($pesoL);//pesol
					$ntem->setValorDescontoAplicado($vDesc); // NOS TOTAL O vDesc
					$ntem->setProdCodigo($IDPROD_REL); // CODIGO PRODUTO
					$ntem->setProdCodFornecedor($cfor); // COD FORNECEDOR
					$ntem->setProdutoQuantidade($qCom); // QUANTIDADE DOS INTENS DO PRODUTO
					$ntem->setProdPrecoUnitario($vUnCom); // PRECO UNITARIO
					$ntem->setProdLiquotaIcms($pICMS); // pICMS	
					$ntem->setProdValorIcm($vICMS); // vICMS
					$ntem->setProdAliquotaIpi($pIPI); // IPI / pIPI
					$ntem->setProdValorIpi($vIPI); //// IPI / VIPI
					$ntem->setProdValorDesc($vDesc); // vDesc 
					$ntem->setProdUnidade(substr($uCom, -1,3)); // 
					$ntem->setProdSubTotal($vtotprod);
					$ntem->setProdIcmsSubst($vICMSST);
					$ntem->setReg('NULL');	
					$ntem->setCfopInteiro($cfopcodinteiro);
					$ntem->setAvistaAprazo(0);
					$ntem->setProdPecas($NPEC_CX);
					$ntem->setReboque('');	
					$ntem->setUfReboque('');
					$ntem->setNfeCst($CST);
					$ntem->setNfeOrig($orig);	
					$ntem->setNfeVbc($vBC);
					$ntem->setNfeVicms($vICMS);
					$ntem->setNfePredBc($pRedBC);
					$ntem->setNfeVbcst($vBCST);
					$ntem->setNfeVicmsSt($vICMSST);
					$ntem->setNfeNfAdFisco('');
					$ntem->setNfeInfCpl(str_replace("'", "", $infAdic)); 
					$ntem->setTipoNota(0);
					$ntem->setNfeGerado('');
					$ntem->setNfeAliqIcmss($pICMSST);
					$ntem->setAcrescerPauta('S');
					$ntem->setPrediCmssNfe('NULL');
					$ntem->setFundesa('NULL');
					$ntem->setVendedor('');
					$ntem->setNpVendedor('');
					$ntem->setPrazo1('NULL');
					$ntem->setPrazo2('NULL');
					$ntem->setPrazo3('NULL');
					$ntem->setPrazo4('NULL');
					$ntem->setPrazo5('NULL');	 		
					$ntem->setCondVendas('');
					$ntem->setTabelaPrecos('');
					$ntem->setCondPaga('');
					$ntem->setRegVendedor('NULL');
					$ntem->setEntrada1(0.00);
					$ntem->setDataEntrada1('NULL');
					$ntem->setBaseIpi(0.00);
					$ntem->setProdBaseIpi(0.00);	
					$ntem->setIndIpiAliqUnid('');
					$ntem->setNumeroCarga('');
					$ntem->setPedido('');		
					$ntem->setProdValorFrete($vFrete);
					$ntem->setProdValorSeguro($vSeg);	
					$ntem->setProdVlRoutDespecas('NULL');
					$ntem->setJageRoucReceber(''); 
					$ntem->setCodPrecoPrawer('');
					$ntem->setSituacao('');
					$ntem->setProdCaixas('NULL');
					$ntem->setEspessura(0.00);
					$ntem->setLargura(0.00);
					$ntem->setComprimento(0.00);
					$ntem->setPercDesc(0.00);
					$ntem->setNfeInfAdProd('');
					$ntem->setConsulate(0.00);
					$ntem->setPcredSn(0.00);
					$ntem->setVcredIcmssn($vICMS);
					$ntem->setCfopNota($cfopcodigo);
					$ntem->setHoraRegistro(date('H:i:s'));
					$ntem->setModBc($modBC);
					$ntem->setModBcSt($modBCST);
					$ntem->setReferencia('');
					$ntem->setManifesto('');
					$ntem->setPedidoLiberado('');
					$ntem->setQuantidadeNfeCompra($OrigqCom);
					$ntem->setVlrUnitNfeCompra($OrigvUnCom);
					
					$daon->inserir($ntem);
					
					}

					$daonsu  = new NfeNsuDAO();
					$vetnsu  = $daonsu->ListaNfeNumeroUm($xml->NFe->infNFe->ide->nNF,$_SESSION['idemp']);
					$numnsu  = count($vetnsu); 

					if($numnsu > 0){
						$nfensus   = $vetnsu[0];
					
						$cod_nsu 	   = $nfensus->getCodigo();                        
					
						$nseu = new NfeNsu();

						$nseu->setCodigo($cod_nsu);
						$nseu->setId_status(3);
						$nseu->setId_emp($_SESSION['idemp']);

						$daonsu->UpdateStatus($nseu);

					}
				$apropria =  !empty($installConfig->apropria) ? $installConfig->apropria : 'N';	
				//}//$xml->NFe->infNFe->ide->nNF
				array_push($resposta,array(
							'msg' => 'Inserido com sucesso!',
							'cd' => '1',
							'tipo'=>$apropria									
						));
				}else{
					array_push($resposta,array(
							'msg' => 'Nota ja existe !!',									
							'cd' => '2',
						));
				}
				echo json_encode($resposta);
				//echo "Inserido com sucesso";
			break;
			case 'relacionar':
				
				$idfornec  = $_REQUEST['idfor'];
				$idprod    = $_REQUEST['idpro'];
				$idprodrel = $_REQUEST['produto'];
				$id		   = $_REQUEST['id'];
				$nomepro   = $_REQUEST['nomepro'];				
				$vator	   = $_REQUEST['vator'];	
				$unforn    = substr($_REQUEST['unforn'], 0, 5);
				$unprod	   = $_REQUEST['unprod'];	
				$cfop      = $_REQUEST['cfopr'];	
				$cxpc      = !empty($_REQUEST['cxpc']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['cxpc'])) : '0';
				$cfopnota  = $_REQUEST['cfopnota'];
				$xProd     = $_REQUEST['xProd'];

				$hash      = "{$idprod}{$xProd}{$cfopnota}"; 

				if(empty($_REQUEST['qtdpun'])){
					$qtdpun = 1;
				}else{
					$qtdpun    = str_replace(',', '.',$_REQUEST['qtdpun']);
				}
				
				$rel = new RelacionaProduto();
			
				$rel->setIdFornecedor($idfornec);
				$rel->setIdProduto($idprod);
				$rel->setIdProdutoRelacionado($idprodrel);
				$rel->setUnidadeFornecedor($unforn);
				$rel->setUnidadeProduto($unprod);
				$rel->setQtdPorUnidade($qtdpun);
				$rel->setCfop($cfop);
				$rel->setNpcCx($cxpc);
				$rel->setVator($vator);	
				$rel->setHash(sha1($hash));

				$dao = new RelacionaProdutoDAO();
				
				$vet    = $dao->ProximoId();
				$rels   = $vet[0];
				$proxid = $rels->getProximoId();
				$_REQUEST['idrel'] = $proxid;
				
				$dao->inserir($rel);
				
				echo json_encode($_REQUEST);
				
			break;
			
			case 'altrelacionar':
				
				$idfornec  = $_REQUEST['idfor'];
				$idprod    = $_REQUEST['idpro'];
				$idprodrel = $_REQUEST['produto'];
				$id		   = $_REQUEST['id'];
				$idrel	   = $_REQUEST['idrel'];
				$nomepro   = $_REQUEST['nomepro'];				
				$vator	   = $_REQUEST['vator'];
				$unforn    = substr($_REQUEST['unforn'], 0, 5);
				$unprod	   = $_REQUEST['unprod'];	
				$cfop      = $_REQUEST['cfopr'];	
				$cxpc      = !empty($_REQUEST['cxpc']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['cxpc'])) : '0';
				if(empty($_REQUEST['qtdpun'])){
					$qtdpun = 1;
				}else{
					$qtdpun    = str_replace(',', '.',$_REQUEST['qtdpun']);
				}
				
				$rel = new RelacionaProduto();
				
				$rel->setCodigo($idrel);
				$rel->setIdFornecedor($idfornec);
				$rel->setIdProduto($idprod);
				$rel->setIdProdutoRelacionado($idprodrel);
				$rel->setUnidadeFornecedor($unforn);
				$rel->setUnidadeProduto($unprod);
				$rel->setQtdPorUnidade($qtdpun);
				$rel->setCfop($cfop);
				$rel->setNpcCx($cxpc);
				$rel->setVator($vator);

				$dao = new RelacionaProdutoDAO();
				$dao->alterar($rel);
				
				echo json_encode($_REQUEST);
				
			break;						
			
			case 'verificarelacionamento':
			
				$item     = $_REQUEST['item'];														
				$condicao = array();
				
				
				foreach($item as $key=>$value){
					
					$cProd 		   = $value['cProd'];
					$xProd 		   = $value['xProd'];
					$qCom  		   = $value['qCom'];
					$vProd		   = $value['vProd'];
					$CFOP  		   = $value['CFOP'];
					$cfor		   = $value['cfor'];
					
					
					if(empty($value['IDPROD_REL'])){
						
						array_push($condicao,array(
							'rel' => ''.$xProd.'',									
						));
						
					}
					
					
				}
				
				
				echo json_encode($condicao);
			
			break;
			
			case 'buscarelacionamento':
			
				$cod     = $_REQUEST['id'];														
				
				
				$dao = new RelacionaProdutoDAO();
				$vet = $dao->BuscaRelacionamento($cod);
				$num = count($vet);
				$condicao = array();
 				
				if($num > 0){

					$rel = $vet[0];
					
					$idfornec  = $rel->getIdFornecedor();
					$idprod    = $rel->getIdProduto();
					$idprodrel = $rel->getIdProdutoRelacionado();
					$QTDPUN    = $rel->getQtdPorUnidade();
					$id		   = $rel->getCodigo();
					$unpro	   = $rel->getUnidadeProduto();
					$desc	   = mb_convert_encoding($rel->getProdDesc(),'UTF-8');
					$idcfop    = $rel->getCfop();
					$cfopnome  = mb_convert_encoding($rel->getNomeCfop(),'UTF-8');
					$NPEC_CX   = $rel->getNpcCx();
					$vator     = $rel->getVator();

					array_push($condicao,array(
							'idfornec' => ''.$idfornec.'',
							'idprod' => ''.$idprod.'',
							'idprodrel' => ''.$idprodrel.'',
							'QTDPUN' => ''.$QTDPUN.'',
							'id' => ''.$id.'',		
							'unpro' => ''.$unpro.'',
							'desc' => ''.$desc.'',
							'idcfop' => ''.$idcfop.'',
							'cfopnome' => ''.$cfopnome.'',
							'NPEC_CX'=>''.$NPEC_CX.'',
							'vator'=>''.$vator.'',									
						));		
				
				}else{
					array_push($condicao,array(
						'idfornec' => '',
						'idprod' => '',
						'idprodrel' => '',
						'QTDPUN' => '',
						'id' => '',		
						'unpro' => '',
						'desc' => '',
						'idcfop' => '',
						'cfopnome' => '',
						'NPEC_CX'=>'',
						'vator'=>'',									
					));	
				}
				
			
  				echo json_encode($condicao);
			
			break;
			
			case 'verificavalor':
				
				
				$arquivo   = $_REQUEST['arquivo'];
				
				$xml =  simplexml_load_file($arquivo);
				
				$vProd		   = floatval($xml->NFe->infNFe->total->ICMSTot->vProd);
				$vST		   = floatval($xml->NFe->infNFe->total->ICMSTot->vST);
				$vIPI		   = floatval($xml->NFe->infNFe->total->ICMSTot->vIPI);
				$vDesc		   = floatval($xml->NFe->infNFe->total->ICMSTot->vDesc);
				$vFrete		   = floatval($xml->NFe->infNFe->total->ICMSTot->vFrete);
				$vOutro		   = floatval($xml->NFe->infNFe->total->ICMSTot->vOutro);
				$vNF		   = floatval($xml->NFe->infNFe->total->ICMSTot->vNF);
				
				$soma = $vProd + ($vST + $vIPI + $vFrete + $vOutro) - $vDesc;
				
				$compara1 = str_replace(',', '.', str_replace('.', '', $soma));
				$compara2 = str_replace(',', '.', str_replace('.', '', $vNF));		
				
				$condicao = array();
					
					
				if($compara1 != $compara2){
				
					array_push($condicao,array(
							'soma' => ''.$soma.'',
							'msg' => '1',
							'vNF' => ''.$vNF.'',
							'aviso' => "O valor total da NF diverge do calculado pelo sistema:\r\nValor NF: ".number_format(doubleval($vNF),2,',','.')."\r\nValor calculado: ".number_format(doubleval($soma),2,',','.')."\r\nDeseja confirmar o lançamento pelo valor da NF ?",
						));					
				}else{
					
					array_push($condicao,array(
						'soma' => ''.$soma.'',
						'msg' => '2',
						'vNF' => ''.$vNF.'',															
						'aviso' => '',
					));
					
				}
				
				echo json_encode($condicao);	
			break;
			case 'zip':
				
				$dao 		  = new Funcoes();
				$id 		  = $_REQUEST['id'];
				$files_to_zip = array();
				$arr 		  = array();			
				$arr['msg']   = '';
				
				foreach($id as $value){	
					
					$files_to_zip[] = "../uploads/".$value;					
				}
				$date = date('dmY_His');
				
				$result     = $dao->create_zip($files_to_zip,'../'.$date.'.zip');
				$arr['url'] = '../'.$date.'.zip';
				echo  json_encode($arr);
				
			break;
			case 'teste2':					
				$arquivo   = '../uploads/43201100027344401053559200000166491000174270-procNFe.xml';
				
				$xml =  simplexml_load_file($arquivo);
				$vltotprod = 0;
				foreach($xml->NFe->infNFe->det as $detp){						
					$tag70 = "ICMS70";
					$tag60 = "ICMS60";
					$tag30 = "ICMS30";
					$tag10 = "ICMS10";					
					
					if(!empty($detp->imposto->ICMS->{$tag70}) or !empty($detp->imposto->ICMS->{$tag60}) or !empty($detp->imposto->ICMS->{$tag30}) or !empty($detp->imposto->ICMS->{$tag10})){
						$vltotprod = $vltotprod + floatval($detp->prod->qCom);
					}
				}
				

			break;	
		}

	

	

	}

	

?>
<?php

	//use NFePHP\NFe\ToolsNFe;
	use NFePHP\NFe\Tools;
	use NFePHP\Common\Certificate;
	use NFePHP\NFe\Common\Standardize;

	require_once('../inc/inc.autoload.php');
	//require_once('../config/config.php');
	session_start();
	//error_reporting(0);
	//ini_set("display_errors", 0 );
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){
						
			case 'sefaz':
				
				$pathFilebird       = '../public/configbird.json';
				$configJsonbird     = file_get_contents($pathFilebird);
				$installConfigbird  = json_decode($configJsonbird);
				
					$dbateste = new DbAdmin('firebird');
					$dbcon = $dbateste->connect($installConfigbird->firebird->hostbird,$installConfigbird->firebird->userbird,$installConfigbird->firebird->senhabird,'');						
					
					if(!$dbcon){
					require_once '../sped-nfe/bootstrap.php';
					$error  	   = false;
					$msgerr 	   = "";
					$files  	   = array();
					$data   	   = array();				
					$uploaddir     = '../uploads/';				
									
					$chNFe 		   = ''.$_REQUEST['file_upload'].'';									
					$xJust 		   = '';
					$tpEvento 	   = ''.$_REQUEST['manif'].'';
					$nSeqEvento    = 1;
					$pathFile      = '../public/config.json';
					$arr           = file_get_contents($pathFile);
					$installConfig = json_decode($arr);
													
						if(!file_exists($uploaddir."$chNFe-procNFe.xml")){															
						
							try {
													
								$pfxcontent = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
								$password   = "".$installConfig->senhacert."";
								
								$certificate = Certificate::readPfx($pfxcontent, $password);					

								$tools = new Tools($arr, $certificate);
								//só funciona para o modelo 55
								$tools->model('55');
								//este serviço somente opera em ambiente de produção
								$tools->setEnvironment(1);
								$chave = trim($chNFe);
								
								$responsemanifest = $tools->sefazManifesta($chNFe,$tpEvento,$xJust = '',$nSeqEvento = 1);
								$st 			  = new Standardize($responsemanifest);
								$stdRes 		  = $st->toStd();
								/*$arra = $st->toArray();
								echo "<pre>";
								print_r($arra);
								echo "</pre>";*/
								if($stdRes->cStat == 128){
									
									$response = $tools->sefazDownload($chave);				
									$stz = new Standardize($response);
									$std = $stz->toStd();
									/*$arra = $stz->toArray();
									
									echo "<pre>";
									print_r($arra);
									echo "</pre>";*/
									if($std->cStat == 138){
										

										$file = $uploaddir."$chNFe-procNFe.xml";
										
										$zip    = $std->loteDistDFeInt->docZip;
										$xml    = gzdecode(base64_decode($zip));
										$xmlget = simplexml_load_string($xml);

										if(!empty($xmlget->NFe->infNFe->ide->nNF)){
											file_put_contents($file, $xml);
											$files[] = $file;
										}else{
											$msgerr = " Documento já manifestado! Aguardando a liberação para download no Ambiente Nacional.<br> Para obter o XML do documento imediatamente, faça o download clicando <a href='http://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConteudo=XbSeqxE8pl8=&tipoConsulta=completa&nfe={$chave}&'>Aqui<a/> ";	
										}


									}else{
										if($std->cStat == 137){
											$msgerr = " Documento já manifestado! Aguardando a liberação para download no Ambiente Nacional.<br> Para obter o XML do documento imediatamente, faça o download clicando <a href='http://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConteudo=XbSeqxE8pl8=&tipoConsulta=completa&nfe={$chave}&' style='color: blue;'>Aqui<a/> ";
										}else{	
											$msgerr = " Download [{$std->cStat}] {$std->xMotivo}";
										}
										$error = true;

									}//aa
								}else{
									$msgerr = " Erro Manifesto: [{$stdRes->cStat}] {$stdRes->xMotivo}";
									$error = true;
								}
								
								
							} catch (\Exception $e) {
								$msgerr = "Exception: ".str_replace("\n", "<br/>", $e->getMessage());
								
								if($e->getCode() ==  500){
										$msgerr = "Não ouve comunicação com a sefaz, espera alguns minutos e tente novamente!";
								}else if($e->getCode() == 2){
									$msgerr = "Não ouve comunicação com a sefaz, espera alguns minutos e tente novamente!";
								}else if($e->getCode() == 3){
									$msgerr = "Não ouve comunicação com a sefaz, espera alguns minutos e tente novamente!";
								}else if($e->getCode() == 0){
									$msgerr = "Erro de certificado verifique a senha correta ou se o certificado foi passado!<br/> {$e->getMessage()} ";	
								}
								
								$error = true;
							}
						}else{
							$files[] = $uploaddir."$chNFe-procNFe.xml";
						}
						
					}else{
						$msgerr = "Exception: ".$dbcon;
						$error = true;
					}
					
				$data = ($error) ? array('error' => ''.$msgerr.'') : array('files' => $files);						
				
				//echo htmlspecialchars($nfe->soapDebug);
				echo json_encode($data);
			
			break;
			case 'box';

			$data 		= array();
			$condicao   = array();
			$condicao2  = array();
			$condicao3  = array();
			
			$pathFile           = '../public/config.json';
			$configJson         = file_get_contents($pathFile);
			$installConfig      = json_decode($configJson);

			$arquivo = $_REQUEST['filenames'];
						
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
								$ende 	  = $dao->filter(substr_replace(",", "", utf8_encode($for->getEndereco())));
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
								
								$cnpjcpf = !empty($xml->NFe->infNFe->emit->CNPJ) ? $xml->NFe->infNFe->emit->CNPJ : $xml->NFe->infNFe->emit->CPF;

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
								$for->setCnpjCpf($cnpjcpf); 
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
									'cnpjcpf' => ''.$cnpjcpf.'',
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
								//$vetr = $daor->verificaRelacionamento($prod->prod->cProd,$prox_id);								
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
				/*<!--echo "<pre>";	
				print_r($data);	-->*/
				echo json_encode($data);
			break;
			case 'testes':
				
				require_once '../sped-nfe/bootstrap.php';
				$error  	= false;
				$msgerr 	= "";
				$files  	= array();
				$data   	= array();				
				$uploaddir  = '../uploads/';				
								
				$chNFe 		= ''.$_REQUEST['file_upload'].'';									
				$xJust 		= '';
				$tpEvento 	= '210210';
				$nSeqEvento = 1;
				$pathFile   = '../public/config.json';
				$arr        = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				try {
										
					$pfxcontent = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
					$password   = "".$installConfig->senhacert."";
										
					$tools = new Tools($arr, Certificate::readPfx($pfxcontent, $password));
					//só funciona para o modelo 55
					$tools->model('55');
					//este serviço somente opera em ambiente de produção
					$tools->setEnvironment(1);
					$chave = trim($chNFe);
					
							
					$response = $tools->sefazDownload($chave);				
					$stz = new Standardize($response);
					$std = $stz->toStd();
					
					echo "<pre>";
					$arra = $stz->toArray();
					print_r($arra);
					
					if($std->cStat == 138){


						$file = $uploaddir."$chNFe-procNFe.xml";

						$zip = $std->loteDistDFeInt->docZip;
						$xml = gzdecode(base64_decode($zip));

						file_put_contents($file, $xml);

						//header('Content-type: text/xml; charset=UTF-8');
						//echo $xml;						

						$files[] = $file;


					}else{

						$msgerr = " Download [{$std->cStat}] {$std->xMotivo}";
						$error = true;

					}//aa
					
					
					
				} catch (\Exception $e) {
					$msgerr = "Exception: ".str_replace("\n", "<br/>", $e->getMessage());
					$error = true;
				}
				
				$data = ($error) ? array('error' => ''.$msgerr.'') : array('files' => $files);						
				
				//echo htmlspecialchars($nfe->soapDebug);
				echo json_encode($data);
				
			break;
			case 'teste2':
				require_once '../sped-nfe/bootstrap.php';
				$error  	= false;
				$msgerr 	= "";
				$files  	= array();
				$data   	= array();				
				$uploaddir  = '../uploads/';				
								
				$chNFe 		= ''.$_REQUEST['file_upload'].'';									
				$xJust 		= '';
				$tpEvento 	= '210210';
				$nSeqEvento = 1;
				$pathFile   = '../public/config.json';
				$arr        = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				try {
										
					$pfxcontent = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
					$password   = "".$installConfig->senhacert."";
										
					$tools = new Tools($arr, Certificate::readPfx($pfxcontent, $password));
					//só funciona para o modelo 55
					$tools->model('55');
					//este serviço somente opera em ambiente de produção
					$tools->setEnvironment(1);
					$chave = trim($chNFe);
					
					$responsemanifest = $tools->sefazManifesta($chNFe,$tpEvento,$xJust = '',$nSeqEvento = 1);
					$st 			  = new Standardize($responsemanifest);
					$stdRes 		  = $st->toStd();
					
					echo "<pre>";
					$arra = $st->toArray();
					print_r($arra);
					
					
					
				} catch (\Exception $e) {
					$msgerr = "Exception: ".str_replace("\n", "<br/>", $e->getMessage());
					$error = true;
				}
				
				$data = ($error) ? array('error' => ''.$msgerr.'') : array('files' => $files);						
				
				//echo htmlspecialchars($nfe->soapDebug);
				echo json_encode($data);
				
			break;
			case 'vai':
				try {
					require_once '../sped-nfe/bootstrap.php';
					$pathFile   = '../public/config.json';
					$arr        = file_get_contents($pathFile);
					$installConfig = json_decode($arr);
					
					$pfxcontent = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
					$password   = "".$installConfig->senhacert."";
										
					$tools = new Tools($arr, Certificate::readPfx($pfxcontent, $password));
					$tools->model('55');

					$chave = '43181026410231000167550010000043301008884821';
					$response = $tools->sefazConsultaChave($chave);

					//você pode padronizar os dados de retorno atraves da classe abaixo
					//de forma a facilitar a extração dos dados do XML
					//NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
					//      quando houver a necessidade de protocolos
					$stdCl = new Standardize($response);
					//nesse caso $std irá conter uma representação em stdClass do XML
					$std = $stdCl->toStd();
					//nesse caso o $arr irá conter uma representação em array do XML
					$arr = $stdCl->toArray();
					//nesse caso o $json irá conter uma representação em JSON do XML
					$json = $stdCl->toJson();
						
					print_r($arr);
					
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
				
			break;
			case 'BuscaNsu':
				
				$result = array();
				$erro	= false;
				$msg    = "";


				/*$nsu = new Nsu();
				$dao = new NsuDAO();
				$vet = $dao->BuscaNsu($_SESSION['idemp']); 
				$num = count($vet);
				
				if($num > 0){*/
				//	$nsus    = $vet[0];
					//$cod 	= $nsus->getCodigo();
				//	$ultNSU = $nsus->getUltNsu();
				//	$maxNSU = $nsus->getMaxNsu();					
			/*	}else{*/
					$cod    = 0;
					$ultNSU = 10093;
					$maxNSU = $ultNSU;
				//}
				echo $maxNSU;
				
				require_once '../sped-nfe/bootstrap.php';
				
				try {

				$loopLimit 	   = 100;
				$iCount    	   = 0;
				$uploaddir     = '../uploads/';
				$pathFile      = '../public/config.json';
				$arr           = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				
				$pfxcontent    = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
				$password      = "".$installConfig->senhacert."";									
				$tools         = new Tools($arr, Certificate::readPfx($pfxcontent, $password));
				
				$tools->model('55');
				$tools->setEnvironment(1);
				
				
				while ($ultNSU <= $maxNSU) {
						
						$iCount++;
						/*if ($iCount >= $loopLimit) {
							$erro = false;
							break;
						}*/
						try {
							//executa a busca pelos documentos
							$resp = $tools->sefazDistDFe($ultNSU);
						} catch (\Exception $e) {
							$msg = $e->getMessage();
							$erro = true;
							break;
						}
						print_r($resp);
						//extrair e salvar os retornos
						$dom = new \DOMDocument();
						$dom->loadXML($resp);
						$node     = $dom->getElementsByTagName('retDistDFeInt')->item(0);
						$tpAmb    = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
						$verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
						$cStat    = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
						$xMotivo  = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
						$dhResp   = $node->getElementsByTagName('dhResp')->item(0)->nodeValue;
						$ultNSU   = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
						$maxNSU   = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;
						$lote 	  = $node->getElementsByTagName('loteDistDFeInt')->item(0);
												
						//echo "Ultimo NSU {$ultNSU}\n";
						//echo "MAX NSU {$maxNSU}\n";
						
						if(!empty($ultNSU) or !empty($maxNSU)){
							if($ultNSU != 0 or $maxNSU != 0){
								//$nsu->setUltNsu($ultNSU);
								//$nsu->setMaxNsu($maxNSU);
								//$nsu->setData(date('Y-m-d'));
								//$nsu->setIdEmp($_SESSION['idemp']);
								//$dao->Inserir($nsu);
							}
						}
						
						
						if (empty($lote)) {
							//lote vazio
							$erro = false;
							break;
						}
						//essas tags irão conter os documentos zipados
						$docs = $lote->getElementsByTagName('docZip');
						echo "<pre>";
						print_r($docs);
						foreach ($docs as $doc) {
							$numnsu = $doc->getAttribute('NSU');
							$schema = $doc->getAttribute('schema');
							//descompacta o documento e recupera o XML original
							$content = gzdecode(base64_decode($doc->nodeValue));
							//identifica o tipo de documento
							$tipo = substr($schema, 0, 6);
							//processar o conteudo do NSU, da forma que melhor lhe interessar
							//esse processamento depende do seu aplicativo
							//$xmlget = simplexml_load_string($content);
							//print_r($xmlget);
							
							/*if(!empty($xmlget->protNFe->infProt->chNFe)){
								$chNFe  = $xmlget->protNFe->infProt->chNFe;
								$file = $uploaddir."$chNFe-procNFe.xml";
								file_put_contents($file, $content);
							}*//*else{
								$chNFe  = $xmlget->chNFe;
								$file = $uploaddir."$chNFe-resNFe.xml";
								file_put_contents($file, $content);
							}*/
														
							
						}
						sleep(2);
					}
					
					}catch (\Exception $e) {
						
						$msg  = "Exception: ".str_replace("\n", "<br/>", $e->getMessage());
						$erro = true;
					}

					$data = ($erro) ? array('msg' => ''.$msg.'','tipo'=>'2') : array('msg' => 'Analise finalizada com sucesso!','tipo'=>'1');						
								
					echo json_encode($data);
				
			break;
			case 'listapornsu':


				$nsu     = new Nsu();
				$dao     = new NsuDAO();
				$daonf   = new NfeNsuDAO();
				$nfensus = new NfeNsu();

				require_once '../sped-nfe/bootstrap.php';

				$pathFile      = '../public/config.json';
				$arr           = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				
				$pfxcontent    = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
				$password      = "".$installConfig->senhacert."";									
				$tools         = new Tools($arr, Certificate::readPfx($pfxcontent, $password));
				
				$tools->model('55');
				$tools->setEnvironment(1);

				$vet = $dao->BuscaNsu($_SESSION['idemp']); 
				$num = count($vet);
				
				if($num > 0){
					$nsus    = $vet[0];					
					$ultNSU  = $nsus->getUltNsu();
					$maxNSU  = $nsus->getMaxNsu();					
				}else{				
					$ultNSU = 0;
					$maxNSU = $ultNSU;
				}

				$uploaddir = '../uploads/';				
				$loopLimit = 50;
				$iCount    = 0;
				$xnsu	   = "";
				$result    = array();
				$erro	   = false;	
				$msg       = "";
				//executa a busca de DFe em loop
				while ($ultNSU <= $maxNSU) {
					
					$iCount++;

					if ($iCount >= $loopLimit) {
						break;
					}

					try {
						//executa a busca pelos documentos
						$resp = $tools->sefazDistDFe($ultNSU);
					} catch (\Exception $e) {						
						$msg  = "Exception: ".str_replace("\n", "<br/>", $e->getMessage());
						$erro = true;
						break;
					}
				
					if(empty($resp)){
						break;
					}
				
					//extrair e salvar os retornos
					$dom = new \DOMDocument();
					$dom->loadXML($resp);

					$node     = $dom->getElementsByTagName('retDistDFeInt')->item(0);
					$tpAmb    = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
					$verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
					$cStat    = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
					$xMotivo  = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
					$dhResp   = $node->getElementsByTagName('dhResp')->item(0)->nodeValue;
					$ultNSU   = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
					$maxNSU   = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;
					$lote 	  = $node->getElementsByTagName('loteDistDFeInt')->item(0);


					if(!empty($ultNSU) or !empty($maxNSU)){
						if($ultNSU != 0 or $maxNSU != 0){

							if($maxNSU != $xnsu){
								$xnsu = $maxNSU;

								$nsu->setUltNsu($ultNSU);
								$nsu->setMaxNsu($maxNSU);
								$nsu->setData(date('Y-m-d'));
								$nsu->setIdEmp($_SESSION['idemp']);
								
								$dao->Inserir($nsu);


							}

						}
					}

					if (empty($lote)) {
						//lote vazio
						continue;
					}
										
					//essas tags irão conter os documentos zipados
					$docs = $lote->getElementsByTagName('docZip');
					
					foreach ($docs as $doc) {
						$numnsu = $doc->getAttribute('NSU');
						$schema = $doc->getAttribute('schema');
						//descompacta o documento e recupera o XML original
						$content = gzdecode(base64_decode($doc->nodeValue));
						//identifica o tipo de documento
						$tipo = substr($schema, 0, 6);
						//processar o conteudo do NSU, da forma que melhor lhe interessar
						//esse processamento depende do seu aplicativo

						$xmlget = simplexml_load_string($content);

						if(!empty($xmlget->NFe->infNFe->ide->nNF)){

							
							$vetnf = $daonf->VerificaNfeExist($xmlget->protNFe->infProt->chNFe,$_SESSION['idemp']); 
							$numnf = count($vetnf);

							if($numnf == 0){
								//insere	

								$nNF   		  = $xmlget->NFe->infNFe->ide->nNF;
								$serie 		  = str_pad($xmlget->NFe->infNFe->ide->serie, 3, "0", STR_PAD_LEFT);
								$nome_empresa = $xmlget->NFe->infNFe->emit->xNome;
								$cnpj 		  = !empty($xmlget->NFe->infNFe->emit->CNPJ) ? $xmlget->NFe->infNFe->emit->CNPJ : $xmlget->NFe->infNFe->emit->CPF;
								$ie			  = $xmlget->NFe->infNFe->emit->IE;
								$dhEm		  =  explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[1])[0];
								$chave		  = $xmlget->protNFe->infProt->chNFe;								

								$nfensus->setNfe_numero($nNF);
								$nfensus->setNfe_serie($serie);
								$nfensus->setNfe_empresa($nome_empresa);
								$nfensus->setNfe_cnpj($cnpj);
								$nfensus->setNfe_ie($ie);
								$nfensus->setNfe_dataemissao($dhEm);
								$nfensus->setId_status(1);								
								$nfensus->setNfe_chave($chave);
								$nfensus->setId_emp($_SESSION['idemp']);
								$nfensus->setNfe_totalnota($xmlget->NFe->infNFe->total->ICMSTot->vNF);
								$nfensus->setNfe_situacao($xmlget->protNFe->infProt->xMotivo);
								$nfensus->setSituacao_manifesto('Desconhecida');

								$daonf->inserir($nfensus);

							}else{
								//atualiza
								$nfensu 	  = $vetnf[0];
								$id 		  = $nfensu->getCodigo();	
								$nNF   		  = $xmlget->NFe->infNFe->ide->nNF;
								$serie 		  = str_pad($xmlget->NFe->infNFe->ide->serie, 3, "0", STR_PAD_LEFT);
								$nome_empresa = $xmlget->NFe->infNFe->emit->xNome;
								$cnpj 		  = !empty($xmlget->NFe->infNFe->emit->CNPJ) ? $xmlget->NFe->infNFe->emit->CNPJ : $xmlget->NFe->infNFe->emit->CPF;
								$ie			  = $xmlget->NFe->infNFe->emit->IE;
								$dhEm		  =   explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[1])[0];
								$chave		  = $xmlget->protNFe->infProt->chNFe;									

								$nfensus->setCodigo($id);
								$nfensus->setNfe_numero($nNF);
								$nfensus->setNfe_serie($serie);
								$nfensus->setNfe_empresa($nome_empresa);
								$nfensus->setNfe_cnpj($cnpj);
								$nfensus->setNfe_ie($ie);
								$nfensus->setNfe_dataemissao($dhEm);														
								$nfensus->setNfe_chave($chave);
								$nfensus->setId_emp($_SESSION['idemp']);
								$nfensus->setNfe_totalnota($xmlget->NFe->infNFe->total->ICMSTot->vNF);
								$nfensus->setNfe_situacao($xmlget->protNFe->infProt->xMotivo);
								$nfensus->setSituacao_manifesto('Desconhecida');

								$daonf->Update($nfensus);

							}

						
							$file = $uploaddir."{$xmlget->protNFe->infProt->chNFe}-procNFe.xml";
							file_put_contents($file, $content);
							
						}else{
							
							if(!empty($xmlget->chNFe)){
								$vetnf = $daonf->VerificaNfeExist($xmlget->chNFe,$_SESSION['idemp']); 
								$numnf = count($vetnf);
								//$file = $uploaddir."{$xmlget->chNFe}-erro.xml";
								//file_put_contents($file, $content);
								if($numnf == 0){
									$nNF   		  = substr($xmlget->chNFe,25,9);
									$serie 		  = str_pad(substr($xmlget->chNFe,22,3), 3, "0", STR_PAD_LEFT);
									$nome_empresa = !empty($xmlget->xNome) ? $xmlget->xNome : "";
									$cnpj 		  = !empty($xmlget->CNPJ) ? $xmlget->CNPJ : $xmlget->CPF;
									$ie			  = $xmlget->IE;
									$dhEm		  = !empty($xmlget->dhEmi) ?  explode(' ',str_replace('T',' ',$xmlget->dhEmi))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->dhEmi))[1])[0] : explode(' ',str_replace('T',' ',$xmlget->dhEvento))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->dhEvento))[1])[0];
									$chave		  = $xmlget->chNFe;									
									$xmotivo      = !empty($xmlget->xMotivo) ? $xmlget->xMotivo : (!empty($xmlget->xEvento) ? $xmlget->xEvento : '') ;

									$nfensus->setNfe_numero($nNF);
									$nfensus->setNfe_serie($serie);
									$nfensus->setNfe_empresa($nome_empresa);
									$nfensus->setNfe_cnpj($cnpj);
									$nfensus->setNfe_ie($ie);
									$nfensus->setNfe_dataemissao($dhEm);
									$nfensus->setId_status(1);								
									$nfensus->setNfe_chave($chave);
									$nfensus->setId_emp($_SESSION['idemp']);									
									$nfensus->setNfe_totalnota($xmlget->vNF);
									$nfensus->setNfe_situacao($xmotivo);
									$nfensus->setSituacao_manifesto('Desconhecida');

									$daonf->inserir($nfensus);
								}
							}	
						}

					}

					sleep(2);
				}


				$data = ($erro) ? array('msg' => ''.$msg.'','tipo'=>'2') : array('msg' => 'Analise finalizada com sucesso!','tipo'=>'1');						
								
				echo json_encode($data);

			break;
			case 'nsuporum':
				$nsu     = new Nsu();
				$dao     = new NsuDAO();
				$daonf   = new NfeNsuDAO();
				$nfensus = new NfeNsu();

				require_once '../sped-nfe/bootstrap.php';

				$pathFile      = '../public/config.json';
				$arr           = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				
				$pfxcontent    = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
				$password      = "".$installConfig->senhacert."";									
				$tools         = new Tools($arr, Certificate::readPfx($pfxcontent, $password));
				
				$tools->model('55');
				$tools->setEnvironment(1);

				$uploaddir = '../uploads/';				
				
				$xnsu	   = "";
				$result    = array();
				$erro	   = false;	
				$msg       = "";
				$numNSU    = $_REQUEST['nsu'];

				try {
					//executa a busca pelos documentos
					$resp = $tools->sefazDistDFe($ultNSU = 0,$numNSU, $fonte = 'AN');

					//extrair e salvar os retornos
					$dom = new \DOMDocument();
					$dom->loadXML($resp);

					$node     = $dom->getElementsByTagName('retDistDFeInt')->item(0);
					$tpAmb    = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
					$verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
					$cStat    = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
					$xMotivo  = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
					$dhResp   = $node->getElementsByTagName('dhResp')->item(0)->nodeValue;
					$ultNSU   = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
					$maxNSU   = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;
					$lote 	  = $node->getElementsByTagName('loteDistDFeInt')->item(0);

					//essas tags irão conter os documentos zipados
					$docs = $lote->getElementsByTagName('docZip');
					
					foreach ($docs as $doc) {
						$numnsu = $doc->getAttribute('NSU');
						$schema = $doc->getAttribute('schema');
						//descompacta o documento e recupera o XML original
						$content = gzdecode(base64_decode($doc->nodeValue));
						//identifica o tipo de documento
						$tipo = substr($schema, 0, 6);
						//processar o conteudo do NSU, da forma que melhor lhe interessar
						//esse processamento depende do seu aplicativo

						$xmlget = simplexml_load_string($content);

						if(!empty($xmlget->NFe->infNFe->ide->nNF)){

							
							$vetnf = $daonf->VerificaNfeExist($xmlget->protNFe->infProt->chNFe,$_SESSION['idemp']); 
							$numnf = count($vetnf);

							if($numnf == 0){
								//insere	

								$nNF   		  = $xmlget->NFe->infNFe->ide->nNF;
								$serie 		  = str_pad($xmlget->NFe->infNFe->ide->serie, 3, "0", STR_PAD_LEFT);
								$nome_empresa = $xmlget->NFe->infNFe->emit->xNome;
								$cnpj 		  = !empty($xmlget->NFe->infNFe->emit->CNPJ) ? $xmlget->NFe->infNFe->emit->CNPJ : $xmlget->NFe->infNFe->emit->CPF;
								$ie			  = $xmlget->NFe->infNFe->emit->IE;
								$dhEm		  =  explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[1])[0];
								$chave		  = $xmlget->protNFe->infProt->chNFe;								

								$nfensus->setNfe_numero($nNF);
								$nfensus->setNfe_serie($serie);
								$nfensus->setNfe_empresa($nome_empresa);
								$nfensus->setNfe_cnpj($cnpj);
								$nfensus->setNfe_ie($ie);
								$nfensus->setNfe_dataemissao($dhEm);
								$nfensus->setId_status(1);								
								$nfensus->setNfe_chave($chave);
								$nfensus->setId_emp($_SESSION['idemp']);
								$nfensus->setNfe_totalnota($xmlget->NFe->infNFe->total->ICMSTot->vNF);
								$nfensus->setNfe_situacao($xmlget->protNFe->infProt->xMotivo);
								$nfensus->setSituacao_manifesto('Desconhecida');

								$daonf->inserir($nfensus);

							}else{
								//atualiza
								$nfensu 	  = $vetnf[0];
								$id 		  = $nfensu->getCodigo();	
								$nNF   		  = $xmlget->NFe->infNFe->ide->nNF;
								$serie 		  = str_pad($xmlget->NFe->infNFe->ide->serie, 3, "0", STR_PAD_LEFT);
								$nome_empresa = $xmlget->NFe->infNFe->emit->xNome;
								$cnpj 		  = !empty($xmlget->NFe->infNFe->emit->CNPJ) ? $xmlget->NFe->infNFe->emit->CNPJ : $xmlget->NFe->infNFe->emit->CPF;
								$ie			  = $xmlget->NFe->infNFe->emit->IE;
								$dhEm		  =   explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->NFe->infNFe->ide->dhEmi))[1])[0];
								$chave		  = $xmlget->protNFe->infProt->chNFe;									

								$nfensus->setCodigo($id);
								$nfensus->setNfe_numero($nNF);
								$nfensus->setNfe_serie($serie);
								$nfensus->setNfe_empresa($nome_empresa);
								$nfensus->setNfe_cnpj($cnpj);
								$nfensus->setNfe_ie($ie);
								$nfensus->setNfe_dataemissao($dhEm);														
								$nfensus->setNfe_chave($chave);
								$nfensus->setId_emp($_SESSION['idemp']);
								$nfensus->setNfe_totalnota($xmlget->NFe->infNFe->total->ICMSTot->vNF);
								$nfensus->setNfe_situacao($xmlget->protNFe->infProt->xMotivo);
								$nfensus->setSituacao_manifesto('Desconhecida');

								$daonf->Update($nfensus);

							}

						
							$file = $uploaddir."{$xmlget->protNFe->infProt->chNFe}-procNFe.xml";
							file_put_contents($file, $content);
							
						}else{
							
							if(!empty($xmlget->chNFe)){
								$vetnf = $daonf->VerificaNfeExist($xmlget->chNFe,$_SESSION['idemp']); 
								$numnf = count($vetnf);
								//$file = $uploaddir."{$xmlget->chNFe}-erro.xml";
								//file_put_contents($file, $content);
								if($numnf == 0){
									$nNF   		  = substr($xmlget->chNFe,25,9);
									$serie 		  = str_pad(substr($xmlget->chNFe,22,3), 3, "0", STR_PAD_LEFT);
									$nome_empresa = !empty($xmlget->xNome) ? $xmlget->xNome : "";
									$cnpj 		  = !empty($xmlget->CNPJ) ? $xmlget->CNPJ : $xmlget->CPF;
									$ie			  = $xmlget->IE;
									$dhEm		  = !empty($xmlget->dhEmi) ?  explode(' ',str_replace('T',' ',$xmlget->dhEmi))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->dhEmi))[1])[0] : explode(' ',str_replace('T',' ',$xmlget->dhEvento))[0].' '.explode('-',explode(' ',str_replace('T',' ',$xmlget->dhEvento))[1])[0];
									$chave		  = $xmlget->chNFe;									
									$xmotivo      = !empty($xmlget->xMotivo) ? $xmlget->xMotivo : (!empty($xmlget->xEvento) ? $xmlget->xEvento : '') ;

									$nfensus->setNfe_numero($nNF);
									$nfensus->setNfe_serie($serie);
									$nfensus->setNfe_empresa($nome_empresa);
									$nfensus->setNfe_cnpj($cnpj);
									$nfensus->setNfe_ie($ie);
									$nfensus->setNfe_dataemissao($dhEm);
									$nfensus->setId_status(1);								
									$nfensus->setNfe_chave($chave);
									$nfensus->setId_emp($_SESSION['idemp']);									
									$nfensus->setNfe_totalnota($xmlget->vNF);
									$nfensus->setNfe_situacao($xmotivo);
									$nfensus->setSituacao_manifesto('Desconhecida');

									$daonf->inserir($nfensus);
								}
							}	
						}

					}



				} catch (\Exception $e) {						
					$msg  = "Exception: ".str_replace("\n", "<br/>", $e->getMessage());
					$erro = true;

				}

				$data = ($erro) ? array('msg' => ''.$msg.'','tipo'=>'2') : array('msg' => 'Analise finalizada com sucesso!','tipo'=>'1');						
								
				echo json_encode($data);

			break;

			case 'listanfes':

				$dao = new NfeNsuDAO();
				$vet = $dao->ListaNfe($_SESSION['idemp']);
				$num = count($vet);
				$res = array();

				for($i = 0; $i < $num; $i++){

					$nfensus		 = $vet[$i];

					$cod 				= $nfensus->getCodigo();
					$nfe_numero  	 	= $nfensus->getNfe_numero();
					$nfe_serie  	 	= $nfensus->getNfe_serie();
					$nfe_empresa 	 	= $nfensus->getNfe_empresa();
					$nfe_cnpj 	 	 	= $nfensus->getNfe_cnpj();
					$nfe_ie 		 	= $nfensus->getNfe_ie();
					$nfe_dataemissao 	= $nfensus->getNfe_dataemissao();
					$id_status 	     	= $nfensus->getId_status();
					$nome 		     	= $nfensus->getNome_status();
					$nfe_chave 	     	= $nfensus->getNfe_chave();
					$nfe_totalnota   	= $nfensus->getNfe_totalnota();
            		$nfe_situacao    	= $nfensus->getNfe_situacao();
            		$situacao_manifesto = $nfensus->getSituacao_manifesto();

					if(empty($nfe_totalnota)){
						$arquivo = "../uploads";
						if(file_exists($arquivo."/{$nfe_chave}-procNFe.xml")){
							$xml =  simplexml_load_file($arquivo."/{$nfe_chave}-procNFe.xml");
							$nfe_totalnota = floatval($xml->NFe->infNFe->total->ICMSTot->vNF);
							$nfe_situacao  = $xml->protNFe->infProt->xMotivo;							
							
						}						
					}

					$cor  = '';
					$icon = '';
					if($id_status == 1){
						$cor = 'info';
						$icon = '<i class="fa fa-info-circle fa-2x text-info"></i>';
					}else if($id_status == 2){
						$cor = 'success';
						$icon = '<i class="fa fa-check-circle fa-2x text-success"></i>';
					}else if($id_status == 3){
						$cor = 'success';
						$icon = '<i class="fa fa-check-circle fa-2x text-success"></i>';
					}

					array_push($res,array(
						'cod'=>$cod,
						'nfe_numero'=>$nfe_numero,
						'nfe_serie'=>$nfe_serie,
						'nfe_empresa'=>$nfe_empresa,
						'nfe_cnpj'=>$nfe_cnpj,
						'nfe_ie'=>$nfe_ie,
						'nfe_dataemissao'=>date('d/m/Y h:i:s',strtotime($nfe_dataemissao)),
						'status'=>$cor,
						'nfe_chave'=>$nfe_chave,
						'nfe_totalnota'=>number_format($nfe_totalnota,2,',','.'),
						'nfe_situacao'=>"{$nfe_situacao}",
						'situacao_manifesto'=>$situacao_manifesto,
						'icon'=>$icon
					));

				}
				echo json_encode($res);

			break;

			case 'pesquisanotas':

				$condicao  = array();	

				if(isset($_REQUEST['dtini']) and !empty($_REQUEST['dtini'])){

					$dataini     =  implode("-", array_reverse(explode("/", $_REQUEST['dtini'])));	
					$condicao[]  = " cast(n.nfe_dataemissao as date) between '".$dataini."' ";		
				}
						
				if(isset($_REQUEST['dtfin']) and !empty($_REQUEST['dtfin'])){
		
					$datafin     =  implode("-", array_reverse(explode("/", $_REQUEST['dtfin'])));		
					$condicao[]  = " '".$datafin."' ";		
				}


				if(isset($_REQUEST['situanfe']) and !empty($_REQUEST['situanfe'])){	
					$situanfe     = $_REQUEST['situanfe'];		
					$condicao[]  = " n.nfe_situacao like '%".$situanfe."%' ";		
				}

				if(isset($_REQUEST['situamanifest']) and !empty($_REQUEST['situamanifest'])){	
					$situamanifest   = $_REQUEST['situamanifest'];		
					$condicao[] 	 = " n.situacao_manifesto like '%".$situamanifest."%' ";		
				}
				
				if(isset($_REQUEST['serie']) and !empty($_REQUEST['serie'])){	
					$serie   		 = $_REQUEST['serie'];		
					$condicao[] 	 = " n.nfe_serie like '%".$serie."%' ";		
				}

				if(isset($_REQUEST['numeroini']) and !empty($_REQUEST['numeroini'])){

					$numeroini     =  $_REQUEST['numeroini'];	
					$condicao[]  = " n.nfe_numero between '".$numeroini."' ";		
				}
						
				if(isset($_REQUEST['numerofim']) and !empty($_REQUEST['numerofim'])){
		
					$numerofim     = $_REQUEST['numerofim'];		
					$condicao[]    = " '".$numerofim."' ";		
				}

				if(isset($_REQUEST['cnpjemit']) and !empty($_REQUEST['cnpjemit'])){
		
					$cnpjemit     = preg_replace('/[^0-9]/', '', $_REQUEST['cnpjemit']);		
					$condicao[]   = " n.nfe_cnpj = '".$cnpjemit."' ";		
				}

				if(isset($_REQUEST['chavenfe']) and !empty($_REQUEST['chavenfe'])){
		
					$chavenfe     = $_REQUEST['chavenfe'];		
					$condicao[]   = " n.nfe_chave = '".$chavenfe."' ";		
				}

				$condicao[]   = " n.id_emp = '".$_SESSION['idemp']."' ";
				

				$where = '';
				if(count($condicao) > 0){			
					$where = ' where'.implode('AND',$condicao);					
				}

				$dao = new NfeNsuDAO();
				$vet = $dao->PesquisaNotas($where);
				$num = count($vet);
				$res = array();

				for($i = 0; $i < $num; $i++){

					$nfensus		 = $vet[$i];

					$cod 				= $nfensus->getCodigo();
					$nfe_numero  	 	= $nfensus->getNfe_numero();
					$nfe_serie  	 	= $nfensus->getNfe_serie();
					$nfe_empresa 	 	= $nfensus->getNfe_empresa();
					$nfe_cnpj 	 	 	= $nfensus->getNfe_cnpj();
					$nfe_ie 		 	= $nfensus->getNfe_ie();
					$nfe_dataemissao 	= $nfensus->getNfe_dataemissao();
					$id_status 	     	= $nfensus->getId_status();
					$nome 		     	= $nfensus->getNome_status();
					$nfe_chave 	     	= $nfensus->getNfe_chave();
					$nfe_totalnota   	= $nfensus->getNfe_totalnota();
            		$nfe_situacao    	= $nfensus->getNfe_situacao();
            		$situacao_manifesto = $nfensus->getSituacao_manifesto();

					if(empty($nfe_totalnota)){
						$arquivo = "../uploads";
						if(file_exists($arquivo."/{$nfe_chave}-procNFe.xml")){
							$xml =  simplexml_load_file($arquivo."/{$nfe_chave}-procNFe.xml");
							$nfe_totalnota = floatval($xml->NFe->infNFe->total->ICMSTot->vNF);
							$nfe_situacao  = $xml->protNFe->infProt->xMotivo;							
							
						}						
					}

					$cor  = '';
					$icon = '';
					if($id_status == 1){
						$cor = 'info';
						$icon = '<i class="fa fa-info-circle fa-2x text-info"></i>';
					}else if($id_status == 2){
						$cor = 'success';
						$icon = '<i class="fa fa-check-circle fa-2x text-success"></i>';
					}else if($id_status == 3){
						$cor = 'success';
						$icon = '<i class="fa fa-check-circle fa-2x text-success"></i>';
					}

					array_push($res,array(
						'cod'=>$cod,
						'nfe_numero'=>$nfe_numero,
						'nfe_serie'=>$nfe_serie,
						'nfe_empresa'=>$nfe_empresa,
						'nfe_cnpj'=>$nfe_cnpj,
						'nfe_ie'=>$nfe_ie,
						'nfe_dataemissao'=>date('d/m/Y h:i:s',strtotime($nfe_dataemissao)),
						'status'=>$cor,
						'nfe_chave'=>$nfe_chave,
						'nfe_totalnota'=>number_format($nfe_totalnota,2,',','.'),
						'nfe_situacao'=>"{$nfe_situacao}",
						'situacao_manifesto'=>$situacao_manifesto,
						'icon'=>$icon
					));

				}
				echo json_encode($res);

			break;
			case 'manifestnfeclick':

				$id = $_REQUEST['id'];
				$pathFile      = '../public/config.json';
				$arr           = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				$xJust 		   = '';
				$tpEvento 	   = ''.$_REQUEST['tpEvento'].'';

				$nseu = new NfeNsu();
				$dao  = new NfeNsuDAO();
				$vet  = $dao->ListaNfeUm($id,$_SESSION['idemp']);
				$num  = count($vet);
				$res  = array();

				if($num > 0){

					$nfensus   = $vet[0];
					
					$cod 	   = $nfensus->getCodigo();                        
		            $id_status = $nfensus->getId_status();            
        	    	$nfe_chave = trim($nfensus->getNfe_chave());
					$cor 	   = '';

					if($id_status == 1){
						$cor = 'info';
					}else if($id_status == 2){
						$cor = 'warning';
					}else if($id_status == 3){
						$cor = 'success';
					}

					require_once '../sped-nfe/bootstrap.php';

					$pfxcontent  = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
					$password    = "".$installConfig->senhacert."";
					
					$certificate = Certificate::readPfx($pfxcontent, $password);					

					$tools = new Tools($arr, $certificate);					
					$tools->model('55');					
					$tools->setEnvironment(1);					
					
					$responsemanifest = $tools->sefazManifesta($nfe_chave,$tpEvento,$xJust = '',$nSeqEvento = 1);
					$st 			  = new Standardize($responsemanifest);
					$stdRes 		  = $st->toStd();
					$arr 			  = $st->toArray();
					/*echo "<pre>";
					print_r($stdRes);
					echo "</pre>";*/
					if($stdRes->cStat == 128){
						
						$nseu->setCodigo($cod);
						$nseu->setId_status(2);
						$nseu->setId_emp($_SESSION['idemp']);

						$dao->UpdateStatus($nseu);


						array_push($res,array(
							'chave'=>$nfe_chave,
							'id'=>$cod,
							'cor'=>'warning',
							'res'=>$arr,
						));

					}


				}
				
				echo json_encode($res);
			break;
			case 'manifestnfeclick2':

				$arrs		   = $_REQUEST['arr'];				
				$pathFile      = '../public/config.json';
				$arr           = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				$res		   = array();
				$data 		   = array();

				$std = new stdClass();
				for ($i=0; $i < count($arrs); $i++) { 

					$chave  	 = $arrs[$i]['chave'];
					$numero 	 = $arrs[$i]['numero'];
					$codmanifest = $arrs[$i]['codmanifest'];
					
					$tipo = array(
						'210200'=>'Confirmação da Operação',
						'210210'=>'Ciência da Operação',
						'210220'=>'Desconhecimento da Operação',
						'210240'=>'Operação não Realizada',
					);

					$nometipo =  $tipo[$codmanifest];
				
					$nseu = new NfeNsu();
					$dao  = new NfeNsuDAO();
					$vet  = $dao->NfeNumero($numero,$_SESSION['idemp']);
					$num  = count($vet);

					if($num > 0){

						$nfensus   = $vet[0];
						
						$cod 	   = $nfensus->getCodigo();                        
						$id_status = $nfensus->getId_status();            
						$nfe_chave = trim($nfensus->getNfe_chave());
						$cor 	   = '';
	
						if($id_status == 1){
							$cor = 'info';
						}else if($id_status == 2){
							$cor = 'warning';
						}else if($id_status == 3){
							$cor = 'success';
						}

						
						$std->evento[$i] =  new stdClass();
						$std->evento[$i]->chNFe      = $nfe_chave;
						$std->evento[$i]->tpEvento   = $codmanifest; //evento ciencia da operação
						$std->evento[$i]->xJust      = null;
						$std->evento[$i]->nSeqEvento = 1;

						$nseu->setCodigo($cod);
						$nseu->setId_status(2);
						$nseu->setId_emp($_SESSION['idemp']);
						$nseu->setSituacao_manifesto($codmanifest.' '.$nometipo);

						$dao->UpdateStatus($nseu);

						$res[] = array(
							'chave'=>$nfe_chave,
							'id'=>$cod,
							'numero'=>$numero,
							'cor'=>'warning',							
						);

					}	
				}

				require_once '../sped-nfe/bootstrap.php';
			try{
				
					$pfxcontent  = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
					$password    = "".$installConfig->senhacert."";
					
					$certificate = Certificate::readPfx($pfxcontent, $password);					

					$tools = new Tools($arr, $certificate);					
					$tools->model('55');					
					$tools->setEnvironment(1);					
					
					$responsemanifest =  $tools->sefazManifestaLote($std);
					$st 			  = new Standardize($responsemanifest);
					$stdRes 		  = $st->toStd();
					$arra			  = $st->toArray();
					

					array_push($data,array(
						'res'=>$res,
						'arr'=>$arra,
						'tipo'=>'1',
					));
					
			} catch (\Exception $e) {
				
				array_push($data,array(
					'tipo'=>'2',
					'msg'=>'Exception: '.$e->getMessage().'',					
				));

			}
				
			echo json_encode($data);

			break;
			case 'testes2':
				$pathFile      = '../public/config.json';
				$arr           = file_get_contents($pathFile);
				$installConfig = json_decode($arr);
				require_once '../sped-nfe/bootstrap.php';

				$pfxcontent  = file_get_contents('../sped-nfe/cert/'.$installConfig->cert.'');
				$password    = "".$installConfig->senhacert."";
				
				$certificate = Certificate::readPfx($pfxcontent, $password);					

				$tools = new Tools($arr, $certificate);					
				$tools->model('55');					
				$tools->setEnvironment(1);					
				$numeroRecibo = '202105171757053';
				$tpAmb = '1';
				$xmlResp = $tools->sefazConsultaRecibo($numeroRecibo, $tpAmb);
				$st = new Standardize();
			    $std = $st->toStd($xmlResp);
				$arra = $st->toArray();
				
				echo "<pre>";
				print_r($arra);
				echo "</pre>";

			break;

		}

	

	

	}

	

?>
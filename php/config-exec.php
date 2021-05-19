<?php
	use NFePHP\NFe\Tools;
	use NFePHP\Common\Certificate;
	use NFePHP\NFe\Common\Standardize;
	require_once('../inc/inc.autoload.php');
	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'inserir':
								
							
				$txtResp = "";
				if(!empty($_FILES['arq']['tmp_name'])){
					//echo "entro";
					$dao 		 = new Funcoes();				
					$ext		 = $dao->getExtension($_FILES['arq']['name']);						
					$actual_name = "cert.".$ext."";												
					$tmp 		 = $_FILES['arq']['tmp_name'];
					$novodir     = "../sped-nfe/cert/";
					if(!move_uploaded_file($tmp, $novodir.$actual_name)){
						echo "Erro ao gravar arquivo, verifique se o arquivo contem acentos ou espaços!";					
					}else{
						$txtResp = "Cetificado gravado na pasta!!";
					}
				 }	
				
				$form1 = !empty($_REQUEST['forml']) ? $_REQUEST['forml']: '';

				$tpAmb = filter_input(
						INPUT_POST,
						'tpAmb',
						FILTER_VALIDATE_INT,
						array("options" => array("min_range"=>1, "max_range"=>2))
					);
				$somtribcusto = filter_input(
						INPUT_POST,
						'somtribcusto',
						FILTER_SANITIZE_STRING,
						array("options" => array("min_range"=>1, "max_range"=>2))
					);
				$atualizaprecocusto = filter_input(
						INPUT_POST,
						'atualizaprecocusto',
						FILTER_SANITIZE_STRING,
						array("options" => array("min_range"=>1, "max_range"=>2))
					);

					$FormulacaoBoiCasado = filter_input(
						INPUT_POST,
						'FormulacaoBoiCasado',
						FILTER_SANITIZE_STRING,
						array("options" => array("min_range"=>1, "max_range"=>2))
					);

					$apropria = filter_input(
						INPUT_POST,
						'apropria',
						FILTER_SANITIZE_STRING,
						array("options" => array("min_range"=>1, "max_range"=>2))
					);

					$relcomp = filter_input(
						INPUT_POST,
						'relcomp',
						FILTER_SANITIZE_STRING,
						array("options" => array("min_range"=>1, "max_range"=>2))
					);

				$razaosocial  = filter_input(INPUT_POST, 'razaosocial', FILTER_SANITIZE_SPECIAL_CHARS);
				$empresa      = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
				$certPassword = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
				$cnpj         = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
				
				$hostbird      = filter_input(INPUT_POST, 'hostbird', FILTER_SANITIZE_STRING);
				$userbird      = filter_input(INPUT_POST, 'userbird', FILTER_SANITIZE_STRING);				
				$senhabird     = filter_input(INPUT_POST, 'senhabird', FILTER_SANITIZE_STRING);
				
				$aProxyConf = array(
					'proxyIp'=> '',
					'proxyPort'=> '',
					'proxyUser'=> '',
					'proxyPass'=> ''
				);
				
				$aConfig = array(
					'atualizacao' => date('Y-m-d h:i:s'),
					'tpAmb' => $tpAmb,
					'razaosocial' => ''.$razaosocial.'',
					'cnpj' => $cnpj,	
					'siglaUF'=> 'RS',
					'schemes' => 'PL_009_V4',
					'versao' => '4.00',
					'tokenIBPT' => '',
					"CSC" => '',
					"CSCid" => '',	
					'empresa' => $empresa,
					'somtribcusto'=>$somtribcusto,
					'atualizaprecocusto'=>$atualizaprecocusto,
					'apropria'=>$apropria,
					'relcomp'=>$relcomp,
					'cert'=>'cert.pfx',
					'senhacert'=>''.$certPassword.'',
					'proxyConf' => $aProxyConf
				);
				
				$firebird = array(
					'hostbird'=>''.$hostbird.'',
					'userbird'=>''.$userbird.'',
					'senhabird'=>''.$senhabird.''
				);
				
				$aConfig2 = array(
					'firebird'=>$firebird,
					'FormulacaoBoiCasado'=>$FormulacaoBoiCasado,
					'listformulacao'=>$form1
				);
				
				$content  = json_encode($aConfig);
				$content2 = json_encode($aConfig2);				
				
				$filePath  = '../public/config.json';
				$filePath2 = '../public/configbird.json';	
				 if (! file_put_contents($filePath, $content)) {
				 	echo "erro ao gravar aquivo";
				 }
					
				if (! file_put_contents($filePath2, $content2)) {
				 	echo "erro ao gravar aquivo";
				 }
				 
				$destino = "importacao.php";
				echo "<div align='center'><img src='../images/checked.png' /><br/><strong>Configuração gravado com sucesso!!</strong><br/>{$txtResp}</div>";
			break;
			case 'inserirnovo':

				$txtResp = "";
				if(!empty($_FILES['arq']['tmp_name'])){
					//echo "entro";
					$dao 		 = new Funcoes();				
					$ext		 = $dao->getExtension($_FILES['arq']['name']);						
					$actual_name = "cert.".$ext."";												
					$tmp 		 = $_FILES['arq']['tmp_name'];
					$novodir     = "../sped-nfe/cert/";
					if(!move_uploaded_file($tmp, $novodir.$actual_name)){
						echo "Erro ao gravar arquivo, verifique se o arquivo contem acentos ou espaços!";					
					}else{
						$txtResp = "Cetificado gravado na pasta!!";
					}
				 }
				 $certPassword = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

				$dao = new EmpresasDAO();
				$vet = $dao->ListaEmpresaUm($_SESSION['idemp']);
				$num = count($vet);
				$emp = $vet[0];
				
				$cnpj_emp = $emp->getCnpj();
				$estado   = $emp->getEstado();

				require_once '../sped-nfe/bootstrap.php'; 

				$pathFile      = '../public/config.json';
				$arrs           = file_get_contents($pathFile);
				$installConfig = json_decode($arrs);

				 try {
				
					$pfxcontent    = file_get_contents('../sped-nfe/cert/cert.pfx');
					$password      = "".$certPassword."";									
					$tools         = new Tools($arrs, Certificate::readPfx($pfxcontent, $password));	
					$tools->model('55');
									
					$uf   = $estado;
					$cnpj = $cnpj_emp;
					$iest = '';
					$cpf  = '';
					$response = $tools->sefazCadastro($uf, $cnpj, $iest, $cpf);
					$stdCl    = new Standardize($response);

					$arr      = $stdCl->toArray();

					$aProxyConf = array(
						'proxyIp'=> '',
						'proxyPort'=> '',
						'proxyUser'=> '',
						'proxyPass'=> ''
					);
					
					$aConfig = array(
						'atualizacao' => date('Y-m-d h:i:s'),
						'tpAmb' => 1,
						'razaosocial' => ''.$arr['infCons']['infCad']['xNome'].'',
						'cnpj' => $arr['infCons']['infCad']['CNPJ'],	
						'siglaUF'=> 'RS',
						'schemes' => 'PL_009_V4',
						'versao' => '4.00',
						'tokenIBPT' => '',
						"CSC" => '',
						"CSCid" => '',	
						'empresa' => $arr['infCons']['infCad']['xNome'],
						'somtribcusto'=>"N",
						'atualizaprecocusto'=>"N",
						'cert'=>'cert.pfx',
						'senhacert'=>''.$certPassword.'',
						'proxyConf' => $aProxyConf
					);

					$filePath  = '../public/config.json';
					$content   = json_encode($aConfig);

					if (! file_put_contents($filePath, $content)) {
						echo "erro ao gravar aquivo";
					}

					echo "<div align='center'><img src='../images/checked.png' /><br/><strong>Configuração gravado com sucesso!!</strong><br/>{$txtResp}</div>";
					$destino = "admin.php";

				} catch (\Exception $e) {
					echo $e->getMessage();
				}
			break;
			
		}

	}

	header("Refresh:3; url=".$destino."", true, 303);

?>
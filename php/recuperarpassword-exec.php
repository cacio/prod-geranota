<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'recuperar':
				
				$ma 	= preg_replace("/[^0-9]/", "", $_REQUEST['cnpjemp']);
				$email  = $_REQUEST['email'];
				$part   = explode('php/',$_SERVER['REQUEST_URI'])[0];
				$actual_link = "http://$_SERVER[HTTP_HOST]$part/php/";
				
				$dao    = new UsuarioDAO();
				$vet    = $dao->VerificaCnpj($ma);
				$num    = count($vet);
				$result = array();

				if($num > 0){

					$usu = $vet[0];

					$cod   = $usu->getCodigo();									
					$sen   = $usu->getSenha();
					$ema   = $usu->getEmail();
					$nom   = $usu->getNome();

					$chave = sha1($cod.$sen);

					$dados = array(
						'SMTPAuth'=>true,
						'SMTPSecure'=>'ssl',
						'Host'=>'smtp.uni5.net',
						'Port'=>465,
						'Username'=>'cacio@iasinformatica.com.br',
						'Password'=>'CacioWeb023',		
					);


					$daoe = new EmailDAO($dados);

					$std = new stdClass();

					$std->mensagem    = "Ola, Para redefinir sua senha clique no link abaixo";
					$std->titulo      = "Redefinir senha";
					$std->nome 		  = "{$nom}";
					$std->assinatura  = "Gera Nota";
					$std->data        = date('d/m/Y');
					$std->url         = "{$actual_link}redefinirsenha.php?token={$chave}&cnpj={$ma}";
					$std->email       = "{$email}";
					$std->msgretorno  = "REDEFINAÇÃO DE SENHA ENVIADO COM SUSSESO!";
					$std->txt_btn     = "REDEFINIR SENHA";

				 	$return =  $daoe->mandaEmail($std);

				 	//print_r($return);
				 	echo json_encode($return);
				}else{

					array_push($result, array('msg'=>'Cnpj não existe tente novamente ou fazer um contato com o a prodasiq Obrigado!','tipo'=>'2'));	
					echo json_encode($result);
				}


			break;
			case 'redefinir':
				error_reporting(E_ALL);
				ini_set('display_errors', 'On');
				$token  		= $_POST['token'];
				$cnpj	  		= preg_replace("/[^0-9]/", "", $_POST['cnpj']);
				$senha  		= $_POST['passw'];
				$senhaconfitma  = $_POST['passw-conf'];
				$result         = array();

				if($senha == $senhaconfitma){
					$dao    = new UsuarioDAO();
					$vet    = $dao->VerificaCnpj2($cnpj,$token);
					$num    = count($vet);

					if($num > 0){

						$usu = $vet[0];

						$cod   = $usu->getCodigo();									
						$sen   = $usu->getSenha();
						$ema   = $usu->getEmail();
						$nom   = $usu->getNome();

						$chave = sha1($cod.$sen);

						if($token == $chave){

							//tudo certo;

							$usuario = new Usuario();
							$usuario->setCodigo($cod);
							$usuario->setSenha(sha1($senha));

							$dao->updatesenha($usuario);	

							array_push($result, array(
								'msg'=>'Senha alterada com sucesso!',
								'tipo'=>'1',	
							));

						}else{
							array_push($result, array(
								'msg'=>'Token não confere !',
								'tipo'=>'2',	
							));
						}


					}else{
						array_push($result, array(
								'msg'=>'Esse E-Mail não existe!',
								'tipo'=>'2',	
						));
					}
				}else{
					array_push($result, array(
						'msg'=>'Senhas não conferem',
						'tipo'=>'2',	
					));
				}

			echo json_encode($result);

			break;		
		}

	}	

?>
<?php

	

	require_once('../inc/inc.autoload.php');

	//session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

			
		switch($act){

			case 'inserir':
		
				               
               /*try {*/
                   
	                $cnpj     =  !empty($_REQUEST['cnpj'])     ? preg_replace("/\D+/", "", $_REQUEST['cnpj']):''; 
	                $ins	  =  !empty($_REQUEST['ins'])      ? $_REQUEST['ins']:''; 
	                $razao    =  !empty($_REQUEST['razao'])    ? $_REQUEST['razao']:''; 
	                $fanta    =  !empty($_REQUEST['fanta'])    ? $_REQUEST['fanta']:'';
	                $cep      =  !empty($_REQUEST['cep']) 	   ? preg_replace("/[^0-9]/","",$_REQUEST['cep']):'';
	                $endereco =  !empty($_REQUEST['endereco']) ? $_REQUEST['endereco']:'';
	                $numero   =  !empty($_REQUEST['numero'])   ? $_REQUEST['numero']:'';
	                $bairro   =  !empty($_REQUEST['bairro'])   ? $_REQUEST['bairro']:'';     
	                $comple   =  !empty($_REQUEST['comple'])   ? $_REQUEST['comple']:'';
	                $cidade   =  !empty($_REQUEST['cidade'])   ? $_REQUEST['cidade']:'';
	                $estado   =  !empty($_REQUEST['estado'])   ? $_REQUEST['estado']:'';
	                $telefone =  !empty($_REQUEST['telefone']) ? preg_replace("/[^0-9]/","",$_REQUEST['telefone']):'';
	                $email    =  !empty($_REQUEST['email'])    ? $_REQUEST['email']:'';
	                $pschave  =  !empty($_REQUEST['pschave'])  ? $_REQUEST['pschave']:'123';

	                $emp = new Empresas();

	                $emp->setCnpj($cnpj);
	                $emp->setRazaoSocial($razao);
	                $emp->setFantasia($fanta);
	                $emp->setInscricaoEstadual($ins);
	                $emp->setEndereco($endereco);
	                $emp->setNumero($numero);
	                $emp->setComplemento($comple);
	                $emp->setCep($cep);
	                $emp->setCidade($cidade);
	                $emp->setEstado($estado);
	                $emp->setBairro($bairro);
	                $emp->setFone1($telefone);
	                $emp->setEmail($email);
	                $emp->setAtivo(1);

	                $dao = new EmpresasDAO();

	                $vet    = $dao->proximoid();
	                $prox   = $vet[0];
	                $idprox = $prox->getIdProx();

	                $dao->inserir2($emp);



	                //inserir um usuario	
	                 $user 	   = new Usuario();	
	                 $daouser  = new UsuarioDAO();	

	                 $vetu      = $daouser->proximoid();
					 $proxs     = $vetu[0];	
					 $proximoid = $proxs->getProxid();

					 $per[] = array(
							'idsub'=>'3',
							'idmenu'=>'8',
							'iduser'=>''.$proximoid.'',
						);

						$per[] = array(
							'idsub'=>'4',
							'idmenu'=>'8',
							'iduser'=>''.$proximoid.'',
						);

						$per[] = array(
							'idsub'=>'5',
							'idmenu'=>'9',
							'iduser'=>''.$proximoid.'',
						);	

						foreach($per as $key=>$value){
						
							$idmenu    = $value['idmenu'];
							$idsubmenu = $value['idsub'];

							$pers = new Permissoes();

							$pers->setIdmenu($idmenu);
							$pers->setIdsubmenu($idsubmenu);
							$pers->setIdusuario($value['iduser']);

							$daop = new PermissoesDAO();

							$daop->inserirpermissao($pers);

						}


						$user->setNome($razao);
						$user->setEmail($email);
						$user->setLogin($razao);
						$user->setSenha(sha1($pschave));
						$user->setIdRota(1);
						$user->setIdsys(1);
						$user->setIdEmp($idprox);
						
						$daouser->inserir($user);	

						$aProxyConf = array(
							'proxyIp'=> '',
							'proxyPort'=> '',
							'proxyUser'=> '',
							'proxyPass'=> ''
						);

						$aConfig = array(
							'atualizacao' => date('Y-m-d h:i:s'),
							'tpAmb' => 1,
							'razaosocial' => ''.$razao.'',
							'cnpj' => $cnpj,	
							'siglaUF'=> 'RS',
							'schemes' => 'PL_009_V4',
							'versao' => '4.00',
							'tokenIBPT' => '',
							"CSC" => '',
							"CSCid" => '',	
							'empresa' => $razao,
							'somtribcusto'=>'N',
							'atualizaprecocusto'=>'N',
							'cert'=>'cert.pfx',
							'senhacert'=>'',
							'proxyConf' => $aProxyConf
						);

						$content  = json_encode($aConfig);
						$filePath = '../public/config.json';
						if (! file_put_contents($filePath, $content)) {
						 	echo "erro ao gravar aquivo";
						 }

	                	$result = array();

		                array_push($result,array(
		                    'success'=>'sucesso',
		                    'codigo'=>''.$idprox.''
		                ));
	                   
	                   echo json_encode($result); 

	                /*} catch (\Throwable $th) {
	                    //throw $th;
	                    array_push($result,array(
	                        'success'=>''.$th.''
	                    ));

	                    echo json_encode($result); 
	                }*/
				
				
			break;
			case 'consulta':

			    $cnpj   =  !empty($_REQUEST['cnpj'])     ? preg_replace("/\D+/", "", $_REQUEST['cnpj']):''; 	
				$dao 	= new EmpresasDAO();
				$vet 	= $dao->VerificaEmpresa($cnpj);
				$num    = count($vet);
				$return = array();

				if($num > 0){
					array_push($return, array(
						'msg'=>'Cnpj Já Existente!',
						'tipo'=>'1'
					));
				}else{
					array_push($return, array(
						'msg'=>'Cnpj Não Existe!',
						'tipo'=>'2'
					));	
				}

				echo json_encode($return);

			break;

			case 'teste':
				
				$per[] = array(
					'idsub'=>'3',
					'idmenu'=>'8',
					'iduser'=>'',
				);

				$per[] = array(
					'idsub'=>'4',
					'idmenu'=>'8',
					'iduser'=>'',
				);

				$per[] = array(
					'idsub'=>'5',
					'idmenu'=>'9',
					'iduser'=>'',
				);

				foreach($per as $key=>$value){
					//echo $value['idsub']." ".$value['idmenu']." ".$value['iduser'];


				}



				echo "<pre>";
				print_r($per);

			break;
			
		}

	}

?>
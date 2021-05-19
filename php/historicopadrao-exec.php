<?php

	require_once('../inc/inc.autoload.php');
	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){	

		$act = $_REQUEST['act'];			

		switch($act){            
            case 'inserir':

                $codigo = !empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : 0;
                $hist   = !empty($_REQUEST['hist']) ? $_REQUEST['hist'] : 0;

                $dao = new HistoricoPadraoDAO();
                $vet = $dao->ListaHistoricoPadraoUm(trim($codigo));
                $num = count($vet);

                if($num > 0){
                    $daoh = new Helpers();
                    $daoh->flash("error","Já existe um codigo {$codigo} cadastrado!");
                    $destino = "cadastro-historicopadrao.php";
                    header('Location:'.$destino);
                    die();
                }

                $hp = new HistoricoPadrao();

                $hp->setCodigo($codigo);
                $hp->setDescricao($hist);

                $dao->inserir($hp);

                $daoh = new Helpers();
                $daoh->flash("success","Histórico padrão cadastrado com sucesso!");

                $destino = "listahistoricopadrao.php";
                header('Location:'.$destino);

            break;

            case 'alterar':
                $id     = !empty($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $codigo = !empty($_REQUEST['codigo']) ? $_REQUEST['codigo'] : 0;
                $hist   = !empty($_REQUEST['hist']) ? $_REQUEST['hist'] : 0;

               
                $hp = new HistoricoPadrao();

                $hp->setId($id);
                $hp->setCodigo($codigo);
                $hp->setDescricao($hist);

                $dao = new HistoricoPadraoDAO();
                $dao->update($hp);

                $daoh = new Helpers();
                $daoh->flash("success","Histórico padrão alterado com sucesso!");

                $destino = "listahistoricopadrao.php";
                header('Location:'.$destino);

            break;
            case 'deletar':
                $id     = !empty($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                
                $hp = new HistoricoPadrao();

                $hp->setId($id);

                $dao = new HistoricoPadraoDAO();
                $dao->deletar($hp);

                echo "removido com sucesso!";

            break;
			case 'busca':
                               
                $dao = new HistoricoPadraoDAO();
                $vet = $dao->ListaHistoricoPadrao();
                $num = count($vet);
                $res   = array();

                for ($i=0; $i < $num; $i++) { 
                    
                    $hp     = $vet[$i];

                    $id     = $hp->getId();            
                    $codigo = $hp->getCodigo();
                    $desc   = utf8_encode($hp->getDescricao());                  
                    
                    $res[]  = trim($codigo).'-'.trim($desc); 
                    
                }   

                echo $dao->ajaxresponse("sugest",[
                    "res"=>$res
                ]);

            break;
            case 'pesquisa':
                $term = $_REQUEST['term'];
                $dao = new HistoricoPadraoDAO();
                $vet = $dao->BuscaHistoricoPadrao($term);
                $num = count($vet);
                $res   = array();

                for ($i=0; $i < $num; $i++) { 
                    
                    $hp     = $vet[$i];

                    $id     = $hp->getId();            
                    $codigo = trim($hp->getCodigo());
                    $desc   = $hp->getDescricao();                  
                    
                    array_push($res, array(
                        'label' => ''.$codigo.'-'.utf8_encode($desc).'',
                        'value' => ''.$codigo.'',
                        'cod'=>''.$id.'',
                        'nom'=>''.utf8_encode($desc).'',
                    ));	
                    
                }   
                echo (json_encode($res));
            break;
					
			
		}


	}

?>
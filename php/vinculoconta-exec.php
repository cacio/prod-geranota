<?php
	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];		

		switch($act){

			case 'inserir':

                $desc  = !empty($_REQUEST['desc']) ? $_REQUEST['desc'] : '';
                $conta = !empty($_REQUEST['conta']) ? $_REQUEST['conta'] : '';

                $pagrec = new FormPagRec();

                $pagrec->setNome(strtoupper($desc));
                $pagrec->setContaCtb($conta);

                $dao =  new FormPagRecDAO();

                $dao->inserir($pagrec);

                
                $daoh = new Helpers();
                $daoh->flash("success","Vínculo Conta cadastrado com sucesso!");

                $destino = "listavinculoconta.php";
                header('Location:'.$destino);
				
            break;
            
            case 'alterar':

                $id    = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '0'; 
                $desc  = !empty($_REQUEST['desc']) ? $_REQUEST['desc'] : '';
                $conta = !empty($_REQUEST['conta']) ? $_REQUEST['conta'] : '';

                $pagrec = new FormPagRec();
                   
                $pagrec->setCodigo($id);
                $pagrec->setNome(strtoupper($desc));
                $pagrec->setContaCtb($conta);

                $dao =  new FormPagRecDAO();

                $dao->alterar($pagrec);

                
                $daoh = new Helpers();
                $daoh->flash("success","Vínculo Conta alterado com sucesso!");

                $destino = "listavinculoconta.php";
                header('Location:'.$destino);
				
    		break;
            case 'deletar':

                $id    = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '0'; 

                $pagrec = new FormPagRec();
                   
                $pagrec->setCodigo($id);

                $dao =  new FormPagRecDAO();

                $dao->deletar($pagrec);

            break;
			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>
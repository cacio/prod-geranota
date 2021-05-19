<?php

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'alteraconta':
                $codconta = !empty($_REQUEST['codconta']) ? $_REQUEST['codconta'] : '';
                $codcli   = !empty($_REQUEST['codcli']) ? $_REQUEST['codcli'] : '0';

                $daof = new FinancContasDAO();
                $vetf = $daof->VerificaFinancContas($codconta);
                $numf = count($vetf);

                if($numf > 0){
                    $cli = new Cliente();
                    
                    $cli->setCodigo($codcli);			
                    $cli->setContaCtb($codconta);
                    
                    $dao = new ClienteDAO();
                    $dao->updateconta($cli);

                    $res = array();
                    array_push($res,array(
                        'msg'=>"Alterado com sucesso!",
                        'idcli'=>$codcli,
                        'tipo'=>1
                    ));
                }else{
                    $res = array();
                    array_push($res,array(
                        'msg'=>"Codigo reduzido não existe!",
                        'idcli'=>$codcli,
                        'tipo'=>2
                    ));
                }

                echo json_encode($res);
				
			break;
			
		}

	}
	
?>
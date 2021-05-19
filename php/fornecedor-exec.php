<?php

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'alteraconta':
                $codconta = !empty($_REQUEST['codconta']) ? $_REQUEST['codconta'] : '';
                $codfor   = !empty($_REQUEST['codfor']) ? $_REQUEST['codfor'] : '0';

                $daof = new FinancContasDAO();
                $vetf = $daof->VerificaFinancContas($codconta);
                $numf = count($vetf);

                if($numf > 0){
                    $for = new Fornecedor();
                    
                    $for->setCodigo($codfor);			
                    $for->setContaCtb($codconta);
                    
                    $dao = new FornecedorDAO();
                    $dao->updateconta($for);

                    $res = array();
                    array_push($res,array(
                        'msg'=>"Alterado com sucesso!",
                        'idcli'=>$codfor,
                        'tipo'=>1
                    ));
                }else{
                    $res = array();
                    array_push($res,array(
                        'msg'=>"Codigo reduzido não existe!",
                        'idcli'=>$codfor,
                        'tipo'=>2
                    ));
                }

                echo json_encode($res);
				
			break;
			
		}

	}
	
?>
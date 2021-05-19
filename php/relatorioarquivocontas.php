<?php
	
	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/relatorioarquivocontas.htm');
	//$tpl->assignInclude('conteudo','../tpl/relatorioarquivodominio.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.menu.php');
    	$tpl->assign('log',$_SESSION['login']);

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

                $tpl->newBlock('listar');

                
                $tpl->assign('reduzido',$reduzido);
                $tpl->assign('codconclas',$codconclas);
                $tpl->assign('nom',$nom);
                $tpl->assign('tipo','A = Analítica');
            }
            //$tpl->assign('codr','1.01.01.01.01');
            
        }
		
				
		
	

	/**************************************************************/
	$tpl->printToScreen();

?>
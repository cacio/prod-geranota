<?php
	
	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/cadastro-lancamento.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
    	$tpl->assign('log',$_SESSION['login']);


        $dao = new FinancContasDAO();
        $vet = $dao->ListaFinancContas();
        $num = count($vet);

        for ($i=0; $i < $num; $i++) { 

            $fct       = $vet[$i];
            
            $codigo    = $fct->getCodigo();
			$descricao = $fct->getDescricao();
            $reduzido  = $fct->getReduzido();

            $tpl->newBlock('lista');

            $tpl->assign('codigo',$codigo);
            $tpl->assign('reduzido',$reduzido);
            $tpl->assign('desc',utf8_encode($descricao));
            
        }

        for ($i=0; $i < $num; $i++) { 

            $fct       = $vet[$i];
            
            $codigo    = $fct->getCodigo();
			$descricao = $fct->getDescricao();
            $reduzido  = $fct->getReduzido();


            $tpl->newBlock('lista2');

            $tpl->assign('codigo',$codigo);
            $tpl->assign('desc',utf8_encode($descricao));
            $tpl->assign('reduzido',$reduzido);
        }

		
        $daoh = new HistoricoPadraoDAO();
        $veth = $daoh->ListaHistoricoPadrao();
        $numh = count($veth);
       
        for ($i=0; $i < $numh; $i++) { 
            
            $hp     = $veth[$i];

            $id      = $hp->getId();            
            $codigoh = $hp->getCodigo();
            $desch   = utf8_encode($hp->getDescricao());                  

            $tpl->newBlock('listahist');

            $tpl->assign('codigoh',trim($codigoh));
            $tpl->assign('desch', $desch);
            
        }   
				

	

	/**************************************************************/
	$tpl->printToScreen();

?>
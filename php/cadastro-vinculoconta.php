<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/cadastro-vinculoconta.htm');
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

	/**************************************************************/

	$tpl->printToScreen();

?>
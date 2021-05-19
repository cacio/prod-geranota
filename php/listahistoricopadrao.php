<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listahistoricopadrao.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

        $daoh = new Helpers();
        $msg = $daoh->flash();

        $tpl->assign('msg',$msg); 
        
        $dao =  new HistoricoPadraoDAO();
        $vet = $dao->ListaHistoricoPadrao();
        $num = count($vet);

        for ($i=0; $i < $num; $i++) { 

            $hp     = $vet[$i];

            $id     = $hp->getId();            
            $codigo = $hp->getCodigo();
            $desc   = $hp->getDescricao();

            $tpl->newBlock('listar');

            $tpl->assign('id',$id);
            $tpl->assign('codigo',$codigo);
            $tpl->assign('desc',utf8_encode($desc));
        }

	/**************************************************************/

	$tpl->printToScreen();

?>
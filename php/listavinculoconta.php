<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listavinculoconta.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
        $tpl->assign('log',$_SESSION['login']);
        
        $daoh = new Helpers();
        $msg = $daoh->flash();

        $tpl->assign('msg',$msg); 

       $dao = new FormPagRecDAO();
       $vet = $dao->ListaFromaPagRec();
       $num = count($vet);

       for ($i=0; $i < $num; $i++) { 

            $pagrec   = $vet[$i];
            $codigo   = $pagrec->getCodigo();
            $nome     = $pagrec->getNome();
            $reduzido = $pagrec->getReduzido();
            $desc     = $pagrec->getDescricao();            


            $tpl->newBlock('listar');

            $tpl->assign('codigo',$codigo);
            $tpl->assign('nome',$nome);
            $tpl->assign('reduzido',$reduzido);
            $tpl->assign('desc',$desc);

       }
        
        
      

	/**************************************************************/

	$tpl->printToScreen();

?>
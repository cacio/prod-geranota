<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/cadastro-historicopadrao.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

        $daoh = new Helpers();
        $msg = $daoh->flash();

        $tpl->assign('msg',$msg);        
      

	/**************************************************************/

	$tpl->printToScreen();

?>
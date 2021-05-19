<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/importacao.htm');
	$tpl->prepare();

	/**************************************************************/		

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		
		$pathFile      = '../public/config.json';
		$arr           = file_get_contents($pathFile);
		$installConfig = json_decode($arr);

		if($_SESSION['cnpj'] != $installConfig->cnpj){
			
			$tpl->newBlock('certificado');
			
		}

	/**************************************************************/

	$tpl->printToScreen();



?>
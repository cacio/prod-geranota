<?php

	include_once('../inc/inc.autoload.php');
	$tpl = new TemplatePower('../tpl/login.htm');
	$tpl->prepare();
	
	
	/**************************************************************/
	
	if(!empty($_REQUEST['token'])){

		$token  = $_REQUEST['token'];	

		$tpl->assign('token',$token);						

	}else{

		//$destino = 'login.php';
		//header('Location:'.$destino);

	}
		
	
	
	/**************************************************************/
	
	$tpl->printToScreen();
?>
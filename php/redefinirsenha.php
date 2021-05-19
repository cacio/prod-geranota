<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/redefinirsenha.htm');	
	$tpl->prepare();
	
	/**************************************************************/
		
		
		if(!empty($_REQUEST['token'])){

			$token  = $_REQUEST['token'];	
			$cnpj   = $_REQUEST['cnpj'];
			$tpl->assign('token',$token);						
			$tpl->assign('cnpj',$cnpj);
			
		}else{

			$destino = 'login.php';
			header('Location:'.$destino);

		}		
			
	
	/**************************************************************/
	$tpl->printToScreen();
?>
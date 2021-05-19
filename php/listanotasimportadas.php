<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listanotasimportadas.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		$dir   = "../uploads/";
		$pasta = opendir($dir);	
		$scan  = scandir($dir);
		
		if(count($scan) > 2) {
			while ($arquivo = readdir($pasta)){
				
				if ($arquivo != "." && $arquivo != ".."){
					$ext = pathinfo($arquivo, PATHINFO_EXTENSION);
					if($ext == 'xml'){
						$xml   = simplexml_load_file(''.$dir.''.$arquivo.'');
						if(!empty($xml->NFe->infNFe->ide->dhEmi)){
							$dEmi  = explode('T',$xml->NFe->infNFe->ide->dhEmi);					
																
							
							$tpl->newBlock('lista');
							
							$tpl->assign('arquivo',$arquivo);
							$tpl->assign('nNF',$xml->NFe->infNFe->ide->nNF);
							$tpl->assign('dEmi',implode("/", array_reverse(explode("-", "".$dEmi[0].""))));					
						}
					}
				}
			}
		}
				

	/**************************************************************/
	$tpl->printToScreen();
?>
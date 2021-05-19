<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/relatorioestoqueproduto.htm');
	//$tpl->assignInclude('conteudo','../tpl/relatorioestoqueproduto.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

		
		$dao = new ProdutosDAO();
		$vet = $dao->RelatorioProdutoEstoqueAtu();
		$num = count($vet);
		$xgrup = "";
		
		for($i = 0; $i < $num; $i++){
			
			$pro	   = $vet[$i];
			
			$codigo    = $pro->getCodigo();
			$descricao = $pro->getDescricao();
			$estatu    = $pro->getEstoqueAtual();
			$descgrup  = $pro->getGrupo();
			$codgrupo  = $pro->getCodGrupo();
			$customedio= $pro->getCustoMedio();	
			$totqtd    = $estatu * $customedio;
			
			if($codgrupo != $xgrup){
				$xgrup = $codgrupo;
				
				$tpl->newBlock('listag');
				$tpl->assign('codgrupo',$codgrupo);
				$tpl->assign('descgrup',utf8_encode($descgrup));
			}
			
			if(isset($_REQUEST['rd'])){
				
				if(trim(number_format($estatu,2)) > 0){
					$tpl->newBlock('lista');
			
					$tpl->assign('codigo',$codigo);
					$tpl->assign('descricao',utf8_encode($descricao));
					$tpl->assign('estatu',number_format($estatu,2,',','.'));
					$tpl->assign('customedio',number_format($customedio,2,',','.'));
					$tpl->assign('totqtd',number_format($totqtd,2,',','.'));					
				}
			}else{
				$tpl->newBlock('lista');
			
				$tpl->assign('codigo',$codigo);
				$tpl->assign('descricao',utf8_encode($descricao));
				$tpl->assign('estatu',number_format($estatu,2,',','.'));
				$tpl->assign('customedio',number_format($customedio,2,',','.'));
				$tpl->assign('totqtd',number_format($totqtd,2,',','.'));
			}
			
			
		}
		
	/**************************************************************/
	$tpl->printToScreen();
?>
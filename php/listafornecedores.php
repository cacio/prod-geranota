<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listafornecedores.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

       $dao = new FornecedorDAO();
       $vet = $dao->ListaFornecedor();
       $num = count($vet);

       for ($i=0; $i < $num; $i++) { 

            $for       = $vet[$i];
            
            $codigo    = $for->getCodigo();
            $nome      = $for->getNome();
            $CONTA_CTB = $for->getContaCtb();
            $REDUZIDO  = $for->getReduzido();
            $DESCRICAO = $for->getDescricao();
            
               if($REDUZIDO == ""){
                    $cl = 'vazio';
               }else{
                    $cl = 'tem';
               }

            $tpl->newBlock('listar');

            $tpl->assign('codigo',$codigo);
            $tpl->assign('nome',$nome);
            $tpl->assign('CONTA_CTB',$CONTA_CTB);
            $tpl->assign('REDUZIDO',$REDUZIDO);
            $tpl->assign('DESCRICAO',utf8_encode($DESCRICAO));
            $tpl->assign('cl',$cl);
       }
        
        
      

	/**************************************************************/

	$tpl->printToScreen();

?>
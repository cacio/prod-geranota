<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listaclientes.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

          $dao = new ClienteDAO();
          $vet = $dao->ListaCliente();
          $num = count($vet);

          for ($i=0; $i < $num; $i++) { 

               $cli       = $vet[$i];
               $codigo    = $cli->getCodigo();
               $nome      = $cli->getNome();
               $CONTA_CTB = $cli->getContaCtb();
               $REDUZIDO  = $cli->getReduzido();
               $DESCRICAO = $cli->getDescricao();
               
               $tpl->newBlock('listar');

               if($REDUZIDO == ""){
                    $cl = 'vazio';
               }else{
                    $cl = 'tem';
               }

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
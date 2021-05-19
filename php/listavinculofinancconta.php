<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/listavinculofinancconta.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

        
        
        $dao = new FinancContasDAO();
        $vet = $dao->ListaFinancContasParaAlteracao();
        $num = count($vet);

        for ($i=0; $i < $num; $i++) { 
            $fct       = $vet[$i];
            
            $codigo       = $fct->getCodigo();
            $descricao    = $fct->getDescricao();			
            $id           = $fct->getIdHistorico();
			$CODHISTORICO = $fct->getCodHistorico();
			$historico    = $fct->getHistorico();
            $reduzido     = $fct->getReduzido();

            $tpl->newBlock('listar');

            if(empty($CODHISTORICO)){
                $tpl->assign('cl','vazio');    
            }else{
                $tpl->assign('cl','tem');                    
            }

            $tpl->assign('codigo',$codigo);
            $tpl->assign('descricao',$descricao);
            $tpl->assign('id',$id);
            $tpl->assign('CODHISTORICO',$CODHISTORICO);
            $tpl->assign('reduzido',$reduzido);
            $tpl->assign('historico',utf8_encode($historico));        
            

        }

      

	/**************************************************************/

	$tpl->printToScreen();

?>
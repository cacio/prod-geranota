<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-vinculoconta.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

        $id = $_REQUEST['id'];

        $dao = new FormPagRecDAO();
        $vet = $dao->ListaFromaPagRecUm($id);
        $num = count($vet);
        if($num > 0){

            $pagrec   = $vet[0];

            $codigor  = $pagrec->getCodigo();
			$nome     = $pagrec->getNome();
			$ID_CONTA = $pagrec->getContaCtb();

            $tpl->assign('id',$codigor);
            $tpl->assign('nome',$nome);

            $dao = new FinancContasDAO();
            $vet = $dao->ListaFinancContas();
            $num = count($vet);

            for ($i=0; $i < $num; $i++) { 

                $fct       = $vet[$i];
                
                $codigo    = $fct->getCodigo();
                $descricao = $fct->getDescricao();
                $reduzido  = $fct->getReduzido();

                $tpl->newBlock('lista');

                if($ID_CONTA == $codigo){
                    $tpl->assign('ck','selected');    
                }else{
                    $tpl->assign('ck','');  
                }

                $tpl->assign('codigo',$codigo);
                $tpl->assign('reduzido',$reduzido);
                $tpl->assign('desc',utf8_encode($descricao));
                
            }
        }

	/**************************************************************/

	$tpl->printToScreen();

?>
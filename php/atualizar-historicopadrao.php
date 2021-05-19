<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-historicopadrao.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);

        if(!empty($_REQUEST['id'])){
            
            $id = $_REQUEST['id'];
        
            $dao =  new HistoricoPadraoDAO();
            $vet = $dao->ListaHistoricoPadraoAlter($id);
            $num = count($vet);

            if($num > 0){

                $hp     = $vet[0];

                $id     = $hp->getId();            
                $codigo = $hp->getCodigo();
                $desc   = $hp->getDescricao();

                $tpl->assign('id',$id);
                $tpl->assign('codigo',$codigo);
                $tpl->assign('desc',utf8_encode($desc));

            }
        }
        

       

	/**************************************************************/

	$tpl->printToScreen();

?>
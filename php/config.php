<?php

	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/config.htm');
	$tpl->prepare();

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
				
		$pathFile      = '../public/config.json';
		$configJson    = file_get_contents($pathFile);
		$installConfig = json_decode($configJson);
		
		$pathFilebird       = '../public/configbird.json';
		$configJsonbird     = file_get_contents($pathFilebird);
		$installConfigbird  = json_decode($configJsonbird);
		
		$ck  = "";
		$ck1 = "";
		
		if($installConfig->somtribcusto == 'S'){			
			$ck = "selected";				
		}else{			
			$ck1 = "selected";			
		}
				
		$tpl->assign('ck',$ck);
		$tpl->assign('ck1',$ck1);
		
		$prc  = "";
		$prc2 = "";

		if($installConfig->atualizaprecocusto == 'S'){			
			$prc = "selected";				
		}else{			
			$prc2 = "selected";			
		}
		$tpl->assign('prc',$prc);
		$tpl->assign('prc2',$prc2);
	
	    $sel  = "";
		$sel1 = "";
		
		if($installConfig->tpAmb == '1'){
			$sel  = "selected";
		}else{
			$sel1 = "selected";
		}
	
		$tpl->assign('sel',$sel);
		$tpl->assign('sel1',$sel1);
		
		$apro1 = "";
		$apro2 = "";
		if(empty($installConfig->apropria)){
			$tpl->assign('apro1','');
			$tpl->assign('apro2','selected');
		}else{

			if($installConfig->apropria == 'S'){
				$apro1 = 'selected';
			}else{
				$apro2 = 'selected';
			}

			$tpl->assign('apro1',$apro1);
			$tpl->assign('apro2',$apro2);
		}


		$razaosocial = $installConfig->razaosocial;
		$cnpj        = $installConfig->cnpj;
		$senhacert   = $installConfig->senhacert;
		$empresa     = $installConfig->empresa;

		$tpl->assign('razaosocial',$razaosocial);
		$tpl->assign('cnpj',$cnpj);
		$tpl->assign('senhacert',$senhacert);
		$tpl->assign('empresa',$empresa);
		
		$tpl->assign('hostbird',$installConfigbird->firebird->hostbird);
		$tpl->assign('userbird',$installConfigbird->firebird->userbird);
		$tpl->assign('senhabird',$installConfigbird->firebird->senhabird);
		$form1 = "";
		$form2 = "";
		if($installConfigbird->FormulacaoBoiCasado == 'S'){
			$form1 = "selected";
		}else{
			$form2 = "selected";
		}
		$tpl->assign('form1',$form1);
		$tpl->assign('form2',$form2);
		
		$relcomp1 = "";
		$relcomp2 = "";
		if(!empty($installConfig->relcomp)){
			if($installConfig->relcomp == 'S'){
				$relcomp1 = "selected";
			}else{
				$relcomp2 = "selected";
			}

			$tpl->assign('relcomp1',$relcomp1);
			$tpl->assign('relcomp2',$relcomp2);
		}

		$listformulacao = json_encode($installConfigbird->listformulacao,true);
		$lista          = json_decode($listformulacao);

		if($lista){
			foreach($lista as $key=>$val){

				$tpl->newBlock('listar');

				$tpl->assign('cod',$key);
				$tpl->assign('id',$val->ID);
				$tpl->assign('desc',$val->DESC);
				$tpl->assign('qtdesc',$val->QTDPECAS);
				$tpl->assign('perc',$val->PERC);

			}

		}

	/**************************************************************/

	$tpl->printToScreen();



?>
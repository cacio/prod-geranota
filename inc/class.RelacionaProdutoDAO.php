<?php

require_once('inc.autoload.php');
require_once('inc.connectfirebird.php');

class RelacionaProdutoDAO{

	private $dba;

	public function RelacionaProdutoDAO(){

		$dba = new DbAdmin('firebird');
		$dba->connect(HOSTS,USERS,SENHAS,BDS);
		$this->dba = $dba;
	}

	public function verificaRelacionamento($cod,$codf){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select first(1) * from relaciona_produtos r
				where r.idprod ='".$cod."' and r.IDFORNEC = '".$codf."' order by r.ID DESC ";

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			$id			  = $prod->ID; 
			$idfornec     = $prod->IDFORNEC;
			$idprod       = $prod->IDPROD;
			$idprodrel    = $prod->IDPROREL;
			$QTDPUN		  = $prod->QTDPUN;	
			$cfop		  = $prod->IDCFOP;
			$NPEC_CX	  = $prod->NPEC_CX;
			$vator		  = $prod->VATOR;

			$rel = new RelacionaProduto();
			
			$rel->setIdFornecedor($idfornec);
			$rel->setIdProduto($idprod);
			$rel->setIdProdutoRelacionado($idprodrel);
			$rel->setQtdPorUnidade($QTDPUN);
			$rel->setCodigo($id);
			$rel->setCfop($cfop);
			$rel->setNpcCx($NPEC_CX);
			$rel->setVator($vator);

			$vet[$i++] = $rel;

		

		}

		return $vet;

	}

	public function verificaRelacionamentoHash($cod,$codf){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select first(1) * from relaciona_produtos r
				where r.HASH ='".$cod."' and r.IDFORNEC = '".$codf."' order by r.ID DESC ";

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			$id			  = $prod->ID; 
			$idfornec     = $prod->IDFORNEC;
			$idprod       = $prod->IDPROD;
			$idprodrel    = $prod->IDPROREL;
			$QTDPUN		  = $prod->QTDPUN;	
			$cfop		  = $prod->IDCFOP;
			$NPEC_CX	  = $prod->NPEC_CX;
			$vator		  = $prod->VATOR;

			$rel = new RelacionaProduto();
			
			$rel->setIdFornecedor($idfornec);
			$rel->setIdProduto($idprod);
			$rel->setIdProdutoRelacionado($idprodrel);
			$rel->setQtdPorUnidade($QTDPUN);
			$rel->setCodigo($id);
			$rel->setCfop($cfop);
			$rel->setNpcCx($NPEC_CX);
			$rel->setVator($vator);

			$vet[$i++] = $rel;

		

		}

		return $vet;

	}
	
	public function BuscaRelacionamento($cod){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select r.*,p.descricao,n.nome from relaciona_produtos r
				left join
				  	produtos p on (p.codigo = r.idprorel)
				  left join 
				  	natureza n on (n.codigo = r.idcfop) 
								where r.id = ".$cod."";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			$id			  = $prod->ID; 
			$idfornec     = $prod->IDFORNEC;
			$idprod       = $prod->IDPROD;
			$idprodrel    = $prod->IDPROREL;
			$QTDPUN		  = $prod->QTDPUN;	
			$unprod		  = $prod->UNPROD;					
			$desc		  = utf8_encode($prod->DESCRICAO);	
			$cfopnome     = $prod->NOME;
			$idcfop		  = $prod->IDCFOP;
			$NPEC_CX	  = $prod->NPEC_CX;
			$vator		  = $prod->VATOR;

			$rel = new RelacionaProduto();
			
			$rel->setIdFornecedor($idfornec);
			$rel->setIdProduto($idprod);
			$rel->setIdProdutoRelacionado($idprodrel);
			$rel->setQtdPorUnidade($QTDPUN);
			$rel->setCodigo($id);
			$rel->setUnidadeProduto($unprod);
			$rel->setProdDesc($desc);
			$rel->setCfop($idcfop);
			$rel->setNomeCfop($cfopnome);
			$rel->setNpcCx($NPEC_CX);
			$rel->setVator($vator);

			$vet[$i++] = $rel;

		

		}

		return $vet;

	}

	public function ListarRelacionamentoIncorretos($idprod,$idfor){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select
		r.id,
		iif((p.codigo is null or p.codigo = ''),r.idprorel,p.codigo) as codigo,
		iif((p.descricao is null or p.descricao = ''),'Sem descrição',p.descricao) as descricao
		from relaciona_produtos r
	left join produtos p on (p.codigo = r.idprorel)
					where r.idprod ='{$idprod}' and r.IDFORNEC = '{$idfor}' order by r.ID DESC";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			$id			  = $prod->ID; 
			$codigo       = $prod->CODIGO;
			$desc         = $prod->DESCRICAO;

			$rel = new RelacionaProduto();
			
			
			$rel->setCodigo($id);
			$rel->setIdProduto($codigo);
			$rel->setProdDesc($desc);
			

			$vet[$i++] = $rel;

		

		}

		return $vet;

	}

	public function ListarRelacionamentoIncorretosHash($idprod,$idp,$idfor){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select
		r.id,
		iif((p.codigo is null or p.codigo = ''),r.idprorel,p.codigo) as codigo,
		iif((p.descricao is null or p.descricao = ''),'Sem descrição',p.descricao) as descricao
		from relaciona_produtos r
	left join produtos p on (p.codigo = r.idprorel)
					where r.HASH ='{$idprod}' and r.IDFORNEC = '{$idfor}' order by r.ID DESC";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			$id			  = $prod->ID; 
			$codigo       = $prod->CODIGO;
			$desc         = $prod->DESCRICAO;

			$rel = new RelacionaProduto();
			
			
			$rel->setCodigo($id);
			$rel->setIdProduto($codigo);
			$rel->setProdDesc($desc);
			

			$vet[$i++] = $rel;

		

		}

		return $vet;

	}	
	
	public function inserir($rel){
		
		$dba = $this->dba;
		
		$idfornec  = $rel->getIdFornecedor();
		$idprod    = $rel->getIdProduto();
		$idprodrel = $rel->getIdProdutoRelacionado();
		$unfor	   = $rel->getUnidadeFornecedor();
		$unpro	   = $rel->getUnidadeProduto();
		$qtdpun	   = $rel->getQtdPorUnidade();
		$cfop      = $rel->getCfop();
		$cxpc	   = $rel->getNpcCx();
		$vator	   = $rel->getVator();
		$hash      = $rel->getHash();

		$sql =" insert into relaciona_produtos
				(idfornec,
				idprod,
				idprorel,
				unforn, 
				unprod, 
				qtdpun,
				IDCFOP,
				NPEC_CX,
				VATOR,
				HASH)
				values
				('".$idfornec."',
				'".$idprod."',
				'".$idprodrel."',
				'".$unfor."',
				'".$unpro."',
				".$qtdpun.",
				'".$cfop."',
				".$cxpc.",
				".$vator.",
				'".$hash."')";
 
		$res = $dba->query($sql);
		
	}
	
	public function alterar($rel){
		
		$dba = $this->dba;
		
		$cod	   = $rel->getCodigo();
		$idfornec  = $rel->getIdFornecedor();
		$idprod    = $rel->getIdProduto();
		$idprodrel = $rel->getIdProdutoRelacionado();
		$unfor	   = $rel->getUnidadeFornecedor();
		$unpro	   = $rel->getUnidadeProduto();
		$qtdpun	   = $rel->getQtdPorUnidade();
		$cfop      = $rel->getCfop();
		$cxpc	   = $rel->getNpcCx();
		$vator	   = $rel->getVator();

		$sql ="update relaciona_produtos 
					set idfornec = '".$idfornec."',
					  idprorel = '".$idprodrel."',
					  idprod = '".$idprod."',
					  unforn = '".$unfor."',
					  unprod = '".$unpro."',
					  qtdpun = ".$qtdpun.",
					  IDCFOP = '".$cfop."',
					  NPEC_CX = ".$cxpc.",
					  VATOR   = ".$vator."				  
					  where id = ".$cod."";
 		//echo $sql;
		$res = $dba->query($sql);
		
	}
	
	public function ProximoId(){

		$dba = $this->dba;

		$vet = array();


		$sql = 'select gen_id(gen_relaciona_produtos_id,0) + 1 as PROXIMOID from rdb$database';
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$PROXIMOID	= $lf->PROXIMOID;						
			
			$rel = new RelacionaProduto();			

			$rel->setProximoId($PROXIMOID);
			
			$vet[$i++] = $rel;

		}

		return $vet;

	}	
}

?>
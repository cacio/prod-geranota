<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class ProdutosDAO{



	

	private $dba;



	public function ProdutosDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function ListaProduto(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ='select first(10) * from produtos';

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$descricao  = $prod->DESCRICAO;
			$precovenda = $prod->PRECO_VENDA;

			$pro = new Produtos();

			

			$pro->setCodigo($codigo);
			$pro->setDescricao($descricao);
			$pro->setPrecovenda($precovenda);
		
			

			$vet[$i++] = $pro;

		

		}

		return $vet;

	}

	public function BuscaProduto($sarch){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select * from produtos p 
				where p.descricao like '%'||UPPER('$sarch')||'%' or  
				      p.codigo = '$sarch'";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$descricao  = $prod->DESCRICAO;
			$precovenda = $prod->PRECO_VENDA;
			$unidade    = $prod->UNIDADE;
			
			$pro = new Produtos();

			

			$pro->setCodigo($codigo);
			$pro->setDescricao(utf8_encode($descricao));
			$pro->setPrecovenda($precovenda);
			$pro->setUnidade($unidade);
			

			$vet[$i++] = $pro;

		

		}

		return $vet;

	}
	
	public function ProdutoFormulacao($cod){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select * from produtos p 
				where p.codigo = '".$cod."'";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$descricao  = $prod->DESCRICAO;
			$precovenda = $prod->PRECO_VENDA;
			$grupo      = $prod->GRUPO;
			$margem     = $prod->MARGEM_LUCRO;
			$ulticusto  = $prod->ULTIMO_CUSTO;
			$PRECDESCAV = $prod->PERCDESCAV;
			
			$pro = new Produtos();			

			$pro->setCodigo($codigo);
			$pro->setDescricao(utf8_encode($descricao));
			$pro->setPrecovenda($precovenda);
			$pro->setGrupo($grupo);
			$pro->setMargem($margem);			
			$pro->setUltimoCusto($ulticusto);
			$pro->setPrecDescAv($PRECDESCAV);
										
			$vet[$i++] = $pro;

		

		}

		return $vet;

	}
	
	public function ListaProdutoUm($id){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select 
					p.dtcontagem, 
					p.qtdecontada,
					p.codigo
				from
					produtos p
				where
					p.codigo = '".$id."'";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo      = $prod->CODIGO;
			$dtcontagem  = $prod->DTCONTAGEM;
			$qtdcontagem = $prod->QTDECONTADA;
 	
			$pro = new Produtos();

			

			$pro->setCodigo($codigo);
			$pro->setDataContagem($dtcontagem);
			$pro->setQuantidadeContagem($qtdcontagem);
			

			$vet[$i++] = $pro;

		

		}

		return $vet;

	}
	
	public function RelatorioProdutoEstoqueAtu(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				P.CODIGO,
				P.DESCRICAO,
				P.ESTOQUE_ATUAL,
				G.CODIGO as CODGRUPO,
				G.DESCRICAO AS DESCGRUP,
				P.CUSTO_MEDIO
			 FROM PRODUTOS P
			 INNER JOIN GRUPOS G ON (G.CODIGO = P.GRUPO)
			 LEFT JOIN NATUREZA N ON (N.CODIGO = P.CFOPESPECIFO)
			 WHERE IIF(P.CFOPESPECIFO IS NOT NULL,N.ST_TRIB_ICMS,P.SITUACAO_TRIBU) IN ('10','30','60','70') ORDER BY G.DESCRICAO";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo      = $prod->CODIGO;
			$descricao	 = $prod->DESCRICAO;
			$estatu      = $prod->ESTOQUE_ATUAL;
			$codgrupo	 = $prod->CODGRUPO;
			$descgrup    = $prod->DESCGRUP;
			$customedio  = $prod->CUSTO_MEDIO;
			
			$pro = new Produtos();

			$pro->setCodigo($codigo);
			$pro->setDescricao($descricao);
			$pro->setEstoqueAtual($estatu);		
			$pro->setGrupo($descgrup);
			$pro->setCodGrupo($codgrupo);
			$pro->setCustoMedio($customedio);
			
			$vet[$i++] = $pro;		

		}

		return $vet;

	}
	
	public function atualizacusto($pro){
		
		$dba = $this->dba;
		
		$codigo	       = $pro->getCodigo();
		$ultimocusto   = $pro->getUltimoCusto();
		$dtultimocusto = $pro->getDataUltimoCusto();
		$ultimocodforn = $pro->getUltimoCodFornec();
		$precomax      = $pro->getPrecoMaximo();
		$precovenda    = $pro->getPrecovenda();
		
		$sql = "update produtos
				  set					 					 
					  ULTIMO_CUSTO = ".$ultimocusto.",
					  DATA_ULTIMA_COMPRA = '".$dtultimocusto."',
					  ULTIMO_COD_FORNEC = '".$ultimocodforn."',
					  PRECO_VENDA = ".$precovenda.",
					  PRECO_MAX = ".$precomax."
				  where (codigo = '".$codigo."' )";
		//echo $sql;
		$res = $dba->query($sql);
	}
	
}

?>
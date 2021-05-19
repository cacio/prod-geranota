<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class CentroCustoDAO{

	

	private $dba;

	

	public function CentroCustoDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	


	public function BuscaCentroCusto($search){

			$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM centro_custo c
                where c.descricao like '%'||UPPER('$search')||'%' or c.codigo like '%'||UPPER('$search')||'%'";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			$codigo     = $prod->CODIGO;
			$descricao  = $prod->DESCRICAO;
			
			$con = new CentroCusto();

			$con->setCodigo($codigo);
			$con->setNome(utf8_encode($descricao));

			$vet[$i] = $con;

		

		}

		return $vet;

			

	}
	
	
	public function BuscaCentroCustoPorFornecedor($cfor,$search){

			$dba = $this->dba;

		$vet = array();


		$sql = "Select 
					c.codigo, c.descricao, f.fornecedor
				from
					centro_custo c
						INNER JOIN
					centro_custo_fornecedor f ON (f.centro = c.codigo)
				where
					f.fornecedor = '".$cfor."'
						and c.descricao like '%'
						|| UPPER('".$search."')
						|| '%'
						or c.codigo = '".$search."'
				GROUP BY c.codigo , c.descricao , f.fornecedor";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			$codigo     = $prod->CODIGO;
			$descricao  = $prod->DESCRICAO;
			$codfor		= $prod->FORNECEDOR;
			
			$con = new CentroCusto();

			$con->setCodigo($codigo);
			$con->setNome(utf8_encode($descricao));
			$con->setCodFornecedor($codfor);
			
			$vet[$i] = $con;

		

		}

		return $vet;

			

	}
	
	

}

?>
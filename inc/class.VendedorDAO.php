<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class VendedorDAO{



	

	private $dba;



	public function VendedorDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function BuscaVendedor($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM repre r
                where r.nome like '%'||UPPER('$search')||'%' or r.codigo = '$search'";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$nome       = $prod->NOME;

			$for = new Fornecedor();
			
			$for->setCodigo($codigo);
			$for->setNome(utf8_encode($nome));
			

			$vet[$i++] = $for;

		

		}

		return $vet;

	}
	
	
	public function ListaFornecedorPorUm($where2){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM repre r
				".$where2." ";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$nome       = $prod->NOME;

			$for = new Fornecedor();
			
			$for->setCodigo($codigo);
			$for->setNome(utf8_encode($nome));
			

			$vet[$i++] = $for;

		

		}

		return $vet;

	}
	

}

?>
<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class RepresentanteDAO{



	

	private $dba;



	public function RepresentanteDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function BuscaRepresentante($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM REPRE r
				where r.nome like '%'||UPPER('$search')||'%' or r.codigo = '$search'";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$nome       = $prod->NOME;

			$cli = new Cliente();
			
			$cli->setCodigo($codigo);
			$cli->setNome(utf8_encode($nome));
			

			$vet[$i++] = $cli;

		

		}

		return $vet;

	}
	
	
	public function ListaRepresentantePorUm($where2){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM REPRE r
				".$where2." ";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($prod = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $prod->CODIGO;
			$nome       = $prod->NOME;

			$cli = new Cliente();
			
			$cli->setCodigo($codigo);
			$cli->setNome(utf8_encode($nome));
			

			$vet[$i++] = $cli;

		

		}

		return $vet;

	}
	

}

?>
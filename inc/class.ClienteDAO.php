<?php

require_once('inc.autoload.php');
require_once('inc.connectfirebird.php');

class ClienteDAO{

	private $dba;

	public function ClienteDAO(){

		$dba = new DbAdmin('firebird');
		$dba->connect(HOSTS,USERS,SENHAS,BDS);
		$this->dba = $dba;
	}

	

	public function BuscaCliente($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM clientes c
				where c.nome like '%'||UPPER('$search')||'%' or c.codigo = '$search'";
 
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

	public function ListaCliente(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT c.codigo,c.nome,c.conta_ctb,f.reduzido ,f.descricao FROM clientes c
				left join financ_contas f on (f.codigo = c.conta_ctb)";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($row = ibase_fetch_object($res)){
			
			$codigo     = $row->CODIGO;
			$nome       = $row->NOME;
			$CONTA_CTB  = $row->CONTA_CTB;
			$REDUZIDO   = $row->REDUZIDO;
			$DESCRICAO  = $row->DESCRICAO;

			$cli = new Cliente();
			
			$cli->setCodigo($codigo);
			$cli->setNome(utf8_encode($nome));
			$cli->setContaCtb($CONTA_CTB);
			$cli->setReduzido($REDUZIDO);
			$cli->setDescricao($DESCRICAO);

			$vet[$i++] = $cli;

		

		}

		return $vet;

	}
	
	
	public function ListaclientePorUm($where2){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM clientes c
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

	public function updateconta($cli){

		$dba = $this->dba;

		$codigo   = $cli->getCodigo();
		$codconta = $cli->getContaCtb();

		$sql = "update clientes
				set
					conta_ctb = '{$codconta}'
				where codigo = '{$codigo}'";
		//echo $sql;
		$res = $dba->query($sql);

	}
	

}

?>
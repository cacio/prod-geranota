<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class CfopDAO{



	

	private $dba;
	private $conns;


	public function CfopDAO(){

		

		$dba   = new DbAdmin('firebird');

		$conns = $dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function ListaCfopUm($id){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select nt.baixaest,nt.CODIGOFISCAL,nt.CODIGO from natureza nt
				where nt.codigo = '".$id."' ";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($cf = ibase_fetch_object($res)){
					
			
			$baixaest    = $cf->BAIXAEST;
			$cod     	 = $cf->CODIGOFISCAL;
			$codigo  	 = $cf->CODIGO;
 	
			$cfop = new Cfop();

			
			$cfop->setBaixaEst($baixaest);
			$cfop->setCodigo($codigo);
			$cfop->setCodigoFiscal($cod);
			

			$vet[$i++] = $cfop;

		

		}
		//ibase_free_result($res);
		//ibase_close($dba);
		return $vet;

	}


public function VeridicaCfop($id){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select * from natureza nt
				where (nt.codigofiscal = '".preg_replace("/[^0-9]/", "", $id)."' or nt.CODIGO = '".$id."') ";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($cf = ibase_fetch_object($res)){
					
			
			$baixaest    = $cf->BAIXAEST;
			$codigo      = $cf->CODIGO;
		 	$nome        = $cf->NOME;
			
			$cfop = new Cfop();

			
			$cfop->setBaixaEst($baixaest);
			$cfop->setCodigo($codigo);
			$cfop->setDescricao($nome);

			$vet[$i++] = $cfop;

		

		}

		return $vet;

	}

	public function BuscaCfop($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select n.codigofiscal,N.CODIGO,cast(UPPER(n.nome) as varchar(500)) as descricao from natureza n
				where  (cast(UPPER(n.nome) as varchar(500)) like '%'||UPPER('$search')||'%' and n.codigofiscal < 5000) or (n.codigofiscal like '%'||UPPER('$search')||'%' and n.codigofiscal < 5000)";
		//echo $sql.'<br/>'; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($cf = ibase_fetch_object($res)){
					
			
			$desc    = $cf->DESCRICAO;
			$cod     = $cf->CODIGOFISCAL;
			$codigo  = $cf->CODIGO;
			
			$cfop = new Cfop();

			
			$cfop->setCodigo($codigo);
			$cfop->setCodigoFiscal($cod);
			$cfop->setDescricao(utf8_decode($desc));
			
			

			$vet[$i++] = $cfop;

		

		}

		return $vet;

	}
	
}

?>
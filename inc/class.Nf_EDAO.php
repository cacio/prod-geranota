<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class Nf_EDAO{



	

	private $dba;



	public function Nf_EDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function VerificaEmitenteDaNota($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM nf_e n ".$where." ";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($nfe = ibase_fetch_object($res)){

		
			//$prod = $dba->result($res,$i);
			
			$codigo     = $nfe->CODIGO;
			$nome       = $nfe->XNOME;

			$nf = new Nf_E();
			
			$nf->setCodigo($codigo);
			$nf->setNome(utf8_encode($nome));
			

			$vet[$i++] = $nf;

		

		}

		return $vet;

	}
	
	
	

}

?>
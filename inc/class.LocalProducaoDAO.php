<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class LocalProducaoDAO{



	

	private $dba;



	public function LocalProducaoDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function BuscaLocalProducao($sarch){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select * from LOCAL_PRODUCAO l
				where l.NOME like '%'||UPPER('$sarch')||'%' or  
				      l.CODIGO = '$sarch'";
					  
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lp = ibase_fetch_object($res)){		
			
			$codigo     = $lp->CODIGO;
			$nome       = $lp->NOME;

			$ldp = new LocalProducao();			

			$ldp->setCodigo($codigo);
			$ldp->setNome($nome);
						

			$vet[$i++] = $ldp;

		}

		return $vet;

	}

	public function inserir($flc){
	

		$dba = $this->dba;
	
		$sql ='';

		$res = $dba->query($sql);
			
	}

	

}

?>
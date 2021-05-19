<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class GrupoDAO{



	

	private $dba;



	public function GrupoDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function ListaGrupoPorUm($id){

		

		$dba = $this->dba;

		$vet = array();


		$sql =" select codigo, descricao
                         from grupos where codigo = ".$id." ";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($grup = ibase_fetch_object($res)){
						
			$codigo     = $grup->CODIGO;
			$nome       = $grup->DESCRICAO;

			$grupo = new Grupo();
			
			$grupo->setCodigo($codigo);
			$grupo->setNome(utf8_encode($nome));
			

			$vet[$i++] = $grupo;

		}

		return $vet;

	}
	
	public function ListaGrupoInventario($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql =" select codigo, descricao
                         from grupos ".$where." ";
 		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($grup = ibase_fetch_object($res)){
						
			$codigo     = $grup->CODIGO;
			$nome       = $grup->DESCRICAO;

			$grupo = new Grupo();
			
			$grupo->setCodigo($codigo);
			$grupo->setNome(utf8_encode($nome));
			

			$vet[$i++] = $grupo;

		}

		return $vet;

	}
	
	public function BuscaGrupo($sarch){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select * from grupos g
                where g.descricao like '%'||UPPER('$sarch')||'%' or
                      g.codigo = '$sarch'";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($grup = ibase_fetch_object($res)){
						
			$codigo     = $grup->CODIGO;
			$nome       = $grup->DESCRICAO;

			$grupo = new Grupo();
			
			$grupo->setCodigo($codigo);
			$grupo->setNome(utf8_encode($nome));
			

			$vet[$i++] = $grupo;

		}

		return $vet;

	}
	
	

}

?>
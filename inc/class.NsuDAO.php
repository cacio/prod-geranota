<?php
	require_once('inc.autoload.php');
	require_once('inc.connect.php');
	
	class NsuDAO{
		
		private $dba;
		
		public function NsuDAO(){
			$dba = new DbAdmin('mysql');
			$dba->connect(HOST,USER,SENHA,BD);
			$this->dba = $dba;
		}
		
		public function BuscaNsu($idemp){

			$dba = $this->dba;
			$vet = array();

			$sql = 'SELECT * FROM nsu n where (data = current_date() or data is null) and atualizo = "2" and idemp = "'.$idemp.'" order by id desc LIMIT 1';

			$res = $dba->query($sql);
			$num = $dba->rows($res);

			for($i=0; $i < $num; $i++){

				$cod        = $dba->result($res,$i,'id');	
				$ultNSU     = $dba->result($res,$i,'ultNSU');
				$maxNSU     = $dba->result($res,$i,'maxNSU');
				$data       = $dba->result($res,$i,'data');
				$atualizo   = $dba->result($res,$i,'atualizo');
				
				$nsu = new Nsu();

				$nsu->setCodigo($cod);
				$nsu->setUltNsu($ultNSU);
				$nsu->setMaxNsu($maxNSU);
				$nsu->setData($data);
				$nsu->setAtualizo($atualizo);
				
				$vet[$i] = $nsu;

			}

			return $vet;
		}	
		
		public function Inserir($nsu){
			$dba = $this->dba;
			
			$ultNSU   = $nsu->getUltNsu();
			$maxNSU   = $nsu->getMaxNsu();
			$data     = $nsu->getData();			
			$idemp    = $nsu->getIdEmp();

			$sql = "INSERT INTO `nsu`
					(`ultNSU`,
					`maxNSU`,
					`data`,
					`atualizo`,
					`idemp`)
					VALUES
					(".$ultNSU.",
					".$maxNSU.",
					'".$data."',
					 2,
					".$idemp.")";			
			
			$res = $dba->query($sql);
		}
		
	}
	
?>
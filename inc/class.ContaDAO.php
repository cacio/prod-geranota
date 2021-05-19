<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ContaDAO{
	
	private $dba;
	
	public function ContaDAO(){
		
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	
	}
	public function listaconta(){
			
			$dba = $this->dba;
			$vet = array();
			
			$sql = 'SELECT o.*,c.cen_nome FROM tb_contas o 
					inner join tb_centros c on (c.Codigo = o.con_idgrupo)';
			$res = $dba->query($sql);
			$num = $dba->rows($res);
			
				
							
			for($i = 0; $i < $num; $i++){
				
				$cod = $dba->result($res,$i,'Codigo');
				$nom = $dba->result($res,$i,'con_nome');
				$idg = $dba->result($res,$i,'con_idgrupo');
				$nomg = $dba->result($res,$i,'cen_nome');
				
				$con = new Conta();
				
				$con->setCodigo($cod);
				$con->setNome($nom);
				$con->setIdgrupo($idg);
				$con->setNomgrupo($nomg);
				
				$vet[$i] = $con;
			}
			
			return $vet;
			
	}
	public function listacontamenosum($nom){
			
			$dba = $this->dba;
			$vet = array();
			
			$sql = 'SELECT o.*,c.cen_nome FROM tb_contas o 
					inner join tb_centros c on (c.Codigo = o.con_idgrupo) where o.con_nome <> "'.$nom.'"';
			$res = $dba->query($sql);
			$num = $dba->rows($res);
			
				
							
			for($i = 0; $i < $num; $i++){
				
				$cod = $dba->result($res,$i,'Codigo');
				$nom = $dba->result($res,$i,'con_nome');
				$idg = $dba->result($res,$i,'con_idgrupo');
				$nomg = $dba->result($res,$i,'cen_nome');
				
				$con = new Conta();
				
				$con->setCodigo($cod);
				$con->setNome($nom);
				$con->setIdgrupo($idg);
				$con->setNomgrupo($nomg);
				
				$vet[$i] = $con;
			}
			
			return $vet;
			
	}
	public function buscaconta($string){
			
			$dba = $this->dba;
			$vet = array();
			
			$sql = 'SELECT o.*,c.cen_nome FROM tb_contas o 
					inner join tb_centros c on (c.Codigo = o.con_idgrupo)
					where o.con_nome like "%'.$string.'%"';
			$res = $dba->query($sql);
			$num = $dba->rows($res);
			
				
							
			for($i = 0; $i < $num; $i++){
				
				$cod = $dba->result($res,$i,'Codigo');
				$nom = $dba->result($res,$i,'con_nome');
				$idg = $dba->result($res,$i,'con_idgrupo');
				$nomg = $dba->result($res,$i,'cen_nome');
				
				$con = new Conta();
				
				$con->setCodigo($cod);
				$con->setNome($nom);
				$con->setIdgrupo($idg);
				$con->setNomgrupo($nomg);
				
				$vet[$i] = $con;
			}
			
			return $vet;
			
	}
	public function listacontaum($cod){
			
			$dba = $this->dba;
			$vet = array();
			
			
			
			$sql = 'SELECT o.*,c.cen_nome FROM tb_contas o 
					inner join tb_centros c on (c.Codigo = o.con_idgrupo)  where o.Codigo='.$cod;
			$res = $dba->query($sql);
			$num = $dba->rows($res);
			
			for($i = 0; $i < $num; $i++){
				
				$cod  = $dba->result($res,$i,'Codigo');
				$nom  = $dba->result($res,$i,'con_nome');
				$idg  = $dba->result($res,$i,'con_idgrupo');
				$nomg = $dba->result($res,$i,'cen_nome');
				
				$con = new Conta();
				
				$con->setCodigo($cod);
				$con->setNome($nom);
				$con->setIdgrupo($idg);
				$con->setNomgrupo($nomg);
				
				$vet[$i] = $con;
			}
			
			return $vet;
	}
	
	public function inserir($con){
		
		$dba = $this->dba;
		
		$nom = $con->getNome();
		$idg = $con->getIdgrupo();
			
			
		$sql = 'INSERT INTO tb_contas
				(con_nome,
				con_idgrupo)
				VALUES
				("'.$nom.'",
				'.$idg.')';
		
		$dba->query($sql);
	
	}
	
	public function alterar($con){
	
		$dba = $this->dba;
		
		$cod = $con->getCodigo();
		$nom = $con->getNome();
		$idg = $con->getIdgrupo();
			
			
		$sql = 'UPDATE tb_contas
				SET
				con_nome  = "'.$nom.'",
				con_idgrupo = '.$idg.'
				WHERE Codigo ='.$cod;
		
		$dba->query($sql);
		
	}
	
	public function deletar($con){
		
		
		$dba = $this->dba;
		
		$idc = $con->getCodigo();
			
			
		$sql = 'DELETE FROM tb_contas
				WHERE Codigo ='.$idc;
		
		$dba->query($sql);
		
	}
	
}
?>
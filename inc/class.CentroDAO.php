<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class CentroDAO{

	private $dba;
	
	public function CentroDAO(){
		
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	
	}
	
	public function listacentro(){
		
		$dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT * FROM tb_centros';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		
		for($i = 0; $i < $num; $i++){
			
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'cen_nome');
			
			
			$cen = new Centro();
			
			$cen->setCodigo($cod);
			$cen->setNome($nom);
			
			$vet[$i] = $cen;
		
		}
		return $vet;
		
	}
	public function listacentromenoum($nomg){
		
		$dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT * FROM tb_centros where cen_nome <> "'.$nomg.'"';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		
		for($i = 0; $i < $num; $i++){
			
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'cen_nome');
			
			
			$cen = new Centro();
			
			$cen->setCodigo($cod);
			$cen->setNome($nom);
			
			$vet[$i] = $cen;
		
		}
		return $vet;
		
	}
	
	public function listacentroum($idg){
		
		$dba = $this->dba;
		
		$vet = array();
		
		$sql = 'SELECT * FROM tb_centros where Codigo='.$idg;
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		
		for($i = 0; $i < $num; $i++){
			
			$cod = $dba->result($res,$i,'Codigo');
			$nom = $dba->result($res,$i,'cen_nome');
			
			
			$cen = new Centro();
			
			$cen->setCodigo($cod);
			$cen->setNome($nom);
			
			$vet[$i] = $cen;
		
		}
		return $vet;
		
	}
	
	public function inserir($gru){
		
		$dba = $this->dba;
		
		$nom = $gru->getNome();
		
		$sql = 'INSERT INTO tb_centros
				(cen_nome)
				VALUES
				("'.$nom.'")';
		
		$dba->query($sql);
	
	}
	
	public function deletar($gru){
		
		$dba = $this->dba;
		
		$cod = $gru->getCodigo();
		
		$sql = 'DELETE FROM tb_centros
				WHERE Codigo='.$cod;
		
		$dba->query($sql);
		
	}
	
	public function editar($gru){
		
		$dba = $this->dba;
		
		$nom = $gru->getNome();
		$cod = $gru->getCodigo();
		
		$sql = 'UPDATE tb_centros
				SET
				cen_nome = "'.$nom.'"
				WHERE Codigo ='.$cod;
		
		$dba->query($sql);
	
	}
	
	public function balancete($dti,$dtf,$idg){
		
		$dba =  $this->dba;
		
		$vet =  array();
		
		if($idg == -1){
			
			$condicao = '';
			
		}else{
			
			$condicao = 'and c.Codigo = "'.$idg.'"';	
			
		}
		
		$sql ='select DATE_FORMAT(m.mov_data,"%d-%m-%Y") as mov_data,a.Codigo,a.con_nome,
				sum(case when m.mov_d_c = "C" then 
					m.mov_valor else "" 
				end) as "ENTRADA", 
				sum(case when m.mov_d_c = "D" then m.mov_valor 
					else "" 
				end) AS "SAIDA" 
				 from tb_centros c
				inner join tb_contas a on (a.con_idgrupo = c.Codigo)
				inner join tb_movim m on (m.idconta = a.Codigo) where cast(m.mov_data as date) 
				between "'.$dti.'" and "'.$dtf.'" '.$condicao.' group by a.Codigo';
			
			//echo $sql;
			
			$res = $dba->query($sql);	
			$num = $dba->rows($res);
			
			for($i=0; $i < $num; $i++){
				
				$cod = $dba->result($res,$i,'Codigo');
				$nom = $dba->result($res,$i,'con_nome');
				$ent = $dba->result($res,$i,'ENTRADA');
				$sai = $dba->result($res,$i,'SAIDA');
				
				
				$cen = new Centro();
				
				$cen->setCodigo($cod);
				$cen->setNome($nom);
				$cen->setEntrada($ent);
				$cen->setSaida($sai);
				
				$vet[$i] = $cen;
			
			}
		return $vet;
	}
}
?>
<?php

require_once('inc.autoload.php');
require_once('inc.connectfirebird.php');

class FormPagRecDAO{
	

	private $dba;

	public function FormPagRecDAO(){

		$dba = new DbAdmin('firebird');
		$dba->connect(HOSTS,USERS,SENHAS,BDS);
		$this->dba = $dba;
	}
	

	public function ListaFromaPagRec(){	

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT
					FP.ID,
					FP.DESCRICAO,
					F.REDUZIDO,
					F.DESCRICAO as des
				FROM FORMAS_PAGREC  FP
				LEFT JOIN FINANC_CONTAS F ON (F.CODIGO = FP.ID_CONTA)";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($row = ibase_fetch_object($res)){
						
			$codigo     = $row->ID;
			$nome       = $row->DESCRICAO;
			$reduzido   = $row->REDUZIDO;
			$desc		= $row->DES;

			$pagrec = new FormPagRec();
			
			$pagrec->setCodigo($codigo);
			$pagrec->setNome(utf8_encode($nome));
			$pagrec->setReduzido($reduzido);
			$pagrec->setDescricao($desc);

			$vet[$i++] = $pagrec;

		}

		return $vet;

	}

	public function ListaFromaPagRecUm($id){	

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT
					FP.ID,
					FP.DESCRICAO,
					FP.ID_CONTA					
				FROM FORMAS_PAGREC  FP where FP.ID = '{$id}'";
 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($row = ibase_fetch_object($res)){
						
			$codigo     = $row->ID;
			$nome       = $row->DESCRICAO;
			$ID_CONTA   = $row->ID_CONTA;		

			$pagrec = new FormPagRec();
			
			$pagrec->setCodigo($codigo);
			$pagrec->setNome(utf8_encode($nome));
			$pagrec->setContaCtb($ID_CONTA);
			

			$vet[$i++] = $pagrec;

		}

		return $vet;

	}
	
	public function inserir($pagrec){
		
		$dba = $this->dba;

		$desc  = $pagrec->getNome();
		$conta = $pagrec->getContaCtb();

		$sql = "insert into formas_pagrec (descricao, id_conta)
				values ('{$desc}', {$conta})";

		$res = $dba->query($sql);

	}

	public function alterar($pagrec){
		
		$dba = $this->dba;

		$id    = $pagrec->getCodigo();
		$desc  = $pagrec->getNome();
		$conta = $pagrec->getContaCtb();

		$sql = "update formas_pagrec
				set descricao = '{$desc}',
					id_conta = {$conta}
				where id = {$id}";
		//echo "{$sql}";
		$res = $dba->query($sql);

	}

	public function deletar($pagrec){
		$dba = $this->dba;

		$id    = $pagrec->getCodigo();
		

		$sql = "DELETE FROM formas_pagrec
				where id = {$id}";
		//echo "{$sql}";
		$res = $dba->query($sql);


	}
	

}

?>
<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class FinancMovimentacaoDAO{

	

	private $dba;

	

	public function FinancMovimentacaoDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function ConsultaFinacMovimentacao($data,$conta){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select f.DATA,f.LANCAMENTO from FINANC_MOVIMENTACAO f
				where f.data = '".$data."' and f.conta = '".$conta."' ";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($f = ibase_fetch_object($res)){		
			
			$data  = $f->DATA;
			$lanca = $f->LANCAMENTO;
						
			$fm = new FinancMovimentacao();			

			$fm->setData($data);
			$fm->setLancamento($lanca);
			
			$vet[$i++] = $fm;

		}

		return $vet;

	}
	

	public function inserir($f){

		

		$dba = $this->dba;

		$conta         		 = $f->getConta();
		$data         		 = $f->getData();
		$historico     		 = $f->getHistorico();	
		$valor	       		 = $f->getValor();
		$debcre	      		 = $f->getDecre();  
		$cedente      		 = $f->getCedente();	
		$documento     		 = $f->getDocumento();
		$tipo	       		 = $f->getTipo();
		$lancamento    		 = $f->getLancamento();
		$conta_partida 		 = $f->getContaPartida();
		$lancamento_x  		 = $f->getLancamentoX();
		$historico_x 		 = $f->getHistoricoX();
		$controla_integracao = $f->getControlaIntegracao();
		$conciliacao		 = $f->getConcilhacao();	
		
		$sql = "insert into financ_movimentacao 
				(conta,
				data, 
				historico, 
				valor, 
				debcre, 
				cedente, 
				documento, 
				tipo, 
				lancamento, 
				conta_partida, 
				lancamento_x, 
				historico_x, 
				controla_integracao, 
				conciliacao)
				 values 
				('".$conta."', 
				 '".$data."', 
				 '".$historico."', 
				 ".$valor.", 
				'".$debcre."', 
				'".$cedente."', 
				'".$documento."', 
				'".$tipo."', 
				".$lancamento.", 
				'".$conta_partida."', 
				".$lancamento_x.", 
				'".$historico_x."', 
				".$controla_integracao.", 
				'".$conciliacao."')";
		//echo $sql;
		$dba->query($sql);

	

	}

	

	public function alterar($f){

	

		$dba = $this->dba;

		$valor	       		 = $f->getValor();		
		$lancamento    		 = $f->getLancamento();
		
		$sql = 'update financ_movimentacao
			   set 
				   valor = '.$valor.'
				where LANCAMENTO ='.$lancamento.'';

		

		$dba->query($sql);

		

	}

	

	public function deletar($con){

		

		

		$dba = $this->dba;

		

		$idc = $con->getCodigo();

			

			

		$sql = '';

		

		$dba->query($sql);

		

	}

	

}

?>
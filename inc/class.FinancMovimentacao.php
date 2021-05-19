<?php



class FinancMovimentacao{

	

	private $codigo;
	private $conta;
	private $data;
	private $historico;
	private $valor;
	private $decre;
	private $cedente;
	private $documento;
	private $tipo;
	private $lancamento;
	private $contapartida;
	private $lancamentox;
	private $historicox;
	private $controlaintegracao;
	private $cocilhacao;

	
	public function FinancMovimentacao(){

		//nada
	}

	public function getCodigo(){
	
		return $this->codigo;

	}

	public function setCodigo($codigo){

		$this->codigo = $codigo;

	}

	public function getConta(){	
	
		return $this->conta;

	}

	public function setConta($conta){

		$this->conta = $conta;

	}

	public function getData(){	
	
		return $this->data;

	}

	public function setData($data){

		$this->data = $data;

	}
	
	public function getHistorico(){	
	
		return $this->historico;

	}

	public function setHistorico($hist){

		$this->historico = $hist;

	}

	public function getValor(){	
	
		return $this->valor;

	}

	public function setValor($vl){

		$this->valor = $vl;

	}
	
	public function getDecre(){	
	
		return $this->decre;

	}

	public function setDecre($decre){

		$this->decre = $decre;

	}
	
	public function getCedente(){	
	
		return $this->cedente;

	}

	public function setCedente($cedente){

		$this->cedente = $cedente;

	}
	public function getDocumento(){	
	
		return $this->documento;

	}

	public function setDocumento($documento){

		$this->documento = $documento;

	}

	public function getTipo(){	
	
		return $this->tipo;

	}

	public function setTipo($tipo){

		$this->tipo = $tipo;

	}
	
	public function getLancamento(){	
	
		return $this->lancamento;

	}

	public function setLancamento($lancamento){

		$this->lancamento = $lancamento;

	}
	
	public function getContaPartida(){	
	
		return $this->contapartida;

	}

	public function setContaPartida($contapartida){

		$this->contapartida = $contapartida;

	}
	
	public function getLancamentoX(){	
	
		return $this->lancamentox;

	}

	public function setLancamentoX($lancamentox){

		$this->lancamentox = $lancamentox;

	}
	
	public function getHistoricoX(){	
	
		return $this->historicox;

	}

	public function setHistoricoX($histx){

		$this->historicox = $histx;

	}
	
	
	public function getControlaIntegracao(){	
	
		return $this->controlaintegracao;

	}

	public function setControlaIntegracao($cotrolaint){

		$this->controlaintegracao = $cotrolaint;

	}
	
	public function getConcilhacao(){	
	
		return $this->cocilhacao;

	}

	public function setConcilhacao($concilhacao){

		$this->cocilhacao = $concilhacao;

	}
	
}

?>
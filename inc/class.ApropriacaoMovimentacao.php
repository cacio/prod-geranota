<?php



class ApropriacaoMovimentacao{

	

	private $codigo;
	private $centro;
	private $conta;
	private $valor;
	private $nota;
	private $fornecedor;
	private $dataemissao;
	private $historico;
	private $debito;
	private $credito;
	private $cod_con_class;
	private $empresa;
	private $proximoid;
	
	public function ApropriacaoMovimentacao(){

		//nada
	}

	public function getCodigo(){
	
		return $this->codigo;

	}

	public function setCodigo($codigo){

		$this->codigo = $codigo;

	}

	public function getCentro(){	
	
		return $this->centro;

	}

	public function setCentro($centro){

		$this->centro = $centro;

	}

	public function getConta(){	
	
		return $this->conta;

	}

	public function setConta($conta){

		$this->conta = $conta;

	}
	
	public function getValor(){	
	
		return $this->valor;

	}

	public function setValor($valor){

		$this->valor = $valor;

	}

	public function getNota(){	
	
		return $this->nota;

	}

	public function setNota($nota){

		$this->nota = $nota;

	}
	
	public function getFornecedor(){	
	
		return $this->fornecedor;

	}

	public function setFornecedor($fornec){

		$this->fornecedor = $fornec;

	}
	
	public function getDataEmissao(){	
	
		return $this->dataemissao;

	}

	public function setDataEmissao($dataemiss){

		$this->dataemissao = $dataemiss;

	}
	
	public function getHistorico(){	
	
		return $this->historico;

	}

	public function setHistorico($historico){

		$this->historico = $historico;

	}
	
	public function getDebito(){	
	
		return $this->debito;

	}

	public function setDebito($debito){

		$this->debito = $debito;

	}
	public function getCredito(){	
	
		return $this->credito;

	}

	public function setCredito($credito){

		$this->credito = $credito;

	}
	
	public function getCodConClass(){	
	
		return $this->cod_con_class;

	}

	public function setCodConClass($codconclass){

		$this->cod_con_class = $codconclass;

	}
	
	public function getEmpresa(){	
	
		return $this->empresa;

	}

	public function setEmpresa($empresa){

		$this->empresa = $empresa;

	}
	
	public function getProximoId(){

		return $this->proximoid;	

	}
	
	public function setProximoId($proximoid){

		$this->proximoid = $proximoid;

	}
}

?>
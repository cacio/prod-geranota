<?php

class Duplic{	

	private $Codigo;
	private $numero;
	private $valordoc;
	private $numeronota;
	private $vencimento;
	private $forneCodigo;
	private $forneNome;
	private $tipo;
	private $emissao;
	private $parcial1;
	private $parcial2;
	private $parcial3;
	private $parcial4;
	private $parcial5;
	private $data_parcial1;
	private $data_parcial2;
	private $data_parcial3;
	private $data_parcial4;
	private $data_parcial5;
	private $desc_abtm;
	private $valdevolucao;
	private $empresa;
	private $totalnota;
	
	public function Duplic(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	
	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getNumero(){

		return $this->numero;	

	}

	
	public function setNumero($numero){

		$this->numero = $numero;

	}
	
	public function getValorDoc(){

		return $this->valordoc;	

	}

	
	public function setValorDoc($valordoc){

		$this->valordoc = $valordoc;

	}
	
	public function getNumeroNota(){

		return $this->numeronota;	

	}

	
	public function setNumeroNota($numeronota){

		$this->numeronota = $numeronota;

	}
	
	public function getVencimento(){

		return $this->vencimento;	

	}

	
	public function setVencimento($vencimento){

		$this->vencimento = $vencimento;

	}
	
	public function getCodFornecedor(){

		return $this->forneCodigo;	

	}

	
	public function setCodFornecedor($codfor){

		$this->forneCodigo = $codfor;

	}
	
	public function getNomeFornevedor(){

		return $this->forneNome;	

	}

	
	public function setNomeFornevedor($nomfor){

		$this->forneNome = $nomfor;

	}
	
	public function getTipo(){

		return $this->tipo;	

	}
	
	public function setTipo($tipo){

		$this->tipo = $tipo;

	}
	
	public function getEmissao(){

		return $this->emissao;	

	}
	
	public function setEmissao($emissao){

		$this->emissao = $emissao;

	}	
	
	public function getParcial1(){

		return $this->parcial1;	

	}

	
	public function setParcial1($parcial1){

		$this->parcial1 = $parcial1;

	}
	
	public function getParcial2(){

		return $this->parcial2;	

	}

	
	public function setParcial2($parcial2){

		$this->parcial2 = $parcial2;

	}
	public function getParcial3(){

		return $this->parcial3;	

	}

	
	public function setParcial3($parcial3){

		$this->parcial3 = $parcial3;

	}
	public function getParcial4(){

		return $this->parcial4;	

	}

	
	public function setParcial4($parcial4){

		$this->parcial4 = $parcial4;

	}
	
	public function getParcial5(){

		return $this->parcial5;	

	}

	
	public function setParcial5($parcial5){

		$this->parcial5 = $parcial5;

	}
	
	public function getDataParcial1(){

		return $this->data_parcial1;	

	}

	
	public function setDataParcial1($dtparcial1){

		$this->data_parcial1 = $dtparcial1;

	}
	
	public function getDataParcial2(){

		return $this->data_parcial2;	

	}

	
	public function setDataParcial2($dtparcial2){

		$this->data_parcial2 = $dtparcial2;

	}
	
	public function getDataParcial3(){

		return $this->data_parcial3;	

	}

	
	public function setDataParcial3($dtparcial3){

		$this->data_parcial3 = $dtparcial3;

	}
	
	public function getDataParcial4(){

		return $this->data_parcial4;	

	}

	
	public function setDataParcial4($dtparcial4){

		$this->data_parcial4 = $dtparcial4;

	}
	
	public function getDataParcial5(){

		return $this->data_parcial5;	

	}

	
	public function setDataParcial5($dtparcial5){

		$this->data_parcial5 = $dtparcial5;

	}	
	public function getDescAbtm(){

		return $this->desc_abtm;	

	}

	
	public function setDescAbtm($desc_abtm){

		$this->desc_abtm = $desc_abtm;

	}
	
	public function getValorDevolucao(){

		return $this->valdevolucao;	

	}

	
	public function setValorDevolucao($valdevol){

		$this->valdevolucao = $valdevol;

	}
	
	
	public function getEmpresa(){

		return $this->empresa;	

	}

	
	public function setEmpresa($emp){

		$this->empresa = $emp;

	}
	
	
	public function getTotalNota(){

		return $this->totalnota;	

	}

	
	public function setTotalNota($totnota){

		$this->totalnota = $totnota;

	}
}

?>
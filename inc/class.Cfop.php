<?php

class Cfop{

	

	

	private $Codigo;
	private $descricao;
	private $baixaest;
	private $codigofiscal;
	
	public function Cfop(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	

	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getDescricao(){

		return $this->descricao;	

	}

	
	public function setDescricao($descricao){

		$this->descricao = $descricao;

	}

	public function getBaixaEst(){

		return $this->baixaest;	

	}

	
	public function setBaixaEst($baixaest){

		$this->baixaest = $baixaest;

	}
	
	public function getCodigoFiscal(){

		return $this->codigofiscal;	

	}

	
	public function setCodigoFiscal($codfisc){

		$this->codigofiscal = $codfisc;

	}

	
}

?>
<?php

class FormPagRec{


	private $Codigo;
	private $NOME;
	private $reduzido;
	private $desc;
	private $CONTA_CTB;
	
	public function FormPagRec(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getNome(){

		return $this->NOME;	

	}

	public function setNome($nome){

		$this->NOME = $nome;

	}	

	public function getReduzido(){
		return $this->reduzido;	
	}

	public function setReduzido($reduzido){
		$this->reduzido = $reduzido;
	}

	public function getDescricao(){
		return $this->desc;	
	}

	public function setDescricao($desc){
		$this->desc = $desc;
	}

	public function getContaCtb(){
		return $this->CONTA_CTB;	
	}

	public function setContaCtb($contactb){
		$this->CONTA_CTB = $contactb;
	}
	

}

?>
<?php

class Grupo{

	

	

	private $Codigo;
	private $NOME;
	
	
	public function Grupo(){

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
	

}

?>
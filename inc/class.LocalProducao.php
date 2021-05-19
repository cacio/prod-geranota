<?php

class LocalProducao{

	

	

	private $Codigo;
	private $nome;
	
	
	public function LocalProducao(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	
	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getNome(){

		return $this->nome;	

	}

	
	public function setNome($nome){

		$this->nome = $nome;

	}
	

}

?>
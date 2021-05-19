<?php
class Centro{
	
	
	private $codigo;
	private $nome;
	private $entrada;
	private $saida;
	
	public function Centro(){
		//nada
	}
	
	public function getCodigo(){
		return $this->codigo;
	}
	
	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}
	
	public function getNome(){
		return $this->nome;
	}
	
	public function setNome($nome){
		$this->nome = $nome;
	}
	public function getEntrada(){
		return $this->entrada;
	}
	
	public function setEntrada($entrada){
		$this->entrada = $entrada;
	}
	
	public function getSaida(){
		return $this->saida;
	}
	
	public function setSaida($saida){
		$this->saida = $saida;
	}


}	
?>
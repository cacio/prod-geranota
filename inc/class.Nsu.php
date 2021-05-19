<?php

class Nsu{

	private $Codigo;
	private $ultNSU;
	private $maxNSU;
	private $data;
	private $atualizo;
	private $idemp;

	public function Nsu(){
		//nada
	}

	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getUltNsu(){
		return $this->ultNSU;	
	}

	public function setUltNsu($ultnsu){
		$this->ultNSU = $ultnsu;
	}	
	
	
	public function getMaxNsu(){
		return $this->maxNSU;	
	}

	public function setMaxNsu($maxnsu){
		$this->maxNSU = $maxnsu;
	}
	
	public function getData(){
		return $this->data;	
	}

	public function setData($data){
		$this->data = $data;
	}
	
	public function getAtualizo(){
		return $this->atualizo;	
	}

	public function setAtualizo($atu){
		$this->atualizo = $atu;
	}

	public function getIdEmp(){
		return $this->idemp;
	}

	public function setIdEmp($idemp){
		$this->idemp = $idemp;
	}
}

?>
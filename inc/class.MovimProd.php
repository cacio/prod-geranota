<?php

class MovimProd{

	

	

	private $Codigo;
	private $lote;
	private $dt_prod;
	private $id_prod;
	private $local;
	private $qtd;
	private $custo;
	private $ent_sai;
	private $desc;
	private $grupo;
	
	public function MovimProd(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	

	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getLote(){

		return $this->lote;	

	}

	
	public function setLote($lote){

		$this->lote = $lote;

	}

	public function getDtProd(){

		return $this->dt_prod;	

	}

	
	public function setDtProd($dtprod){

		$this->dt_prod = $dtprod;

	}
	
	public function getIdProd(){

		return $this->id_prod;	

	}

	
	public function setIdProd($idprod){

		$this->id_prod = $idprod;

	}
	
	
	public function getLocal(){

		return $this->local;	

	}

	
	public function setLocal($local){

		$this->local = $local;

	}
	
	public function getQtd(){

		return $this->qtd;	

	}

	
	public function setQtd($qtd){

		$this->qtd = $qtd;

	}
	
	public function getCusto(){

		return $this->custo;	

	}

	
	public function setCusto($custo){

		$this->custo = $custo;

	}
	
	public function getEntSai(){

		return $this->ent_sai;	

	}

	
	public function setEntSai($entsai){

		$this->ent_sai = $entsai;

	}
	
	public function getDescricao(){

		return $this->desc;	

	}

	
	public function setDescricao($desc){

		$this->desc = $desc;

	}
	
	
	public function getGrupo(){

		return $this->grupo;	

	}

	
	public function setGrupo($grupo){

		$this->grupo = $grupo;

	}
	
}

?>
<?php

class RelacionaProduto{

	private $Codigo;
	private $idfornec;
	private $idprod;
	private $idprorel;
	private $UNFORN;
	private $UNPROD;
	private $QTDPUN;
	private $proddesc;
	private $proximoid;
	private $cfop;
	private $nomecfop;
	private $npec_cx;
	private $vator;
	private $hash;

	public function RelacionaProduto(){

		//nada

	}


	public function getCodigo(){
		return $this->Codigo;	
	}

	public function setCodigo($codigo){
		$this->Codigo = $codigo;
	}

	public function getIdFornecedor(){
		return $this->idfornec;	
	}

	public function setIdFornecedor($idfornec){
		$this->idfornec = $idfornec;
	}
	
	public function getIdProduto(){
		return $this->idprod;	
	}

	public function setIdProduto($idprod){
		$this->idprod = $idprod;
	}
	
	public function getIdProdutoRelacionado(){
		return $this->idprorel;	
	}

	public function setIdProdutoRelacionado($idprodrel){
		$this->idprorel = $idprodrel;
	}
	
	public function getUnidadeFornecedor(){
		return $this->UNFORN;	
	}

	public function setUnidadeFornecedor($unf){
		$this->UNFORN = $unf;
	}
	
	
	public function getUnidadeProduto(){
		return $this->UNPROD;	
	}

	public function setUnidadeProduto($unp){
		$this->UNPROD = $unp;
	}
	
	public function getQtdPorUnidade(){
		return $this->QTDPUN;	
	}

	public function setQtdPorUnidade($qtdu){
		$this->QTDPUN = $qtdu;
	}
	
	public function getProdDesc(){
		return $this->proddesc;	
	}

	public function setProdDesc($desc){
		$this->proddesc = $desc;
	}
	
	public function getProximoId(){

		return $this->proximoid;	

	}
	
	public function setProximoId($proximoid){

		$this->proximoid = $proximoid;

	}
	
	public function getCfop(){
		return $this->cfop;	
	}

	public function setCfop($cfop){
		$this->cfop = $cfop;
	}
	
	public function getNomeCfop(){
		return $this->nomecfop;	
	}

	public function setNomeCfop($nomcfop){
		$this->nomecfop = $nomcfop;
	}

	public function getNpcCx(){
		return $this->npec_cx;	
	}

	public function setNpcCx($npccx){
		$this->npec_cx = $npccx;
	}

	public function getVator(){
		return $this->vator;	
	}

	public function setVator($vator){
		$this->vator = $vator;
	}

	public function getHash(){
		return $this->hash;	
	}

	public function setHash($hash){
		$this->hash = $hash;
	}
}

?>
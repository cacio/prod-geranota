<?php

class Produtos{

	private $Codigo;
	private $descricao;
	private $preco_venda;
	private $dtcontagem;
	private $qtdecontada;
	private $unidade;
	private $grupo;
	private $ultimocusto;
	private $margem;
	private $customedio;
	private $precoloja;
	private $precominimo;
	private $precomax;
	private $PRECDESCAV;
	private $dataultocusto;
	private $ultcodfornc;
	private $estoqueatual;
	private $codgrupo;
	
	public function Produtos(){

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

	public function getPrecovenda(){

		return $this->preco_venda;	

	}

	
	public function setPrecovenda($precovenda){

		$this->preco_venda = $precovenda;

	}
	
	public function getDataContagem(){

		return $this->dtcontagem;	

	}

	
	public function setDataContagem($dtcontagem){

		$this->dtcontagem = $dtcontagem;

	}
	
	public function getQuantidadeContagem(){

		return $this->qtdecontada;	

	}

	
	public function setQuantidadeContagem($qtdcontagem){

		$this->qtdecontada = $qtdcontagem;

	}
	
	public function getUnidade(){

		return $this->unidade;	

	}

	
	public function setUnidade($unidade){

		$this->unidade = $unidade;

	}
	
	public function getGrupo(){

		return $this->grupo;	

	}

	
	public function setGrupo($grupo){

		$this->grupo = $grupo;

	}
	
	public function getUltimoCusto(){

		return $this->ultimocusto;	

	}

	
	public function setUltimoCusto($ultimocusto){

		$this->ultimocusto = $ultimocusto;

	}

	public function getMargem(){

		return $this->margem;	

	}

	public function setMargem($margem){

		$this->margem = $margem;

	}
	
	public function getCustoMedio(){

		return $this->customedio;	

	}

	public function setCustoMedio($customedio){

		$this->customedio = $customedio;

	}
	
	public function getPrecoLoja(){

		return $this->precoloja;	

	}

	public function setPrecoLoja($precoloja){

		$this->precoloja = $precoloja;

	}
	
	public function getPrecoMinimo(){

		return $this->precominimo;	

	}

	public function setPrecoMinimo($precominimo){

		$this->precominimo = $precominimo;

	}
	
	public function getPrecoMaximo(){

		return $this->precomax;	

	}

	public function setPrecoMaximo($precoximo){

		$this->precomax = $precoximo;

	}
	
	public function getPrecDescAv(){

		return $this->PRECDESCAV;	

	}

	public function setPrecDescAv($precdescav){

		$this->PRECDESCAV = $precdescav;

	}


	public function getDataUltimoCusto(){

		return $this->dataultocusto;	

	}

	public function setDataUltimoCusto($dtultcusto){

		$this->dataultocusto = $dtultcusto;

	}
	
	public function getUltimoCodFornec(){
		return $this->ultcodfornc;	
	}

	public function setUltimoCodFornec($ultcodfornc){
		$this->ultcodfornc = $ultcodfornc;
	}

	public function getEstoqueAtual(){
		return $this->estoqueatual;	
	}

	public function setEstoqueAtual($estatu){
		$this->estoqueatual = $estatu;
	}
	
	public function getCodGrupo(){
		return $this->codgrupo;	
	}

	public function setCodGrupo($codgrupo){
		$this->codgrupo = $codgrupo;
	}

}

?>
<?php

class Formulacao{

	

	

	private $Codigo;
	private $COD_PROD;
	private $COD_INSUMO;
	private $DIVISAO;
	private $QUANTIDADEKG;
	private $COD_LOCAL_PRODUCAO;
	private $PRECO_UNITARIO;
	private $participacao;
	private $materiaprima;
	private $nomeproduto;
	
	public function Formulacao(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	
	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getCodProduto(){

		return $this->COD_PROD;	

	}

	
	public function setCodProduto($codproduto){

		$this->COD_PROD = $codproduto;

	}
	
	public function getCodInsumo(){

		return $this->COD_INSUMO;	

	}

	
	public function setCodInsumo($codinsumo){

		$this->COD_INSUMO = $codinsumo;

	}	
	
	public function getDivisao(){

		return $this->DIVISAO;	

	}

	
	public function setDivisao($divisao){

		$this->DIVISAO = $divisao;

	}
	
	public function getQuantidade(){

		return $this->QUANTIDADEKG;	

	}

	
	public function setQuantidade($qtd){

		$this->QUANTIDADEKG = $qtd;

	}
	
	public function getCodLocalProducao(){

		return $this->COD_LOCAL_PRODUCAO;	

	}

	public function setCodLocalProducao($codlocalprod){

		$this->COD_LOCAL_PRODUCAO = $codlocalprod;

	}
	
	public function getPrecoUnitario(){

		return $this->PRECO_UNITARIO;	

	}

	public function setPrecoUnitario($precounitario){

		$this->PRECO_UNITARIO = $precounitario;

	}
	public function getParticipacao(){

		return $this->participacao;	

	}

	public function setParticipacao($participacao){

		$this->participacao = $participacao;

	}
	
	public function getMateriaPrima(){

		return $this->materiaprima;	

	}

	public function setMateriaPrima($materiaprima){

		$this->materiaprima = $materiaprima;

	}
	public function getNomeProduto(){

		return $this->nomeproduto;	

	}

	public function setNomeProduto($nomeproduto){

		$this->nomeproduto = $nomeproduto;

	}
	
}

?>
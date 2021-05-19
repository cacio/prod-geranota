<?php

class Cliente{

	

	

	private $Codigo;
	private $CNPJ_CPF;
	private $NOME;
	private $ENDERECO;
	private $BAIRRO;
	private $CEP;
	private $CIDADE;
	private $ESTADO;
	private $TELEFONE;
	private $INSCRICAO;
	private $ATIVO;
	private $CONTA_CTB;
	private $MOSTRA_FATURAS;
	private $PRAZO1;
	private $PRAZO2;
	private $PRAZO3;
	private $PRAZO4;
	private $PRAZO5;
	private $COND_VENDAS;
	private $REPRESENTANTE;
	private $FANTASIA;
	private $RESTRICAO;
	private $TABELA_PRECOS;
	private $CONTATO;
	private $E_MAIL;
	private $FAX;
	private $COND_PAG;
	private $LIMITE;
	private $SEGMENTO;
	private $GERARBOLETO;
	private $PESSOA;
	private $END_ENTREGA;
	private $BAIRRO_ENTREGA;
	private $CIDADE_ENTREGA;
	private $reduzido;
	private $desc;

	public function Cliente(){

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
	
	public function getContaCtb(){
		return $this->CONTA_CTB;	
	}

	public function setContaCtb($contactb){
		$this->CONTA_CTB = $contactb;
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
	

}

?>
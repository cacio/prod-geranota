<?php

class Vendedor{

	

	

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
	
	public function Vendedor(){

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
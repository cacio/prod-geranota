<?php

class Fornecedor{

	

	

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
	private $placa;
	private $antt;
	private $pais;
	private $produtor;
	private $obs;
	private $tipo;
	private $idproximo;
	private $uf;
	private $reduzido;
	private $desc;
	
	public function Fornecedor(){

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
	
	
	public function getEndereco(){
		return $this->ENDERECO;	
	}

	public function setEndereco($endereco){
		$this->ENDERECO = $endereco;
	}
	
	public function getEstado(){
		return $this->uf;	
	}

	public function setEstado($estado){
		$this->uf = $estado;
	}
	
	public function getCidade(){
		return $this->CIDADE;	
	}

	public function setCidade($cidade){
		$this->CIDADE = $cidade;
	}
	
	public function getBairro(){
		return $this->BAIRRO;	
	}

	public function setBairro($bairro){
		$this->BAIRRO = $bairro;
	}
	
	public function getCep(){
		return $this->CEP;	
	}

	public function setCep($cep){
		$this->CEP = $cep;
	}
	
	public function getTelefone(){
		return $this->TELEFONE;	
	}

	public function setTelefone($tel){
		$this->TELEFONE = $tel;
	}
	
	public function getFax(){
		return $this->FAX;	
	}

	public function setFax($fax){
		$this->FAX = $fax;
	}
	
	public function getContato(){
		return $this->CONTATO;	
	}

	public function setContato($contato){
		$this->CONTATO = $contato;
	}
		
	public function getCnpjCpf(){
		return $this->CNPJ_CPF;	
	}

	public function setCnpjCpf($cnpjcpf){
		$this->CNPJ_CPF = $cnpjcpf;
	}
	
	public function getIncricoesEstadual(){
		return $this->INSCRICAO;	
	}

	public function setIncricoesEstadual($ins){
		$this->INSCRICAO = $ins;
	}
	
	public function getContaCtb(){
		return $this->CONTA_CTB;	
	}

	public function setContaCtb($contactb){
		$this->CONTA_CTB = $contactb;
	}
	
	public function getPlaca(){
		return $this->placa;	
	}

	public function setPlaca($placa){
		$this->placa = $placa;
	}
	
	public function getUf(){
		return $this->ESTADO;	
	}

	public function setUf($uf){
		$this->ESTADO = $uf;
	}
	
	public function getAntt(){
		return $this->antt;	
	}

	public function setAntt($ant){
		$this->antt = $ant;
	}
	
	public function getPais(){
		return $this->pais;	
	}

	public function setPais($pais){
		$this->pais = $pais;
	}
	
	public function getEmail(){
		return $this->E_MAIL;	
	}

	public function setEmail($email){
		$this->E_MAIL = $email;
	}
	
	public function getProdutor(){
		return $this->produtor;	
	}

	public function setProdutor($produtor){
		$this->produtor = $produtor;
	}
	
	public function getObs(){
		return $this->obs;	
	}

	public function setObs($obs){
		$this->obs = $obs;
	}
	
	public function getTipo(){
		return $this->tipo;	
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	public function getProximo_Id(){
		return $this->idproximo;	
	}

	public function setProximo_Id($idproximo){
		$this->idproximo = $idproximo;
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
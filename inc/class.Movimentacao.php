<?php

class Movimentacao{

	
	private $codigo;
	private $data;
	private $conta;
	private $nomconta;
	private $dc;
	private $historico;
	private $valor;
	private $entrada;
	private $saida;
	private $idmovi;
	private $id_duplic_r;
	private $id_duplic_p;
	private $litroscomb;
	private $idviagem;
	private $idmotorista;
	private $idveiculo;
	private $quilometragem;
	private $xnomeviagem;
	private $idduplicatarpconta;
	private $idhistorico;
	private $idcontacredito;
	private $idcontadebido;	
	private $descricao;
	private $idusuario;
	private $codigofm;

	public function __construct(){
		//nada
	}
	
	public function getCodigo(){
		return $this->codigo;
	}
	
	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}
	
	
	public function getData(){
		return $this->data;
	}
	
	public function setData($data){
		$this->data = $data;
	}
	
	public function getConta(){
		return $this->conta;
	}
	
	public function setConta($conta){
		$this->conta = $conta;
	}
	
	public function getNomeconta(){
		return $this->nomconta;
	}
	
	public function setNomeconta($nomconta){
		$this->nomconta = $nomconta;
	}
	
	public function getDc(){
		return $this->dc;
	}
	
	public function setDc($dc){
		$this->dc = $dc;
	}
	
	public function getHistorico(){
		return $this->historico;
	}
	
	public function setHistorico($hist){
		$this->historico = $hist;
	}
	
	public function getValor(){
		return $this->valor;
	}
	
	public function setValor($vl){
		$this->valor = $vl;
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
	
	public function getIdmovi(){
		return $this->idmovi;
	}
	
	public function setIdmovi($idmovi){
		$this->idmovi = $idmovi;
	}
	public function getId_duplic_r(){
		return $this->id_duplic_r;
	}
	public function setId_duplic_r($Id_duplic_r){
		$this->id_duplic_r = $Id_duplic_r;
	}
	public function getId_duplic_p(){
		return $this->id_duplic_p;
	}
	public function setId_duplic_p($Id_duplic_p){
		$this->id_duplic_p = $Id_duplic_p;
	}
	public function getLitroscomb(){
		return $this->litroscomb;
	}
	public function setLitroscomb($litroscomb){
		$this->litroscomb = $litroscomb;
	}
	public function getIdViagem(){
		return $this->idviagem;
	}
	public function setIdViagem($idViagem){
		$this->idviagem = $idViagem;
	}
	
	public function getIdmotorista(){
		return $this->idmotorista;
	}
	public function setIdmotorista($idmotorista){
		$this->idmotorista = $idmotorista;
	}
	
	public function getIdveiculo(){
		return $this->idveiculo;
	}
	public function setIdveiculo($idveiculo){
		$this->idveiculo = $idveiculo;
	}
	
	public function getQuilometragem(){
		return $this->quilometragem;
	}
	public function setQuilometragem($quilometragem){
		$this->quilometragem = $quilometragem;
	}
	public function getXnomeviagem(){
		return $this->xnomeviagem;
	}
	public function setXnomeviagem($Xnomeviagem){
		$this->xnomeviagem = $Xnomeviagem;
	}
	
	public function getIdduplicatarpconta(){
		return $this->idduplicatarpconta;
	}
	
	public function setIdduplicatarpconta($idduplicatarpconta){
		$this->idduplicatarpconta = $idduplicatarpconta;
	}

	public function getIdHistorico(){
		return $this->idhistorico;
	}
	
	public function setIdHistorico($idhist){
		$this->idhistorico = $idhist;
	}

	public function getIdContaCredito(){
		return $this->idcontacredito;
	}
	
	public function setIdContaCredito($idcontacred){
		$this->idcontacredito = $idcontacred;
	}

	public function getIdContaDebito(){
		return $this->idcontadebido;
	}
	
	public function setIdContaDebito($idcontdeb){
		$this->idcontadebido = $idcontdeb;
	}

	public function getDescricao(){
		return $this->descricao;
	}
	
	public function setDescricao($desc){
		$this->descricao = $desc;
	}

	public function getIdUsuario(){
		return $this->idusuario;
	}
	
	public function setIdUsuario($idusuario){
		$this->idusuario = $idusuario;
	}

	public function getCodigoFM(){
		return $this->codigofm;
	}
	
	public function setCodigoFM($codigofm){
		$this->codigofm = $codigofm;
	}
	
	
}
?>
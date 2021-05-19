<?php
    
    class Requisicoes{
        
        private $item;
        private $data;
        private $hora;
        private $produto;
        private $quantidade;
        private $entsai;
        private $tiporeq;
        private $numero;
        private $valor;
        private $justificativa;
        private $pecas;

        public function Requisicoes(){

        }

        public function getItem(){
            return $this->item;
        }

        public function setItem($item){
            $this->item = $item;
        }

        public function getData(){
            return $this->data;
        }

        public function setData($data){
            $this->data = $data;
        }

        public function getHora(){
            return $this->hora;
        }

        public function setHora($hora){
            $this->hora = $hora;
        }

        public function getProduto(){
            return $this->produto;
        }

        public function setProduto($produto){
            $this->produto = $produto;
        }

        public function getQuantidade(){
            return $this->quantidade;
        }

        public function setQuantidade($qtd){
            $this->quantidade = $qtd;
        }

        public function getEntSai(){
            return $this->entsai;
        }

        public function setEntSai($entsai){
            $this->entsai = $entsai;
        }

        public function getTipoReq(){
            return $this->tiporeq;
        }

        public function setTipoReq($tipo){
            $this->tiporeq = $tipo;
        }

        public function getNumero(){
            return $this->numero;
        }

        public function setNumero($numero){
            $this->numero = $numero;
        }

        public function getValor(){
            return $this->valor;
        }

        public function setValor($valor){
            $this->valor = $valor;
        }

        public function getJustificativa(){
            return $this->justificativa;
        }

        public function setJustificativa($just){
            $this->justificativa = $just;
        }

        public function getPecas(){
            return $this->pecas;
        }

        public function setPecas($pecas){
            $this->pecas = $pecas;
        }
    }

?>
<?php
class NfeNsu{
    
    private $codigo;
    private $nfe_chave;
    private $nfe_numero;
    private $nfe_serie;
    private $nfe_empresa;
    private $nfe_cnpj;
    private $nfe_ie;    
    private $nfe_dataemissao;
    private $id_status;
    private $nome_status;
    private $id_emp;
    private $nfe_totalnota;
    private $nfe_situacao;
    private $situacao_manifesto;
    public function __construct(){

    }

    public function setCodigo($codigo) { $this->codigo = $codigo; }
    public function getCodigo() { return $this->codigo; }
    public function setNfe_chave($nfe_chave) { $this->nfe_chave = $nfe_chave; }
    public function getNfe_chave() { return $this->nfe_chave; }
    public function setNfe_numero($nfe_numero) { $this->nfe_numero = $nfe_numero; }
    public function getNfe_numero() { return $this->nfe_numero; }
    public function setNfe_serie($nfe_serie) { $this->nfe_serie = $nfe_serie; }
    public function getNfe_serie() { return $this->nfe_serie; }
    public function setNfe_empresa($nfe_empresa) { $this->nfe_empresa = $nfe_empresa; }
    public function getNfe_empresa() { return $this->nfe_empresa; }
    public function setNfe_cnpj($nfe_cnpj) { $this->nfe_cnpj = $nfe_cnpj; }
    public function getNfe_cnpj() { return $this->nfe_cnpj; }
    public function setNfe_ie($nfe_ie) { $this->nfe_ie = $nfe_ie; }
    public function getNfe_ie() { return $this->nfe_ie; }
    public function setNfe_dataemissao($nfe_dataemissao) { $this->nfe_dataemissao = $nfe_dataemissao; }
    public function getNfe_dataemissao() { return $this->nfe_dataemissao; }
    public function setId_status($id_status) { $this->id_status = $id_status; }
    public function getId_status() { return $this->id_status; }
    public function setNome_status($nome_status) { $this->nome_status = $nome_status; }
    public function getNome_status() { return $this->nome_status;}
    public function setId_emp($id_emp) { $this->id_emp = $id_emp; }
    public function getId_emp() { return $this->id_emp; }
    public function setNfe_totalnota($nfetot) { $this->nfe_totalnota = $nfetot; }
    public function getNfe_totalnota() { return $this->nfe_totalnota; }
    public function setNfe_situacao($nfe_situacao) { $this->nfe_situacao = $nfe_situacao; }
    public function getNfe_situacao() { return $this->nfe_situacao; }
    public function setSituacao_manifesto($situacao_manifesto) { $this->situacao_manifesto = $situacao_manifesto; }
    public function getSituacao_manifesto() { return $this->situacao_manifesto; }

}
?>
<?php
class HistoricoPadrao{
    private $id;
    private $codigo;
    private $descricao;
        
    public function __construct()
    {
        
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getCodigo(){
        return $this->codigo;
    }

    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($desc){
        $this->descricao = $desc;
    }


}
?>
<?php

require_once('inc.autoload.php');
require_once('inc.connectfirebird.php');

class RequisicoesDAO{

	private $dba;

	public function RequisicoesDAO(){

		$dba = new DbAdmin('firebird');
		$dba->connect(HOSTS,USERS,SENHAS,BDS);
		$this->dba = $dba;
	}

	
    
    public function inserir($req){

        $dba = $this->dba;

        $data    = $req->getData();
        $hora    = $req->getHora();
        $prodcod = $req->getProduto();
        $qtd     = $req->getQuantidade();
        $entsai  = $req->getEntSai();
        $tiporeq = $req->getTipoReq();
        $numero  = $req->getNumero();
        $valor   = $req->getValor();
        $just    = $req->getJustificativa();
		$pecas   = $req->getPecas();

        $sql = "insert into REQUISICOES (DATA, HORA, PRODUTO, QUANTIDADE, ENTSAI, TIPO_REQ, NUMERO,VALOR,JUSTIFICATIVA,PECAS)
        values ('".$data."', '".$hora."', '".$prodcod."', ".$qtd.", '".$entsai."', '".$tiporeq."', '".$numero."',".$valor.",'".$just."',".$pecas.")";
		//echo "{$sql}<br/>"; 
		$res = $dba->query($sql);

    }
	
	

}

?>
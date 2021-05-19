<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class NfeNsuDAO{

    private $dba;

    public function __construct(){
        $dba = new DbAdmin('mysql');
        $dba->connect(HOST,USER,SENHA,BD);
        $this->dba = $dba;
    }


    public function ListaNfe($idemp){
        $dba = $this->dba;
        $vet = array();

        $sql = 'SELECT 
                    n.id,
                    n.nfe_numero,
                    n.nfe_serie,
                    n.nfe_empresa,
                    n.nfe_cnpj,
                    n.nfe_ie,
                    n.nfe_dataemissao,
                    n.id_status,
                    s.nome,
                    n.nfe_chave,
                    n.nfe_totalnota,
                    n.nfe_situacao,
                    n.situacao_manifesto
                FROM notas_nfe_nsu n
                inner join status s on (s.id = n.id_status) where  n.id_emp = '.$idemp.' and s.id <> "3" order by n.nfe_dataemissao desc';

        $res = $dba->query($sql);
        $num = $dba->rows($res);

        for($i=0; $i < $num; $i++){

            $cod                = $dba->result($res,$i,'id');	      
            $nfe_numero         = $dba->result($res,$i,'nfe_numero');
            $nfe_serie          = $dba->result($res,$i,'nfe_serie');
            $nfe_empresa        = $dba->result($res,$i,'nfe_empresa');
            $nfe_cnpj           = $dba->result($res,$i,'nfe_cnpj');
            $nfe_ie             = $dba->result($res,$i,'nfe_ie');
            $nfe_dataemissao    = $dba->result($res,$i,'nfe_dataemissao');
            $id_status          = $dba->result($res,$i,'id_status');    
            $nome               = $dba->result($res,$i,'nome');
            $nfe_chave          = $dba->result($res,$i,'nfe_chave');
            $nfe_totalnota      = $dba->result($res,$i,'nfe_totalnota');
            $nfe_situacao       = $dba->result($res,$i,'nfe_situacao');
            $situacao_manifesto = $dba->result($res,$i,'situacao_manifesto');

            $nfensus = new NfeNsu();

            $nfensus->setCodigo($cod);
            $nfensus->setNfe_numero($nfe_numero);
            $nfensus->setNfe_serie($nfe_serie);
            $nfensus->setNfe_empresa($nfe_empresa);
            $nfensus->setNfe_cnpj($nfe_cnpj);
            $nfensus->setNfe_ie($nfe_ie);
            $nfensus->setNfe_dataemissao($nfe_dataemissao);
            $nfensus->setId_status($id_status);
            $nfensus->setNome_status($nome);
            $nfensus->setNfe_chave($nfe_chave);
            $nfensus->setNfe_totalnota($nfe_totalnota);
            $nfensus->setNfe_situacao($nfe_situacao);
            $nfensus->setSituacao_manifesto($situacao_manifesto);

            $vet[$i] = $nfensus;

        }

        return $vet;
    }

    public function VerificaNfeExist($numero,$idemp){
        $dba = $this->dba;
        $vet = array();

        $sql = 'SELECT 
                    n.id,
                    n.nfe_numero,
                    n.nfe_serie,
                    n.nfe_empresa,
                    n.nfe_cnpj,
                    n.nfe_ie,
                    n.nfe_dataemissao,
                    n.nfe_chave
                FROM notas_nfe_nsu n 
                where n.nfe_chave = "'.$numero.'" and  n.id_emp = '.$idemp.' ';

        $res = $dba->query($sql);
        $num = $dba->rows($res);

        for($i=0; $i < $num; $i++){

            $cod             = $dba->result($res,$i,'id');	      
            $nfe_numero      = $dba->result($res,$i,'nfe_numero');
            $nfe_serie       = $dba->result($res,$i,'nfe_serie');
            $nfe_empresa     = $dba->result($res,$i,'nfe_empresa');
            $nfe_cnpj        = $dba->result($res,$i,'nfe_cnpj');
            $nfe_ie          = $dba->result($res,$i,'nfe_ie');
            $nfe_dataemissao = $dba->result($res,$i,'nfe_dataemissao');                        
            $nfe_chave       = $dba->result($res,$i,'nfe_chave');

            $nfensus = new NfeNsu();

            $nfensus->setCodigo($cod);
            $nfensus->setNfe_numero($nfe_numero);
            $nfensus->setNfe_serie($nfe_serie);
            $nfensus->setNfe_empresa($nfe_empresa);
            $nfensus->setNfe_cnpj($nfe_cnpj);
            $nfensus->setNfe_ie($nfe_ie);
            $nfensus->setNfe_dataemissao($nfe_dataemissao);            
            $nfensus->setNfe_chave($nfe_chave);

            $vet[$i] = $nfensus;

        }

        return $vet;
    }


    public function ListaNfeUm($id,$idemp){
        $dba = $this->dba;
        $vet = array();

        $sql = 'SELECT 
                    n.id,                    
                    n.id_status,                    
                    n.nfe_chave
                FROM notas_nfe_nsu n
                inner join status s on (s.id = n.id_status) 
                where  n.id = '.$id.' and  n.id_emp = '.$idemp.' ';

        $res = $dba->query($sql);
        $num = $dba->rows($res);

        for($i=0; $i < $num; $i++){

            $cod             = $dba->result($res,$i,'id');	                  
            $id_status       = $dba->result($res,$i,'id_status');                
            $nfe_chave       = $dba->result($res,$i,'nfe_chave');

            $nfensus = new NfeNsu();

            $nfensus->setCodigo($cod);                        
            $nfensus->setId_status($id_status);            
            $nfensus->setNfe_chave($nfe_chave);

            $vet[$i] = $nfensus;

        }

        return $vet;
    }


    public function ListaNfeNumeroUm($id,$idemp){
        $dba = $this->dba;
        $vet = array();

        $sql = 'SELECT 
                    n.id,                    
                    n.id_status,                    
                    n.nfe_chave
                FROM notas_nfe_nsu n
                inner join status s on (s.id = n.id_status) 
                where  n.nfe_numero like "%'.$id.'%" and  n.id_emp = '.$idemp.' ';

        $res = $dba->query($sql);
        $num = $dba->rows($res);

        for($i=0; $i < $num; $i++){

            $cod             = $dba->result($res,$i,'id');	                  
            $id_status       = $dba->result($res,$i,'id_status');                
            $nfe_chave       = $dba->result($res,$i,'nfe_chave');

            $nfensus = new NfeNsu();

            $nfensus->setCodigo($cod);                        
            $nfensus->setId_status($id_status);            
            $nfensus->setNfe_chave($nfe_chave);

            $vet[$i] = $nfensus;

        }

        return $vet;
    }

    public function NfeNumero($id,$idemp){
        $dba = $this->dba;
        $vet = array();

        $sql = 'SELECT 
                    n.id,                    
                    n.id_status,                    
                    n.nfe_chave
                FROM notas_nfe_nsu n
                inner join status s on (s.id = n.id_status) 
                where  n.nfe_numero = '.$id.' and  n.id_emp = '.$idemp.' ';

        $res = $dba->query($sql);
        $num = $dba->rows($res);

        for($i=0; $i < $num; $i++){

            $cod             = $dba->result($res,$i,'id');	                  
            $id_status       = $dba->result($res,$i,'id_status');                
            $nfe_chave       = $dba->result($res,$i,'nfe_chave');

            $nfensus = new NfeNsu();

            $nfensus->setCodigo($cod);                        
            $nfensus->setId_status($id_status);            
            $nfensus->setNfe_chave($nfe_chave);

            $vet[$i] = $nfensus;

        }

        return $vet;
    }

    public function PesquisaNotas($where){
        $dba = $this->dba;
        $vet = array();

        $sql = 'SELECT 
                    n.id,
                    n.nfe_numero,
                    n.nfe_serie,
                    n.nfe_empresa,
                    n.nfe_cnpj,
                    n.nfe_ie,
                    n.nfe_dataemissao,
                    n.id_status,
                    s.nome,
                    n.nfe_chave,
                    n.nfe_totalnota,
                    n.nfe_situacao,
                    n.situacao_manifesto
                FROM notas_nfe_nsu n
                inner join status s on (s.id = n.id_status) '.$where.' order by n.nfe_dataemissao desc';
        
        $res = $dba->query($sql);
        $num = $dba->rows($res);

        for($i=0; $i < $num; $i++){

            $cod                = $dba->result($res,$i,'id');	      
            $nfe_numero         = $dba->result($res,$i,'nfe_numero');
            $nfe_serie          = $dba->result($res,$i,'nfe_serie');
            $nfe_empresa        = $dba->result($res,$i,'nfe_empresa');
            $nfe_cnpj           = $dba->result($res,$i,'nfe_cnpj');
            $nfe_ie             = $dba->result($res,$i,'nfe_ie');
            $nfe_dataemissao    = $dba->result($res,$i,'nfe_dataemissao');
            $id_status          = $dba->result($res,$i,'id_status');    
            $nome               = $dba->result($res,$i,'nome');
            $nfe_chave          = $dba->result($res,$i,'nfe_chave');
            $nfe_totalnota      = $dba->result($res,$i,'nfe_totalnota');
            $nfe_situacao       = $dba->result($res,$i,'nfe_situacao');
            $situacao_manifesto = $dba->result($res,$i,'situacao_manifesto');

            $nfensus = new NfeNsu();

            $nfensus->setCodigo($cod);
            $nfensus->setNfe_numero($nfe_numero);
            $nfensus->setNfe_serie($nfe_serie);
            $nfensus->setNfe_empresa($nfe_empresa);
            $nfensus->setNfe_cnpj($nfe_cnpj);
            $nfensus->setNfe_ie($nfe_ie);
            $nfensus->setNfe_dataemissao($nfe_dataemissao);
            $nfensus->setId_status($id_status);
            $nfensus->setNome_status($nome);
            $nfensus->setNfe_chave($nfe_chave);
            $nfensus->setNfe_totalnota($nfe_totalnota);
            $nfensus->setNfe_situacao($nfe_situacao);
            $nfensus->setSituacao_manifesto($situacao_manifesto);

            $vet[$i] = $nfensus;

        }

        return $vet;
    }

    public function inserir($nfensus){
        $dba = $this->dba;
        
        
        $nfe_numero      = $nfensus->getNfe_numero();
        $nfe_serie       = $nfensus->getNfe_serie();
        $nfe_empresa     = $nfensus->getNfe_empresa();
        $nfe_cnpj        = $nfensus->getNfe_cnpj();
        $nfe_ie          = $nfensus->getNfe_ie();
        $nfe_dataemissao = $nfensus->getNfe_dataemissao();
        $id_status       = $nfensus->getId_status();        
        $nfe_chave       = $nfensus->getNfe_chave();
        $idemp           = $nfensus->getId_emp();
        $nfe_total       = $nfensus->getNfe_totalnota();
        $nfe_situacao    = $nfensus->getNfe_situacao();
        $manifest_situa  = $nfensus->getSituacao_manifesto();

        $sql = "INSERT INTO `notas_nfe_nsu`
                (`nfe_numero`,
                `nfe_serie`,
                `nfe_empresa`,
                `nfe_cnpj`,
                `nfe_ie`,
                `nfe_dataemissao`,
                `id_status`,
                `nfe_chave`,
                `id_emp`,
                `nfe_totalnota`,
                `nfe_situacao`,
                `situacao_manifesto`)
                VALUES
                ('". $nfe_numero."',
                '".$nfe_serie."',
                '". $nfe_empresa."',
                '".$nfe_cnpj."',
                '".$nfe_ie."',
                '".$nfe_dataemissao."',
                ".$id_status.",
                '".$nfe_chave."',
                ".$idemp.",
                ".$nfe_total.",
                '".$nfe_situacao."',
                '".$manifest_situa."')";

        $res = $dba->query($sql);
    }

    public function Update($nfensus){
        $dba = $this->dba;
        
        $codigo          = $nfensus->getCodigo();
        $nfe_numero      = $nfensus->getNfe_numero();
        $nfe_serie       = $nfensus->getNfe_serie();
        $nfe_empresa     = $nfensus->getNfe_empresa();
        $nfe_cnpj        = $nfensus->getNfe_cnpj();
        $nfe_ie          = $nfensus->getNfe_ie();
        $nfe_dataemissao = $nfensus->getNfe_dataemissao();                 
        $idemp           = $nfensus->getId_emp();
        $nfe_total       = $nfensus->getNfe_totalnota();
        $nfe_situacao    = $nfensus->getNfe_situacao();
        $manifest_situa  = $nfensus->getSituacao_manifesto();

        $sql = "UPDATE `notas_nfe_nsu`
                SET
                `nfe_numero` = '". $nfe_numero."',
                `nfe_serie` = '".$nfe_serie."',
                `nfe_empresa` = '". $nfe_empresa."',
                `nfe_cnpj` = '".$nfe_cnpj."',
                `nfe_ie` = '".$nfe_ie."',
                `nfe_dataemissao` = '".$nfe_dataemissao."',               
                `nfe_totalnota` = ".$nfe_total.",
                `nfe_situacao` = '".$nfe_situacao."',
                `situacao_manifesto` = '".$manifest_situa."'
                WHERE `id` = ".$codigo." and id_emp = ".$idemp." ";

        $res = $dba->query($sql);
    }


    public function UpdateStatus($nfensus){
        $dba = $this->dba;
        
        $codigo          = $nfensus->getCodigo();
        $id_status       = $nfensus->getId_status();  
        $idemp           = $nfensus->getId_emp();
        $manifest_situa  = $nfensus->getSituacao_manifesto();

        $sql = "UPDATE `notas_nfe_nsu`
                SET      
                `id_status` = '".$id_status."' ,
                `situacao_manifesto`= '".$manifest_situa."'              
                WHERE `id` = ".$codigo." and id_emp = ".$idemp." ";
        //echo $sql;  
        $res = $dba->query($sql);
    }

}
?>
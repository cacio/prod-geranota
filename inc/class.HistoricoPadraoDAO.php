<?php
    
    require_once('inc.autoload.php');
    require_once('inc.connectfirebird.php');

    class HistoricoPadraoDAO{
        
        private $dba;

        public function __construct()
        {
            $dba = new DbAdmin('firebird');
		    $dba->connect(HOSTS,USERS,SENHAS,BDS);
            $this->dba = $dba;
            
        }

        public function ListaHistoricoPadrao(){


            $dba = $this->dba;

            $vet = array();


            $sql ="select
                    h.id,
                    h.codigo,
                    h.descricao
                from historicopadrao h order by h.id";
        
            $res = $dba->query($sql);

            $num = $dba->rows($res); 

            $i = 0;
                
            while($row = ibase_fetch_object($res)){		
                
                $id         = $row->ID;
                $codigo     = $row->CODIGO;
                $desc       = $row->DESCRICAO;
                
                $hp = new HistoricoPadrao();			

                $hp->setId($id);            
                $hp->setCodigo($codigo);
                $hp->setDescricao($desc);
                
                $vet[$i++] = $hp;

            }

            return $vet;

        }

        public function ListaHistoricoPadraoAlter($id){


            $dba = $this->dba;

            $vet = array();


            $sql ="select
                    h.id,
                    h.codigo,
                    h.descricao
                from historicopadrao h where h.id = '{$id}' ";
        
            $res = $dba->query($sql);

            $num = $dba->rows($res); 

            $i = 0;
                
            while($row = ibase_fetch_object($res)){		
                
                $id         = $row->ID;
                $codigo     = $row->CODIGO;
                $desc       = $row->DESCRICAO;
                
                $hp = new HistoricoPadrao();			

                $hp->setId($id);            
                $hp->setCodigo($codigo);
                $hp->setDescricao($desc);
                
                $vet[$i++] = $hp;

            }

            return $vet;

        }

        public function ListaHistoricoPadraoUm($codigo){


            $dba = $this->dba;

            $vet = array();


            $sql ="select
                    h.id,
                    h.codigo,
                    h.descricao
                from historicopadrao h where h.codigo = '{$codigo}' ";
        
            $res = $dba->query($sql);

            $num = $dba->rows($res); 

            $i = 0;
                
            while($row = ibase_fetch_object($res)){		
                
                $id         = $row->ID;
                $codigo     = $row->CODIGO;
                $desc       = $row->DESCRICAO;
                
                $hp = new HistoricoPadrao();			

                $hp->setId($id);            
                $hp->setCodigo($codigo);
                $hp->setDescricao($desc);
                
                $vet[$i++] = $hp;

            }

            return $vet;

        }

        public function BuscaHistoricoPadrao($search){


            $dba = $this->dba;

            $vet = array();


            $sql ="select
                    h.id,
                    h.codigo,
                    h.descricao
                from historicopadrao h where (h.descricao like '%{$search}%' or h.codigo like '%{$search}%') ";
        
            $res = $dba->query($sql);

            $num = $dba->rows($res); 

            $i = 0;
                
            while($row = ibase_fetch_object($res)){		
                
                $id         = $row->ID;
                $codigo     = $row->CODIGO;
                $desc       = $row->DESCRICAO;
                
                $hp = new HistoricoPadrao();			

                $hp->setId($id);            
                $hp->setCodigo($codigo);
                $hp->setDescricao($desc);
                
                $vet[$i++] = $hp;

            }

            return $vet;

        }

        public function inserir($hp){
            $dba = $this->dba;

            $codigo = $hp->getCodigo();
            $desc   = $hp->getDescricao();

            $sql = "insert into historicopadrao (codigo, descricao, created_at, updated_at)
            values ('".$codigo."', '".$desc."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')";

            $dba->query($sql);

        }

        public function update($hp){
            $dba = $this->dba;

            $id     = $hp->getId();
            $codigo = $hp->getCodigo();
            $desc   = $hp->getDescricao();

            $sql = "update historicopadrao
                    set  codigo = '".$codigo."',
                        descricao = '".$desc."',                        
                        updated_at = '".date('Y-m-d H:i:s')."'
                        where ID = {$id} ";

            $dba->query($sql);

        }

        public function deletar($hp){

            $dba = $this->dba;

            $id  = $hp->getId();

            $sql = "DELETE FROM historicopadrao where ID = {$id}";

            $dba->query($sql);

        }

        public function ajaxresponse($param,$values){
            return json_encode([$param => $values]);
        }

    }

?>
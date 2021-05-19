<?php
    require_once('inc.autoload.php');
    class ArqDominio{
        
        protected $fimLinha                     = "\n";
        protected $fimArquivo                   = null;
        protected $pipe                         = "|";
        private   $detalheHeader                = "";
        private   $detalheLancamento            = "";
        private   $detalheLancamentoGeral       = "";
        private   $detalheLancamentoCentroCusto = "";
        private   $detalheFinalizador           = "";
        private   $dadosHeader                  = array();
        private   $dadosLancamento              = array();
        private   $dadosLancamentoGeral         = array();
        private   $dadosLancamentoCentroCusto   = array();
        private   $dadosFinalizador             = array();

        public function __construct(){

        }

        public function __init($params = []){
            $this->dadosHeader                = $params['dadosheader'];
            $this->dadosLancamento            = $params['dadoslancamento'];
            $this->dadosLancamentoGeral       = $params['dadoslancamentogeral'];
            $this->dadosLancamentoCentroCusto = $params['dadoslancamentocentrocusto'];
            $this->dadosFinalizador           = $params['dadosfinalizador'];
        } 

        public function __dadosHeader(){

            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['REG']}", 2, " ",STR_PAD_RIGHT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['codemp']}", 7, "0",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['cgc']}", 14, " ",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['dtini']}", 10, "0",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['dtfim']}", 10, "0",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['tipo']}", 1, " ",STR_PAD_RIGHT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['tipoNota']}", 2, "0",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['constante']}", 5, "0",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['sistema']}", 1, "0",STR_PAD_LEFT);
            $this->detalheHeader .= str_pad("{$this->dadosHeader[0]['valorfix']}", 1, "0",STR_PAD_LEFT);

            return $this->detalheHeader;

        }

        public function __lancamento(){

            $this->detalheLancamento = "";

            for ($i=0; $i < count($this->dadosLancamento); $i++) { 
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['REG']}", 2, " ",STR_PAD_RIGHT);
                $this->detalheLancamento .= str_pad("".($i+1)."", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['data']}", 10, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['valorlancamento']}", 15, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['contadebito']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['contacredito']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad($this->dadosLancamento[$i]['historico'], 512, " ",STR_PAD_RIGHT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['origem']}", 2, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['usuario']}", 30, " ",STR_PAD_RIGHT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['codfm']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['codhistorico']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamento .= str_pad("{$this->dadosLancamento[$i]['brancos']}", 43, " ",STR_PAD_RIGHT).$this->fimLinha;
            }
            
            return $this->detalheLancamento;
        }

        public function __lancamentoGeral(){

            $this->detalheLancamentoGeral = "";

            for ($i=0; $i < count($this->dadosLancamentoGeral); $i++) { 
                $this->detalheLancamentoGeral .= str_pad("{$this->dadosLancamentoGeral[$i]['REG']}", 2, " ",STR_PAD_RIGHT);
                $this->detalheLancamentoGeral .= str_pad("{$i}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamentoGeral .= str_pad("{$this->dadosLancamentoGeral[$i]['contadebito']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamentoGeral .= str_pad("{$this->dadosLancamentoGeral[$i]['contacredito']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamentoGeral .= str_pad("{$this->dadosLancamentoGeral[$i]['valorlancamento']}", 15, "0",STR_PAD_LEFT);
                $this->detalheLancamentoGeral .= str_pad("{$this->dadosLancamentoGeral[$i]['brancos']}", 50, " ",STR_PAD_RIGHT).$this->fimLinha;
            }
            
            return $this->detalheLancamentoGeral;
        }

        public function __lancamentoCentroCusto(){

            $this->detalheLancamentoCentroCusto = "";

            for ($i=0; $i < count($this->dadosLancamentoCentroCusto); $i++) { 
                $this->detalheLancamentoCentroCusto .= str_pad("{$this->dadosLancamentoCentroCusto[$i]['REG']}", 2, " ",STR_PAD_RIGHT);
                $this->detalheLancamentoCentroCusto .= str_pad("{$i}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamentoCentroCusto .= str_pad("{$this->dadosLancamentoCentroCusto[$i]['contadebito']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamentoCentroCusto .= str_pad("{$this->dadosLancamentoCentroCusto[$i]['contacredito']}", 7, "0",STR_PAD_LEFT);
                $this->detalheLancamentoCentroCusto .= str_pad("{$this->dadosLancamentoCentroCusto[$i]['valorlancamento']}", 15, "0",STR_PAD_LEFT);
                $this->detalheLancamentoCentroCusto .= str_pad("{$this->dadosLancamentoCentroCusto[$i]['brancos']}", 50, " ",STR_PAD_RIGHT).$this->fimLinha;
            }
            
            return $this->detalheLancamentoCentroCusto;


        }

        public function __finalizador(){

            $this->detalheFinalizador =  str_pad("{$this->dadosFinalizador[0]['final']}", 100, "0",STR_PAD_LEFT);
            return $this->detalheFinalizador;

        }

        public function gerar()
        {
        
            $string = '';
                
            $string .= $this->__dadosHeader().$this->fimLinha; 
            $string .= $this->__lancamento();
            //$string .= $this->__lancamentoGeral();
            //$string .= $this->__lancamentoCentroCusto();
            $string .= $this->__finalizador();

            return Encoding::toUTF8($string);
        }
    
        public function save($path)
        {
            $folder = dirname($path);
            if (! is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
    
            if (! is_writable(dirname($path))) {
                throw new \Exception('Path ' . $folder . ' não possui permissao de escrita');
            }
    
            $string = $this->gerar();
            file_put_contents($path, $string);
    
            return $path;
        }

    }

?>
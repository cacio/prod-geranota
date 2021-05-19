<?php
    class FuncoesDAO{


        function FuncoesDAO(){

        }

        public function pegaidboicasado($nome){

            $pathFilebird       = '../public/configbird.json';
            $configJsonbird     = file_get_contents($pathFilebird);
            $installConfigbird  = json_decode($configJsonbird);

            $listformulacao     = json_encode($installConfigbird->listformulacao,true);
            $lista              = json_decode($listformulacao);

            $arr = array();
            $retorna = array();
            foreach ($lista as $key => $value) {
                $array = (array) $value;
                if($array['DESC'] == $nome){
                    $retorna = $array;
                }					
            }
                
            return $retorna;

        }

        public function asset($path,$time = true){
            $file = "{$path}"; 
            $fileOnDir = "{$path}"; 
            if($time && file_exists($fileOnDir)){
                $file.="?time=".filemtime($fileOnDir);                          
            }
            return $file;
        }

    }

?>
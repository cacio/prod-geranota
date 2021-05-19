<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class StatusDAO{
    private $dba;
    public function __construct(){
        $dba = new DbAdmin('mysql');
        $dba->connect(HOST,USER,SENHA,BD);
        $this->dba = $dba;    
    }


    public function update(){

        $dba = $this->dba;
        
        $sql = "";

        $res = $dba->query($sql);

    }

}
?>
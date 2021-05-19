<?php
    class Helpers{
        
        public function __construct()
        {
            
        }

        public function flash($type = null,$message = null){
            if($type && $message){
                $_SESSION['flash'] = [
                    "type"=>$type,
                    "message"=>$message
                ];
                return null;
            }
            
            if(!empty($_SESSION['flash']) && $flash = $_SESSION['flash']){
                unset($_SESSION['flash']);
                return "<div class=\"message {$flash["type"]}\">{$flash["message"]}</div>";
            }
    
            return null;
        }

        public function ajaxresponse($param,$values){
            return json_encode([$param => $values]);
        }

    }
?>
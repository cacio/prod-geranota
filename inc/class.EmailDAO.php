<?php 
require_once('inc.autoload.php');

class EmailDAO{

	private $SMTPAuth;
	private $SMTPSecure;
	private $Host;
	private $Port;
	private $Username;
	private $Password;

	function __construct($config = array()){


		$this->SMTPAuth   = $config['SMTPAuth'];
		$this->SMTPSecure = $config['SMTPSecure'];
		$this->Host 	  = $config['Host'];
		$this->Port       = $config['Port'];
		$this->Username   = $config['Username'];
		$this->Password   = $config['Password'];		

	}


	public function mandaEmail(stdClass $std){

		//var_dump($std);
		require_once('../PHPMailer/PHPMailerAutoload.php');

		$retorno = array();
		$Mailer  = new PHPMailer();

		$body  = file_get_contents('../tpl/corpoemail.html');
		$body  = str_replace(array('{mensagem}',
							 '{data}',
							 '{Name}',
							 '{assinatura}',
							 '{url}',
							 '{txt_btn}'), 
						array("{$std->mensagem}",									
							  "{$std->data}",
							  "{$std->nome}",
							  "{$std->assinatura}",
							  "{$std->url}",
							  "{$std->txt_btn}"), $body);

		
		$Mailer->isSMTP();
		$Mailer->isHTML(true);
		//$Mailer->SMTPDebug = 1;
		$Mailer->Charset    = 'UTF-8';	
		$Mailer->SMTPAuth   = $this->SMTPAuth;
		$Mailer->SMTPSecure = $this->SMTPSecure;
		$Mailer->Host       = $this->Host;
		$Mailer->Port       = $this->Port;
		$Mailer->Username   = $this->Username;
		$Mailer->Password   = $this->Password;
		
		$Mailer->From       = $std->email;
		$Mailer->FromName   = $std->nome;
		$Mailer->Subject    = $std->titulo;
						
		$Mailer->MsgHTML($body);
		
		$Mailer->AltBody = $std->mensagem;
		$Mailer->AddAddress($std->email);

		if ($Mailer->Send()){

			array_push($retorno, array('msg'=>$std->msgretorno,'tipo'=>'1'));

		}else{

			array_push($retorno, array('msg'=>'ERRO AO ENVIAR: '.$Mailer->ErrorInfo.'','tipo'=>'2'));

		}



		return $retorno;



	}

	public function mandaEmail2(stdClass $std){
		$retorno = array();
		$body  = file_get_contents('../tpl/corpoemail.html');
		$body  = str_replace(array('{mensagem}',
							 '{data}',
							 '{Name}',
							 '{assinatura}',
							 '{url}',
							 '{txt_btn}'), 
						array("{$std->mensagem}",									
							  "{$std->data}",
							  "{$std->nome}",
							  "{$std->assinatura}",
							  "{$std->url}",
							  "{$std->txt_btn}"), $body);
		
		ini_set("SMTP","ssl://smtp.kinghost.net");
		//ini_set("smtp_ssl",true);
		ini_set("smtp_port","465");
		
		
		$headers = "MIME-Version: 1.1\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "From: {$this->Username}\r\n"; // remetente
		$headers .= "Return-Path: {$this->Username}\r\n"; // return-path
		$envio    = mail("{$std->email}", "{$std->titulo}", "{$body}", $headers);
		 
		if($envio){
		 array_push($retorno, array('msg'=>$std->msgretorno,'tipo'=>'1'));
		}else{
		 array_push($retorno, array('msg'=>'A mensagem não pode ser enviada','tipo'=>'2'));
		}
		
		return $retorno;
	}




}	

?>
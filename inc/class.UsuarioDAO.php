<?php

require_once('inc.autoload.php');
require_once('inc.connect.php');

class UsuarioDAO{


	private $dba;


	public function __construct(){

		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	

	public function listaLogin($ema,$sen){

		$dba = $this->dba;

		$vet = array();

		$idsys = 1;

		$sql ='SELECT 
					u.*,
					e.cnpj
				FROM
					usuario u
						INNER JOIN
					empresas e ON (e.id = u.id_emp)
				WHERE e.cnpj = "'.$ema.'"
				AND	u.senha = "'.$sen.'"';
			  

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){

			$cod   = $dba->result($res,$i,'id');
			$nom   = $dba->result($res,$i,'nome');
			$ema   = $dba->result($res,$i,'email');
			$log   = $dba->result($res,$i,'login');
			$sen   = $dba->result($res,$i,'senha');
			$idr   = $dba->result($res,$i,'idrota');
			$ids   = $dba->result($res,$i,'idsys');
			$idemp = $dba->result($res,$i,'id_emp');
			$cnpj   = $dba->result($res,$i,'cnpj');

			$usu = new Usuario();

			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);
			$usu->setIdEmp($idemp);
			$usu->setCnpj($cnpj);

			$vet[$i] = $usu;

		}

		return $vet;

	}

	public function listausuario($idemp){	

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT * FROM usuario where nome <> "cacio" and nome <> "demo" and id_emp = '.$idemp.' '; 
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 


		for($i = 0; $i<$num; $i++){

		
			$cod = $dba->result($res,$i,'id');
			$nom = $dba->result($res,$i,'nome');
			$ema = $dba->result($res,$i,'email');
			$log = $dba->result($res,$i,'login');
			$sen = $dba->result($res,$i,'senha');
			$idr = $dba->result($res,$i,'idrota');
			$ids = $dba->result($res,$i,'idsys');		

			$usu = new Usuario();
			
			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);			

			$vet[$i] = $usu;

		}

		return $vet;

	}

	public function listausuarioum($idu){

	
		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT *
			  FROM usuario where id='.$idu;

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){


			$cod = $dba->result($res,$i,'id');
			$nom = $dba->result($res,$i,'nome');
			$ema = $dba->result($res,$i,'email');
			$log = $dba->result($res,$i,'login');
			$sen = $dba->result($res,$i,'senha');
			$idr = $dba->result($res,$i,'idrota');
			$ids = $dba->result($res,$i,'idsys');

			$usu = new Usuario();

			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);

			$vet[$i] = $usu;

		}

		return $vet;

	}
	
	public function VerificaCnpj($cnpj){

	
		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					u.id, 
					u.email, 
					u.nome, 
					u.senha
				FROM
					usuario u
						INNER JOIN
					empresas e ON (e.id = u.id_emp)
				WHERE
					e.cnpj = "'.$cnpj.'" ';

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){


			$cod = $dba->result($res,$i,'id');
			$nom = $dba->result($res,$i,'nome');
			$ema = $dba->result($res,$i,'email');			
			$sen = $dba->result($res,$i,'senha');
			
			$usu = new Usuario();

			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setSenha($sen);


			$vet[$i] = $usu;

		}

		return $vet;

	}
	
	public function VerificaCnpj2($cnpj,$cha){

	
		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					u.id, 
					u.email, 
					u.nome, 
					u.senha
				FROM
					usuario u
						INNER JOIN
					empresas e ON (e.id = u.id_emp)
				WHERE
					e.cnpj = "'.$cnpj.'" and sha1(concat(u.id,"",u.senha)) = "'.$cha.'" ';

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){


			$cod = $dba->result($res,$i,'id');
			$nom = $dba->result($res,$i,'nome');
			$ema = $dba->result($res,$i,'email');			
			$sen = $dba->result($res,$i,'senha');
			
			$usu = new Usuario();

			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setSenha($sen);


			$vet[$i] = $usu;

		}

		return $vet;

	}
	
	public function inserir($usu){

		$dba = $this->dba;

		$nom   = $usu->getNome();
		$ema   = $usu->getEmail();
		$log   = $usu->getLogin();
		$idr   = $usu->getIdRota();
		$ids   = $usu->getIdsys();
		$sen   = $usu->getSenha();
		$idemp = $usu->getIdEmp();

		$sql = 'INSERT INTO usuario

							(
							 nome,
							 email,
							 login,
							 senha,
							 idrota,
							 idsys,
							 id_emp)

							VALUES

							(

							"'.$nom.'",
							"'.$ema.'",
							"'.$log.'",
							"'.$sen.'",
							'.$idr.',
							'.$ids.',
							'.$idemp.'
							)';

		$dba->query($sql);		

	}

	

	public function update($usu){

		$dba = $this->dba;
	
		$idu = $usu->getCodigo();
		$nom = $usu->getNome();
		$ema = $usu->getEmail();
		$log = $usu->getLogin();
		$idr = $usu->getIdRota();
		$ids = $usu->getIdsys();
		$sen = $usu->getSenha();

		$sql = 'UPDATE  usuario

				SET

				nome = "'.$nom.'",
				email  = "'.$ema.'",
				login  = "'.$log.'",
				idrota = '.$idr.',
				idsys = '.$ids.',
				senha = "'.$sen.'"
				WHERE id='.$idu;		

		$dba->query($sql);	

	}

	public function deletar($usu){

		$dba = $this->dba;

		$idu = $usu->getCodigo();

		$sql = 'DELETE FROM usuario WHERE id='.$idu;

		$dba->query($sql);	
		
	}

	public function updatesenha($usu){
		
		$dba = $this->dba;
	
		$idu = $usu->getCodigo();	
		$sen = $usu->getSenha();	 	
		
		$sql = 'UPDATE  usuario
			SET				
			senha = "'.$sen.'"
			WHERE id='.$idu;	
		
		//echo $sql;
		$dba->query($sql);	

	}

	public function proximoid(){

		$dba = $this->dba;

		$vet = array();		

		$sql = 'SELECT max(id) + 1 as max_id from usuario';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'max_id');	 

		$usu = new Usuario();

		$usu->setProxid($prox_id);

		$vet[$i] = $usu;

		return $vet;
	
	}	

}

?>
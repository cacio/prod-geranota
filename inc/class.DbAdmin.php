<?php

class DbAdmin{

	//tipo: ira receber o tipo de linguagem que iremos utilizar no DB.
	private $tipo;
	//conn: irá receber o endereço da conexao ativa.
	private $conn;
	
	public function __construct($tipo){
		$this->tipo = $tipo;
	}
	
	public function connect($host,$user,$sen,$base){
		
		switch($this->tipo){			
			
			case 'mysql':
			$this->conn = mysqli_connect($host,$user,$sen,$base) 
						or die("Erro ao conectar ao sql");
			mysqli_set_charset($this->conn,"utf8");			
/*			mysqli_select_db($base) 
				or die("Não foi possivel conectar na base informada");*/			
				
			break;
			
			case 'pgsql':
			
				//codigo da classe refente a conexao com o banco em pgsql
			
			break;
			
			case 'mssql':
				
				//codigo da classe refente a conexao com o banco em mssql
				
			break;
			
			case 'firebird':
			
				//codigo da classe refente a conexao com o banco em firebird
				$this->conn = ibase_connect($host,$user,$sen) or die("".ibase_errmsg()."|".ibase_errcode()."");

			break;
		
		}
		
	}
	public function query($sql){
		
		switch($this->tipo){			
			
			case 'mysql':
			
				$res = mysqli_query($this->conn,$sql) 
								  or die(mysqli_error());		
								  
				
			break;
			
			case 'pgsql':
			
				//codigo da classe refente a conexao com o banco em pgsql
			
			break;
			
			case 'mssql':
				
				//codigo da classe refente a conexao com o banco em mssql
				
			break;
			
			case 'firebird':
			
				//codigo da classe refente a conexao com o banco em firebird
				$res = ibase_query($this->conn,$sql) or die("".ibase_errmsg()."|".ibase_errcode()."");
				
			break;
		
		}	
		return $res;
	}
	public function rows($res){
	
		switch($this->tipo){			
			
			case 'mysql':
			
				$num = mysqli_num_rows($res);
				
			break;
			
			case 'pgsql':
			
				//codigo da classe refente a conexao com o banco em pgsql
			
			break;
			
			case 'mssql':
				
				//codigo da classe refente a conexao com o banco em mssql
				
			break;
			
			case 'firebird':
			
				//codigo da classe refente a conexao com o banco em firebird
				$num = ibase_num_fields($res);
				
			break;
		
		}	
		return $num;
	}
	public function result($res, $lin,$col){
		
		switch($this->tipo){			
			
			case 'mysql':
			
			$val = $this->mysqliresult($res, $lin,$col);
				
			break;
			
			case 'pgsql':
			
				//codigo da classe refente a conexao com o banco em pgsql
			
			break;
			
			case 'mssql':
				
				//codigo da classe refente a conexao com o banco em mssql
				
			break;
			
			case 'firebird':
			
				//codigo da classe refente a conexao com o banco em firebird
				$val = ibase_field_info($res, $lin);
			break;
		
		}	
		return $val;	
	}
	
	public function mysqliresult($result, $number, $field) {
    	mysqli_data_seek($result, $number);
    	$row = mysqli_fetch_assoc($result);
    	return $row[$field];
	}
}	
?>
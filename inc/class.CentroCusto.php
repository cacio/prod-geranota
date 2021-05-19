<?php



class CentroCusto{

	

	private $codigo;

	private $nome;

	private $idgrupo;

	private $nomgrupo;

	private $codfor;

	public function CentroCusto(){

		//nada

		

	}

	

	public function getCodigo(){

		

		return $this->codigo;

	}

	public function setCodigo($codigo){

		$this->codigo = $codigo;

	}

	

	public function getNome(){

		

		return $this->nome;

	}

	

	public function setNome($nome){

		$this->nome = $nome;

	}

	

	public function getIdgrupo(){

		return $this->idgrupo;

	}

	

	public function setIdgrupo($idgrupo){

		$this->idgrupo = $idgrupo;

	}

	

	public function getNomgrupo(){

		return $this->nomgrupo;

	}

	

	public function setNomgrupo($nomgrupo){

		$this->nomgrupo = $nomgrupo;

	}


	public function getCodFornecedor(){

		return $this->codfor;

	}

	

	public function setCodFornecedor($codfor){

		$this->codfor = $codfor;

	}
	

}

?>
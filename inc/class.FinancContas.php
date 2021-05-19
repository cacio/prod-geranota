<?php



class FinancContas{

	

	private $codigo;
	private $descricao;
	private $saldo;
	private $cod_con_redu;
	private $cod_con_class;
	private $anali_sinte;
	private $dataatualizacao;
	private $saldoini;
	private $centrocusto;
	private $reduzido;
	private $idhistorico;
	private $codhistorico;
	private $historico;
	private $proxidredu;
	private $proxid;
	
	public function __construct(){

		//nada
	}

	public function getCodigo(){
	
		return $this->codigo;

	}

	public function setCodigo($codigo){

		$this->codigo = $codigo;

	}

	public function getDescricao(){	
	
		return $this->descricao;

	}

	public function setDescricao($descricao){

		$this->descricao = $descricao;

	}

	public function getSaldo(){	
	
		return $this->saldo;

	}

	public function setSaldo($saldo){

		$this->saldo = $saldo;

	}
	
	public function getCodConRedu(){	
	
		return $this->cod_con_redu;

	}

	public function setCodConRedu($codconredu){

		$this->cod_con_redu = $codconredu;

	}

	public function getCodConClass(){	
	
		return $this->cod_con_class;

	}

	public function setCodConClass($codconclass){

		$this->cod_con_class = $codconclass;

	}
	
	public function getAnaliSinte(){	
	
		return $this->anali_sinte;

	}

	public function setAnaliSinte($anali_sinte){

		$this->anali_sinte = $anali_sinte;

	}
	
	public function getDataAtualizacao(){	
	
		return $this->dataatualizacao;

	}

	public function setDataAtualizacao($dtatu){

		$this->dataatualizacao = $dtatu;

	}
	public function getSaldoIni(){	
	
		return $this->saldoini;

	}

	public function setSaldoIni($saldoini){

		$this->saldoini = $saldoini;

	}

	public function getCentroCusto(){	
	
		return $this->centrocusto;

	}

	public function setCentroCusto($centrocusto){

		$this->centrocusto = $centrocusto;

	}


	public function getReduzido(){	
	
		return $this->reduzido;

	}

	public function setReduzido($reduz){

		$this->reduzido = $reduz;

	}

	public function getIdHistorico(){	
		return $this->idhistorico;
	}

	public function setIdHistorico($idhist){
		$this->idhistorico = $idhist;
	}

	public function getCodHistorico(){	
		return $this->codhistorico;
	}

	public function setCodHistorico($codhist){
		$this->codhistorico = $codhist;
	}

	public function getHistorico(){	
		return $this->historico;
	}

	public function setHistorico($hist){
		$this->historico = $hist;
	}

	public function getProxIdRedu(){	
		return $this->proxidredu;
	}

	public function setProxIdRedu($proxidred){
		$this->proxidredu = $proxidred;
	}

	public function getProxId(){	
		return $this->proxid;
	}

	public function setProxId($proxid){
		$this->proxid = $proxid;
	}

}

?>
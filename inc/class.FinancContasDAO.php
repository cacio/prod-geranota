<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class FinancContasDAO{

	

	private $dba;

	

	public function __construct()
	{

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function RelatorioFinancContas($dtini,$dtfim){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select * from balancete('".$dtini."','".$dtfim."')";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->R_CODIGO;
			$as              = $fc->R_AS;	
			$descricao       = $fc->R_DESCRICAO;
			$codconredu      = $fc->R_COD_CON_REDU;
			$codconclas      = $fc->R_COD_CON_CLAS;
			$saldo           = $fc->R_SALDO;
			
			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao($descricao);
			$fct->setAnaliSinte($as);
			$fct->setCodConRedu($codconredu);
			$fct->setCodConClass($codconclas);
			$fct->setSaldo($saldo);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}
	
public function RelatorioFinancCadastroContas($dtini,$dtfim){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select * from balancete_contas('".$dtini."','".$dtfim."')";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->R_CODIGO;
			$as              = $fc->R_AS;	
			$descricao       = $fc->R_DESCRICAO;
			$codconredu      = $fc->R_COD_CON_REDU;
			$codconclas      = $fc->R_COD_CON_CLAS;
			$saldo           = $fc->R_SALDO;
			$centro          = $fc->CENTROCUSTO;
			
			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao($descricao);
			$fct->setAnaliSinte($as);
			$fct->setCodConRedu($codconredu);
			$fct->setCodConClass($codconclas);
			$fct->setSaldo($saldo);
			$fct->setCentroCusto($centro);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}	
public function BuscaFinancContas($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM FINANC_CONTAS f
			 where (f.descricao like '%'||UPPER('$search')||'%' or f.reduzido like '%'||UPPER('$search')||'%') and f.ANALI_SINTE = 'A' ";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			$centrocusto     = $fc->CENTRO_CUSTO;
			$codconclas		 = $fc->COD_CON_CLAS;	
			$reduzido		 = $fc->REDUZIDO;

			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao($descricao);
			$fct->setCentroCusto($centrocusto);
			$fct->setCodConClass($codconclas);
			$fct->setReduzido($reduzido);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}


	public function ListaFinancContas(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM FINANC_CONTAS f where f.ANALI_SINTE = 'A'";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			$centrocusto     = $fc->CENTRO_CUSTO;
			$codconclas		 = $fc->COD_CON_CLAS;	
			$reduzido        = $fc->REDUZIDO;

			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao(utf8_decode($descricao));
			$fct->setCentroCusto($centrocusto);
			$fct->setCodConClass($codconclas);
			$fct->setReduzido($reduzido);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}
	public function ListaFinancContasPsq($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM FINANC_CONTAS f {$where}";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			$centrocusto     = $fc->CENTRO_CUSTO;
			$codconclas		 = $fc->COD_CON_CLAS;	
			$reduzido        = $fc->REDUZIDO;

			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao(utf8_decode($descricao));
			$fct->setCentroCusto($centrocusto);
			$fct->setCodConClass($codconclas);
			$fct->setReduzido($reduzido);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}

	public function ListaFinancContasParaAlteracao(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				F.CODIGO,
				F.DESCRICAO,
				H.ID, 
				H.CODIGO AS CODHISTORICO,
				H.DESCRICAO AS DESCHISTORICO,
				F.REDUZIDO
				FROM FINANC_CONTAS F
				LEFT JOIN HISTORICOPADRAO H ON (H.ID = F.CENTRO_CUSTO)
				WHERE F.ANALI_SINTE = 'A'";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			$id				 = $fc->ID;
			$CODHISTORICO	 = $fc->CODHISTORICO;
			$historico       = $fc->DESCHISTORICO;
			$reduzido        = $fc->REDUZIDO;

			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao(utf8_decode($descricao));
			$fct->setIdHistorico($id);
			$fct->setCodHistorico($CODHISTORICO);
			$fct->setHistorico($historico);
			$fct->setReduzido($reduzido);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}

	public function VerificaFinancContas($cod){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT f.REDUZIDO FROM FINANC_CONTAS f where f.CODIGO = '{$cod}' and f.ANALI_SINTE = 'A'";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			
			$reduzido        = $fc->REDUZIDO;

			$fct = new FinancContas();			
			
			$fct->setReduzido($reduzido);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}
	
	public function BuscaFinancContasPorCentro($ccont,$search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
					*
				FROM
					financ_contas f
						left join
					financ_centro c ON (c.financ = f.codigo)
				where
					c.centro = '".$ccont."' or f.descricao like '%'
						|| UPPER('".$search."')
						|| '%'
						or f.codigo = '".$search."'";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			$centrocusto     = $fc->CENTRO_CUSTO;
			$codconclas		 = $fc->COD_CON_CLAS;	
			
			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao(utf8_decode($descricao));
			$fct->setCentroCusto($centrocusto);
			$fct->setCodConClass($codconclas);

			
			$vet[$i++] = $fct;

		}

		return $vet;

	}

	public function VerificaFinancContasPorCentro($cconta){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select F.REDUZIDO as CODIGO,
					   (select *
						from TIRA_ACENTOS(F.DESCRICAO)) as DESCRICAO, F.CENTRO_CUSTO, F.COD_CON_CLAS
				from FINANC_CONTAS F
				where F.CODIGO = '".$cconta."' and
					  F.ANALI_SINTE = 'A'
				order by F.COD_CON_CLAS asc  ";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			$centrocusto     = $fc->CENTRO_CUSTO;
			$codconclas		 = $fc->COD_CON_CLAS;	
		
			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao(utf8_decode($descricao));
			$fct->setCentroCusto($centrocusto);
			$fct->setCodConClass($codconclas);

			
			$vet[$i++] = $fct;

		}

		return $vet;

	}

	public function ProxIdRedu(){

		$dba = $this->dba;

		$vet = array();

		$sql ="select
					(max(f.reduzido) + 1) as codigo,
					(select lpad(cast(COALESCE(max(ff.codigo),0) as integer) + 1,5,'0') from financ_contas ff where ff.codigo < 9999) as proximo
				from financ_contas f where (f.reduzido is not null or f.reduzido <> '')";

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$proximo		 = $fc->PROXIMO;
	
			$fct = new FinancContas();			

			$fct->setProxIdRedu($codigo);
			$fct->setProxId($proximo);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}

	public function ValidaFincHistoricoPadrao(){

		$dba = $this->dba;

		$vet = array();

		$sql ="select distinct FC.CODIGO, FC.DESCRICAO, FC.CENTRO_CUSTO
				from FINANC_CONTAS FC
				inner join APROPRIACAO_MOVIMENTACAO AM on (AM.CONTA = FC.CODIGO)
				where FC.CENTRO_CUSTO is null;";

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo      = $fc->CODIGO;
			$desc		 = $fc->DESCRICAO;
	
			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao($desc);
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}	

	public function alterarhist($con){

		$dba = $this->dba;

		$codigo = $con->getCodigo();		
		$idhist = $con->getIdHistorico();

		$sql = "update financ_contas
		set
			centro_custo = {$idhist}
		where (codigo = '{$codigo}')";

		$dba->query($sql);

	}

	public function inserirfinanc($fc){

		$dba = $this->dba;

		$codigo   	 = $fc->getCodigo();		
		$reduzido 	 = $fc->getReduzido();
		$codconclass = $fc->getCodConClass();
		$desc 		 = $fc->getDescricao();
		
		$sql = "insert into financ_contas (codigo, descricao, cod_con_clas, anali_sinte, reduzido)
				values ('{$codigo}', '{$desc}', '{$codconclass}', 'A', {$reduzido})";

		$dba->query($sql);

	}
	
	public function save($path,$string)
	{
		$folder = dirname($path);
		if (! is_dir($folder)) {
			mkdir($folder, 0777, true);
		}

		if (! is_writable(dirname($path))) {
			throw new \Exception('Path ' . $folder . ' não possui permissao de escrita');
		}

		file_put_contents($path, $string);

		return $path;
	}

	public function alterar($con){

	

		$dba = $this->dba;

		

		$cod = $con->getCodigo();

		$nom = $con->getNome();

		$idg = $con->getIdgrupo();

			

			

		$sql = '';

		

		$dba->query($sql);

		

	}

	

	public function deletar($con){

		

		

		$dba = $this->dba;

		

		$idc = $con->getCodigo();

			

			

		$sql = '';

		

		$dba->query($sql);

		

	}

	

}

?>
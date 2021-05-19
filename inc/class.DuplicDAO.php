<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class DuplicDAO{



	

	private $dba;



	public function DuplicDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function RelatorioContasPagarVencimento($where,$dt){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
			d.numero,
			sum(d.valordoc) as valordoc,
			d.numero_nota,
			d.vencimento,
			f.codigo,
			f.nome,
			d.tipo
		FROM
			duplic d
				inner join
			fornecedores f ON (f.codigo = d.cedente)
			".$where." and d.vencimento = '".$dt."' and d.saldo != '0.00' AND d.saldo >= '0.00'
		group by d.numero , d.numero_nota , d.vencimento , f.codigo , f.nome , d.tipo";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero      = $dup->NUMERO;
			$valordoc    = $dup->VALORDOC;
			$numnota     = $dup->NUMERO_NOTA;
			$vencimento  = $dup->VENCIMENTO;
			$codfor      = $dup->CODIGO;
			$nomfor      = $dup->NOME;
			$tipo        = $dup->TIPO; 			
			
			$duplic = new Duplic();			

			$duplic->setNumero($numero);
			$duplic->setValorDoc($valordoc);
			$duplic->setNumeroNota($numnota);
			$duplic->setVencimento($vencimento);
			$duplic->setCodFornecedor($codfor);
			$duplic->setNomeFornevedor($nomfor);
			$duplic->setTipo($tipo);
			
			
			$vet[$i++] = $duplic;

		}

		return $vet;

	}
	
	
	public function ContasPagarVencimentoPorData($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				d.vencimento
			FROM
				duplic d
					inner join
				fornecedores f ON (f.codigo = d.cedente)
				".$where." and d.saldo != '0.00' AND d.saldo >= '0.00'
			group by  d.vencimento";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
				
			$vencimento  = $dup->VENCIMENTO;
				
			
			$duplic = new Duplic();			
		
			$duplic->setVencimento($vencimento);
			
			
			$vet[$i++] = $duplic;

		}

		return $vet;

	}
	
	public function RelatorioContasPagarEmissao($where,$dtemi){

		
		//and d.saldo != '0.00' AND d.saldo > '0.00'
				
		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				d.emissao,
				d.vencimento,
				d.numero,
				sum(d.valordoc) as valordoc,
				d.numero_nota,
				f.codigo,
				f.nome
			FROM
				duplic d
					inner join
				fornecedores f ON (f.codigo = d.cedente)
			".$where." and d.emissao = '".$dtemi."'
			group by d.numero , d.numero_nota , d.emissao , f.codigo , f.nome , d.tipo,d.vencimento";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero      = $dup->NUMERO;
			$valordoc    = $dup->VALORDOC;
			$numnota     = $dup->NUMERO_NOTA;
			$emissao	 = $dup->EMISSAO;
			$codfor      = $dup->CODIGO;
			$nomfor      = $dup->NOME;					
			$vencimento  = $dup->VENCIMENTO;
			
			$duplic = new Duplic();			

			$duplic->setNumero($numero);
			$duplic->setValorDoc($valordoc);
			$duplic->setNumeroNota($numnota);
			$duplic->setCodFornecedor($codfor);
			$duplic->setNomeFornevedor($nomfor);
			$duplic->setEmissao($emissao);
			$duplic->setVencimento($vencimento);
			
			$vet[$i++] = $duplic;

		}

		return $vet;

	}
	
		public function DataContasPagarEmissao($where){

		
		//and d.saldo != '0.00' AND d.saldo > '0.00'
				
		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				d.emissao
			FROM
				duplic d
					inner join
				fornecedores f ON (f.codigo = d.cedente)
			".$where."
			group by d.emissao order by d.emissao";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			

			$emissao	 = $dup->EMISSAO;

			
			$duplic = new Duplic();			
		
			$duplic->setEmissao($emissao);
		
			$vet[$i++] = $duplic;

		}

		return $vet;

	}

	public function RelatorioContasPagarPorFornecedor($where,$codigo){

		
		//and d.saldo != '0.00' AND d.saldo > '0.00'
				
		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				d.emissao,
				d.vencimento,
				d.numero,
				sum(d.valordoc) as valordoc,
				d.numero_nota,
				f.codigo,
				f.nome,
				d.parcial1,
				d.parcial2,
				d.parcial3,
				d.parcial4,
				d.parcial5,
				d.data_parcial1,
				d.data_parcial2,
				d.data_parcial3,
				d.data_parcial4,
				d.data_parcial5
			FROM
				duplic d
					inner join
				fornecedores f ON (f.codigo = d.cedente)
				".$where."
					and f.codigo = ".$codigo."
			group by d.numero , d.numero_nota , d.emissao , f.codigo , f.nome , d.tipo , d.vencimento , d.parcial1 , d.parcial2 , d.parcial3 , d.parcial4 , d.parcial5 , d.data_parcial1 , d.data_parcial2 , d.data_parcial3 , d.data_parcial4 , d.data_parcial5";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero        = $dup->NUMERO;
			$valordoc      = $dup->VALORDOC;
			$numnota       = $dup->NUMERO_NOTA;
			$emissao	   = $dup->EMISSAO;
			$codfor        = $dup->CODIGO;
			$nomfor        = $dup->NOME;					
			$vencimento    = $dup->VENCIMENTO;
			$parcial1      = $dup->PARCIAL1;
			$parcial2      = $dup->PARCIAL2;
			$parcial3      = $dup->PARCIAL3;
			$parcial4      = $dup->PARCIAL4;
			$parcial5      = $dup->PARCIAL5;
			$data_parcial1 = $dup->DATA_PARCIAL1;
			$data_parcial2 = $dup->DATA_PARCIAL2;
			$data_parcial3 = $dup->DATA_PARCIAL3;
			$data_parcial4 = $dup->DATA_PARCIAL4;
			$data_parcial5 = $dup->DATA_PARCIAL5;
			
			
			
			$duplic = new Duplic();			

			$duplic->setNumero($numero);
			$duplic->setValorDoc($valordoc);
			$duplic->setNumeroNota($numnota);
			$duplic->setCodFornecedor($codfor);
			$duplic->setNomeFornevedor($nomfor);
			$duplic->setEmissao($emissao);
			$duplic->setVencimento($vencimento);
			$duplic->setParcial1($parcial1);
			$duplic->setParcial2($parcial2);
			$duplic->setParcial3($parcial3);
			$duplic->setParcial4($parcial4);
			$duplic->setParcial5($parcial5);
			$duplic->setDataParcial1($data_parcial1);
			$duplic->setDataParcial2($data_parcial2);
			$duplic->setDataParcial3($data_parcial3);
			$duplic->setDataParcial4($data_parcial4);
			$duplic->setDataParcial5($data_parcial5);	
			
			$vet[$i++] = $duplic;

		}

		return $vet;

	}	
		public function RelatorioContasPagarPorFluxo($dt){

		
		//and d.saldo != '0.00' AND d.saldo > '0.00'
				
		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT
					sum(d.valordoc) as valordoc,
					d.vencimento,
					sum(d.parcial1) as parcial1,
					sum(d.parcial2) as parcial2,
					sum(d.parcial3) as parcial3,
					sum(d.parcial4) as parcial4,
					sum(d.parcial5) as parcial5,
				    sum(d.desc_abatm) as desc_abatm,
					sum(d.valdevolucao) as valdevolucao
					FROM
					duplic d
					where d.vencimento = '".$dt."'
					group by d.vencimento";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$valordoc      = $dup->VALORDOC;			
			$vencimento	   = $dup->VENCIMENTO;										
			$parcial1      = $dup->PARCIAL1;
			$parcial2      = $dup->PARCIAL2;
			$parcial3      = $dup->PARCIAL3;
			$parcial4      = $dup->PARCIAL4;
			$parcial5      = $dup->PARCIAL5;			
			$desc_abatm    = $dup->DESC_ABATM;
			$valdevolucao  = $dup->VALDEVOLUCAO;
			
			$duplic = new Duplic();			

			$duplic->setValorDoc($valordoc);
			$duplic->setVencimento($vencimento);
			$duplic->setParcial1($parcial1);
			$duplic->setParcial2($parcial2);
			$duplic->setParcial3($parcial3);
			$duplic->setParcial4($parcial4);
			$duplic->setParcial5($parcial5);
			$duplic->setDescAbtm($desc_abatm);
			$duplic->setValorDevolucao($valdevolucao);
			
			$vet[$i++] = $duplic;

		}

		return $vet;

	}		
	public function inserir($dup){
	

		$dba = $this->dba;
	
		$empresa    = $dup->getEmpresa();
		$codfor	    = $dup->getCodFornecedor();
		$emissao    = $dup->getEmissao();
		$numero     = $dup->getNumero();
		$tipo       = $dup->getTipo();
		$vencimento = $dup->getVencimento();
		$valordoc   = $dup->getValorDoc();
		$numeronota = $dup->getNumeroNota();
		$totanota   = $dup->getTotalNota();
		
		$sql = "insert into duplic
				(empresa,
				cedente, 
				emissao, 
				numero, 
				tipo, 
				vencimento, 
				valordoc, 
				numero_nota, 
				total_nota)
				 values
				('".$empresa."',
				'".$codfor."', 
				'".$emissao."', 
				REPLACE('".$numero."',' ',''), 
				'".$tipo."', 
				'".$vencimento."', 
				".$valordoc.", 
				'".$numeronota."',
				".$totanota.")";
		//echo $sql; 
		$res = $dba->query($sql);
			
	}

	

}

?>
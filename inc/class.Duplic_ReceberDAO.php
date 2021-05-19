<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class Duplic_ReceberDAO{



	

	private $dba;



	public function Duplic_ReceberDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function RelatorioContasReceberVencimento($where,$dt){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				c.codigo,
				c.nome,
				dr.numero,
				dr.tipo,
				sum(dr.valordoc) as valordoc,
				dr.vencimento,
				sum(dr.saldo) as total_nota,
				 dr.parcial1,
				dr.parcial2,
				dr.parcial3,
				dr.parcial4,
				dr.parcial5,
				dr.desc_abatm
			FROM
				duplic_receber dr
					INNER JOIN
				clientes c ON (c.codigo = dr.cedente)
				".$where."
					and dr.vencimento = '".$dt."'
					and dr.datapag is null
					and dr.saldo > 0
			group by c.codigo , c.nome , dr.numero , dr.tipo , dr.vencimento , dr.parcial1 , dr.parcial2 , dr.parcial3 , dr.parcial4 , dr.parcial5 , dr.desc_abatm";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero      = $dup->NUMERO;
			$valordoc    = $dup->VALORDOC;
			$vencimento  = $dup->VENCIMENTO;
			$codcli      = $dup->CODIGO;
			$nomcli      = $dup->NOME;
			$tipo        = $dup->TIPO; 			
			$toatlnota   = $dup->TOTAL_NOTA;
			$parcial1    = $dup->PARCIAL1;
			$parcial2    = $dup->PARCIAL2;
			$parcial3    = $dup->PARCIAL3;
			$parcial4    = $dup->PARCIAL4;
			$parcial5    = $dup->PARCIAL5;
			$desc_abatm  = $dup->DESC_ABATM; 	
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setValorDoc($valordoc);
			$duplicR->setVencimento($vencimento);
			$duplicR->setCodCliente($codcli);
			$duplicR->setNomeCliente($nomcli);
			$duplicR->setTipo($tipo);
			$duplicR->setTotalNota($toatlnota);
			$duplicR->setParcial1($parcial1);
			$duplicR->setParcial2($parcial2);
			$duplicR->setParcial3($parcial3);
			$duplicR->setParcial4($parcial4);
			$duplicR->setParcial5($parcial5);
			$duplicR->setDescAbtm($desc_abatm);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	
	public function ContasReceberVencimentoPorData($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				dr.vencimento
			FROM
				duplic_receber dr
					INNER JOIN
				clientes c ON (c.codigo = dr.cedente)
				".$where." and dr.datapag is null and dr.saldo > 0
			group by dr.vencimento";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
				
			$vencimento  = $dup->VENCIMENTO;
				
			
			$duplicR = new Duplic_Receber();			
		
			$duplicR->setVencimento($vencimento);
			
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	public function RelatorioContasReceberPorCliente($where,$codcli){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				dr.emissao,
				dr.vencimento,
				dr.numero,
				sum(dr.valordoc) as valordoc,
				dr.parcial1,
				dr.parcial2, 
				dr.parcial3, 
				dr.parcial4, 
				dr.parcial5,
				dr.data_parcial1,
				dr.data_parcial2,
				dr.data_parcial3,
				dr.data_parcial4,
				dr.data_parcial5, 
				dr.desc_abatm
			FROM
				duplic_receber dr
					inner join
				clientes c ON (c.codigo = dr.cedente)
				".$where."
					and c.codigo = '".$codcli."'
					and dr.datapag is null
					and dr.saldo > 0
			group by dr.emissao , dr.vencimento , dr.numero , c.nome , dr.parcial1 , dr.parcial2 , dr.parcial3 , dr.parcial4 , dr.parcial5 , dr.desc_abatm, dr.data_parcial1 , dr.data_parcial2 , dr.data_parcial3 , dr.data_parcial4 , dr.data_parcial5  order by dr.emissao";

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero        = $dup->NUMERO;
			$valordoc      = $dup->VALORDOC;
			$vencimento    = $dup->VENCIMENTO;
			$emissao       = $dup->EMISSAO;		
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
			$desc_abatm    = $dup->DESC_ABATM; 				
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setValorDoc($valordoc);
			$duplicR->setVencimento($vencimento);
			$duplicR->setParcial1($parcial1);
			$duplicR->setParcial2($parcial2);
			$duplicR->setParcial3($parcial3);
			$duplicR->setParcial4($parcial4);
			$duplicR->setParcial5($parcial5);
			$duplicR->setDataParcial1($data_parcial1);
			$duplicR->setDataParcial2($data_parcial2);
			$duplicR->setDataParcial3($data_parcial3);
			$duplicR->setDataParcial4($data_parcial4);
			$duplicR->setDataParcial5($data_parcial5);		
			$duplicR->setDescAbtm($desc_abatm);
			$duplicR->setEmissao($emissao);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	
	public function RelatorioListagePorRepresentante($where,$codrepre){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				dr.emissao,
				dr.vencimento,
				dr.numero,
				sum(dr.valordoc) as valordoc,
				r.nome as representante,
				c.nome as cliente
			FROM
				duplic_receber dr
					inner join
				clientes c ON (c.codigo = dr.cedente)
					inner join
				repre r ON (r.codigo = c.representante)
				".$where." and r.codigo = ".$codrepre."
			group by r.nome , c.nome , dr.emissao , dr.vencimento , dr.numero";

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero        = $dup->NUMERO;
			$valordoc      = $dup->VALORDOC;
			$vencimento    = $dup->VENCIMENTO;
			$emissao       = $dup->EMISSAO;		
			$repre         = $dup->REPRESENTANTE;
			$cli           = $dup->CLIENTE;				
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setValorDoc($valordoc);
			$duplicR->setVencimento($vencimento);
			$duplicR->setEmissao($emissao);
			$duplicR->settNomeRepresentante($repre);
			$duplicR->setNomeCliente($cli);
			
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
		public function RelatorioContasReceberPorFluxo($dt){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				sum(dr.valordoc) as valordoc,
				dr.vencimento,
				sum(dr.parcial1) as parcial1,
				sum(dr.parcial2) as parcial2,
				sum(dr.parcial3) as parcial3,
				sum(dr.parcial4) as parcial4,
				sum(dr.parcial5) as parcial5,
				sum(dr.desc_abatm) as desc_abatm,
			    sum(dr.valdevolucao) as valdevolucao
			FROM
				duplic_receber dr
				where dr.vencimento = '".$dt."'
			group by dr.vencimento";
		//echo $sql; 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			
			$valordoc      = $dup->VALORDOC;			
			$vencimento    = $dup->VENCIMENTO;		
			$parcial1      = $dup->PARCIAL1;
			$parcial2      = $dup->PARCIAL2;
			$parcial3      = $dup->PARCIAL3;
			$parcial4      = $dup->PARCIAL4;
			$parcial5      = $dup->PARCIAL5;
			$desc_abatm    = $dup->DESC_ABATM;	
			$valdevolucao  = $dup->VALDEVOLUCAO;
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setValorDoc($valordoc);
			$duplicR->setParcial1($parcial1);
			$duplicR->setParcial2($parcial2);
			$duplicR->setParcial3($parcial3);
			$duplicR->setParcial4($parcial4);
			$duplicR->setParcial5($parcial5);	
			$duplicR->setVencimento($vencimento);
			$duplicR->setDescAbtm($desc_abatm);
			$duplicR->setValorDevolucao($valdevolucao);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	public function RelatorioListagemdecomissaopordatapagamento($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="Select 
				r.emissao,
				r.datapag,
				r.numero,
				c.nome as sacado,
				r.valordoc as valor
			from
				duplic_receber r
					inner join
				clientes c ON (c.codigo = r.cedente)
					inner join
				repre re ON (re.codigo = c.representante)
			".$where." and
				r.datapag is not null";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero        = $dup->NUMERO;
			$valordoc      = $dup->VALOR;
			$emissao       = $dup->EMISSAO;		
			$datapag	   = $dup->DATAPAG;
			$sacado 	   = $dup->SACADO;
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setValorDoc($valordoc);
			$duplicR->setEmissao($emissao);
			$duplicR->setDataPag($datapag);
			$duplicR->setNomeCliente($sacado);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeConferenciaDetalhe($where2,$code){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select 
				r.numero,
				r.numero_nota,
				r.valordoc as total_nota,
				r.vencimento,
				r.nosso_num,
				b.motivo
			FROM
				duplic_receber r
				left join
			    retorno_banco b on (b.numerodocumento = r.numero)
			".$where2." and
				r.numero_nota = '".$code."'";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$numero 	   = $dup->NUMERO;
			$totalnota     = $dup->TOTAL_NOTA;
			$vencimento    = $dup->VENCIMENTO;
			$nossonumero   = $dup->NOSSO_NUM;
			$numeronota    = $dup->NUMERO_NOTA;
			$motivo        = $dup->MOTIVO;  
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setTotalNota($totalnota);
			$duplicR->setVencimento($vencimento);
			$duplicR->setNossoNumero($nossonumero);	
			$duplicR->setNumeroNota($numeronota);
			$duplicR->setMotivo($motivo);
						
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	public function RelatorioListagemdecomissaoderecebimento($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
					r.emissao,
					r.datapag,
					r.numero,
					c.nome,
					(r.parcial1 + r.parcial2 + r.parcial3 + r.parcial4 + r.parcial5) as valor_pago
				FROM
					duplic_receber r
						inner join
					clientes c ON (c.codigo = r.cedente)
						inner join
					repre rp ON (rp.codigo = c.representante) 
					".$where." ";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$emissao       = $dup->EMISSAO;
			$datapag	   = $dup->DATAPAG;
			$numero        = $dup->NUMERO;
			$nome	       = $dup->NOME;								
			$vlpago 	   = $dup->VALOR_PAGO;
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setEmissao($emissao);
			$duplicR->setDataPag($datapag);
			$duplicR->setNumero($numero);
			$duplicR->setNomeCliente($nome);
			$duplicR->setValorPago($vlpago);						
			
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	public function RelatorioListagemdecomissaovencer($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				r.emissao,
				r.vencimento,
				r.numero,
				c.nome,
				r.valordoc
			FROM
				duplic_receber r
					inner join
				clientes c ON (c.codigo = r.cedente)
					inner join
				repre rp ON (rp.codigo = c.representante)
				".$where." ";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$emissao       = $dup->EMISSAO;	
			$numero        = $dup->NUMERO;
			$valordoc      = $dup->VALORDOC;				
			$dataven	   = $dup->VENCIMENTO;
			$sacado 	   = $dup->NOME;
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setValorDoc($valordoc);
			$duplicR->setEmissao($emissao);
			$duplicR->setVencimento($dataven);
			$duplicR->setNomeCliente($sacado);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	public function Relatoriocomissaoemissaoanterior($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				r.emissao,
				r.vencimento,
				r.numero,
				c.nome,
				r.valordoc
			FROM
				duplic_receber r
					inner join
				clientes c ON (c.codigo = r.cedente)
					inner join
				repre rp ON (rp.codigo = c.representante)
				".$where." ";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$emissao       = $dup->EMISSAO;	
			$numero        = $dup->NUMERO;
			$valordoc      = $dup->VALORDOC;				
			$dataven	   = $dup->VENCIMENTO;
			$sacado 	   = $dup->NOME;
			
			$duplicR = new Duplic_Receber();			

			$duplicR->setNumero($numero);
			$duplicR->setValorDoc($valordoc);
			$duplicR->setEmissao($emissao);
			$duplicR->setVencimento($dataven);
			$duplicR->setNomeCliente($sacado);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	
		public function RelatorioContasSomaValor($where,$str){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				   d.emissao,
				   sum(d.valordoc) as VALORDOC
				FROM duplic_receber d
			   ".$where." ".$str." group by d.emissao";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($dup = ibase_fetch_object($res)){		
			
			$valordoc      = $dup->VALORDOC;				
			$emissao	   = $dup->EMISSAO;	
						
			$duplicR = new Duplic_Receber();			

			$duplicR->setValorDoc($valordoc);
			$duplicR->setEmissao($emissao);
			
			$vet[$i++] = $duplicR;

		}

		return $vet;

	}
	
	public function inserir($flc){
	

		$dba = $this->dba;
	
		$sql ='';

		$res = $dba->query($sql);
			
	}

	

}

?>
<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class NotasEntradaMDAO{



	

	private $dba;



	public function NotasEntradaMDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function RelatorioListagemFaturamento($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT distinct
				(DATA_EMISSAO),
				COD_FORNEC,
				CLIENTES.nome AS CLIENTE,
				cfop,
				NUMERO_NOTA,
				SERIE_NOTA,
				valor_total_nota,
				repre.nome,
				n.RELFAT
			FROM
				notas_de_entradas_m
					INNER JOIN
				clientes ON (CLIENTES.codigo = COD_FORNEC)
					INNER JOIN
				repre ON (REPRE.codigo = CLIENTES.representante)
				left join natureza n on (n.codigo = cfop)
				".$where." ";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$dataemi     = $lf->DATA_EMISSAO;
			$codfor      = $lf->COD_FORNEC;
			$cliente     = $lf->CLIENTE;
			$cfop        = $lf->CFOP;
			$numnota     = $lf->NUMERO_NOTA;
			$serienota   = $lf->SERIE_NOTA;
			$vltotalnota = $lf->VALOR_TOTAL_NOTA;
			$represe     = $lf->NOME;
			$relfat		 = $lf->RELFAT;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dataemi);
			$nte->setCodigoFornecedor($codfor);
			$nte->setCliente(utf8_encode($cliente));			
			$nte->setCfop($cfop);
			$nte->setNumeroNota($numnota);
			$nte->setSerieNota($serienota);
			$nte->setValortotalnota($vltotalnota);
			$nte->setNomeRepresentante(utf8_encode($represe));
			$nte->setRelFat($relfat);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}


		public function RelatorioListagemDeProducao($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select 
    id, dt_prod, lote, descricao, preco_venda, saida, entrada
from
    (Select 
            n.id,
            n.dt_prod,
            n.lote,
            p.descricao,
            n.custo as preco_venda,
            case
                when n.ent_sai = 'S' then n.qtd
            end as Saida,
            case
                when n.ent_sai = 'E' then n.qtd
            end as entrada,
            p.grupo,
            p.codigo
    from
        movim_prod n
    inner join produtos p ON (p.codigo = n.id_prod) union all Select 
        m.reg,
            m.data_emissao,
            m.numero_nota,
            p.descricao,
            m.prod_preco_unitario,
            case
                when m.entrada_saida = 'E' then m.prod_quantidade
            end as entrada,
            case
                when m.entrada_saida = 'S' then m.prod_quantidade
            end as Saida,
            p.grupo,
            p.codigo
    from
        notas_de_entradas_m m
    inner join produtos p ON (p.codigo = m.prod_codigo)
        and p.grupo = 1) 
				".$where." order by dt_prod";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$dataemi     = $lf->DT_PROD;
			$numnota     = $lf->LOTE;
			$produto     = $lf->DESCRICAO;			
			$entrada     = $lf->ENTRADA;
			$saida       = $lf->SAIDA;
			$precvenda   = $lf->PRECO_VENDA;
			$id 		 = $lf->ID;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dataemi);
			$nte->setNumeroNota($numnota);
			$nte->setNomePorduto($produto);
			$nte->setEntrada($entrada);
			$nte->setSaida($saida);
			$nte->setPrecoUnitario($precvenda);
			$nte->setCodigo($id);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
		public function RelatorioListagemDeInventario($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM inventario(".$where.") order by grupo;";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$produto     = $lf->DESCRICAO;			
			$entrada     = $lf->ENTRADA;
			$saida       = $lf->SAIDA;
			$codpro      = $lf->CODIGO;
			$grupo       = $lf->GRUPO;
			
			$nte = new NotasEntradaM();			

			
			
			$nte->setNomePorduto($produto);
			$nte->setEntrada($entrada);
			$nte->setSaida($saida);
			$nte->setCodProduto($codpro);
			$nte->setGrupo($grupo);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioExtratoDoProduto($where,$where2){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select 
				data_emissao, numero_nota, descricao, Saida, entrada
			from
				(Select 
					n.data_emissao,
						n.numero_nota,
						p.descricao,
						case
							when n.entrada_saida = 'S' then n.prod_quantidade
						end as Saida,
						case
							when n.entrada_saida = 'E' then n.prod_quantidade
						end as entrada
				from
					notas_de_entradas_m n
				inner join produtos p ON (p.codigo = n.prod_codigo)
				".$where."
				group by n.numero_nota , n.data_emissao , p.descricao , Saida , entrada union all SELECT 
					mv.dt_prod,
						mv.lote,
						p.descricao,
						case
							when mv.ent_sai = 'S' then mv.qtd
						end as Saida,
						case
							when mv.ent_sai = 'E' then mv.qtd
						end as entrada
				FROM
					movim_prod mv
				inner join produtos p ON (p.codigo = mv.id_prod)
				".$where2."
				group by mv.lote , mv.dt_prod , p.descricao , Saida , entrada)
			order by data_emissao asc";
					
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$produto     = $lf->DESCRICAO;			
			$entrada     = $lf->ENTRADA;
			$saida       = $lf->SAIDA;
			$dataemi     = $lf->DATA_EMISSAO;
			$numnota     = $lf->NUMERO_NOTA;
			//$cfop        = $lf->CFOP;
			
			$nte = new NotasEntradaM();			

			
			
			$nte->setNomePorduto($produto);
			$nte->setEntrada($entrada);
			$nte->setSaida($saida);
			$nte->setDataEmissao($dataemi);
			$nte->setNumeroNota($numnota);
			//$nte->setCfop($cfop);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeVendedor($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				m.data_emissao,
				m.numero_nota,
				c.nome as cliente,
				m.prod_codigo || ' - ' || p.descricao as produto,
				m.prod_quantidade,
				m.prod_preco_unitario
			FROM
				notas_de_entradas_m m
					INNER JOIN
				clientes c ON (c.codigo = m.cod_fornec)
					INNER JOIN
				produtos p ON (P.codigo = m.prod_codigo)
				".$where." ";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$dataemi       = $lf->DATA_EMISSAO;
			$numnota       = $lf->NUMERO_NOTA;
			$cliente       = $lf->CLIENTE;
			$produto       = $lf->PRODUTO;
			$quantidade    = $lf->PROD_QUANTIDADE;
			$prod_precouni = $lf->PROD_PRECO_UNITARIO;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dataemi);
			$nte->setCliente(utf8_encode($cliente));			
			$nte->setNumeroNota($numnota);
			$nte->setCliente($cliente);
			$nte->setProduto($produto);
			$nte->setKg($quantidade);
			$nte->setPrecoUnitario($prod_precouni);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	
	public function RelatorioListagemDeVendedorResumo($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
			m.prod_codigo ||' - '|| p.descricao as produto,
			sum(m.prod_quantidade) as prod_quantidade,
			sum(m.prod_preco_unitario) as prod_preco_unitario,
			sum((m.prod_preco_unitario * m.prod_quantidade)) as total
			FROM notas_de_entradas_m m
			INNER JOIN
			    clientes c ON (c.codigo = m.cod_fornec)
			INNER JOIN
				produtos p ON (P.codigo = m.prod_codigo)
			".$where."  group by m.prod_codigo, p.descricao";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
		

			$produto       = $lf->PRODUTO;
			$quantidade    = $lf->PROD_QUANTIDADE;
			$prod_precouni = $lf->PROD_PRECO_UNITARIO;
			$total         = $lf->TOTAL;
			
			$nte = new NotasEntradaM();			
		
			$nte->setProduto($produto);
			$nte->setKg($quantidade);
			$nte->setPrecoUnitario($prod_precouni);
			$nte->setValortotalnota($total);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeClienteResumo($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
			m.prod_codigo ||' - '|| p.descricao as produto,
			sum(m.prod_quantidade) as prod_quantidade,
			sum(m.prod_preco_unitario) as prod_preco_unitario,
			sum((m.prod_preco_unitario * m.prod_quantidade)) as total
			FROM notas_de_entradas_m m
			INNER JOIN
			    clientes c ON (c.codigo = m.cod_fornec)
			INNER JOIN
				produtos p ON (P.codigo = m.prod_codigo)
			".$where."  group by m.prod_codigo, p.descricao";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
		

			$produto       = $lf->PRODUTO;
			$quantidade    = $lf->PROD_QUANTIDADE;
			$prod_precouni = $lf->PROD_PRECO_UNITARIO;
			$total         = $lf->TOTAL;
			
			$nte = new NotasEntradaM();			
		
			$nte->setProduto($produto);
			$nte->setKg($quantidade);
			$nte->setPrecoUnitario($prod_precouni);
			$nte->setValortotalnota($total);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeCliente($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
				m.data_emissao,
				m.numero_nota,
				c.nome as cliente,
				m.prod_codigo || ' - ' || p.descricao as produto,
				m.prod_quantidade,
				m.prod_preco_unitario
			FROM
				notas_de_entradas_m m
					INNER JOIN
				clientes c ON (c.codigo = m.cod_fornec)
					INNER JOIN
				produtos p ON (P.codigo = m.prod_codigo)
				".$where." ";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$dataemi       = $lf->DATA_EMISSAO;
			$numnota       = $lf->NUMERO_NOTA;
			$cliente       = $lf->CLIENTE;
			$produto       = $lf->PRODUTO;
			$quantidade    = $lf->PROD_QUANTIDADE;
			$prod_precouni = $lf->PROD_PRECO_UNITARIO;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dataemi);
			$nte->setCliente(utf8_encode($cliente));			
			$nte->setNumeroNota($numnota);
			$nte->setCliente($cliente);
			$nte->setProduto($produto);
			$nte->setKg($quantidade);
			$nte->setPrecoUnitario($prod_precouni);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	
	public function RelatorioListagemDeComprasPorProducao($where,$where2){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select 
    descricao, preco_venda, saida, entrada, total
from
    (Select 
        p.descricao,
            sum(n.custo) as preco_venda,
            sum(case
                when n.ent_sai = 'S' then n.qtd
            end) as Saida,
            sum(case
                when n.ent_sai = 'E' then n.qtd
            end) as entrada,
            sum(case
                when n.ent_sai = 'E' then n.qtd * n.custo
            end) as total
    from
        movim_prod n
    inner join produtos p ON (p.codigo = n.id_prod)
   ".$where."
    group by p.descricao union all Select 
        p.descricao,
            sum(m.prod_preco_unitario) as preco_venda,
            sum(case
                when m.entrada_saida = 'E' then m.prod_quantidade
            end) as entrada,
            sum(case
                when m.entrada_saida = 'S' then m.prod_quantidade
            end) as Saida,
            sum(case
                when m.entrada_saida = 'E' then m.prod_quantidade * m.prod_preco_unitario
            end) as total
    from
        notas_de_entradas_m m
    inner join produtos p ON (p.codigo = m.prod_codigo)
		".$where2."
    group by p.descricao)";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


			$produto     = $lf->DESCRICAO;			
			$entrada     = $lf->ENTRADA;
			$saida       = $lf->SAIDA;
			$precvenda   = $lf->PRECO_VENDA;
			$total       = $lf->TOTAL;
			
			$nte = new NotasEntradaM();			

			$nte->setNomePorduto($produto);
			$nte->setEntrada($entrada);
			$nte->setSaida($saida);
			$nte->setPrecoUnitario($precvenda);
			$nte->setTotal($total);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	
		public function RelatorioClientesPositivados($where,$where2){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select 
					rp.codigo,
					rp.nome,
					sum((m.prod_preco_unitario * m.prod_quantidade)) as faturamento,
					sum(m.prod_quantidade) as KGS,
					(select 
							count(distinct (cod_fornec))
						from
							notas_de_entradas_m
								inner join
							clientes ON (codigo = cod_fornec)
								inner join
							repre ON (repre.codigo = representante)
						".$where2."
								and entrada_saida = 'S'
								and repre.codigo = rp.codigo
						group by REPRESENTANTE) as cliente
				from
					notas_de_entradas_m m
						inner join
					clientes c ON (c.codigo = m.cod_fornec)
						inner join
					repre rp ON (rp.codigo = c.representante)
					".$where."
					 and m.entrada_saida = 'S'
				group by rp.codigo , rp.nome";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


			$codrepre      = $lf->CODIGO;			
			$nomerepre     = $lf->NOME;
			$faturamento   = $lf->FATURAMENTO;
			$kgs   		   = $lf->KGS;
			$cliente       = $lf->CLIENTE;
			
			$nte = new NotasEntradaM();			

			$nte->setCodRepresentante($codrepre);
			$nte->setNomeRepresentante($nomerepre);
			$nte->setFaturamento($faturamento);
			$nte->setKg($kgs);
			$nte->setCliente($cliente);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeConferencia($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select 
				m.data_emissao,
				m.numero_nota,
				m.valor_total_nota,
				c.nome
			from
				notas_de_entradas_m m
				 inner join
				clientes c on (c.codigo = m.cod_fornec)
				".$where."
			group by m.data_emissao , m.numero_nota,c.nome,m.valor_total_nota order by m.data_emissao,c.nome";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


			$dtemis	       = $lf->DATA_EMISSAO;			
			$numeronota    = $lf->NUMERO_NOTA;
			$valortotnot   = $lf->VALOR_TOTAL_NOTA;
			$cliente	   = $lf->NOME;

			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dtemis);
			$nte->setNumeroNota($numeronota);
			$nte->setValortotalnota($valortotnot);
			$nte->setCliente($cliente);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioCurvaAbc($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
					c.codigo,
					c.nome,
					sum(m.valor_total_nota) as valor_total_nota
				FROM
					NOTAS_DE_ENTRADAS_M m
						inner join
					clientes c ON (c.codigo = m.cod_fornec)
				".$where."	
				group by c.codigo , c.nome
				order by valor_total_nota desc";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$codigo	       = $lf->CODIGO;			
			$valortotnot   = $lf->VALOR_TOTAL_NOTA;
			$cliente	   = $lf->NOME;

			
			$nte = new NotasEntradaM();			

			$nte->setCodigo($codigo);
			$nte->setValortotalnota($valortotnot);
			$nte->setCliente($cliente);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioCurvaAbcTotal($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				sum(m.valor_total_nota) as valor_total_nota
			FROM
				NOTAS_DE_ENTRADAS_M m
					inner join
				clientes c ON (c.codigo = m.cod_fornec)
				".$where."";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
					
			$valortotnot   = $lf->VALOR_TOTAL_NOTA;
			
			$nte = new NotasEntradaM();			
			
			$nte->setValortotalnota($valortotnot);
			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemFofao($where,$pro,$cli){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				m.data_emissao,
				(select  first 1 r.vencimento from duplic_receber r
					where r.numero_nota = m.numero_nota) as vencimento,
				c.codigo,
				c.codigo||' - '|| c.nome as CLIENTE,
				m.numero_nota,
				sum(m.PROD_PRECO_UNITARIO * m.prod_quantidade) as valor_total_nota,
				c.fantasia
			FROM
				NOTAS_DE_ENTRADAS_M m
					inner join
				clientes c ON (c.codigo = m.cod_fornec)
				".$where." ".$pro." ".$cli."
			group by c.nome,m.data_emissao, vencimento,m.numero_nota,c.fantasia,c.codigo";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$dtemis	       = $lf->DATA_EMISSAO;			
			$vencimento	   = $lf->VENCIMENTO;
			$cliente	   = $lf->CLIENTE;
			$numeronota	   = $lf->NUMERO_NOTA;
			$vltotanota	   = $lf->VALOR_TOTAL_NOTA;
			$fatasia	   = $lf->FANTASIA;
			$codcli 	   = $lf->CODIGO;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dtemis);
			$nte->setCliente($cliente);
			$nte->setNumeroNota($numeronota);
			$nte->setValortotalnota($vltotanota);
			$nte->setFantasia($fatasia);
			$nte->setVencimento($vencimento);
			$nte->setCodigo($codcli);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	public function RelatorioListagemFofaoDetalhesProdutos($where,$cod,$pro,$cli){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				m.prod_codigo,
				p.descricao,
				m.prod_quantidade,
				m.PROD_PRECO_UNITARIO,
				(m.PROD_PRECO_UNITARIO * m.prod_quantidade) as valor_total_produtos
			FROM
				NOTAS_DE_ENTRADAS_M m
					inner join
				clientes c ON (c.codigo = m.cod_fornec)
					inner join
				produtos p ON (p.codigo = m.prod_codigo)
			".$where." and c.codigo = ".$cod." ".$pro." ".$cli."";
					
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$prodcodigo	       = $lf->PROD_CODIGO;			
			$descricao         = $lf->DESCRICAO;	
			$vltotalprod	   = $lf->VALOR_TOTAL_PRODUTOS;
			$prodqtd		   = $lf->PROD_QUANTIDADE;	
			$precunit		   = $lf->PROD_PRECO_UNITARIO;
			
			$nte = new NotasEntradaM();			

			$nte->setCodProduto($prodcodigo);
			$nte->setNomePorduto($descricao);
			$nte->setProdutoValor($vltotalprod);
			$nte->setProdutoQuantidade($prodqtd);
			$nte->setPrecoUnitario($precunit);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	public function RelatorioListagemFofaorResumo($where,$pro,$cli){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				m.prod_codigo,
				p.descricao,
				sum(m.PROD_PRECO_UNITARIO * m.prod_quantidade) as valor_total_produtos,
				sum((m.PROD_PRECO_UNITARIO - (m.PROD_PRECO_UNITARIO * (100 -(100 - 3))/100) - (m.PROD_PRECO_UNITARIO * (100 -(100 - 2))/100)- (m.PROD_PRECO_UNITARIO * (100 -(100 -  1))/100)) * m.prod_quantidade) as valor_total_liquido,
				sum(m.prod_quantidade) as prod_quantidade
			FROM
				NOTAS_DE_ENTRADAS_M m
					inner join
				clientes c ON (c.codigo = m.cod_fornec)
					inner join
				produtos p ON (p.codigo = m.prod_codigo)
				".$where." ".$pro." ".$cli."
				group by m.data_emissao,m.prod_codigo,p.descricao";
					
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$prodcodigo	       = $lf->PROD_CODIGO;			
			$descricao         = $lf->DESCRICAO;	
			$vltotalprod	   = $lf->VALOR_TOTAL_PRODUTOS;
			$prodqtd		   = $lf->PROD_QUANTIDADE;	
			$vltotliq          = $lf->VALOR_TOTAL_LIQUIDO;
			
			$nte = new NotasEntradaM();			

			$nte->setCodProduto($prodcodigo);
			$nte->setNomePorduto($descricao);
			$nte->setProdutoValor($vltotalprod);
			$nte->setProdutoQuantidade($prodqtd);
			$nte->setValorTotalLiquido($vltotliq);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemFofaorResumoCliente($where,$pro,$cli){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT
				m.data_emissao,
				(select first 1 r.vencimento from duplic_receber r where r.numero_nota = m.numero_nota) as vencimento,
				c.nome as CLIENTE,
				SUBSTRING(c.cnpj_cpf FROM 1 FOR 8) as CNPJ,
				m.numero_nota,
				sum(m.PROD_PRECO_UNITARIO * m.prod_quantidade) as valor_total_nota,
				c.fantasia,
				m.prod_quantidade
			FROM
				NOTAS_DE_ENTRADAS_M m
					inner join
				clientes c ON (c.codigo = m.cod_fornec)
			".$where." ".$pro." ".$cli."
			group by c.nome , m.data_emissao , vencimento , m.numero_nota , c.fantasia ,CNPJ,m.prod_quantidade
			order by SUBSTRING(c.cnpj_cpf FROM 1 FOR 8) asc";
								
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$dtemis	       = $lf->DATA_EMISSAO;			
			$vencimento	   = $lf->VENCIMENTO;
			$cliente	   = $lf->CLIENTE;
			$numeronota	   = $lf->NUMERO_NOTA;
			$vltotanota	   = $lf->VALOR_TOTAL_NOTA;
			$cnpj	 	   = $lf->CNPJ;
			$prodqtd	   = $lf->PROD_QUANTIDADE;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dtemis);
			$nte->setCliente($cliente);
			$nte->setNumeroNota($numeronota);
			$nte->setValortotalnota($vltotanota);
			$nte->setVencimento($vencimento);
			$nte->setCnpjCliente($cnpj);
			$nte->setProdutoQuantidade($prodqtd);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function BuscaPlaca($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "Select placa from  notas_de_entradas_m
				where placa like '%'||UPPER('$search')||'%'
				group by placa";
								
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$placa = $lf->PLACA;			
		
			
			$nte = new NotasEntradaM();			

			$nte->setPlaca($placa);
			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	public function BuscaPlacaPorData($dtini,$dtfim){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				   m.placa
				FROM
					notas_de_entradas_m m
						INNER JOIN
					clientes c ON (c.codigo = m.cod_fornec)
				where
				   m.data_emissao between '".$dtini."' and '".$dtfim."'
				group by m.placa";
								
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$placa = $lf->PLACA;			
		
			
			$nte = new NotasEntradaM();			

			$nte->setPlaca($placa);
			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	public function RelatorioListagemDeConferenciaPorPlaca($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
					m.numero_nota, c.codigo, c.nome
				FROM
					notas_de_entradas_m m
						INNER JOIN
					clientes c ON (c.codigo = m.cod_fornec)
				".$where."
				group by m.numero_nota , c.codigo , c.nome";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


	
			$numeronota    = $lf->NUMERO_NOTA;
			$codcli		   = $lf->CODIGO;
			$cliente	   = $lf->NOME;

			
			$nte = new NotasEntradaM();			

			$nte->setNumeroNota($numeronota);
			$nte->setCliente($cliente);
			$nte->setCodigo($codcli);
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeConferenciaPorPlacaDetalhe($nnota){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
					p.codigo,
					p.descricao,
					m.prod_quantidade,
					m.prod_preco_unitario,
					(m.prod_preco_unitario * m.prod_quantidade) as subtotal
				FROM
					notas_de_entradas_m m
						INNER JOIN
					produtos p ON (p.codigo = m.prod_codigo)
				where
					m.numero_nota = '".$nnota."'";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


	
			$prodcodigo	       = $lf->CODIGO;			
			$descricao         = $lf->DESCRICAO;	
			$prodqtd		   = $lf->PROD_QUANTIDADE;	
			$prodprec		   = $lf->PROD_PRECO_UNITARIO;
			$subtotal          = $lf->SUBTOTAL;
			
			$nte = new NotasEntradaM();			

			$nte->setCodProduto($prodcodigo);
			$nte->setNomePorduto($descricao);
			$nte->setProdutoValor($prodprec);
			$nte->setProdutoQuantidade($prodqtd);
			$nte->setSubTotal($subtotal);

			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemDeConferenciaPorPlacaProdutos($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
				p.codigo,
				p.descricao,
				sum(m.prod_quantidade) as prod_quantidade,
				sum(m.prod_preco_unitario) as prod_preco_unitario,
				sum((m.prod_preco_unitario * m.prod_quantidade)) as subtotal
			FROM
				notas_de_entradas_m m
					INNER JOIN
				produtos p ON (p.codigo = m.prod_codigo)
				".$where."
			group by   p.codigo,   p.descricao";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


	
			$prodcodigo	       = $lf->CODIGO;			
			$descricao         = $lf->DESCRICAO;	
			$prodqtd		   = $lf->PROD_QUANTIDADE;	
			$prodprec		   = $lf->PROD_PRECO_UNITARIO;
			$subtotal          = $lf->SUBTOTAL;
			
			$nte = new NotasEntradaM();			

			$nte->setCodProduto($prodcodigo);
			$nte->setNomePorduto($descricao);
			$nte->setProdutoValor($prodprec);
			$nte->setProdutoQuantidade($prodqtd);
			$nte->setSubTotal($subtotal);

			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function RelatorioListagemSenar($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "SELECT 
					m.data_emissao,
					f.nome,
					m.valor_total_nota,
					m.valor_desc_aplicado,
					m.numero_nota
				FROM
					notas_de_entradas_m m
						inner join
					fornecedores f ON (f.codigo = m.cod_fornec)
					".$where." AND m.cfop_inteiro = 1101
        and m.valor_desc_aplicado > 0";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


	
			$dtemis		       = $lf->DATA_EMISSAO;			
			$nome		       = $lf->NOME;	
			$vltotnota		   = $lf->VALOR_TOTAL_NOTA;	
			$vltotprod		   = $lf->VALOR_DESC_APLICADO;
			$numnota           = $lf->NUMERO_NOTA;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dtemis);
			$nte->setFornecedor($nome);
			$nte->setValortotalnota($vltotnota);
			$nte->setProdutoValor($vltotprod);
			$nte->setNumeroNota($numnota);

			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	public function VerificaNotas($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "Select * from notas_de_entradas_m m
				inner join fornecedores f on (f.codigo = m.cod_fornec)
				".$where." ";
		
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			


	
			$dtemis		       = $lf->DATA_EMISSAO;			
			$nome		       = $lf->NOME;	
			$vltotnota		   = $lf->VALOR_TOTAL_NOTA;	
			$vltotprod		   = $lf->VALOR_DESC_APLICADO;
			$numnota           = $lf->NUMERO_NOTA;
			
			$nte = new NotasEntradaM();			

			$nte->setDataEmissao($dtemis);
			$nte->setFornecedor($nome);
			$nte->setValortotalnota($vltotnota);
			$nte->setProdutoValor($vltotprod);
			$nte->setNumeroNota($numnota);

			
				
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
	
	public function ProximoId(){

		$dba = $this->dba;

		$vet = array();


		$sql = "Select (max(m.reg) + 1) as PROXIMOID from notas_de_entradas_m m";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$PROXIMOID	= $lf->PROXIMOID;						
			
			$nte = new NotasEntradaM();			

			$nte->setProximoId($PROXIMOID);
			
			$vet[$i++] = $nte;

		}

		return $vet;

	}
	
		
	public function inserirE($nte){
	
		
		$dba = $this->dba;
		
		$codproduto = $nte->getCodProduto();
		$kgs        = $nte->getKg();
		$dtpro      = $nte->getDtProducao();
		$entrada    = $nte->getEntrada();
		$cod        = $nte->getNumeroNota();
		
		$sql ="insert into notas_de_entradas_m 
				(entrada_saida, 
				 numero_nota, 
				 serie_nota, 
				 data_emissao, 
				 cfop, 
				 data_entrada, 
				 cod_fornec,  
				 prod_codigo, 
				 prod_quantidade,
				 NFE_CST,
				 AVISTA_APRAZO,
				 NFE_ORIG)
				 values (
				 '".$entrada."',
				 '".$cod."',
				 'PRO',
				 '".$dtpro."',
				 1101,
				 '".$dtpro."',
				 0,
				 '".$codproduto."',			
				 ".$kgs.",
				 0,
				 0,
				 0)";
		
		
		$res = $dba->query($sql);
			
	}
	
	public function inserirS($ntem){
		
		$codpro       = $ntem->getCodProduto();
		$quantidade   = $ntem->getKg();
		$custo        = $ntem->getPrecoUnitario();
		$dataproducao = $ntem->getDtProducao();
		$cod 		  = $ntem->getNumeroNota();
		$entsai 	  = $ntem->getSaida();

		$dba = $this->dba;
	
		$sql ="insert into notas_de_entradas_m 
				(entrada_saida, 
				 numero_nota, 
				 serie_nota, 
				 data_emissao, 
				 cfop, 
				 data_entrada, 
				 cod_fornec,  
				 prod_codigo, 
				 prod_quantidade, 
				 prod_preco_unitario,
				  NFE_CST,
				 AVISTA_APRAZO,
				 NFE_ORIG)
				 values (
				 	'".$entsai."',
					'".$cod."',
					'PRO',
					'".$dataproducao."',
					null,
					'".$dataproducao."',
					0,
					'".$codpro."',
					".$quantidade.",
					".$custo.",
					0,
					0,
					0)";

		$res = $dba->query($sql);
		
		ibase_close($dba);	
	}
	
	
	
	public function inserir($ntem){
		
		$entsai      	    = $ntem->getEntradaSaida();
		$numero_nota 	    = $ntem->getNumeroNota();
		$serienota	 	    = $ntem->getSerieNota();
		$dataemissao 	    = $ntem->getDataEmissao();
		$cfop		 	    = $ntem->getCfop();		
		$dataentrada 	    = $ntem->getDataEntrada();
		$codfornec		    = $ntem->getCodigoFornecedor();
		$horario	 	    = $ntem->getHorario();
		$valorfrete         = $ntem->getValorFrete();
		$valorseguro        = $ntem->getValorSeguro();
		$outrasdespecas     = $ntem->getOutrosDespecas();
		$valoripi	        = $ntem->getValorIpi();
		$basecalculo	    = $ntem->getBaseCalculo();
		$valoricm		    = $ntem->getValorIcm();
		$basecalculosubs    = $ntem->getBaseCalculoSubs();
		$valoricmsubs	    = $ntem->getValorIcmSubs();
		$valortoralproduto  = $ntem->getValorTotalProdutos();
		$valortotalnota	    = $ntem->getValortotalnota();
		$codtransp		    = $ntem->getCodTransp();
		$frete			    = $ntem->getFrete();
		$placa			    = $ntem->getPlaca();
		$ufplaca		    = $ntem->getUfPlaca();
 		$quantidaderodape   = $ntem->getQuantidadeRodape();
		$especie		    = $ntem->getEspecie();
		$marca			    = $ntem->getMarca();
		$numero			    = $ntem->getNumero();
		$pesobruto		    = $ntem->getPesoBruto();
		$pesoliquido	    = $ntem->getPesoLiquido();
		$valordescontoapli  = $ntem->getValorDescontoAplicado();
		$prodcodigo		    = $ntem->getProdCodigo();
		$prodcodfornec	    = $ntem->getProdCodFornecedor();
		$quantidadeproduto  = $ntem->getProdutoQuantidade();
		$prodprecounitario  = $ntem->getProdPrecoUnitario();
		$prodliquotaicms    = $ntem->getProdLiquotaIcms();	
		$prodvaloricm	    = $ntem->getProdValorIcm();
		$prodaliquotaipi    = $ntem->getProdAliquotaIpi();
		$prodvaloripi	    = $ntem->getProdValorIpi();
		$prodvalordesc	    = $ntem->getProdValorDesc();
		$produnidade	    = $ntem->getProdUnidade();
		$prodsubtotal	    = $ntem->getProdSubTotal();
		$prodicmssubst      = $ntem->getProdIcmsSubst();
		$reg			    = $ntem->getReg();	
		$cfopinteiro	    = $ntem->getCfopInteiro();
		$avistaaprazo	    = $ntem->getAvistaAprazo();
		$prodpecas		    = $ntem->getProdPecas();
		$reboque		    = $ntem->getReboque();	
		$ufreboque		    = $ntem->getUfReboque();
		$nfecst			    = $ntem->getNfeCst();
		$nfeorig		    = $ntem->getNfeOrig();	
		$nfevbc		        = $ntem->getNfeVbc();
		$nfevicms		    = $ntem->getNfeVicms();
		$nfepredbc		    = $ntem->getNfePredBc();
		$nfevbcst		    = $ntem->getNfeVbcst();
		$nfevicmsst		    = $ntem->getNfeVicmsSt();
		$nfenfadfisco	    = $ntem->getNfeNfAdFisco();
		$nfeinfcpl	        = $ntem->getNfeInfCpl(); 
		$tiponota		    = $ntem->getTipoNota();
		$nfegerado		    = $ntem->getNfeGerado();
		$nfealiqicmss	    = $ntem->getNfeAliqIcmss();
		$acrecerpauta	    = $ntem->getAcrescerPauta();
		$predicmssnfe       = $ntem->getPrediCmssNfe();
		$fudesa			    = $ntem->getFundesa();
		$vendedor		    = $ntem->getVendedor();
		$npvendedor		    = $ntem->getNpVendedor();
		$prazo1			    = $ntem->getPrazo1();
		$prazo2			    = $ntem->getPrazo2();
		$prazo3			    = $ntem->getPrazo3();
		$prazo4			    = $ntem->getPrazo4();
		$prazo5			    = $ntem->getPrazo5();	 		
		$condvendas		    = $ntem->getCondVendas();
		$tabelaprecos	    = $ntem->getTabelaPrecos();
		$condpaga		    = $ntem->getCondPaga();
		$regvendedor        = $ntem->getRegVendedor();
		$entrada1 		    = $ntem->getEntrada1();
		$dataentrada1	    = $ntem->getDataEntrada1();
		$baseipi		    = $ntem->getBaseIpi();
		$prodbaseipi	    = $ntem->getProdBaseIpi();	
		$indipialiqunid	    = $ntem->getIndIpiAliqUnid();
		$numerocarga	    = $ntem->getNumeroCarga();
		$pedido			    = $ntem->getPedido();		
		$prodvalorfrete	    = $ntem->getProdValorFrete();
		$prodvalorseguro    = $ntem->getProdValorSeguro();	
		$prodvlroutdespecas = $ntem->getProdVlRoutDespecas();
		$jageroucreceber    = $ntem->getJageRoucReceber(); 
		$codprecoprawer		= $ntem->getCodPrecoPrawer();
		$situacao		    = $ntem->getSituacao();
		$prodcaixas		    = $ntem->getProdCaixas();
		$espessura			= $ntem->getEspessura();
		$largura			= $ntem->getLargura();
		$comprimento		= $ntem->getComprimento();
		$percdec			= $ntem->getPercDesc();
		$nfeinfadprod		= $ntem->getNfeInfAdProd();
		$consulate			= $ntem->getConsulate();
		$pcredsn			= $ntem->getPcredSn();
		$vcredicmssn		= $ntem->getVcredIcmssn();
		$cfopnote			= $ntem->getCfopNota();
		$horaregistro		= $ntem->getHoraRegistro();
		$modbc				= $ntem->getModBc();
		$modbcst			= $ntem->getModBcSt();
		$referencia			= $ntem->getReferencia();
		$manifesto			= $ntem->getManifesto();
		$pedidoliberado		= $ntem->getPedidoLiberado();
		$qtdnfcompra		= $ntem->getQuantidadeNfeCompra();
		$vlrunitnfcompra    = $ntem->getVlrUnitNfeCompra();
		
		
		$dba = $this->dba;
	
		$sql ="  insert into notas_de_entradas_m (entrada_saida, 
                                   numero_nota, 
                                   serie_nota, 
                                   data_emissao, 
                                   cfop, 
                                   data_entrada, 
                                   cod_fornec, 
                                   horario, 
                                   valor_frete, 
                                   valor_seguro, 
                                   outras_despesas, 
                                   valor_ipi, 
                                   base_calculo, 
                                   valor_icm, 
                                   base_calculo_subs, 
                                   valor_icm_subs, 
                                   valor_total_produtos, 
                                   valor_total_nota, 
                                   cod_transp, 
                                   frete, 
                                   placa, 
                                   uf_placa, 
                                   quantidade_rodape, 
                                   especie, 
                                   marca, 
                                   numero, 
                                   peso_bruto, 
                                   peso_liquido, 
                                   valor_desc_aplicado, 
                                   prod_codigo, 
                                   prod_cod_fornec, 
                                   prod_quantidade, 
                                   prod_preco_unitario, 
                                   prod_aliquota_icms, 
                                   prod_valor_icm, 
                                   prod_aliquota_ipi, 
                                   prod_valor_ipi, 
                                   prod_valor_desc, 
                                   prod_unidade, 
                                   prod_sub_total, 
                                   prod_icms_subst, 
                                   reg, 
                                   cfop_inteiro, 
                                   avista_aprazo, 
                                   prod_pecas, 
                                   reboque, 
                                   ufreboque, 
                                   nfe_cst, 
                                   nfe_orig, 
                                   nfe_vbc, 
                                   nfe_vicms, 
                                   nfe_predbc, 
                                   nfe_vbcst, 
                                   nfe_vicmsst, 
                                   nfe_nfadfisco, 
                                   nfe_infcpl, 
                                   tipo_nota, 
                                   nfe_gerado, 
                                   nfe_aliqicmss, 
                                   acrescer_pauta, 
                                   predicmss_nfe, 
                                   fundesa, 
                                   vendedor, 
                                   np_vendedor, 
                                   prazo1, 
                                   prazo2, 
                                   prazo3, 
                                   prazo4, 
                                   prazo5, 
                                   cond_vendas, 
                                   tabela_precos, 
                                   cond_paga, 
                                   reg_vendedor, 
                                   entrada_1, 
                                   data_entrada_1, 
                                   base_ipi, 
                                   prod_baseipi, 
                                   ind_ipi_aliq_unid, 
                                   numerocarga, 
                                   pedido, 
                                   prod_valorfrete, 
                                   prod_valorseguro, 
                                   prod_vlroutdespesas, 
                                   jageroucreceber, 
                                   cod_preco_prawer, 
                                   situacao, 
                                   prod_caixas, 
                                   espessura, 
                                   largura, 
                                   comprimento, 
                                   perc_desc, 
                                   nfe_infadprod, 
                                   consulate, 
                                   pcredsn, 
                                   vcredicmssn, 
                                   cfop_nota, 
                                   hora_registro, 
                                   modbc, 
                                   modbcst, 
                                   referencia, 
                                   manifesto, 
                                   pedidoliberado,
								   QUANTIDADE_NFECOMPRA,
								   VLRUNITNFECOMPRA)
                                  values ('".$entsai."', 
                                          '".substr($numero_nota, 0,9)."', 
                                          '".$serienota."', 
                                          '".$dataemissao."', 
                                          '".$cfop."', 
                                          '".$dataentrada."', 
                                          '".str_pad($codfornec, 5, "0", STR_PAD_LEFT)."', 
                                          '".$horario."', 
                                          ".$valorfrete.", 
                                          ".$valorseguro.", 
                                          ".$outrasdespecas.", 
                                          ".$valoripi.", 
                                          ".$basecalculo.", 
                                          ".$valoricm.", 
                                          ".$basecalculosubs.", 
                                          ".$valoricmsubs.", 
                                          ".$valortoralproduto.", 
                                          ".$valortotalnota.", 
                                          '".$codtransp."', 
                                          '".$frete."', 
                                          '".$placa."', 
                                          '".$ufplaca."', 
                                          ".$quantidaderodape.", 
                                          '".$especie."', 
                                          '".$marca."', 
                                          '".$numero."', 
                                          ".$pesobruto.", 
                                          ".$pesoliquido.", 
                                          ".$valordescontoapli.", 
                                          '".$prodcodigo."', 
                                          '".$prodcodfornec."', 
                                          ".$quantidadeproduto.", 
                                          ".$prodprecounitario.", 
                                          ".$prodliquotaicms.", 
                                          ".$prodvaloricm.", 
                                          ".$prodaliquotaipi.", 
                                          ".$prodvaloripi.", 
                                          ".$prodvalordesc.", 
                                          '".$produnidade."', 
                                          ".$prodsubtotal.", 
                                          ".$prodicmssubst.", 
                                          ".$reg.", 
                                          ".$cfopinteiro.", 
                                          '".$avistaaprazo."', 
                                          ".$prodpecas.", 
                                          '".$reboque."', 
                                          '".$ufreboque."', 
                                          '".$nfecst."', 
                                          '".$nfeorig."', 
                                          ".$nfevbc.", 
                                          ".$nfevicms.", 
                                          ".$nfepredbc.", 
                                          ".$nfevbcst.", 
                                          ".$nfevicmsst.", 
                                          '".$nfenfadfisco."', 
                                          '".$nfeinfcpl."', 
                                          '".$tiponota."', 
                                          '".$nfegerado."', 
                                          ".$nfealiqicmss.", 
                                          '".$acrecerpauta."', 
                                          ".$predicmssnfe.", 
                                          ".$fudesa.", 
                                          '".$vendedor."', 
                                          '".$npvendedor."', 
                                          ".$prazo1.", 
                                          ".$prazo2.", 
                                          ".$prazo3.", 
                                          ".$prazo4.", 
                                          ".$prazo5.", 
                                          '".$condvendas."', 
                                          '".$tabelaprecos."', 
                                          '".$condpaga."', 
                                          ".$regvendedor.", 
                                          ".$entrada1.", 
                                          ".$dataentrada1.", 
                                          ".$baseipi.", 
                                          ".$prodbaseipi.", 
                                          '".$indipialiqunid."', 
                                          '".$numerocarga."', 
                                          '".$pedido."', 
                                          ".$prodvalorfrete.", 
                                          ".$prodvalorseguro.", 
                                          ".$prodvlroutdespecas.", 
                                          '".$jageroucreceber."', 
                                          '".$codprecoprawer."', 
                                          '".$situacao."', 
                                          ".$prodcaixas.", 
                                          ".$espessura.", 
                                          ".$largura.", 
                                          ".$comprimento.", 
                                          ".$percdec.", 
                                          '".$nfeinfadprod."', 
                                          ".$consulate.", 
                                          ".$pcredsn.", 
                                          ".$vcredicmssn.", 
                                          '".$cfopnote."', 
                                          '".$horaregistro."', 
                                          ".$modbc.", 
                                          ".$modbcst.", 
                                          '".$referencia."', 
                                          '".$manifesto."', 
                                          '".$pedidoliberado."',
										   ".$qtdnfcompra.", 
										   ".$vlrunitnfcompra.")";
		
		//echo $sql.'<br/>';
		$res = $dba->query($sql);
		
		//ibase_close($dba);	
	}
	

}

?>
<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class ApropriacaoMovimentacaoDAO{

	

	private $dba;

	

	public function ApropriacaoMovimentacaoDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	public function RelatorioApropriacaoMovimentacao($where){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT 
					m.data_emissao,
					a.nota || ' - ' || fo.nome as historico,
					case
						when m.entrada_saida = 'E' then a.valor
					end as entrada,
					case
						when m.entrada_saida = 'S' then a.valor
					end as Saida,
					f.cod_con_clas
				FROM
					apropriacao_movimentacao a
						INNER JOIN
					notas_de_entradas_m m ON (m.numero_nota = a.nota)
						INNER JOIN
					financ_contas f ON (f.codigo = a.conta)
						INNER JOIN
					fornecedores fo ON (fo.codigo = a.fornecedor)
					".$where." order by m.data_emissao";
		//echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($am = ibase_fetch_object($res)){		
			
			$dtemiss          = $am->DATA_EMISSAO;
			$historico        = $am->HISTORICO;
			$debito 		  = $am->SAIDA;
			$credito		  = $am->ENTRADA;	
			$codconclas		  = $am->COD_CON_CLAS;
			
			$ram = new ApropriacaoMovimentacao();			

			$ram->setDataEmissao($dtemiss);
			$ram->setHistorico($historico);
			$ram->setDebito($debito);
			$ram->setCredito($credito);
			$ram->setCodConClass($codconclas);
						
			$vet[$i++] = $ram;

		}

		return $vet;

	}
public function BuscaFinancContas($search){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT * FROM FINANC_CONTAS f
			 where f.descricao like '%'||UPPER('$search')||'%' or f.codigo = '$search'";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->CODIGO;
			$descricao       = $fc->DESCRICAO;
			
			$fct = new FinancContas();			

			$fct->setCodigo($codigo);
			$fct->setDescricao(utf8_decode($descricao));
			
			$vet[$i++] = $fct;

		}

		return $vet;

	}
	
	public function ProximoId(){

		$dba = $this->dba;

		$vet = array();


		$sql = "select distinct(gen_id(gen_apropriacao_movimentacao_id,0)) as PROXIMOID from apropriacao_movimentacao";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($lf = ibase_fetch_object($res)){		
			
			$PROXIMOID	= $lf->PROXIMOID;						
			
			$apro = new ApropriacaoMovimentacao();			

			$apro->setProximoId($PROXIMOID);
			
			$vet[$i++] = $apro;

		}

		return $vet;

	}	
	
	
	public function inserir($apro){

		

		$dba = $this->dba;
			
		$centro  = $apro->getCentro();	
		$conta   = $apro->getConta();			
		$valor   = $apro->getValor();
		$nota    = $apro->getNota();
		$cfor    = $apro->getFornecedor();
		$empresa = $apro->getEmpresa();
		$emissao = $apro->getDataEmissao();
		
		$sql = "insert into apropriacao_movimentacao (
									centro,
                                    conta, 
                                    valor, 
                                    nota, 
                                    fornecedor, 
                                    empresa, 
                                    emissao)
                                    values 
									(".$centro.",
                                    '".$conta."', 
                                    '".$valor."', 
                                    '".$nota."', 
                                    '".$cfor."', 
                                    ".$empresa.", 
                                    '".$emissao."')";

		
		//echo $sql; 
		$dba->query($sql);

	

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

	
		$sql = 'delete from apropriacao_movimentacao
                               where id = '.$idc.'';
	

		$dba->query($sql);

		

	}

	

}

?>
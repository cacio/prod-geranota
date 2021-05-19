<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class MovimProdDAO{



	

	private $dba;



	public function MovimProdDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}

	

	public function ListaMovimentacaoProducao(){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select 
				m.id,
				m.lote,
				m.dt_prod,
				case
					when m.local = 0 then 'Ceasa'
					when m.local = 1 then 'Chuletão'
					when m.local = 2 then 'SP'
				end AS LOCAL,
				p.descricao,
				m.qtd
			from
				movim_prod m
					inner join
				produtos p ON (p.codigo = m.id_prod)
			where
				m.ent_sai = 'E'";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($mov = ibase_fetch_object($res)){
					
			
			$id     = $mov->ID;
			$dtprod = $mov->DT_PROD;
			$local  = $mov->LOCAL;
			$desc   = $mov->DESCRICAO;
			$qtd    = $mov->QTD; 
 			$lote   = $mov->LOTE; 
			
			$mvp = new MovimProd();

			$mvp->setCodigo($id);
			$mvp->setDtProd($dtprod);
			$mvp->setLocal($local);
			$mvp->setDescricao($desc);
			$mvp->setQtd($qtd);
			$mvp->setLote($lote);
			

			$vet[$i++] = $mvp;

		

		}

		return $vet;

	}
		
		public function ListaMovimentacaoProducaoUm($id){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select 
				m.id,
				m.lote,
				m.dt_prod,
				m.id_prod,
				case
					when m.local = 0 then 'Ceasa'
					when m.local = 1 then 'Chuletão'
					when m.local = 2 then 'SP'
				end AS LOCAL,
				p.descricao,
				m.qtd
			from
				movim_prod m
					inner join
				produtos p ON (p.codigo = m.id_prod)
			where
				m.ent_sai = 'E' and m.lote = '".$id."' ";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($mov = ibase_fetch_object($res)){
					
			
			$id     = $mov->ID;
			$dtprod = $mov->DT_PROD;
			$local  = $mov->LOCAL;
			$desc   = $mov->DESCRICAO;
			$qtd    = $mov->QTD; 
 			$lote   = $mov->LOTE; 
			$codpro = $mov->ID_PROD;
			
			$mvp = new MovimProd();

			$mvp->setCodigo($id);
			$mvp->setDtProd($dtprod);
			$mvp->setLocal($local);
			$mvp->setDescricao($desc);
			$mvp->setQtd($qtd);
			$mvp->setLote($lote);
			$mvp->setIdProd($codpro);

			$vet[$i++] = $mvp;

		

		}

		return $vet;

	}	
		
		public function ListaMovimentacaoProducaoGrupo($id){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select 
					p.grupo
				from
					movim_prod m
						inner join
					produtos p ON (p.codigo = m.id_prod)
				where
					m.ent_sai = 'S' and m.lote = '".$id."'  group by  p.grupo";
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($mov = ibase_fetch_object($res)){
					
			
			$id     = $mov->GRUPO;
			
			
			$mvp = new MovimProd();

			$mvp->setGrupo($id);
			
		
			$vet[$i++] = $mvp;

		

		}

		return $vet;

	}	
	
	public function ListaMovimentacaoProducaoPorGrupo($lote,$grupo){

		

		$dba = $this->dba;

		$vet = array();


		$sql = " select 
					m.id,
					m.lote, 
					m.dt_prod,
					m.id_prod,
					case
						when m.local = 0 then 'Ceasa'
						when m.local = 1 then 'Chuletão'
						when m.local = 2 then 'SP'
					end,
					p.descricao,
					m.qtd,
					p.grupo,
					p.preco_venda
				from
					movim_prod m
						inner join
					produtos p ON (p.codigo = m.id_prod)
				where
					m.ent_sai = 'S' and m.lote = '".$lote."' and p.grupo = ".$grupo." ";
						
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($mov = ibase_fetch_object($res)){
					
			
			$id      = $mov->ID;
			$dtprod  = $mov->DT_PROD;
			//$local   = $mov->LOCAL;
			$desc    = $mov->DESCRICAO;
			$qtd     = $mov->QTD; 
 			$lote    = $mov->LOTE; 
			$idgrupo = $mov->GRUPO;
			$precv   = $mov->PRECO_VENDA;
			$codpro  = $mov->ID_PROD;
			
			$mvp = new MovimProd();

			$mvp->setCodigo($id);
			$mvp->setDtProd($dtprod);
			//$mvp->setLocal($local);
			$mvp->setDescricao($desc);
			$mvp->setQtd($qtd);
			$mvp->setLote($lote);
			$mvp->setGrupo($idgrupo);
			$mvp->setCusto($precv);
			$mvp->setIdProd($codpro);
			
			$vet[$i++] = $mvp;

		

		}

		return $vet;

	}
	
		public function ListaMovimentacaoParaDeletar($lote){

		

		$dba = $this->dba;

		$vet = array();


		$sql = "select m.id,m.lote from movim_prod m
				where m.lote = '".$lote."'";
						
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($mov = ibase_fetch_object($res)){
					
			
			$id      = $mov->ID;
 			$lote    = $mov->LOTE; 
			
			
			$mvp = new MovimProd();

			$mvp->setCodigo($id);
			$mvp->setLote($lote);
	
			
			$vet[$i++] = $mvp;

		

		}

		return $vet;

	}
	
		
	public function inserir($mov){
	
		$dba = $this->dba;
		
		$codpro     = $mov->getIdProd();
		$kgs        = $mov->getQtd();
		$dtpro      = $mov->getDtProd();
		$entsai     = $mov->getEntSai();
		$lote       = $mov->getLote();
		$local      = $mov->getLocal();
		$custo      = $mov->getCusto();
		
		$sql = "insert into movim_prod 
				( lote, 
				 dt_prod, 
				 id_prod, 
				 local, 
				 qtd, 
				 custo, 
				 ent_sai)
			    values 
				( '".$lote."', 
				 '".$dtpro."', 
				 '".$codpro."', 
				 '".$local."', 
				 ".$kgs.", 
				 ".$custo.", 
				 '".$entsai."')";
		echo $sql;
		$res = $dba->query($sql);
		
		ibase_close($dba);	
	}
	
	public function alterar($mov){
	
		$dba = $this->dba;
		
		$codpro     = $mov->getIdProd();
		$kgs        = $mov->getQtd();
		$dtpro      = $mov->getDtProd();
		$entsai     = $mov->getEntSai();
		//$lote       = $mov->getLote();
		$local      = $mov->getLocal();
		$custo      = $mov->getCusto();
		$code		= $mov->getCodigo();
		// lote = '".$lote."',
		$sql = "update movim_prod
				 set dt_prod = '".$dtpro."',
					 id_prod = '".$codpro."',
					 local = '".$local."',
					 qtd = ".$kgs.",
					 custo = ".$custo.",
					 ent_sai = '".$entsai."'
				 where (id = ".$code.")";
		//echo $sql.'<br/>';
		$res = $dba->query($sql);
		
		ibase_close($dba);	
	}
	
	public function deletar($mov){
		$dba = $this->dba;
		
		$code		= $mov->getCodigo();
		$lote       = $mov->getLote();
		
		$sql = " delete from movim_prod m
				 where m.lote = '".$lote."' and (id = ".$code.") ";
		
		//echo $sql.'<br/>'; 
		
		$res = $dba->query($sql);
		
		ibase_close($dba);	
		
	}
	
	
}

?>
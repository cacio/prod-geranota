<?php

require_once('inc.autoload.php');

require_once('inc.connectfirebird.php');

class FormulacaoDAO{



	

	private $dba;



	public function FormulacaoDAO(){

		

		$dba = new DbAdmin('firebird');

		$dba->connect(HOSTS,USERS,SENHAS,BDS);

		$this->dba = $dba;

	

	}
	
	public function ListaFormulacao(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ='select 
					f.cod_produto, p.descricao
				from
					formulacao_ind f
						inner join
					produtos p ON (p.codigo = f.cod_produto)
				group by f.cod_produto , p.descricao';

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($form = ibase_fetch_object($res)){

			

			$cod_prod    = $form->COD_PRODUTO;
			$descricao   = $form->DESCRICAO;	
			
			$formula = new Formulacao();
			
			$formula->setCodProduto($cod_prod);
			$formula->setNomeProduto($descricao);
		
			

			$vet[$i++] = $formula;

		

		}

		return $vet;

	}
	
	public function ListaFormulaUm($cod_prod){

		

		$dba = $this->dba;

		$vet = array();


		$sql ='select 
				id,
				cod_produto,
				cod_materia_prima,
				kg,
				participacao,
				custo_unit
			from
				formulacao_ind
				 inner join produtos on (produtos.codigo = formulacao_ind.cod_materia_prima)
				where
				cod_produto = '.$cod_prod.' order by produtos.grupo';

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($form = ibase_fetch_object($res)){

			
			$codigo      = $form->ID;
			$cod_prod    = $form->COD_PRODUTO;
			$cod_materia = $form->COD_MATERIA_PRIMA;
			$kg          = $form->KG;
			$participa   = $form->PARTICIPACAO;
			$custo_unit  = $form->CUSTO_UNIT;
			
			$formula = new Formulacao();
			
			$formula->setCodigo($codigo);	
			$formula->setCodProduto($cod_prod);
			$formula->setQuantidade($kg);
			$formula->setPrecoUnitario($custo_unit);
			$formula->setMateriaPrima($cod_materia);
			$formula->setParticipacao($participa);
		
			

			$vet[$i++] = $formula;

		

		}

		return $vet;

	}	
public function ListaFormulaPorMateriaPrima($cod_materia,$codproduto){

		

		$dba = $this->dba;

		$vet = array();


		$sql ='select 
				id,
				cod_produto,
				cod_materia_prima,
				kg,
				participacao,
				custo_unit
			from
				formulacao_ind
			where
				cod_produto = '.$codproduto.'
					and cod_materia_prima = '.$cod_materia.'';

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($form = ibase_fetch_object($res)){

			
			$codigo      = $form->ID;
			$cod_prod    = $form->COD_PRODUTO;
			$cod_materia = $form->COD_MATERIA_PRIMA;
			$kg          = $form->KG;
			$participa   = $form->PARTICIPACAO;
			$custo_unit  = $form->CUSTO_UNIT;
			
			$formula = new Formulacao();
			
			$formula->setCodigo($codigo);	
			$formula->setCodProduto($cod_prod);
			$formula->setQuantidade($kg);
			$formula->setPrecoUnitario($custo_unit);
			$formula->setMateriaPrima($cod_materia);
			$formula->setParticipacao($participa);
		
			

			$vet[$i++] = $formula;

		

		}

		return $vet;

	}	
	public function inserir($flc){
		

		$dba = $this->dba;
				
		$codproduto   = $flc->getCodProduto();
		$kg           = $flc->getQuantidade();
		$cunit        = $flc->getPrecoUnitario();
		$codmat       = $flc->getMateriaPrima();
		$participacao = $flc->getParticipacao();
		
	
		$sql = " insert into formulacao_ind 
				(cod_produto, 
				 cod_materia_prima, 
				 kg, participacao, 
				 custo_unit) 
				 values 
				 ('".$codproduto."', 
				  '".$codmat."', 
				  ".$kg.", 
				  ".$participacao.", 
				  ".$cunit.")";

		$res = $dba->query($sql);
			
	}

	public function excluir($flc){
		
		$dba = $this->dba;
		
		$codigo = $flc->getCodigo();
		
		$sql = "delete from formulacao_ind
                where id = ".$codigo." ";
			
		$res = $dba->query($sql);
	}

}

?>
<?php

require_once('inc.autoload.php');
require_once('inc.connectfirebird.php');

class MovimentacaoDAO{

	private $dba;	

	public function __construct(){

		$dba = new DbAdmin('firebird');
		$dba->connect(HOSTS,USERS,SENHAS,BDS);
		$this->dba = $dba;

	}

	public function ListaFinancContas(){

		

		$dba = $this->dba;

		$vet = array();


		$sql ="select
					m.id,
					m.mov_data,
					(iif(f.reduzido is null, 0,f.reduzido)||' - '|| f.descricao) as mov_idconta_c,
					(iif(ff.reduzido is null, 0,ff.reduzido)||' - '||ff.descricao) as mov_idconta_d,
					m.mov_historico,
					m.mov_valor,
					m.mov_idhistorico,
					h.descricao
				from tb_movim m
				inner join financ_contas f on (f.codigo = m.mov_idconta_c)
				inner join financ_contas ff on (ff.codigo = m.mov_idconta_d)
				inner join historicopadrao h on (h.codigo = trim(m.mov_idhistorico))
				";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->ID;
			$data            = $fc->MOV_DATA;
			$mov_idconta_c   = $fc->MOV_IDCONTA_C;
			$mov_idconta_d   = $fc->MOV_IDCONTA_D;	
			$mov_historico   = $fc->MOV_HISTORICO;
			$mov_idhistorico = $fc->MOV_IDHISTORICO;
			$mov_valor		 = $fc->MOV_VALOR;
			$deschistoricop  = $fc->DESCRICAO;

			$mov = new Movimentacao();			

			$mov->setCodigo($codigo);
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
			$mov->setDescricao($deschistoricop);
			
			$vet[$i++] = $mov;

		}

		return $vet;

	}

	public function ListaFinancContasUm($id){

		$dba = $this->dba;

		$vet = array();


		$sql ="select
				ID,
				MOV_DATA,
				MOV_IDCONTA_C,
				MOV_IDCONTA_D,
				MOV_HISTORICO,
				MOV_IDHISTORICO,
				MOV_VALOR
				from tb_movim m  where m.id = {$id}";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$codigo          = $fc->ID;
			$data            = $fc->MOV_DATA;
			$mov_idconta_c   = $fc->MOV_IDCONTA_C;
			$mov_idconta_d   = $fc->MOV_IDCONTA_D;	
			$mov_historico   = $fc->MOV_HISTORICO;
			$mov_idhistorico = $fc->MOV_IDHISTORICO;
			$mov_valor		 = $fc->MOV_VALOR;
			
			$mov = new Movimentacao();			

			$mov->setCodigo($codigo);
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
						
			$vet[$i++] = $mov;

		}

		return $vet;

	}


	public function DadosArquivoDominio($dtini,$dtfim){

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				DATA,
				CAST(VALORDOC AS DECIMAL(10,2)) AS VALORDOC,
				CONTADEBIDO,
				CONTACREDITO,
				HISTORICO,
				USUARIO,
				CODIGOFM,
				CODHISTORICO
				FROM (
					SELECT
						P.DATA AS DATA,
						p.valor as VALORDOC,
						FFC.REDUZIDO AS CONTADEBIDO,
						FC.REDUZIDO AS CONTACREDITO,
						'PAGTO REF. '||DP.NUMERO||' DE '||F.NOME AS HISTORICO,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						'19' AS CODHISTORICO
					FROM DUPLIC DP
					INNER JOIN FORNECEDORES F ON (F.CODIGO = DP.CEDENTE)
					INNER JOIN PAGAMENTOS P ON (P.DOCUMENTO = DP.NUMERO) AND (P.CEDENTE = DP.CEDENTE)
					INNER JOIN FORMAS_PAGREC FG ON (FG.DESCRICAO = P.FORMA)
					INNER JOIN FINANC_CONTAS FC ON (FC.CODIGO = FG.ID_CONTA)
					INNER JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = F.CONTA_CTB)
						UNION ALL
						SELECT
                         A.emissao AS DATA,
                         A.VALOR,
                         N.REDUZIDO AS CONTADEBIDO,
                         FFC.REDUZIDO AS CONTACREDITO,
                         'REF. NF '||A.NOTA||' DE '||F.NOME AS HISTORICO,
                         'GERENTE' AS USUARIO,
                         '505' AS CODIGOFM,
                         H.codigo AS CODHISTORICO
                     FROM APROPRIACAO_MOVIMENTACAO A
                     INNER JOIN FORNECEDORES F ON (F.CODIGO = A.FORNECEDOR)
                     INNER JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = F.CONTA_CTB)
                     INNER JOIN FINANC_CONTAS N ON (N.CODIGO = A.CONTA)
                     INNER JOIN HISTORICOPADRAO H ON (H.id = N.centro_custo)/*USADO o campo centro custo no financ contas pra coloca o codigo DO HISTORICO padrão*/
					 UNION ALL
					SELECT
						D.DATAPAG AS DATA,
						D.VALORDOC,
						FC.REDUZIDO AS CONTADEBIDO,
						FFC.REDUZIDO AS CONTACREDITO,
						'REF. '||D.NUMERO||' DE '||C.NOME AS HISTORICO,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						'101' AS CODHISTORIC
					FROM DUPLIC_RECEBER D
					INNER JOIN CLIENTES C ON (C.CODIGO =D.CEDENTE)
					INNER JOIN RECEBIMENTOS RC ON (RC.DOCUMENTO = D.NUMERO) AND (RC.CEDENTE = C.CODIGO)
					INNER JOIN FORMAS_PAGREC FG ON (FG.DESCRICAO = RC.FORMA)
					INNER JOIN FINANC_CONTAS FC ON (FC.CODIGO = FG.ID_CONTA)
					INNER JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = C.CONTA_CTB)
						UNION ALL
					
					
					
					SELECT
						M.MOV_DATA AS DATA,
						M.MOV_VALOR,
						IIF(F.REDUZIDO IS NULL, 0,F.REDUZIDO) AS CONTADEBIDO,
						IIF(FF.REDUZIDO IS NULL, 0,FF.REDUZIDO) AS CONTACREDITO,
						'DESPESA '||(SELECT UPPER(RETORNO) FROM TIRA_ACENTOS(H.DESCRICAO))||' '||(SELECT UPPER(RETORNO) FROM TIRA_ACENTOS(M.MOV_HISTORICO)) AS HISTORICO,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						M.MOV_IDHISTORICO AS CODHISTORICO
					FROM TB_MOVIM M
					INNER JOIN FINANC_CONTAS F ON (F.CODIGO = M.MOV_IDCONTA_C)
					INNER JOIN FINANC_CONTAS FF ON (FF.CODIGO = M.MOV_IDCONTA_D)
					INNER JOIN HISTORICOPADRAO H ON (H.CODIGO = trim(M.MOV_IDHISTORICO))
			
			) WHERE  DATA between '{$dtini}' AND '{$dtfim}' ORDER BY DATA ASC ";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$data            = $fc->DATA;
			$mov_idconta_c   = $fc->CONTACREDITO;
			$mov_idconta_d   = $fc->CONTADEBIDO;	
			$mov_historico   = $fc->HISTORICO;
			$mov_idhistorico = $fc->CODHISTORICO;
			$mov_valor		 = $fc->VALORDOC;
			$idusuario		 = $fc->USUARIO;
			$CODIGOFM		 = $fc->CODIGOFM;
			
			$mov = new Movimentacao();			
			
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
			$mov->setIdUsuario($idusuario);
			$mov->setCodigoFM($CODIGOFM);
						
			$vet[$i++] = $mov;

		}

		return $vet;

	}

	public function DadosArquivoDominioRel($dtini,$dtfim){

		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				DATA,
				VALORDOC,
				CONTADEBIDO,
				CONTACREDITO,
				HISTORICO,
				USUARIO,
				CODIGOFM,
				CODHISTORICO
				FROM (
					SELECT
						P.DATA as DATA,
						p.valor as VALORDOC,
						FFC.REDUZIDO||' - '||ffc.descricao AS CONTADEBIDO,
						FC.REDUZIDO||' - '||fc.descricao AS CONTACREDITO,
						'PAGTO REF. '||DP.NUMERO||' DE '||F.NOME AS HISTORICO,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						'19' AS CODHISTORICO
					FROM DUPLIC DP
					INNER JOIN FORNECEDORES F ON (F.CODIGO = DP.CEDENTE)
					INNER JOIN PAGAMENTOS P ON (P.DOCUMENTO = DP.NUMERO) AND (P.CEDENTE = DP.CEDENTE)
					INNER JOIN FORMAS_PAGREC FG ON (FG.DESCRICAO = P.FORMA)
					INNER JOIN FINANC_CONTAS FC ON (FC.CODIGO = FG.ID_CONTA)
					INNER JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = F.CONTA_CTB)
						UNION ALL
						SELECT
							A.EMISSAO AS DATA,
							A.VALOR,
							N.REDUZIDO||' - '||N.DESCRICAO AS CONTADEBIDO,
							FFC.REDUZIDO||' - '||FFC.DESCRICAO AS CONTACREDITO,
							'REF. NF '||A.NOTA||' DE '||F.NOME AS HISTORICO,
							'GERENTE' AS USUARIO,
							'505' AS CODIGOFM,
							H.CODIGO AS CODHISTORICO
						FROM APROPRIACAO_MOVIMENTACAO A
						INNER JOIN FORNECEDORES F ON (F.CODIGO = A.FORNECEDOR)
						INNER JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = F.CONTA_CTB)
						INNER JOIN FINANC_CONTAS N ON (N.CODIGO = A.CONTA)
						INNER JOIN HISTORICOPADRAO H ON (H.id = N.centro_custo)/*USADO o campo centro custo no financ contas pra coloca o codigo DO HISTORICO padrão*/
						UNION ALL
					SELECT
						D.DATAPAG as DATA,
						D.VALORDOC,
						FC.REDUZIDO||' - '||fc.descricao AS CONTADEBIDO,
						FFC.REDUZIDO||' - '||ffc.descricao AS CONTACREDITO,
						'REF. '||D.NUMERO||' DE '||C.NOME AS HISTORICO,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						'101' AS CODHISTORIC
					FROM DUPLIC_RECEBER D	
					INNER JOIN CLIENTES C ON (C.CODIGO =D.CEDENTE)
					INNER JOIN RECEBIMENTOS RC ON (RC.DOCUMENTO = D.NUMERO) AND (RC.CEDENTE = C.CODIGO)
					INNER JOIN FORMAS_PAGREC FG ON (FG.DESCRICAO = RC.FORMA)
					INNER JOIN FINANC_CONTAS FC ON (FC.CODIGO = FG.ID_CONTA)
					INNER JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = C.CONTA_CTB)
						UNION ALL
					
					
					
					SELECT
						M.MOV_DATA as DATA,
						M.MOV_VALOR,
						IIF(F.REDUZIDO IS NULL, 0,F.REDUZIDO)||' - '||f.descricao AS CONTADEBIDO,
						IIF(FF.REDUZIDO IS NULL, 0,FF.REDUZIDO)||' - '||ff.descricao AS CONTACREDITO,
						'DESPESA '||(SELECT UPPER(RETORNO) FROM TIRA_ACENTOS(H.DESCRICAO))||' '||(SELECT UPPER(RETORNO) FROM TIRA_ACENTOS(M.MOV_HISTORICO)) AS HISTORICO,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						M.MOV_IDHISTORICO AS CODHISTORICO
					FROM TB_MOVIM M
					INNER JOIN FINANC_CONTAS F ON (F.CODIGO = M.MOV_IDCONTA_C)
					INNER JOIN FINANC_CONTAS FF ON (FF.CODIGO = M.MOV_IDCONTA_D)
					INNER JOIN HISTORICOPADRAO H ON (H.CODIGO = trim(M.MOV_IDHISTORICO))
			
			) WHERE  DATA between '{$dtini}' AND '{$dtfim}' ORDER BY DATA ASC ";
		//echo "{$sql}";
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
			
			$data            = $fc->DATA;
			$mov_idconta_c   = $fc->CONTACREDITO;
			$mov_idconta_d   = $fc->CONTADEBIDO;	
			$mov_historico   = $fc->HISTORICO;
			$mov_idhistorico = $fc->CODHISTORICO;
			$mov_valor		 = $fc->VALORDOC;
			$idusuario		 = $fc->USUARIO;
			$CODIGOFM		 = $fc->CODIGOFM;
			
			$mov = new Movimentacao();			
			
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
			$mov->setIdUsuario($idusuario);
			$mov->setCodigoFM($CODIGOFM);
						
			$vet[$i++] = $mov;

		}

		return $vet;

	}

	public function InconcistenciaPagamentos($dtini,$dtfim){
		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
				DATA,
				CAST(VALORDOC AS DECIMAL(10,2)) AS VALORDOC,
				iif(CONTADEBIDO IS NULL,'SEM VÍNCULO DE CONTA NO FORNECEDOR: '||''||NOME,CONTADEBIDO) as CONTADEBIDO,
				iif(CONTACREDITO is null, 'SEM VÍNCULO DE CONTA PARA FORMA DE PAGAMENTO ('||FORMA||')  NA DUPLICATA: '||''||NUMERO,'') as CONTACREDITO,
				HISTORICO,
				USUARIO,
				CODIGOFM,
				CODHISTORICO,
				FORMA
				FROM (
					SELECT
						DP.NUMERO,
						P.DATA AS DATA,
						p.valor as VALORDOC,
						FFC.REDUZIDO  AS CONTADEBIDO,
						FC.REDUZIDO  AS CONTACREDITO,
						'PAGTO REF. BOLETO'||DP.NUMERO||' DE '||F.NOME AS HISTORICO,
						F.NOME,
						'GERENTE' AS USUARIO,
						'505' AS CODIGOFM,
						'19' AS CODHISTORICO ,
						P.FORMA
					FROM DUPLIC DP
					inner JOIN FORNECEDORES F ON (F.CODIGO = DP.CEDENTE)
					inner JOIN PAGAMENTOS P ON (P.DOCUMENTO = DP.NUMERO) AND (P.CEDENTE = DP.CEDENTE)
					left JOIN FORMAS_PAGREC FG ON (FG.DESCRICAO = P.FORMA)
					left JOIN FINANC_CONTAS FC ON (FC.CODIGO = FG.ID_CONTA)
					left JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = F.CONTA_CTB)

			) WHERE  DATA between '{$dtini}' AND '{$dtfim}' and (CONTADEBIDO is null or CONTACREDITO is null or  CODHISTORICO is null  )";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
				

			$data            = $fc->DATA;
			$mov_idconta_c   = $fc->CONTACREDITO;
			$mov_idconta_d   = $fc->CONTADEBIDO;	
			$mov_historico   = $fc->HISTORICO;
			$mov_idhistorico = $fc->CODHISTORICO;
			$mov_valor		 = $fc->VALORDOC;
			$idusuario		 = $fc->USUARIO;
			$CODIGOFM		 = $fc->CODIGOFM;
			
			$mov = new Movimentacao();			
			
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
			$mov->setIdUsuario($idusuario);
			$mov->setCodigoFM($CODIGOFM);
						
			$vet[$i++] = $mov;

		}

		return $vet;
	}

	public function InconcistenciaProvisionamento($dtini,$dtfim){
		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
			DATA,
			CAST(VALORDOC AS DECIMAL(10,2)) AS VALORDOC,
			iif(CONTADEBIDO is null , 'SEM VÍNCULO DE CONTA NA APROPRIAÇÃO DA NOTA '||' '||NUMERO_NOTA,CONTADEBIDO) as CONTADEBIDO,
			iif(CONTACREDITO is null,'SEM VÍNCULO DE CONTA PARA O FORNECEDOR: '||''||NOME,CONTACREDITO) AS CONTACREDITO,
			HISTORICO,
			USUARIO,
			CODIGOFM,
			iif(CODHISTORICO is null, 'SEM VÍNCULO DE HISTORICO PADRÃO PARA CONTA: '||''||CONTADEBIDO||' - '||DESCRICAO,CODHISTORICO) AS CODHISTORICO,
			iif(id is null,'FALTA APROPRIAR A NOTA '||' '||NUMERO_NOTA, id) AS id ,
			DESCRICAO
			FROM (
		
					SELECT
					DP.NUMERO_NOTA,
					DP.EMISSAO AS DATA,
					DP.VALORDOC,
					N.REDUZIDO AS CONTADEBIDO,
					A.id ,
					F.NOME,
					FFC.REDUZIDO AS CONTACREDITO,
					'REF. NF '||DP.NUMERO||' DE '||F.NOME AS HISTORICO,
					'GERENTE' AS USUARIO,
					'505' AS CODIGOFM,
					h.codigo AS CODHISTORICO,
					N.DESCRICAO
				FROM DUPLIC DP
				inner JOIN APROPRIACAO_MOVIMENTACAO A ON ((A.NOTA = DP.NUMERO_NOTA) AND (A.FORNECEDOR = DP.CEDENTE))
				inner JOIN FORNECEDORES F ON (F.CODIGO = DP.CEDENTE)
				left JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = F.CONTA_CTB)
				LEFT JOIN FINANC_CONTAS N ON (N.CODIGO = A.CONTA)
				left JOIN HISTORICOPADRAO H ON (H.id = trim(N.centro_custo))
		) WHERE  DATA between '{$dtini}' AND '{$dtfim}' and (CONTADEBIDO is null or CONTACREDITO is null or  CODHISTORICO is null)";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
						

			$data            = $fc->DATA;
			$mov_idconta_c   = $fc->CONTACREDITO;
			$mov_idconta_d   = $fc->CONTADEBIDO;	
			$mov_historico   = $fc->HISTORICO;
			$mov_idhistorico = $fc->CODHISTORICO;
			$mov_valor		 = $fc->VALORDOC;
			$idusuario		 = $fc->USUARIO;
			$CODIGOFM		 = $fc->CODIGOFM;
			
			$mov = new Movimentacao();			
			
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
			$mov->setIdUsuario($idusuario);
			$mov->setCodigoFM($CODIGOFM);
						
			$vet[$i++] = $mov;

		}

		return $vet;
	}
	
	public function InconcistenciaRecebimentos($dtini,$dtfim){
		$dba = $this->dba;

		$vet = array();


		$sql ="SELECT
        DATA,
        CAST(VALORDOC AS DECIMAL(10,2)) AS VALORDOC,
        IIF(CONTADEBIDO IS NULL,'SEM VÍNCULO DE CONTA PARA FORMA DE RECEBIMENTO ('||id||') NA DUPLICATA:'||' '||NUMERO,CONTADEBIDO) AS CONTADEBIDO,
        iif(CONTACREDITO is NULL,'SEM VÍNCULO DE CONTA NO CLIENTE:'||' '||NOME,CONTACREDITO) AS CONTACREDITO,
        HISTORICO,
        USUARIO,
        CODIGOFM,
        CODHISTORICO ,
        id
        FROM (
            SELECT
                D.numero,
                D.DATAPAG AS DATA,
                D.VALORDOC,
                FC.REDUZIDO AS CONTADEBIDO,
                FFC.REDUZIDO AS CONTACREDITO,
                'REF. '||D.NUMERO||' DE '||C.NOME AS HISTORICO,
                C.NOME,
                'GERENTE' AS USUARIO,
                '505' AS CODIGOFM,
                '101' AS CODHISTORICO,
                RC.FORMA AS ID
            FROM DUPLIC_RECEBER D
            inner JOIN CLIENTES C ON (C.CODIGO =D.CEDENTE)
            inner JOIN RECEBIMENTOS RC ON (RC.DOCUMENTO = D.NUMERO) AND (RC.CEDENTE = C.CODIGO)
            LEFT JOIN FORMAS_PAGREC FG ON (FG.DESCRICAO = RC.FORMA)
            left JOIN FINANC_CONTAS FC ON (FC.CODIGO = FG.ID_CONTA)
            left JOIN FINANC_CONTAS FFC ON (FFC.CODIGO = C.CONTA_CTB)
    
    ) WHERE  DATA between '{$dtini}' AND '{$dtfim}' and (CONTADEBIDO is null or CONTACREDITO is null or  CODHISTORICO is null  )";
	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		$i = 0;
			
		while($fc = ibase_fetch_object($res)){		
						

			$data            = $fc->DATA;
			$mov_idconta_c   = $fc->CONTACREDITO;
			$mov_idconta_d   = $fc->CONTADEBIDO;	
			$mov_historico   = $fc->HISTORICO;
			$mov_idhistorico = $fc->CODHISTORICO;
			$mov_valor		 = $fc->VALORDOC;
			$idusuario		 = $fc->USUARIO;
			$CODIGOFM		 = $fc->CODIGOFM;
			
			$mov = new Movimentacao();			
			
			$mov->setData($data);
			$mov->setIdContaCredito($mov_idconta_c);
			$mov->setIdContaDebito($mov_idconta_d);		
			$mov->setHistorico($mov_historico);
			$mov->setIdHistorico($mov_idhistorico);
			$mov->setValor($mov_valor);
			$mov->setIdUsuario($idusuario);
			$mov->setCodigoFM($CODIGOFM);
						
			$vet[$i++] = $mov;

		}

		return $vet;
	}

	
	public function inserir($mov){

	
		$dba = $this->dba;	

		$data          = $mov->getData();
		$contacred     = $mov->getIdContaCredito();
		$contadeb	   = $mov->getIdContaDebito();		
		$hist          = $mov->getHistorico();
		$idhist        = $mov->getIdHistorico();
		$val           = $mov->getValor();		 

		$sql = "insert into tb_movim (mov_data, mov_idconta_c, mov_idconta_d, mov_historico, mov_idhistorico, mov_valor)
		values ('".$data."', '".$contacred."', '".$contadeb."', '".$hist."', ".$idhist.", ".$val.")";

		//echo"{$sql}";
		$dba->query($sql);

		

	}

	public function alterar($mov){
	
		$dba = $this->dba;
	
		$idm           = $mov->getCodigo();
		$data          = $mov->getData();
		$contacred     = $mov->getIdContaCredito();
		$contadeb	   = $mov->getIdContaDebito();		
		$hist          = $mov->getHistorico();
		$idhist        = $mov->getIdHistorico();
		$val           = $mov->getValor();
		
		$sql = "update tb_movim
		set
			mov_data = '".$data."',
			mov_idconta_c = '".$contacred."',
			mov_idconta_d =  '".$contadeb."',
			mov_historico = '".$hist."',
			mov_idhistorico = ".$idhist.",
			mov_valor = ".$val."
			where id = {$idm}";
		echo "{$sql}";

		$dba->query($sql);

	}

	

	public function deletar($idm){

		$dba = $this->dba;

		$id = $idm->getCodigo();

		$sql = 'DELETE FROM tb_movim
				WHERE id='.$id;	

		$dba->query($sql);

		

	}

	

}

?>
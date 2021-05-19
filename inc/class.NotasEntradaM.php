<?php

class NotasEntradaM{

	

	

	private $Codigo;
	private $codfornecedor;
	private $cliente;
	private $cfop;
	private $numero_nota;
	private $serie_nota;
	private $valortotanota;
	private $representante;
	private $dataemissao;
	private $nomeproduto;
	private $entrada;
	private $saida;
	private $reg;
	private $codpro;	
	private $kg;
	private $dtproducao;
	private $precounitario;
	private $grupo;
	private $produto;
	private $total;
	private $codrepre;
	private $faturamento;
	private $fantasia;
	private $vencimento;
	private $valorproduto;
	private $qtdprod;
	private $cnpjcli;
	private $vlrtotalliquido;
	private $placa;
	private $subtotal;
	private $fornecedor;
	private $relfat;
	private $entrada_saida;
	private $data_entrada;
	private $horario;
	private $valor_frete;
	private $valor_seguro;
	private $outras_despesas;
	private $valor_ipi;
	private $base_calculo;
	private $valor_icm;
	private $base_calculo_subs;
	private $valor_icm_subs;
	private $valor_total_produtos;
	private $cod_transp;
	private $frete;
	private $uf_placa;
	private $quantidade_rodape;
	private $especie;
	private $marca;
	private $numero;
	private $peso_bruto;
	private $peso_liquido;
	private $valor_desc_aplicado;
	private $prod_codigo;
	private $prod_cod_fornec;
	private $prod_preco_unitario;
	private $prod_aliquota_icms;
	private $prod_valor_icm;
	private $prod_aliquota_ipi;
	private $prod_valor_ipi;
	private $prod_valor_desc;
	private $prod_unidade;
	private $prod_sub_total;
	private $prod_icms_subst;
	private $cfop_inteiro;
	private $avista_aprazo;
	private $prod_pecas;
	private $reboque;
	private $ufreboque;
	private $nfe_cst;
	private $nfe_orig;
	private $nfe_vbc;
	private $nfe_vicms;
	private $nfe_predbc;
	private $nfe_vbcst;
	private $nfe_vicmsst;
	private $nfe_nfadfisco;
	private $nfe_infcpl;
	private $tipo_nota;
	private $nfe_gerado;
	private $nfe_aliqicmss;
	private $acrescer_pauta;
	private $predicmss_nfe;
	private $fundesa;
	private $vendedor;
	private $np_vendedo;
	private $prazo1;
	private $prazo2;
	private $prazo3;
	private $prazo4;
	private $prazo5;
	private $cond_vendas;
	private $tabela_precos;
	private $cond_paga;
	private $reg_vendedor;
	private $entrada_1;
	private $data_entrada_1;
	private $base_ipi;
	private $prod_baseipi;
	private $ind_ipi_aliq_unid;
	private $numerocarga;
	private $pedido;	
	private $prod_valorfrete; 
	private $prod_valorseguro; 
	private $prod_vlroutdespesas; 
	private $jageroucreceber; 
	private $cod_preco_prawer; 
	private $situacao; 
	private $prod_caixas; 
	private $espessura; 
	private $largura; 
	private $comprimento; 
	private $perc_desc; 
	private $nfe_infadprod; 
	private $consulate; 
	private $pcredsn; 
	private $vcredicmssn; 
	private $cfop_nota; 
	private $hora_registro; 
	private $modbc; 
	private $modbcst; 
	private $referencia; 
	private $manifesto; 
	private $pedidoliberado;
	private $proximoid;
	private $quantidadenfecompra;
	private $vlrunitnfecompra;
	
	
	public function NotasEntradaM(){

		//nada

	}


	public function getCodigo(){

		return $this->Codigo;	

	}

	
	public function setCodigo($codigo){

		$this->Codigo = $codigo;

	}

	public function getCodigoFornecedor(){

		return $this->codfornecedor;	

	}
	
	public function setCodigoFornecedor($codfor){

		$this->codfornecedor = $codfor;

	}
	
	
	public function getCliente(){

		return $this->cliente;	

	}
	
	public function setCliente($cliente){

		$this->cliente = $cliente;

	}
	
	public function getCfop(){

		return $this->cfop;	

	}
	
	public function setCfop($cfop){

		$this->cfop = $cfop;

	}
	
	public function getNumeroNota(){

		return $this->numero_nota;	

	}
	
	public function setNumeroNota($numnota){

		$this->numero_nota = $numnota;

	}
	
	public function getSerieNota(){

		return $this->serie_nota;	

	}
	
	public function setSerieNota($serienota){

		$this->serie_nota = $serienota;

	}
	
	public function getValortotalnota(){

		return $this->valortotanota;	

	}
	
	public function setValortotalnota($valortotalnota){

		$this->valortotanota = $valortotalnota;

	}
	
	public function getRepresentante(){

		return $this->representante;	

	}
	
	public function setRepresentante($representante){

		$this->representante = $representante;

	}
	
	public function getDataEmissao(){

		return $this->dataemissao;	

	}
	
	public function setDataEmissao($dtemi){

		$this->dataemissao = $dtemi;

	}
	
	public function getNomeRepresentante(){

		return $this->representante;	

	}
	
	public function setNomeRepresentante($represent){

		$this->representante = $represent;

	}
	
	public function getNomePorduto(){

		return $this->nomeproduto;	

	}
	
	public function setNomePorduto($nomprod){

		$this->nomeproduto = $nomprod;

	}
	
	public function getEntrada(){

		return $this->entrada;	

	}
	
	public function setEntrada($entrada){

		$this->entrada = $entrada;

	}
	public function getSaida(){

		return $this->saida;	

	}
	
	public function setSaida($saida){

		$this->saida = $saida;

	}
	public function getReg(){

		return $this->reg;	

	}
	
	public function setReg($reg){

		$this->reg = $reg;

	}
	public function getCodProduto(){

		return $this->codpro;	

	}
	
	public function setCodProduto($codpro){

		$this->codpro = $codpro;

	}
	public function getKg(){

		return $this->kg;	

	}
	
	public function setKg($kg){

		$this->kg = $kg;

	}
	
	public function getDtProducao(){

		return $this->dtproducao;	

	}
	
	public function setDtProducao($dtproducao){

		$this->dtproducao = $dtproducao;

	}
	
	public function getPrecoUnitario(){

		return $this->precounitario;	

	}
	
	public function setPrecoUnitario($precunit){

		$this->precounitario = $precunit;

	}
	
	public function getGrupo(){

		return $this->grupo;	

	}
	
	public function setGrupo($grupo){

		$this->grupo = $grupo;

	}
	
	public function getProduto(){

		return $this->produto;	

	}
	
	public function setProduto($produto){

		$this->produto = $produto;

	}
	
	public function getTotal(){

		return $this->total;	

	}
	
	public function setTotal($total){

		$this->total = $total;

	}
	
	
	public function getCodRepresentante(){

		return $this->codrepre;	

	}
	
	public function setCodRepresentante($codrepre){

		$this->codrepre = $codrepre;

	}
	
	public function getFaturamento(){

		return $this->faturamento;	

	}
	
	public function setFaturamento($faturamento){

		$this->faturamento = $faturamento;

	}
	
	public function getFantasia(){

		return $this->fantasia;	

	}
	
	public function setFantasia($fantasia){

		$this->fantasia = $fantasia;

	}
	public function getVencimento(){

		return $this->vencimento;	

	}
	
	public function setVencimento($vencimento){

		$this->vencimento = $vencimento;

	}
	public function getProdutoValor(){

		return $this->valorproduto;	

	}
	
	public function setProdutoValor($valpro){

		$this->valorproduto = $valpro;

	}
	
	public function getProdutoQuantidade(){

		return $this->qtdprod;	

	}
	
	public function setProdutoQuantidade($qtdprod){

		$this->qtdprod = $qtdprod;

	}
	public function getCnpjCliente(){

		return $this->cnpjcli;	

	}
	
	public function setCnpjCliente($cnpjcli){

		$this->cnpjcli = $cnpjcli;

	}
	
	public function getValorTotalLiquido(){

		return $this->vlrtotalliquido;	

	}
	
	public function setValorTotalLiquido($vlrtotalliquido){

		$this->vlrtotalliquido = $vlrtotalliquido;

	}
	public function getPlaca(){

		return $this->placa;	

	}
	
	public function setPlaca($placa){

		$this->placa = $placa;

	}
	
	public function getSubTotal(){

		return $this->subtotal;	

	}
	
	public function setSubTotal($subtotal){

		$this->subtotal = $subtotal;

	}
	public function getFornecedor(){

		return $this->fornecedor;	

	}
	
	public function setFornecedor($fornecedor){

		$this->fornecedor = $fornecedor;

	}
	
	public function getRelFat(){

		return $this->relfat;	

	}
	
	public function setRelFat($relfat){

		$this->relfat = $relfat;

	}
	
	public function getEntradaSaida(){

		return $this->entrada_saida;	

	}
	
	public function setEntradaSaida($entsai){

		$this->entrada_saida = $entsai;

	}
	
	public function getDataEntrada(){

		return $this->data_entrada;	

	}
	
	public function setDataEntrada($dataentrada){

		$this->data_entrada = $dataentrada;

	}
	
	public function getHorario(){

		return $this->horario;	

	}
	
	public function setHorario($horario){

		$this->horario = $horario;

	}
	
	public function getValorFrete(){

		return $this->valor_frete;	

	}
	
	public function setValorFrete($valorfrete){

		$this->valor_frete = $valorfrete;

	}
	
	public function getValorSeguro(){

		return $this->valor_seguro;	

	}
	
	public function setValorSeguro($valorseguro){

		$this->valor_seguro = $valorseguro;

	}
	
	public function getOutrosDespecas(){

		return $this->outras_despesas;	

	}
	
	public function setOutrosDespecas($outrasdespecas){

		$this->outras_despesas = $outrasdespecas;

	}
	
	public function getValorIpi(){

		return $this->valor_ipi;	

	}
	
	public function setValorIpi($valoripi){

		$this->valor_ipi = $valoripi;

	}
	
	public function getBaseCalculo(){

		return $this->base_calculo;	

	}
	
	public function setBaseCalculo($basecalculo){

		$this->base_calculo = $basecalculo;

	}
	
	public function getValorIcm(){

		return $this->valor_icm;	

	}
	
	public function setValorIcm($valoricm){

		$this->valor_icm = $valoricm;

	}
	
	public function getBaseCalculoSubs(){

		return $this->base_calculo_subs;	

	}
	
	public function setBaseCalculoSubs($basecalcsubs){

		$this->base_calculo_subs = $basecalcsubs;

	}
	
	public function getValorIcmSubs(){

		return $this->valor_icm_subs;	

	}
	
	public function setValorIcmSubs($valoricmsubs){

		$this->valor_icm_subs = $valoricmsubs;

	}
	
	public function getValorTotalProdutos(){

		return $this->valor_total_produtos;	

	}
	
	public function setValorTotalProdutos($valortotalproduto){

		$this->valor_total_produtos = $valortotalproduto;

	}
	
	public function getCodTransp(){

		return $this->cod_transp;	

	}
	
	public function setCodTransp($codtransp){

		$this->cod_transp = $codtransp;

	}
	
	
	public function getFrete(){

		return $this->frete;	

	}
	
	public function setFrete($frete){

		$this->frete = $frete;

	}
	
	public function getUfPlaca(){

		return $this->uf_placa;	

	}
	
	public function setUfPlaca($ufplaca){

		$this->uf_placa = $ufplaca;

	}
	
	public function getQuantidadeRodape(){

		return $this->quantidade_rodape;	

	}
	
	public function setQuantidadeRodape($qtdrodape){

		$this->quantidade_rodape = $qtdrodape;

	}
	
	public function getEspecie(){

		return $this->especie;	

	}
	
	public function setEspecie($especie){

		$this->especie = $especie;

	}
	
	public function getMarca(){

		return $this->marca;	

	}
	
	public function setMarca($marca){

		$this->marca = $marca;

	}
	
	public function getNumero(){

		return $this->numero;	

	}
	
	public function setNumero($numero){

		$this->numero = $numero;

	}
	
	public function getPesoBruto(){

		return $this->peso_bruto;	

	}
	
	public function setPesoBruto($pesobruto){

		$this->peso_bruto = $pesobruto;

	}
	
	public function getPesoLiquido(){

		return $this->peso_liquido;	

	}
	
	public function setPesoLiquido($pesoliquido){

		$this->peso_liquido = $pesoliquido;

	}
	
	public function getValorDescontoAplicado(){

		return $this->valor_desc_aplicado;	

	}
	
	public function setValorDescontoAplicado($valordescontoaplicado){

		$this->valor_desc_aplicado = $valordescontoaplicado;

	}
	
	public function getProdCodigo(){

		return $this->prod_codigo;	

	}
	
	public function setProdCodigo($prodcodigo){

		$this->prod_codigo = $prodcodigo;

	}
	
	public function getProdCodFornecedor(){

		return $this->prod_cod_fornec;	

	}
	
	public function setProdCodFornecedor($prodcodfornec){

		$this->prod_cod_fornec = $prodcodfornec;

	}
	
	public function getProdPrecoUnitario(){

		return $this->prod_preco_unitario;	

	}
	
	public function setProdPrecoUnitario($prodprecounitario){

		$this->prod_preco_unitario = $prodprecounitario;

	}
	
	public function getProdLiquotaIcms(){

		return $this->prod_aliquota_icms;	

	}
	
	public function setProdLiquotaIcms($prodliquotaicms){

		$this->prod_aliquota_icms = $prodliquotaicms;

	}
	
	public function getProdValorIcm(){

		return $this->prod_valor_icm;	

	}
	
	public function setProdValorIcm($prodvaloricm){

		$this->prod_valor_icm = $prodvaloricm;

	}
	
	public function getProdAliquotaIpi(){

		return $this->prod_aliquota_ipi;	

	}
	
	public function setProdAliquotaIpi($prodaliquotaipi){

		$this->prod_aliquota_ipi = $prodaliquotaipi;

	}
	
	public function getProdValorIpi(){

		return $this->prod_valor_ipi;	

	}
	
	public function setProdValorIpi($prodvaloripi){

		$this->prod_valor_ipi = $prodvaloripi;

	}
	
	public function getProdValorDesc(){

		return $this->prod_valor_desc;	

	}
	
	public function setProdValorDesc($prodvalordesc){

		$this->prod_valor_desc = $prodvalordesc;

	}
	
	public function getProdUnidade(){

		return $this->prod_unidade;	

	}
	
	public function setProdUnidade($produnidade){

		$this->prod_unidade = $produnidade;

	}
	
	public function getProdSubTotal(){

		return $this->prod_sub_total;	

	}
	
	public function setProdSubTotal($prodsubtotal){

		$this->prod_sub_total = $prodsubtotal;

	}
	
	public function getProdIcmsSubst(){

		return $this->prod_icms_subst;	

	}
	
	public function setProdIcmsSubst($prodicms){

		$this->prod_icms_subst = $prodicms;

	}
	
	public function getCfopInteiro(){

		return $this->cfop_inteiro;	

	}
	
	public function setCfopInteiro($cfopinteiro){

		$this->cfop_inteiro = $cfopinteiro;

	}
	
	public function getAvistaAprazo(){

		return $this->avista_aprazo;	

	}
	
	public function setAvistaAprazo($avistaaprazo){

		$this->avista_aprazo = $avistaaprazo;

	}
	
	public function getProdPecas(){

		return $this->prod_pecas;	

	}
	
	public function setProdPecas($prodpecas){

		$this->prod_pecas = $prodpecas;

	}
	
	public function getReboque(){

		return $this->reboque;	

	}
	
	public function setReboque($reboque){

		$this->reboque = $reboque;

	}

	
	public function getUfReboque(){

		return $this->ufreboque;	

	}
	
	public function setUfReboque($ufreboque){

		$this->ufreboque = $ufreboque;

	}
	
	public function getNfeCst(){

		return $this->nfe_cst;	

	}
	
	public function setNfeCst($nfecst){

		$this->nfe_cst = $nfecst;

	}
	
	public function getNfeOrig(){

		return $this->nfe_orig;	

	}
	
	public function setNfeOrig($nfeorig){

		$this->nfe_orig = $nfeorig;

	}
	
	public function getNfeVbc(){

		return $this->nfe_vbc;	

	}
	
	public function setNfeVbc($nfevbc){

		$this->nfe_vbc = $nfevbc;

	}
	
	public function getNfeVicms(){

		return $this->nfe_vicms;	

	}
	
	public function setNfeVicms($nfevicms){

		$this->nfe_vicms = $nfevicms;

	}
	
	public function getNfePredBc(){

		return $this->nfe_predbc;	

	}
	
	public function setNfePredBc($nfepredbc){

		$this->nfe_predbc = $nfepredbc;

	}
	
	public function getNfeVbcst(){

		return $this->nfe_vbcst;	

	}
	
	public function setNfeVbcst($nfevbcst){

		$this->nfe_vbcst = $nfevbcst;

	}
	
	public function getNfeVicmsSt(){

		return $this->nfe_vicmsst;	

	}
	
	public function setNfeVicmsSt($nfevicmsst){

		$this->nfe_vicmsst = $nfevicmsst;

	}
	
	public function getNfeNfAdFisco(){

		return $this->nfe_nfadfisco;	

	}
	
	public function setNfeNfAdFisco($nfeadfisco){

		$this->nfe_nfadfisco = $nfeadfisco;

	}
	
	public function getNfeInfCpl(){

		return $this->nfe_infcpl;	

	}
	
	public function setNfeInfCpl($nfeinfcpl){

		$this->nfe_infcpl = $nfeinfcpl;

	}
	
	public function getTipoNota(){

		return $this->tipo_nota;	

	}
	
	public function setTipoNota($tiponota){

		$this->tipo_nota = $tiponota;

	}
	
	public function getNfeGerado(){

		return $this->nfe_gerado;	

	}
	
	public function setNfeGerado($nfegerado){

		$this->nfe_gerado = $nfegerado;

	}
	
	public function getNfeAliqIcmss(){

		return $this->nfe_aliqicmss;	

	}
	
	public function setNfeAliqIcmss($nfealiqicmss){

		$this->nfe_aliqicmss = $nfealiqicmss;

	}
	
	public function getAcrescerPauta(){

		return $this->acrescer_pauta;	

	}
	
	public function setAcrescerPauta($acrecerpauta){

		$this->acrescer_pauta = $acrecerpauta;

	}
	
	public function getPrediCmssNfe(){

		return $this->predicmss_nfe;	

	}
	
	public function setPrediCmssNfe($predicmssnfe){

		$this->predicmss_nfe = $predicmssnfe;

	}
	
	public function getFundesa(){

		return $this->fundesa;	

	}
	
	public function setFundesa($fundesa){

		$this->fundesa = $fundesa;

	}
	
	public function getVendedor(){

		return $this->vendedor;	

	}
	
	public function setVendedor($vendedor){

		$this->vendedor = $vendedor;

	}
	
	public function getNpVendedor(){

		return $this->np_vendedo;	

	}
	
	public function setNpVendedor($npvendedor){

		$this->np_vendedo = $npvendedor;

	}
	
	public function getPrazo1(){

		return $this->prazo1;	

	}
	
	public function setPrazo1($prazo1){

		$this->prazo1 = $prazo1;

	}
	public function getPrazo2(){

		return $this->prazo2;	

	}
	
	public function setPrazo2($prazo2){

		$this->prazo2 = $prazo2;

	}
	public function getPrazo3(){

		return $this->prazo3;	

	}
	
	public function setPrazo3($prazo3){

		$this->prazo3 = $prazo3;

	}
	public function getPrazo4(){

		return $this->prazo4;	

	}
	
	public function setPrazo4($prazo4){

		$this->prazo4 = $prazo4;

	}
	public function getPrazo5(){

		return $this->prazo5;	

	}
	
	public function setPrazo5($prazo5){

		$this->prazo5 = $prazo5;

	}
	
	public function getCondVendas(){

		return $this->cond_vendas;	

	}
	
	public function setCondVendas($condvendas){

		$this->cond_vendas = $condvendas;

	}
	
	public function getTabelaPrecos(){

		return $this->tabela_precos;	

	}
	
	public function setTabelaPrecos($tabelarprecos){

		$this->tabela_precos = $tabelarprecos;

	}
	
	public function getCondPaga(){

		return $this->cond_paga;	

	}
	
	public function setCondPaga($condpaga){

		$this->cond_paga = $condpaga;

	}
	
	public function getRegVendedor(){

		return $this->reg_vendedor;	

	}
	
	public function setRegVendedor($regvendedor){

		$this->reg_vendedor = $regvendedor;

	}
	
	public function getEntrada1(){

		return $this->entrada_1;	

	}
	
	public function setEntrada1($entrada1){

		$this->entrada_1 = $entrada1;

	}
	
	public function getDataEntrada1(){

		return $this->data_entrada_1;	

	}
	
	public function setDataEntrada1($dataentrada1){

		$this->data_entrada_1 = $dataentrada1;

	}
	
	
	public function getBaseIpi(){

		return $this->base_ipi;	

	}
	
	public function setBaseIpi($baseipi){

		$this->base_ipi = $baseipi;

	}
	
	public function getProdBaseIpi(){

		return $this->prod_baseipi;	

	}
	
	public function setProdBaseIpi($prodabaseipi){

		$this->prod_baseipi = $prodabaseipi;

	}
	
	
	public function getIndIpiAliqUnid(){

		return $this->ind_ipi_aliq_unid;	

	}
	
	public function setIndIpiAliqUnid($indipialiqunid){

		$this->ind_ipi_aliq_unid = $indipialiqunid;

	}
	
	public function getNumeroCarga(){

		return $this->numerocarga;	

	}
	
	public function setNumeroCarga($numerocarga){

		$this->numerocarga = $numerocarga;

	}
	
	public function getPedido(){

		return $this->pedido;	

	}
	
	public function setPedido($pedido){

		$this->pedido = $pedido;

	}
	
	public function getProdValorFrete(){

		return $this->prod_valorfrete;	

	}
	
	public function setProdValorFrete($prodvalorfrete){

		$this->prod_valorfrete = $prodvalorfrete;

	}
	
	
	public function getProdValorSeguro(){

		return $this->prod_valorseguro;	

	}
	
	public function setProdValorSeguro($prodvalorseq){

		$this->prod_valorseguro = $prodvalorseq;

	}
	
	public function getProdVlRoutDespecas(){

		return $this->prod_vlroutdespesas;	

	}
	
	public function setProdVlRoutDespecas($prodvldespecas){

		$this->prod_vlroutdespesas = $prodvldespecas;

	}
	
	public function getJageRoucReceber(){

		return $this->jageroucreceber;	

	}
	
	public function setJageRoucReceber($jageroucreceber){

		$this->jageroucreceber = $jageroucreceber;

	}
	
	public function getCodPrecoPrawer(){

		return $this->cod_preco_prawer;	

	}
	
	public function setCodPrecoPrawer($codprecoprawer){

		$this->cod_preco_prawer = $codprecoprawer;

	}
	
	
	public function getSituacao(){

		return $this->situacao;	

	}
	
	public function setSituacao($situacao){

		$this->situacao = $situacao;

	}
	
	public function getProdCaixas(){

		return $this->prod_caixas;	

	}
	
	public function setProdCaixas($prodcaixas){

		$this->prod_caixas = $prodcaixas;

	}	
	
	public function getEspessura(){

		return $this->espessura;	

	}
	
	public function setEspessura($espessura){

		$this->espessura = $espessura;

	}
	
	public function getLargura(){

		return $this->largura;	

	}
	
	public function setLargura($largura){

		$this->largura = $largura;

	}
	
	public function getComprimento(){

		return $this->comprimento;	

	}
	
	public function setComprimento($comprimento){

		$this->comprimento = $comprimento;

	}
	
	
	public function getPercDesc(){

		return $this->perc_desc;	

	}
	
	public function setPercDesc($percdesc){

		$this->perc_desc = $percdesc;

	}	
	
	public function getNfeInfAdProd(){

		return $this->nfe_infadprod;	

	}
	
	public function setNfeInfAdProd($nfeinfdprod){

		$this->nfe_infadprod = $nfeinfdprod;

	}
	
	public function getConsulate(){

		return $this->consulate;	

	}
	
	public function setConsulate($consulate){

		$this->consulate = $consulate;

	}
	
	public function getPcredSn(){

		return $this->pcredsn;	

	}
	
	public function setPcredSn($pcredsn){

		$this->pcredsn = $pcredsn;

	}
	
	public function getVcredIcmssn(){

		return $this->vcredicmssn;	

	}
	
	public function setVcredIcmssn($vcredicmssn){

		$this->vcredicmssn = $vcredicmssn;

	}
	
	public function getCfopNota(){

		return $this->cfop_nota;	

	}
	
	public function setCfopNota($cfopnota){

		$this->cfop_nota = $cfopnota;

	}
	
	public function getHoraRegistro(){

		return $this->hora_registro;	

	}
	
	public function setHoraRegistro($horaregistro){

		$this->hora_registro = $horaregistro;

	}
	
	public function getModBc(){

		return $this->modbc;	

	}
	
	public function setModBc($modbc){

		$this->modbc = $modbc;

	}
	
	public function getModBcSt(){

		return $this->modbcst;	

	}
	
	public function setModBcSt($modbcst){

		$this->modbcst = $modbcst;

	}
	
	public function getReferencia(){

		return $this->referencia;	

	}
	
	public function setReferencia($referencia){

		$this->referencia = $referencia;

	}
	
	public function getManifesto(){

		return $this->manifesto;	

	}
	
	public function setManifesto($manifesto){

		$this->manifesto = $manifesto;

	}
	
	public function getPedidoLiberado(){

		return $this->pedidoliberado;	

	}
	
	public function setPedidoLiberado($pedidoliberado){

		$this->pedidoliberado = $pedidoliberado;

	}
	
	public function getProximoId(){

		return $this->proximoid;	

	}
	
	public function setProximoId($proximoid){

		$this->proximoid = $proximoid;

	}
	
	public function getQuantidadeNfeCompra(){

		return $this->quantidadenfecompra;	

	}
	
	public function setQuantidadeNfeCompra($qtdnfecompra){

		$this->quantidadenfecompra = $qtdnfecompra;

	}
	
	public function getVlrUnitNfeCompra(){

		return $this->vlrunitnfecompra;	

	}
	
	public function setVlrUnitNfeCompra($vlrunit){

		$this->vlrunitnfecompra = $vlrunit;

	}
	
}

?>
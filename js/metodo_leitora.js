// JavaScript Document

$(document).ready(function(e) {
    
	$("#por_arquivo").hide();
	$("#por_nsu").hide();
	$("#por_barras").show();
	
	$("input[name='RadioGroup1']").click(function(){			
		if($(this).val() == 1){			
			$("#por_arquivo").show();
			$("#por_barras").hide();		
			$("#por_nsu").hide();	
		}else if($(this).val() == 2){			
			$("#por_arquivo").hide();
			$("#por_nsu").hide();
			$("#por_barras").show();			
		}else if($(this).val() == 3){
			
			$("#por_nsu").show();
			$("#por_arquivo").hide();
			$("#por_barras").hide()
		}
		
	});


	$("input[name='rdntoas']").change(function(){		
		
		$(".m-menu-notas li a").removeClass('selecionado');
		this.closest('a').classList.toggle('selecionado', this.checked);
		
		if($(this).val() == 1){			
			$("#por_arquivo").show();
			$("#por_barras").hide();		
			$("#por_nsu").hide();	
		}else if($(this).val() == 2){			
			$("#por_arquivo").hide();
			$("#por_nsu").hide();
			$("#por_barras").show();
			
			$("#nota_dados").hide();
			$("#for_dados").hide();
			$("#total_dados").hide();
			$("#btnaction").hide();

		}else if($(this).val() == 3){
			
			$("#por_nsu").show();
			$("#por_arquivo").hide();
			$("#por_barras").hide()
		}
		
	});

});

$(document).ready(function () {
   $('form[id="upload2"]').bind('submit', function(){
	   	$("#subimp").click();
     	return false;
   });
});
$(document).ready(function(e) {
    $("#dtentrada").change(function(){
				
		var data_inicio   = $(this).val();
		var data_fim      = $("#nota_dtemi").html();		
		
		var compara1 = parseInt(data_inicio.split(".")[2].toString() + data_inicio.split(".")[1].toString() + data_inicio.split(".")[0].toString());
		var compara2 = parseInt(data_fim.split(".")[2].toString() + data_fim.split(".")[1].toString() + data_fim.split(".")[0].toString());
	
		
		if(compara1 == ""){
			alert("O campo data não pode estar em branco!");
		}else{
			
			if(compara1 < compara2){
				alert('Data de entrada não pode ser menor que a data da emissão!');	
		
			}
			
		}
		
	});
	
	$("#cfop").blur(function(){
		
		if($(this).val()){
		$.ajax({
			url: '../php/cfop-exec.php',
            type: 'POST',
            data: {act:'veridicacfop',cf:$(this).val()},
            cache: false,
            dataType: 'json',
			success: function(data){
				if(data[0].label == '2'){
					alert(data[0].msg);
				}else{
					
					if(data[0].cod > 2000 && data[0].cod < 3000){
						$(".vlguiast").removeClass('hide');
						$("#vlguiast").maskMoney({							
						   decimal:",",
						   thousands:"."
							  
						  });
					}else{	
						$(".vlguiast").addClass('hide');
					}

					$.confirm({
						title: '<img src="../images/icon.ico" width="15" /> Sistema',
						content: 'Deseja utilizar esta CFOP para todos os itens desta nota ?',
						type: 'orange',
						typeAnimated: true,
						buttons: {
							sim: {
								text: 'Sim',
								btnClass: 'btn-green',
								action: function(){
									$('#cfopsn').val(1);
									$("#dtentrada").focus();
								}
							},
							nao: {
								text: 'Não',
								btnClass: 'btn-red',
								action: function(){
									$('#cfopsn').val(2);
									$("#dtentrada").focus();
								}
							}
						}
					});
				
				}
				
				
			},
			error: function(data){
				alert(data);
			}
						
		});
		}
		if($(this).val() > 5000){
			//alert('CFOP MAIOR QUE O PLANEJADO');	
			bootbox.alert("CFOP informada não é valida para operações de entrada!", function() {
				
			});			
		}
		
	});
	
});

$(function()
{
    
	$("#subimp").attr('disabled',true);
	
	$("#subimp").click(function(){
		var dialoger;	
		var dialognsu;
			
		var manifest = $("select[name='manif']").val();
		
		if(manifest == ""){
			alert("Informe uma operação!");
			 bootbox.hideAll();
			return false;
		}
		
		dialognsu = '<div class="loadermodal"><img src="../images/icon.ico" width="15" /> Sistema<br/>Aguarde<img src="../images/loader19.gif"/> Fazendo Comunicação com a SEFAZ!</div>';
		$(".sel_arq").append(dialognsu);		

	
		if( $("#file_upload2").val() != ""){
		$(".loadermodal").remove();
		$.ajax({
            url: '../php/relacionamento2-exec.php',
            type: 'POST',
            data: {file_upload:$("#file_upload2").val(),act:'sefaz',manif:$('select[name="manif"] :selected').val()},
            cache: false,
            dataType: 'json',
			beforeSend:function(){
									
				ajax_load("open");
				$('.ajax_load_box_title').html('Aguarde<img src="../images/loader19.gif"/> Fazendo Comunicação com a SEFAZ!');
			},
            success: function(data, textStatus, jqXHR)
            {
				
				ajax_load("close");
				$('.ajax_load_box_title').html('Aguarde, carregando...');
				//$('.jconfirm-closeIcon').click();
            	if(typeof data.error === 'undefined')
            	{
            		// Sucesso até chamar a função para processar o formulário
            		var file = data.files;
					
					//console.log('sucesso: ' + data.files);
					//$('#msg').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Ok Tudo certo!</div>');
					var view = '<div class="message success">Ok Tudo certo!</div>';
					$(".barra_form_callback").html(view);
					$(".message").effect("bounce");

					submitForm2(data);
					$("#subimp").focus();
								
            	}
            	else
            	{
					var view = '<div class="message error">' + data.error+' </div>';
					$(".barra_form_callback").html(view);
					$(".message").effect("bounce");
            		//console.log('ERRORS: ' + data.error);
					//$('#msg').html('<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Aviso!</h4>'+data.error+'</div>')
					$("#subimp").focus();
					
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	
				//console.log('ERRORS2: ' + textStatus);
				
				var caminho = jqXHR.responseText.search('DATABASE.FDB" Error while trying to open file O sistema');
				var timeff30  = jqXHR.responseText.search('time of 30');
				var m = "";
				if(caminho > 0){
					m = '<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Aviso!</h4> Verifica o caminho nas Configuração do Firebird<br/> Vá na aba [funções] depois [Configurações do Sistema] depois na parte onde diz [Configuração do Firebird] depois [Caminho (FDB)] verificar se esta correto ;) </div>';
				}

				if(timeff30 > 0){
					m = '<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Aviso!</h4> O tempo de comunicação com a sefaz demorou, tente enviar novamente até estabelecer a conexão com a sefaz ;) </div>';
				}

				var view = '<div class="message error">' + jqXHR.responseText + '<br/> '+m+' </div>';
				$(".barra_form_callback").html(view);
				$(".message").effect("bounce");

				//console.log('<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Aviso!</h4>'+jqXHR.responseText+'</div>');
				//$('#msg').html('<div class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Aviso!</h4>'+jqXHR.responseText+'</div>');
				 bootbox.hideAll();
				$("#subimp").focus();
				//dialog.close();
            	
            }
        });
		
		$("#subimp").attr('disabled',false);
		}else{
			
			alert("Digite a chave ou passe o leitor de codigo de barras!");
			 //bootbox.hideAll();
			$("#subimp").focus();
			
		}
		function ajax_load(action) {
			ajax_load_div = $(".ajax_load");

			if (action === "open") {
				ajax_load_div.fadeIn(200).css("display", "flex");
			}

			if (action === "close") {
				ajax_load_div.fadeOut(200);
			}
		}
	});

	
});

 function submitForm2(data2)
	{
		//data.files
		var dialogs;
		
		$.ajax({
			url: '../php/relacionamento2-exec.php',
            type: 'POST',
            data: {act:'box',filenames:data2.files},
            cache: false,
            dataType: 'json',
			beforeSend: function(){
				 dialogs = $.dialog({
						title: '<img src="../images/icon.ico" width="15" /> Sistema',
						closeIcon: true,
						content: 'Aguarde<img src="../images/loader19.gif"/> estou coletando os dados!',
					});	
			},
            success: function(data, textStatus, jqXHR)
            {

				if(data[0].msgerro == '1'){
				
					alert('Nota já existe!');
					window.location.href = '../php/importacao.php';
				}else if(data[0].msgerro == '2'){
					alert('Essa Nota não pertencia a este destinatario!');
				}else{
				
				$("#nota_dados").show();
				$("#for_dados").show();
				$("#total_dados").show();
				$("#btnaction").show();

				$("#nota_numero").html(data[0].Numero);
				$("#nota_dtemi").html(data[0].dtemis);
				//$("#cfop").val(data[0].cfop);
				$("#cfop").val();
				$("#cfop").focus();
				$("#dtentrada").val(data[0].dtentrada);				
				$("#for_nome").html(data[0].cod+'-'+data[0].nome);
				$("#for_endereco").html(data[0].ende);
				$("#for_cidade").html(data[0].cid);
				$("#for_estado").html(data[0].esta);
				$("#for_cnpjcpf").html(data[0].cnpjcpf);
				
				// para o formulario de apropriação
				
				$("#for_nomes").html(data[0].nome);
				$("#nota_numeros").html(data[0].Numero);
				$("#dt_emissao").html(data[0].dtemis);
				$("#cfor").val(data[0].cod);
				
				//$("").html(data[0].ende);
				var table   = "";
				var vtotpro = 0;
				var vltotal = 0;
				for (var i = 1; i < data.length; i++) {
					
					var dados = data[i];
					
					
					
					table += ' <tr id="'+dados.cProd+'">';
					table += '<input type="hidden" name="item['+i+'][cfor]" value="'+data[0].cod+'"/>';
					table += '<td style="width: 6%;"><input type="hidden" name="item['+i+'][cProd]" value="'+dados.cProd+'"/>'+dados.cProd+'</td>';
					table += '<td><input type="hidden" name="item['+i+'][xProd]" value="'+dados.xProd+'"/><input type="hidden" name="item['+i+'][uTrib]" value="'+dados.uTrib+'"/>'+dados.xProd+' '+dados.uTrib+'</td>';
					table += '<td style="text-align: right;   width: 7%;"><input type="hidden" name="item['+i+'][qCom]" value="'+dados.qCom+'"/>'+number_format(dados.qCom,2,',','.')+'</td>';
					table += '<td style="text-align: right;   width: 7%;"><input type="hidden" name="item['+i+'][vProd]" value="'+dados.vUnCom+'"/>'+number_format(dados.vUnCom,2,',','.')+'</td>';
					table += '<td style="text-align: right;   width: 7%;"><input type="hidden" name="item['+i+'][vSubProd]" value="'+dados.vProd+'"/>'+number_format(dados.vProd,2,',','.')+'</td>';
					table += '<td style="width: 4%;"><input type="hidden" name="item['+i+'][CFOP]" value="'+dados.CFOP+'"/>'+dados.CFOP+'</td>';
					if(dados.relacionado == " "){
						table += '<td id="rel'+dados.cProd+''+i+'"><a href="#" onClick="relacionarproduto(\''+data[0].cod+'\',\''+dados.cProd+'\','+i+',\''+dados.idrel+'\',\'relacionar\',\''+dados.ncabecaqtd+'\');" class="btn btn-default">Relacionar</a></td>';
					}else{
						var rela = dados.relacionado.split(" ");
						table += '<td id="rel'+dados.cProd+''+i+'"><input type="hidden" name="item['+i+'][IDPROD_REL]" value="'+rela[0]+'"/>'+dados.relacionado+'     <a href="#" class="separapencil" onClick="relacionarproduto(\''+data[0].cod+'\',\''+dados.cProd+'\','+i+',\''+dados.idrel+'\',\'altrelacionar\',\''+dados.ncabecaqtd+'\');"><i class="icon-pencil"></i></a></td>';
					}
					table += '</tr>';
								
					vtotpro = vtotpro + parseFloat(dados.vProd);
				}
				
				vltotal = parseFloat(vtotpro) + parseFloat(data[0].vIPI) + parseFloat(data[0].vST) + parseFloat(data[0].vFrete) + parseFloat(data[0].vOutro) - parseFloat(data[0].vDesc);
				
				$("#totalnota").html(number_format(vltotal,2,',','.'));
				$("#valor_tot").html(number_format(vltotal,2,',','.'));
				$("#valor_tots").val(vltotal);
				
				
				$('#dyntable').dataTable({					
					 "bSort" : false,
					 "paging":   false,
					 "ordering": false,
					 "info":     false,
					 "bDestroy": true
				});
				
				$("#dyntable_filter").css({'display':'none'})
				
				$('#relaciona').append('<input type="hidden" name="arquivo" value="'+data[0].arquivo+'"/>');
				$('#dyntable tbody').html(table);	
					dialogs.close();		
				}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
            	//console.log('ERRORS: ' + textStatus);
				
				var view = '<div class="message error">' + jqXHR.responseText + '</div>';
				$(".barra_form_callback").html(view);
				$(".message").effect("bounce");
				 bootbox.hideAll();
            },
            complete: function()
            {
            	// STOP LOADING SPINNER
            }
		});
	}
	
	
	$(document).ready(function(e) {
        
		$('select[name="manif"]').change(function(){
			
			
			switch ($(this).val()){
				
				case '210200':
				
					bootbox.confirm("<h2>CONFIRMAÇÃO DA OPERAÇÃO</h2> confirmando a ocorrência da operação e o recebimento da mercadoria (para as operações com circulação de mercadoria).<br/> EX.: Você adiquiriu a mercadoria e ja recebeu a mesma em seu estabelecimento. Deseja confirmar esta manifestação?", function(result) {
			
						 if(result == true){
								$("#file_upload2").focus();
								$("#subimp").attr('disabled',false);
							}else{
								$("#subimp").attr('disabled',true);						
							}
					
						}); 
					
				break;
				case '210210':
									
						
					
					bootbox.confirm("<h2>CIÊNCIA DA OPERAÇÃO</h2> declarando ter ciência da operação destinada ao CNPJ, mas ainda não possui elementos suficientes para apresentar uma manifestação conclusiva, como as acima citadas. <br/>EX.: Você confirma estar ciente desta compra, mas ainda não recebeu a mercadoria. Deseja confirmar esta manifestação? ", function(result) {
						 
								
						if(result == true){
								$("#file_upload2").focus();
								$("#subimp").attr('disabled',false);
							}else{
							
								$("#subimp").attr('disabled',true);
							}
									 
						 
						}); 
					
					
					
					
				break;
				case '210220':
															
					
					bootbox.confirm("<H2>DESCONHECIMEMTO DA OPERAÇÃO</H2> declarando o Desconhecimento da Operação. <br/>EX.: Você não fez esta compra. Deseja confirmar esta manifestação?", function(result) {
					 	
						if(result == true){
						$("#file_upload2").focus();
							$("#subimp").attr('disabled',false);
						}else{
						
							$("#subimp").attr('disabled',true);
						}
							
					}); 
					
				break;
				case '210240':										
										
					bootbox.confirm(" OPERAÇÃO NÃO REALIZADO – declarando que a Operação não foi Realizada (com Recusa do Recebimento da mercadoria e outros) e a justificativa porque a operação não se realizou. EX.: Você comprou a mercadoria, mas por motivos de (devolução, furto, desacordo, ou outros) não recebeu a mercadoria. Deseja confirmar esta manifestação?", function(result) {
						
							if(result == true){
								
								$("#file_upload2").focus();
								$("#subimp").attr('disabled',false);
								
							}else{
							
								$("#subimp").attr('disabled',true);	
								
							}
					}); 
										
				break;
				default:
										
					$("#subimp").attr('disabled',true);	
					
				break;
			
			}
		
		});
		
    });
	
	function consultasefaznsu(){

		var ret = [];

		$.ajax({
			url: '../php/relacionamento2-exec.php',
            type: 'POST',
            data: {act:'BuscaNsu'},
            cache:false,
            dataType: 'json',
			success: function(data){
				console.log(data);
				ret = data;		
					
			},
			error: function(data){
				
				ret = [];
			}
						
		});

		return ret;
	}
	$(document).on('click', '#svs', function() {        

		//		alert(''+$("#valor_tots").val()+' '+$("#valortotal").val()+'');
				var dtemi 	   = $("#dt_emissao").html();
				var cfor  	   = $("#cfor").val();
				var numeronota = $("#nota_numeros").html();
				var totalnota  = $("#valor_tot").html();
				var arquivo    = $("input[name='arquivo']").val();
				var cfop	   = $("#cfop").val();
							
				var array = [{"empresa":"0001","cedente":""+cfor+"","emissao":""+dtemi+"","tipo":"DP","numeronota":""+numeronota+"","totalnota":""+totalnota+"","arquivo":""+arquivo+"","cfop":""+cfop+""}];
			
				if(parseFloat($("#valor_tots").val()) != parseFloat($("#valortotal").val())){
						
						alert('O valor apropriado é diferente do valor da nota. Reveja O(s) Lançamento(s).');
						
				}else{
						
					mostrarduplics(arquivo,array);
						
				}
		
			
			/*$(".close").click(function(){
				window.location.href=window.location.href;
			});	*/
		});
		
	$(document).ready(function(e) {
        
		$("#sv").click(function(){
	//		alert(''+$("#valor_tots").val()+' '+$("#valortotal").val()+'');
			
		});
		
		
		$(".close").click(function(){
			window.location.href=window.location.href;
		});	
    });
	
	
	$(document).on('click','.btn-naousar',function(e){

		var id = $(this).attr('data-id');
		var coprod   = id.split("|")[0];
		var codrepre = id.split("|")[1];
		console.log(coprod+" - "+codrepre);

		$.ajax({
			url: '../php/relacionamento2-exec.php',
            type: 'POST',
            data: {act:'limparel',codprod:coprod,codrepre:codrepre},
            cache:false,
            dataType: 'json',
			success: function(data){
				console.log(data);
				
					
			},
			error: function(data){
								
			}
						
		});

	});
	
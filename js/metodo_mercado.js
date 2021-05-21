// JavaScript Document
var dlog;
var boxlanca;
var table;
$(document).ready(function(){										
	
    // Crio uma variável chamada $forms que pega o valor da tag form
    $forms = $('form[id="relaciona"]');	
    $forms.bind('submit', function(){
		var logd;
        var params = $(this.elements).serialize();
		
		var result = verificarrelacionamentos(params);
		
		if(result > 0){
			alert('Faltam relacionar '+result+'');
			return false;
		}
			
		if($("#cfop").val() > 5000){
			alert('CFOP informada não é valida para operações de entrada!');	
			$("#cfop").focus();
			return false;
		}
				
		if($("#cfop").val() == ""){
			alert('O campo cfop não pode estar em branco!!');	
			$("#cfop").focus();
			return false;
		}
		
		if($("#cfop").val() > 2000 && $("#cfop").val() < 3000){
			if($("#vlguiast").val() == ''){
				var cof = confirm("Valor da Guia ST está vazio, deseja manter sem informação da Guia ST ? ");
				if(cof == false){
					return false;	
				}
			}
		}

		var data_inicio   = $("#dtentrada").val();
		var data_fim      = $("#nota_dtemi").html();		
		
		if(data_inicio == ""){
			alert("O campo data não pode estar em branco!");
			$("#dtentrada").focus();
			return false;
		}
				
		
		var compara1 = parseInt(data_inicio.split(".")[2].toString() + data_inicio.split(".")[1].toString() + data_inicio.split(".")[0].toString());		
		var compara2 = parseInt(data_fim.split(".")[2].toString() + data_fim.split(".")[1].toString() + data_fim.split(".")[0].toString());
		
		if(compara1 == ""){
			alert("O campo data não pode estar em branco!");
			$("#dtentrada").focus();
			return false;
		}else{
			
			if(compara1 < compara2){
				alert('Data de entrada não pode ser menor que a data da emissão!');	
				return false;
			}
			
		}
	
		var totvalornota = verificavaloresdanota($("input[name='arquivo']").val());	
					
        var self = this;
        $.ajax({
            type: 'POST',
             url: '../php/relacionamento-exec.php?act=inserir&novovalortotnota ='+totvalornota+'',
            data: params,
			cache: false,
			dataType: 'json',
            // Antes de enviar
            beforeSend: function(){             	
				logd = $.dialog({
					title: '<img src="../images/icon.ico" width="15" /> Sistema',
					closeIcon: false,
					content: 'Aguarde<img src="../images/loader19.gif"/> enquanto salvo suas alterações ;D!',
				});	
            },
            success: function(data){				
	             logd.close();
				
			if(data[0].cd == '1'){ 				
				 //alert(data[0].msg);
				
				var dtemi 	   = $("#dt_emissao").html();
				var cfor  	   = $("#cfor").val();
				var numeronota = $("#nota_numeros").html();
				var totalnota  = $("#valor_tot").html();
				var arquivo    = $("input[name='arquivo']").val();
				var cfop	   = $("#cfop").val();
						
				var array = [{"empresa":"0001","cedente":""+cfor+"","emissao":""+dtemi+"","tipo":"DP","numeronota":""+numeronota+"","totalnota":""+totalnota+"","arquivo":""+arquivo+"","cfop":""+cfop+""}];
				
				if(data[0].tipo == 'N'){
					mostrarduplics(arquivo,array);						
						
									
					$('#dtapropria').dataTable({					
						"bSort" : false,
						"paging":   false,
						"ordering": false,
						"info":     false,
						"bDestroy": true,
						"bFilter": false
					});
				}else if(data[0].tipo == 'S'){

					
					
						var vlapropriadotot = parseFloat($("#valor_tots").val());
						$("#valor_totpropria").html(number_format(vlapropriadotot,2,',','.'));
						$("#valor_tots").val(parseFloat(vlapropriadotot.toFixed(2)));
						
						$('#dtapropria').dataTable({					
							 "bSort" : false,
							 "paging":   false,
							 "ordering": false,
							 "info":     false,
							 "bDestroy": true,
							 "bFilter": false
						});
						
						 $('#apropria').css('width', '48%');
						 $('#apropria').modal({
							 show    : true,
							 keyboard: false,
							 backdrop: 'static'
						});
							
					

				}else{
					mostrarduplics(arquivo,array);						
						
									
					$('#dtapropria').dataTable({					
						"bSort" : false,
						"paging":   false,
						"ordering": false,
						"info":     false,
						"bDestroy": true,
						"bFilter": false
					});
				}
				
				
				}else{
					
					alert(data[0].msg);
					window.location.href = '../php/importacao.php';
				}
				
			 },
            error: function(jqXHR, textStatus, errorThrown){
				
				var view = '<div class="message error">' + jqXHR.responseText + '</div>';
				$(".msg").html(view);
				$(".message").effect("bounce"); 
				logd.close();              
				
            }
        })
        return false;
    });
});

$(document).ready(function(){			
	var pathArray = window.location.pathname.split('/');
	var newPathname = "";
	for (i = 0; i < pathArray.length; i++) {
	  newPathname += "/";
	  newPathname += pathArray[i];
		//alert(pathArray[i]);
		if(pathArray[i] == 'admin.php' || pathArray[i] == 'importacao.php'){
			getNfesColetados();
		}
	}	
});


function mostrarduplics(arquivo,array){
	
		
			$.ajax({
				type:'POST',
				url:"../php/duplic-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'mostra',arquivo: arquivo},
				success: function(data){
					 var html = "";
					 
					 if(data[0].msg != '0'){ 
					 for(i =0; i < data.length; i++){
					 	
						html += '<strong>Numero Da Duplicata: </strong>'+data[i].nDup+' <br/>';
						html += '<strong>Data vecimento Da Duplicata:</strong> '+data[i].dVenc+' <br/>';
						html += '<strong>Valor Da Duplicata:</strong> '+data[i].vDup+'<br/>';
						html += '------------------------------------------------- <br/>';	
					 
					 }
													
					var cf = $.confirm({
						title: 'Duplicatas a serem inseridas',
						content: ''+html+'',
						type: 'green',
						typeAnimated: true,
						buttons: {
							tryAgain: {
								text: 'FINALIZAR',
								btnClass: 'btn-green',
								action: function(){
									
									$.ajax({
									type:'POST',
									url:"../php/duplic-exec.php",
									cache: false,
									dataType: 'json',
									data:{act:'inserir',dados: array},
									success: function(data){
										
										if(data[0].tipo == 'ok'){
											//alert(data[0].msg);

											$.confirm({
												title: 'Inserido com sucesso!',
												content: ''+data[0].msg+'<br><img src="../images/59362-sucess-page.gif" style="width: 208px;margin: auto 0px 0px 73px;" />',
												type: 'green',
												typeAnimated: true,
												buttons: {
													VAMOS: {
														text: 'Vamos para próxima',
														btnClass: 'btn-green',
														action: function(){
															$("#nota_dados").hide();
															$("#for_dados").hide();
															$("#total_dados").hide();
															$("#btnaction").hide();
															$("#subimp").attr('disabled',true);
															$("#msg").hide();
															$("#cfop").val('');
															$("#file_upload2").val('');
															$("#file_upload2").focus();
															bootbox.hideAll(); 
															$('#apropria').modal('hide');
															window.location.href = '../php/importacao.php';
														}
													}
												}
											});
											 
											
											cf.close();
										}else if(data[0].tipo == 'not'){
											alert(data[0].msg);
										}
																							
										
									}	
								});
										
								return false;
									
								}
							},							
						}
					});
					
					
					 }else{
						
						bootbox.confirm("Não existem duplicatas, deseja inserir duplicatas?", function(result) {
								
							if(result == true){
								//aqui vai inserir as duplicatas
								inserirduplicatas(array);	
							}else{
								$.confirm({
									title: 'Inserido com sucesso!',
									content: ''+data[0].msg+'<br><img src="../images/59362-sucess-page.gif" style="width: 208px;margin: auto 0px 0px 73px;" />',
									type: 'green',
									typeAnimated: true,
									buttons: {
										VAMOS: {
											text: 'Vamos para próxima',
											btnClass: 'btn-green',
											action: function(){
												$("#nota_dados").hide();
												$("#for_dados").hide();
												$("#total_dados").hide();
												$("#btnaction").hide();
												$("#subimp").attr('disabled',true);
												$("#msg").hide();
												$("#cfop").val('');
												$("#file_upload2").val('');
												$("#file_upload2").focus();
												bootbox.hideAll(); 
												$('#apropria').modal('hide');
												window.location.href = '../php/importacao.php';
											}
										}
									}
								});
								
								
							}	
								
						}); 
						
						
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					
					var view = '<div class="message error">' + jqXHR.responseText + '</div>';
					$("#msg").html(view);
					$(".message").effect("bounce");
				}	
			});
	
	}
$(function()
{
	// Variável para armazenar seus arquivos
	var files;

	// adicionado o evento
	$('input[type=file]').on('change', prepareUpload);
	
	$('form[id="upload"]').on('submit', uploadFiles);

	// Pegue os arquivos e colocá-las à nossa variável
	function prepareUpload(event)
	{
		files = event.target.files;
	}

	// Pegar o envio de formulário e enviar os arquivos
	function uploadFiles(event)
	{
		event.stopPropagation(); // Pare de coisas acontecendo
        event.preventDefault(); // Totalmente parar coisas acontecendo

        // / Iniciar um spinner CARREGANDO AQUI

        // Criar um objeto FormData e adicionar os arquivos
		var data = new FormData();
		$.each(files, function(key, value)
		{
			data.append(key, value);
		});
        
        $.ajax({
            url: '../php/relacionamento-exec.php?act=box&files',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, 
            contentType: false, 
            success: function(data, textStatus, jqXHR)
            {
	
            	if(typeof data.error === 'undefined')
            	{
            		// Sucesso até chamar a função para processar o formulário
            		submitForm(event, data);
            	}
            	else
            	{
					var view = '<div class="message error">' + data.error + '</div>';
					$("#msg").html(view);
					$(".message").effect("bounce");	            		
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	
            	var view = '<div class="message error">' + jqXHR.responseText + '</div>';
				$("#msg").html(view);
				$(".message").effect("bounce");
            	
            }
        });
    }

    function submitForm(event, data)
	{
		// Create a jQuery object from the form
		$form = $(event.target);
		
		// Serialize the form data
		var formData = $form.serialize();
		
		// You should sterilise the file names
		$.each(data.files, function(key, value)
		{
			formData = formData + '&filenames[]=' + value;
		});

		$.ajax({
			url: '../php/relacionamento-exec.php?act=box',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
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
					table += '<td style="width: 7%;"><input type="hidden" name="item['+i+'][cProd]" value="'+dados.cProd+'"/>'+dados.cProd+'</td>';
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
								
				}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	// Handle errors here
				var view = '<div class="message error">' + jqXHR.responseText + '</div>';
				$("#msg").html(view);
				$(".message").effect("bounce");
				
				//$('#msg').html(''+jqXHR.responseText+'');
            },
            complete: function()
            {
            	// STOP LOADING SPINNER
            }
		});
	}
});

function relacionarproduto(idfor,ifpro,i,idrel,act,npc){
		
		var idfornec  = idfor;
		var idprod    = ifpro;
		var idprodrel = idrel;
		var QTDPUN    = 1;
		var id        = "";
		var unpro	  = "";
		var cpu		  = $("input[name='item["+i+"][vProd]']").val() / QTDPUN; 
		var desc	  = "";
		var idcfop	  = $('#cfop').val();
		var nomcfop   = "";
		var NPEC_CX	  = npc == undefined ? 0 : npc;	
		var vator     = 2;

		if($("#dtentrada").val() == ''){
			$("#dtentrada").focus();
			$("#dtentrada").addClass('inputerr');
			return false;
		}
		
		if($("#cfop").val() == ''){
			$("#cfop").focus();
			$("#cfop").addClass('inputerr');
			return false;
		}

		if(idrel != ""){
			
			var data = ListaAlteracaoRelacionamento(idrel);
			
			idfornec  = data['idfornec'];
			idprod	  = data['idprod'];
			idprodrel = data['idprodrel'];
			QTDPUN    = data['QTDPUN'];
			id 		  = data['id'];	
			unpro	  = data['unpro'];
			desc	  = data['desc'];	
			cpu		  = data['vator'] == 1 ? $("input[name='item["+i+"][vProd]']").val() * QTDPUN : $("input[name='item["+i+"][vProd]']").val() / QTDPUN;
			idcfop    = data['idcfop']; 
			nomcfop   = data['Nomecfop'];
			NPEC_CX   = data['NPEC_CX'] == undefined ? 0 : data['NPEC_CX']; 
			vator     = data['vator'];
		}

		var vsubtotal = $("input[name='item["+i+"][vSubProd]']").val();
		var qCom      = $("input[name='item["+i+"][qCom]']").val();
		var cfopnota  = $("input[name='item["+i+"][CFOP]']").val();

		var chek1 = "";
		var chek2 = "";

		if(vator == 2){
			chek2 = "checked";
		}else{
			chek1 = "checked";
		}

		if(vator == 1){
			//multiplicação
			var soma = parseFloat(qCom) * parseFloat(QTDPUN);
			var estoq	  = parseFloat(vsubtotal) /  parseFloat(soma);
		
		}else{
			var soma      = parseFloat(qCom) / parseFloat(QTDPUN);
			var estoq	  = parseFloat(vsubtotal) /  parseFloat(soma);			
		}


		var html = "";
									
		html += '<dl class="dl-horizontal">';
			html += '<dt>Produto Fornecedor</dt>';
			html += '<dd>'+$("input[name='item["+i+"][xProd]']").val()+'</dd>';
			html += '<dt>Unidade</dt>';
			html += '<dd>'+$("input[name='item["+i+"][uTrib]']").val()+'</dd>';
			html += '<dt>Quantidade Nota:</dt>';
			html += '<dd>'+$("input[name='item["+i+"][qCom]']").val()+'</dd>';
			html += '<dt>Valor Unitario</dt>';
			html += '<dd id="">'+number_format($("input[name='item["+i+"][vProd]']").val(),2,',','.')+'</dd>';
		html += '</dl>';
		
		html += "<form method='post' id='relacionaproduto' onSubmit='submeterformulario(this); return false;'>";

		html += "<input type='hidden' name='act' value='"+act+"'/>"
		html += "<input type='hidden' name='idfor' value='"+idfornec+"'/>"
		html += "<input type='hidden' name='idpro' value='"+idprod+"'/>"
		html += "<input type='hidden' name='id' value='"+i+"'/>"
		html += "<input type='hidden' name='idrel' value='"+id+"'/>"
		html += "<input type='hidden' name='unforn' value='"+$("input[name='item["+i+"][uTrib]']").val()+"'>";	
		html += "<input type='hidden' name='vSubProd' value='"+$("input[name='item["+i+"][vSubProd]']").val()+"'>";	
		html += "<input type='hidden' name='cfopnota' value='"+cfopnota+"'>";	
		html += "<input type='hidden' name='xProd' value='"+$("input[name='item["+i+"][xProd]']").val()+"'>";

			if($('#cfopsn').val() == 1){					
				html += "<div class='input-prepend'>";	
					html += "<label>CFOP:<i class='cfo'></i></label>";	
					html += "<span class='add-on icon-terminal'></span>";
					html += "<input type='text' name='cfopr' class='form-control span3' style='height: 33px;' required id='cfop1' value='"+idcfop+"'>";	
				html += "</div>";	
				}else{
					
				html += "<div class='input-prepend'>";	
					html += "<label>CFOP:<i class='cfo'>"+nomcfop+"</i></label>";	
					html += "<span class='add-on icon-terminal'></span>";
					html += "<input type='text' name='cfopr' style='height: 33px;' class='form-control span3' required id='cfop1' value='"+idcfop+"'>";	
				html += "</div>";							
			}
		
		html += "<div class='input-prepend'>";
			
			html += "<label>Produto:<i id='npr'>"+idprodrel+" "+desc+"</i></label>";	
			html += "<span class='add-on icon-terminal'></span>";
			html += "<input type='text' name='produto' style='height: 33px;' class='form-control span3' onKeyPress='buscaproduto();' id='codproduto' value='"+idprodrel+"' >";
			html += "<input type='hidden' name='nomepro' id='nomepro' value='"+desc+"'/>";
			html += "<input type='hidden' name='idprod' id='idprod' value='"+idprodrel+"'/>";			
			html += "<input type='hidden' name='unprod' id='unprod' value='"+unpro+"'/>";	
		html += "</div>";
		
		html += "<div class='input-prepend span4' style='margin: 0;'>";			
			html += "<label><strong>Opção:</strong></label>";
			html += "<label style='display: inline-block; margin: 5px;'><input type='radio' name='vator' value='2' "+chek2+" style='margin-right: 5px;'/>Divisão</label>";				
			html += "<label style='display: inline-block; margin: 5px;'><input type='radio' name='vator' value='1' "+chek1+" style='margin-right: 5px;'/>Multiplicação</label>";									
			html += "";
		html += "</div>";	

		html += "<div class='input-prepend'>";			
			html += "<label>Quantidade p/ unidade</label>";	
			html += "<span class='add-on icon-terminal'></span>";
			html += "<input type='text' name='qtdpun' class='form-control span2' style='height: 33px;' onKeyUp='somacustopunidade(this);' id='qtdpun' value='"+QTDPUN+"' >";					
		html += "</div>";
				
		html += "<div class='input-prepend'>";			
			html += "<label>Nº CX/PC</label>";	
			html += "<span class='add-on icon-terminal'></span>";
			html += "<input type='text' name='cxpc' class='form-control span2 text-right' style='height: 33px;' id='cxpc' value='"+NPEC_CX+"' >";					
		html += "</div>";	
		

		html += '<dl class="dl-horizontal">';
			html += '<dt>Custo p/ Unidade:</dt>';
			html += '<dd id="cpu">'+number_format(estoq,2,',','.')+'</dd>';
			html += '<dt>Estoque:</dt>';
			html += '<dd id="cpuest">'+number_format(soma,2,',','.')+'</dd>';
		html += '</dl>';
		
		
		
		html += "<input class='btn btn-large btn-block btn-primary' type='submit' name='submit'  value='Relacionar'>";
		
		html += "</form>";
		
		dlog = $.confirm({
			title: 'Relacionar Produto',
			content: ''+html+'',
			type: 'green',
			typeAnimated: true,
			columnClass: 'col-md-5 col-md-offset-4 col-xs-4 col-xs-offset-8',
			buttons: {
				tryAgain: {
					text: 'Fechar',
					btnClass: 'btn-orange',
					action: function(){
					}
				}
			}
		});
		
		
}
function inserirduplicatas(array){
		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
	
		var yyyy = today.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		} 
		var today = dd+'.'+mm+'.'+yyyy;
		
		
		var html = "";
		html += "<strong>Valor Total da Nota:</strong> <span id='vltn'>"+array[0].totalnota+"</span> "
		html += "<form method='post' id='duplic' onSubmit='formularioduplic(this); return false;'>";
		
		html += "<input type='hidden' name='act' value='inserir'/>"
		
		for(i = 0; i < array.length; i++){
			
			html += "<input type='hidden' name='dados["+i+"][empresa]' value='"+array[i].empresa+"'/>";
			html += "<input type='hidden' name='dados["+i+"][cedente]' value='"+array[i].cedente+"'/>";
			html += "<input type='hidden' name='dados["+i+"][emissao]' value='"+array[i].emissao+"'/>";
			html += "<input type='hidden' name='dados["+i+"][tipo]' value='"+array[i].tipo+"'/>";
			html += "<input type='hidden' name='dados["+i+"][numeronota]' value='"+array[i].numeronota+"'/>";
			html += "<input type='hidden' name='dados["+i+"][totalnota]' value='"+array[i].totalnota+"'/>";
			html += "<input type='hidden' name='dados["+i+"][arquivo]' value='"+array[i].arquivo+"'/>";
			html += "<input type='hidden' name='dados["+i+"][cfop]' value='"+array[i].cfop+"'/>";
		}
		html += "<input type='hidden' name='parcela' id='parcela' value='1'/>";
		html += "<input type='hidden' name='comparavalor' id='comparavalor' value='"+array[0].totalnota+"'/>";		
		html += "<div class='input-prepend'>";			
			html += "<label>Numero Duplicata:<i id='npr'></i></label>";	
			html += "<span class='add-on icon-th-large'></span>";
			html += "<input type='text' name='nDup' value='"+array[0].numeronota+"' class='span3' id='nDup' >";			
		html += "</div>";
		html += "<div class='input-prepend'>";			
			html += "<label>Data vencimeto Duplicata:<i id='npr'></i></label>";	
			html += "<span class='add-on icon-calendar'></span>";
			html += "<input type='text' name='dVenc'  value='"+today+"' onClick='mostradataparaduplic();' class='span3' id='dVenc' >";			
		html += "</div>";
		
		html += "<div class='input-prepend'>";			
			html += "<label>Valor Duplicata:<i id='npr'></i></label>";	
			html += "<span class='add-on icon-usd'></span>";
			html += "<input type='text' name='vDup' value='"+array[0].totalnota+"' onClick='mask();' class='span3' id='vDup' >";			
		html += "</div><br/><br/>";
		
		
		html += "<input class='btn btn-primary' type='submit' name='submit'  value='Salvar'>";
		
		html += "</form>";
		
		bootbox.dialog({
		  message: ""+html+"",
		  title: "Inserir Duplicatas",
		  closeButton:false,								  
		});
	

}
function buscaproduto(){
	
	$("#codproduto").autocomplete(
		{	
		 source:'../php/produto-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$("#npr").html(ui.item.nom)			
			$("#nomepro").val(ui.item.nom);		
			$("#unprod").val(ui.item.uni);
			$("#idprod").val(ui.item.cod);
		},
		focus: function( event, ui ) {
			$("#npr").html(ui.item.nom);
			$("#unprod").val(ui.item.uni);
		}		
	});

}

function submeterformulario(elemento){
	var dialog;
	if($("#idprod").val() == ''){
		alert('Deve informar um produto e selecionar!');
		$("#nomepro").focus();
		$("#nomepro").select();
		return false;
	}
	// Crio uma variável chamada $forms que pega o valor da tag form
    $forms = $('form[id="relacionaproduto"]');	

    //var params = $forms.elements;
		//params.serialize()
		var params = $(elemento.elements).serialize();
		       
	    $.ajax({
            type: 'POST',
             url: "../php/relacionamento-exec.php",
            data: params,
			cache: false,
            dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
             	
				 dialog = $.dialog({
						title: '<img src="../images/icon.ico" width="15" /> Sistema',
						closeIcon: false,
						content: 'Aguarde<img src="../images/loader19.gif"/> enquanto salvo suas alterações ;D!',
					});	
            },
            success: function(data){
				
	          
				 $("tr[id='"+data.idpro+"'] > td[id='rel"+data.idpro+""+data.id+"']").html('<input type="hidden" name="item['+data.id+'][IDPROD_REL]" value="'+data.produto+'"/>'+data.produto+' - '+data.nomepro+'     <a href="#" class="separapencil" onClick="relacionarproduto(\''+data.idfor+'\',\''+data.idpro+'\','+data.id+',\''+data.idrel+'\',\'altrelacionar\',\''+data.ncabecaqtd+'\');"><i class="icon-pencil"></i></a>');				 
				 dialog.close();
				 dlog.close();
				 /*
				 var pos = $('#dyntable tbody tr[id="'+data.idpro+'"]').position().top;
				 $('html, body').animate({
					 scrollTop: pos											
				 }, 1000);*/
				 $('html, body').animate({
					scrollTop: $('#dyntable tbody td:contains("' + data.idpro + '")').offset().top - 140
				  }, 'slow');
				 $('#dyntable tbody tr[id="'+data.idpro+'"]').animate({backgroundColor: '#3d9400','color':'#fff'}, 'slow');				 
					
			 },
             error: function(jqXHR, textStatus, errorThrown){
				 
				 $.dialog({
					title: '<img src="../images/icon.ico" width="15" /> Sistema',
					content: ''+jqXHR.responseText+'',
					closeIcon: true,
				});
				dialog.close();
            }
        });
	   
	 return false;
}
function formularioduplic(elemento){

	// Crio uma variável chamada $forms que pega o valor da tag form
    $forms = $('form[id="duplic"]');	
			
    //var params = $forms.elements;
		//params.serialize()
		var params = $(elemento.elements).serialize();
		   
		var comparavalor = convertevalores($("#comparavalor").val());
		var vd			 = convertevalores($("#vDup").val());
		var valor 		 =  comparavalor - vd;
		
		
		
		if(vd > valor && valor != 0){
			
			alert('Valor incoreto!');
			return false;
		}
		$("#comparavalor").val(number_format(valor,2,',','.'));

	    $.ajax({
            type: 'POST',
             url: "../php/duplic-exec.php",
            data: params,
			cache: false,
            dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
             	
            },
            success: function(data){				
	            
				if(data[0].tipo == 'ok'){					
				alert("Duplicata inserido(a) com sucesso!");
				if(valor == 0){

					$.confirm({
						title: 'Duplicata inserido(a) com sucesso!',
						content: ''+data[0].msg+'<br><img src="../images/59362-sucess-page.gif" style="width: 208px;margin: auto 0px 0px 73px;" />',
						type: 'green',
						typeAnimated: true,
						buttons: {
							VAMOS: {
								text: 'Vamos para próxima',
								btnClass: 'btn-green',
								action: function(){
									$("#nota_dados").hide();
									$("#for_dados").hide();
									$("#total_dados").hide();
									$("#btnaction").hide();
									$("#subimp").attr('disabled',true);
									$("#msg").hide();
									$("#cfop").val('');
									$("#file_upload2").val('');
									$("#file_upload2").focus();
									bootbox.hideAll(); 
									$('#apropria').modal('hide');
									window.location.href = '../php/importacao.php';	
								}
							}
						}
					});
													
				}
				
				if(parseFloat($("#vDup").val()) != parseFloat($("#valor_tot").html())){
					
					var vare  = "";						
					$("#nDup").val("");
					var part  = parseInt($("#parcela").val()) + 1;	
					var ndup  = $("#nota_numeros").html();				
					vare  = ndup+'/'+part+'';							
					$("#nDup").val(vare);					
					$("#parcela").val(part);
					
						
				}else{
										
					$.confirm({
						title: 'Duplicata inserido(a) com sucesso!',
						content: ''+data[0].msg+'<br><img src="../images/59362-sucess-page.gif" style="width: 208px;margin: auto 0px 0px 73px;" />',
						type: 'green',
						typeAnimated: true,
						buttons: {
							VAMOS: {
								text: 'Vamos para próxima',
								btnClass: 'btn-green',
								action: function(){
									$("#nota_dados").hide();
									$("#for_dados").hide();
									$("#total_dados").hide();
									$("#btnaction").hide();
									$("#subimp").attr('disabled',true);
									$("#msg").hide();
									$("#cfop").val('');
									$("#file_upload2").val('');
									$("#file_upload2").focus();
									bootbox.hideAll(); 
									$('#apropria').modal('hide');
									window.location.href = '../php/importacao.php';
								}
							}
						}
					});

				}				
						
				}else if(data[0].tipo == 'not'){
					alert(data[0].msg);
				}
					 
				
				// bootbox.hideAll(); 
			 },
            error: function(jqXHR, textStatus, errorThrown){
                var view = '<div class="message error">' + jqXHR.responseText + '</div>';
				$("#msg").html(view);
				$(".message").effect("bounce"); 
            }
        });
	   
	 return false;
}
function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}


function verificarrelacionamentos(items){
	var retorno;
	$.ajax({
            type: 'POST',
             url: '../php/relacionamento-exec.php?act=verificarelacionamento',
             data: items,
			 async:false,
             cache: false,
             dataType: 'json', 
            success: function(data){								
				retorno = data.length;
			 },
            error: function(data){
                
            }
        });
      return retorno;

}
function buscacentro(){
	var cfor = $("#cfor").val();
    $( "#centro" ).autocomplete(
		{	
		 source:'../php/centro-exec.php?act=busca&cfor='+cfor+'',
		 minLength: 1,
		select: function(event, ui) {
			$("#xcentro").val(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
		}	
	});
}
function buscafinanccentro(){

	 var ccentro = $("#centro").val();
    $("#conta").autocomplete(
		{	
		 source:'../php/conta-exec.php?act=busca&ccont='+ccentro+'',
		 minLength: 1,
		select: function(event, ui) {
			$("#xconta").val(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
		}	
	});


}


/*$(document).ready(function(){
	
    // Crio uma variável chamada $forms que pega o valor da tag form
   $("#apm").click(function(){	
			
		var centro  = $("#centro").val();
		var conta   = $("#conta").val();
		var valor   = $("#vl").val();
		var nota    = $("#nota_numeros").html();
		var cfor    = $("#cfor").val();
		var empresa = "-1";
		var emissao = $("#dt_emissao").html();
		var xcentro = $("#xcentro").val();
		var xconta	= $("#xconta").val();
					
        $.ajax({
            type: 'POST',
             url: '../php/apropriacao-exec.php?act=inserir',
            data: {centros:centro,contas:conta,vl:valor,notas:nota,cfors:cfor,emp:empresa,dtemi:emissao,xcentros:xcentro,xcontas:xconta },
			cache: false,
            dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
             	bootbox.dialog({
				  message: "Aguarde..",
				  title: "<img src='../images/loading-icon.gif'/> <br/>Aguarde..",								  
				});
            },
            success: function(data){				
	             bootbox.hideAll();
				 
				 var html = "";
				 var subtotal = 0;
				$("#dtapropria tbody tr .dataTables_empty").remove(); 
				 for(i =0; i < data.length; i++){
					
						 html += '<tr id="'+data[i].cod+'">';
						 html += '<td><span class="center"><input type="checkbox" name="id[]" class="cinput" value="'+data[i].cod+'" /> </span></td>';
						 html += '<td>'+data[i].centros+' - '+data[i].xcentros+'</td>';
						 html += '<td>'+data[i].contas+' - '+data[i].xcontas+'</td>';
						 html += '<td>'+data[i].vl+'<input type="hidden" id="val'+data[i].cod+'" value="'+data[i].vl+'"/></td>';
					     html += '</tr>';					
						subtotal = 	parseFloat(data[i].vl) + parseFloat($("#valortotal").val());
						//alert(''+data[i].vl+' - '+$("#valortotal").val()+'')
				 }
				 
				var subt = subtotal;
				
				$("#valortotal").val(subt);
				
				$("#subtotal").html('');					
				$("#subtotal").html('Valor Apropriado: '+number_format(subt,2,',','.'));
									
				$("#dtapropria tbody").append(html);
								
				$('#vl').val('');
				$('#vl').focus();
				$('#centro').val('');
				$('#conta').val('');
				
			 },
            error: function(jqXHR, textStatus, errorThrown){
                var view = '<div class="message error">' + jqXHR.responseText + '</div>';
				$("#msg").html(view);
				$(".message").effect("bounce"); 
            }
        })
        return false;
    });
});*/

$(document).on('click', '#apm', function() { 
			
	var centro  = $("#centro").val();
	var conta   = $("#conta").val();
	var valor   = $("#vl").val();
	var valor2  = $("#valor_tots").val(); 
	
	//alert('valor: '+valor+'\n'+valor2+'');
	//valor em reais apos informar o percentual de rateio
	
	var nota    = $("#nota_numeros").html();
	var cfor    = $("#cfor").val();
	var empresa = "1";
	var emissao = $("#dt_emissao").html();
	var xcentro = $("#xcentro").val();
	var xconta	= $("#xconta").val();
				
	$.ajax({
		type: 'POST',
		 url: '../php/apropriacao-exec.php?act=inserir',
		data: {centros:centro,contas:conta,vl:valor,notas:nota,cfors:cfor,emp:empresa,dtemi:emissao,xcentros:xcentro,xcontas:xconta },
		cache: false,
		dataType: 'json',
		// Antes de enviar
		beforeSend: function(){
			 bootbox.dialog({
			  message: "Aguarde..",
			  title: "<img src='../images/loading-icon.gif'/> <br/>Aguarde..",								  
			});
		},
		success: function(data){				
			 bootbox.hideAll();
			 
			 var html = "";
			 var subtotal = 0;
			 var i = 0;
			$("#dtapropria tbody tr .dataTables_empty").remove(); 
			if(data[i].tipo == '1'){ 
			// for(i =0; i < data.length; i++){
				
					 html += '<tr id="'+data[i].cod+'">';
					 html += '<td><span class="center"><input type="checkbox" name="id[]" class="cinput" value="'+data[i].cod+'" /> </span></td>';
					 html += '<td>'+data[i].centros+' - '+data[i].xcentros+'</td>';
					 html += '<td>'+data[i].contas+' - '+data[i].xcontas+'</td>';
					 html += '<td>'+number_format(parseFloat(data[i].vl),2,',','.')+'<input type="hidden" id="val'+data[i].cod+'" value="'+data[i].vl+'"/></td>';
					 html += '</tr>';					
					 subtotal = 	parseFloat(data[i].vl) + parseFloat($("#valortotal").val());
					//alert(''+data[i].vl+' - '+$("#valortotal").val()+'')
			 //}
			 
			var subt = subtotal;
			
			$("#valortotal").val(subt);
			
			$("#subtotal").html('');					
			$("#subtotal").html('Valor total: '+number_format(subt,2,',','.'));
								
			$("#dtapropria tbody").append(html);
							
			$('#vl').val('');
			$('#vl').focus();
			$('#centro').val('');
			$('#conta').val('');
			$(".mp").html('');
			$(".mpc").html('');
			}else if(data[i].tipo == '2'){
				alert("Selecione um centro custo valido para este fornecedor!");
				
				$("#centro").val('');					
				$("#centro").focus();					
				$("#conta").val('');
				$("#centro").trigger("enterKey");
			
			}else if(data[i].tipo == '3'){
				
				alert("Informe uma conta Válida!");
				$("#conta").val('');
				$("#conta").focus();
				$("#conta").trigger("enterKey");
			}
			
		 },
		error: function(data){
			
		}
	})
	return false;    
});

function deletar(){

		var files = '';
		var array = [];
   	    var subtotal;
		
		$(".cinput:checked").each(function(){
		//if(this.checked) {
			files = this.value;
			//ids = array.push(files);
			array.push(files);				
		//}
		});
		//alert(array);
		var conf = confirm('Continue delete?');
	    if(conf){
                
					
			$.ajax({
				type:'POST',
				url:"../php/apropriacao-exec.php",
				cache: false,
	            dataType: 'json',
				data:{act:'deletar',id: array},
				success: function(data){
					
				 for(i =0; i < data.length; i++){
					 
					 //alert(''+$("#valortotal").val()+' '+$("#val"+data[i].cod+"").val()+' ');
					 subtotal = parseFloat($("#valortotal").val()) - parseFloat($("#val"+data[i].cod+"").val());
					 
					$("#dtapropria tbody tr[id='"+data[i].cod+"']").remove();										
					$("#valortotal").val(subtotal);
				}
				
					
					
					$("#subtotal").html('');					
					$("#subtotal").html('Valor Apropriado: '+number_format(subtotal,2,',','.'));
					
				}	
			});
					
			return false;		
		}
	    
		
}


function valoremtemporeal(){
	
	var valortot = parseFloat($("#valor_tot").html());	
	var valor    = valortot - parseFloat($("#vDup").val());
		
	$("#vltn").html(number_format(valor,2,',','.'));
}

$(document).ready(function(e) {
    $(".close").click(function(){
		window.location.href = 'importacao.php';
	});
});

function convertevalores(valor2){
	if(valor2.length > 2 && valor2.length <= 6){
			var valstr2 = parseFloat(valor2.replace(",","."));
	}else{
		var valstr2 = parseFloat(valor2.replace(",",".").replace(".",""));
	}
	
	return valstr2.toFixed(2);
}


function somacustopunidade(data){
	
	//console.log(vator+' - '+qtdv+' - '+$("input[name='item["+$("input[name='id']").val()+"][vProd]']").val());
	var qtd       = data.value;
	var qtdv      = qtd.replace(',','.');
	var vator     = $('input[name="vator"]:checked').val();
	var vsubtotal = $("input[name='item["+$("input[name='id']").val()+"][vSubProd]']").val();
	var qCom      = $("input[name='item["+$("input[name='id']").val()+"][qCom]']").val();
	

	if(qtdv < 1){
		return false;
	}else{
		//alert($("input[name='id']").val());
		if(vator == 1){
			//multiplicação
			var soma = parseFloat(qCom) * parseFloat(qtdv);
			var estoq	  = parseFloat(vsubtotal) /  parseFloat(soma);
		
		}else{
			var soma      = parseFloat(qCom) / parseFloat(qtdv);
			var estoq	  = parseFloat(vsubtotal) /  parseFloat(soma);			
		}
		
		//$('tr[id=""] td:eq(2)')
		//alert(soma);
		$("#cpu").html(number_format(estoq,2,',','.'));
		$("#cpuest").html(number_format(soma,2,',','.'));
		

	}
}

$(document).on('change','input[name="vator"]',function(){

	var qtd       = $('input[name="qtdpun"]').val();;
	var qtdv      = qtd.replace(',','.');
	var vator     = $(this).val();
	var vsubtotal = $("input[name='item["+$("input[name='id']").val()+"][vSubProd]']").val();
	var qCom      = $("input[name='item["+$("input[name='id']").val()+"][qCom]']").val();
	

	if(qtdv < 1){
		return false;
	}else{
		//alert($("input[name='id']").val());
		if(vator == 1){
			//multiplicação
			var soma = parseFloat(qCom) * parseFloat(qtdv);
			var estoq	  = parseFloat(vsubtotal) /  parseFloat(soma);
		
		}else{
			var soma      = parseFloat(qCom) / parseFloat(qtdv);
			var estoq	  = parseFloat(vsubtotal) /  parseFloat(soma);			
		}
		
		//$('tr[id=""] td:eq(2)')
		//alert(soma);
		$("#cpu").html(number_format(estoq,2,',','.'));
		$("#cpuest").html(number_format(soma,2,',','.'));
		

	}


});

function ListaAlteracaoRelacionamento(cod){
	
		var retorno;
		
		$.ajax({
			type:'POST',
			url:"../php/relacionamento-exec.php",
			async:false,
			cache: false,
			dataType: 'json',
			data:{act:'buscarelacionamento',id: cod},
			success: function(data){
														
				retorno = {idfornec:""+data[0].idfornec+"", 
							idprod:""+data[0].idprod+"", 
							idprodrel:""+data[0].idprodrel+"",
							QTDPUN:""+data[0].QTDPUN+"",
							id:""+data[0].id+"",
							unpro:""+data[0].unpro+"",
							desc:""+data[0].desc+"",
							idcfop:""+data[0].idcfop+"",
							Nomecfop:""+data[0].cfopnome+"",
							NPEC_CX:""+data[0].NPEC_CX+"",
							vator:data[0].vator};
				
			},
			error: function(data){
				alert(data.status);
			}	
		});
				
		return retorno;	
	
}

function verificavaloresdanota(param){

		var retorno;
		
		$.ajax({
			type:'POST',
			url:"../php/relacionamento-exec.php",
			async:false,
			cache: false,
			dataType: 'json',
			data:{act:'verificavalor',arquivo: param},
			success: function(data){
														
				if(data[0].msg == 1){
					
					var conf = confirm(""+data[0].aviso+"");
					
					if(conf == true){
						retorno = data[0].vNF;
					}else{
						retorno = data[0].soma;
					}
				}else{
					
					retorno = "";
					
				}
				
				//retorno ;
				
			},
			error: function(data){
				alert(data.status);
			}	
		});
				
		return retorno;	

}


$(document).ready(function(){
	$("#btnbuscansu").click(function(){
		VerificaNsuSefaz();
	});
});


$(document).on('click','.btnmanifest',function(){
	var id = $(this).parents('tr').attr('id');
	//alert(id);
	var htm = `
		<div class="msgnsu"></div>
		<div class="input-prepend">
			<label>Tipo Manifestação:</label>
			<select name="manifclik" id="manifclik" class="form-control">
				<option value="" selected>Informe uma operação</option>
				<option value="210200">Confirmação da Operação</option>
				<option value="210210">Ciência da Operação</option>
				<option value="210220">Desconhecimento da Operação</option>
				<option value="210240">Operação não Realizada</option>
			</select>
		</div>	
	`;
	$.confirm({
		title: 'Selecione um tipo de manifestação!',
		content: ''+htm+'',
		type: 'orange',
		typeAnimated: true,
		buttons: {
			tryAgain: {
				text: 'MANIFESTAR',
				btnClass: 'btn-green',
				action: function(){
					var codmanifest = this.$content.find('#manifclik option:selected').val();
					//alert(codmanifest+' - '+id);

					if(codmanifest == ''){
						var view = '<div class="message alert">Selecione um tipo de manifestação</div>';
						$(".msgnsu").html(view);
						$(".message").effect("bounce");
						return false;
					}

					ManifestarNota(id,codmanifest);
					

				}
			},
			close: function () {
			}
		}
	});	

});

function ManifestarNota(id,codmanifest){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState === 4){
			if(this.status === 200){
				console.log(this.response);
				var res = JSON.parse(this.responseText);
				$('.tbnfes tbody tr[id="'+res[0].id+'"]').removeClass('info');
				$('.tbnfes tbody tr[id="'+res[0].id+'"]').addClass(''+res[0].cor+'');
				
				$.confirm({
					title: 'Mensagem do sistema',
					content: 'Manifestado com sucesso !, <br> Deseja Importar a NF-e Manifestado(a) ?  ',
					type: 'orange',
					typeAnimated: true,
					buttons: {
						nao: {
							text: 'Não',
							btnClass: 'btn-red',
							action: function(){
							}
						},
						sim: {
							text: 'Sim',
							btnClass: 'btn-green',
							action: function(){
								$("#por_arquivo").hide();
								$("#por_nsu").hide();
								$("#por_barras").show();	

								$("select[name='manif'] option[value='210210']").attr("selected","selected");				
								document.getElementById('file_upload2').value = res[0].chave;
								$(".m-menu-notas li a").removeClass('selecionado');
								$("#subimp").click();

							}
						}
					}
				});

			}else{
				console.log(this.status+' - '+this.statusText);
				var view = '<div class="message error">' + this.responseText + '</div>';
				$(".msgnsu").html(view);
				$(".message").effect("bounce"); 
			}
		}
	};

	xhttp.open("POST",'../php/relacionamento2-exec.php?act=manifestnfeclick&id='+id+'&tpEvento='+codmanifest+'',true);
	xhttp.setRequestHeader("Content-Type","application/json");
	xhttp.send();
}

$(document).on('click','.btnnovasnfes',function(e){

	var htm = `
		<div class="nsu_form_callback"></div>
		<form id="formnfesbusca" autocomplete="off">
			<ul class="option-nfe">
				<li>					
					<label>
						<input type="radio" name="cknfe" id="cknfe1" checked value="1"/>
						Pesquisa a partir do último registro consultado
						<span class="help-block">A pesquisa fará a busca em relação ao último registro já consultado no aplicativo, mesmo que registro tenha sido excluído.</span>
					</label>

				</li>
				<li>
					<label>
						<input type="radio" name="cknfe" id="cknfe2" value="2"/>
						Pesquisa a partir de um registro (NSU) específico
						<span class="help-block">A pesquisa retornará pontualmente os documento associado ao NSU informado.</span>
						NSU: <input type="text" id="numero_nsu" name="numero_nsu" disabled/>
					</label>
				</li>
			</ul>		
			<button type="submit" class="btn btn-large btn-block btn-primary">Consultar Destinadas</button>
		</form>
	`;

	$.confirm({
		title: 'Busca novas NF-e',
		content: ''+htm+'',
		type: 'blue',
		typeAnimated: true,
		columnClass: 'col-md-6 col-md-offset-3 col-xs-4 col-xs-offset-8',
		buttons: {
			tryAgain: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
				}
			},
			
		}
	});
});

$(document).on("submit","#formnfesbusca",function(e){
	var form = $(this);
	//var action = form.attr("action");
	//var data = form.serialize();
	var option = $('input[name="cknfe"]:checked').val();
	if(option == 1){
		var data = {
			act:'listapornsu'
		}
	}else{
		var numnsu = $('#numero_nsu').val();
		if(numnsu.trim() == ''){
			alert('Informar um número');
			return false;
		}
		var data = {
			act:'nsuporum',
			nsu:numnsu
		}
	}
	
	$.ajax({
		url: '../php/relacionamento2-exec.php',
		data: data,
		type: "post",
		dataType: "json",
		beforeSend: function (load) {
			ajax_load("open");
		},
		success: function (su) {
			ajax_load("close");

			if (su.msg) {

				if(su.tipo == 1){
					var view = '<div class="message success">' + su.msg + '</div>';
				}else{
					var view = '<div class="message error">' + su.msg + '</div>';
				}
				
				$(".nsu_form_callback").html(view);
				$(".message").effect("bounce");
				
				getNfesColetados();
				
				return;
			}

			
		}
	});
	return false;
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


$(document).on('change','input[name="cknfe"]',function(){
	if($(this).val() == 2){
		$("#numero_nsu").attr('disabled',false);	
		$("#numero_nsu").focus();	
	}else{
		$("#numero_nsu").attr('disabled',true);
		$("#numero_nsu").val('');
	}
});

var psqnfe;
$(document).ready(function(){

	$('.btnfilternfe').click(function(){
		var htm = `
			<div class="psq_form_callback"></div>
			<form id="formpesquisanotas" autocomplete="off">
				<input type="hidden" name="act" value="pesquisanotas" />
				<div class="row">
					<div class="span6">
						<div class="input-prepend">
							<label>Data Inicial</label>
							<span class="add-on icon-calendar"></span>
							<input class="span2 padraoinputheight" id="dtini" name="dtini" value="" type="text">
						</div>
						<div class="input-prepend">
							<label>Data Final</label>
							<span class="add-on icon-calendar"></span>
							<input class="span2 padraoinputheight" id="dtfin" name="dtfin" value="" type="text">
						</div>
					</div>
					
					<div class="span6">
						<div class="input-prepend">
							<label>Situação da NF-e</label>
							<span class="add-on icon-list"></span>
							<select name="situanfe">
								<option value="">Todos</option>
								<option value="Sem">Sem Situação</option>
								<option value="Autorizado">Autorizada</option>
								<option value="Denegada">Denegada</option>
								<option value="Cancelada">Cancelada</option>
							</select>	
						</div>

						<div class="input-prepend">
							<label>Situação da Manifestação</label>
							<span class="add-on icon-list"></span>
							<select name="situamanifest">
								<option value="">Todos</option>
								<option value="Sem">Sem Manifestação do Destinatário</option>
								<option value="210200">Confirmação da Operação</option>
								<option value="210210">Ciência da Operação</option>
								<option value="210220">Desconhecimento da Operação</option>
								<option value="210240">Operação não Realizada</option>
							</select>	
						</div>
					</div>

					<div class="span6">
						<div class="input-prepend">
							<label>Série</label>
							<span class="add-on"></span>
							<input class="span1 padraoinputheight" id="serie" name="serie" value="" type="text" onkeypress="return somenteNumeros(event)">
						</div>
						<div class="input-prepend">
							<label>Número inicial</label>
							<span class="add-on icon-terminal"></span>
							<input class="span2 padraoinputheight" id="numeroini" name="numeroini" value="" type="text" onkeypress="return somenteNumeros(event)">
						</div>
						
						<div class="input-prepend">
							<label>Número Final</label>
							<span class="add-on icon-terminal"></span>
							<input class="span2 padraoinputheight" id="numerofim" name="numerofim" value="" type="text" onkeypress="return somenteNumeros(event)">
						</div>
					</div>
					<div class="span6">
						<div class="input-prepend">
							<label>CNPJ do Emitente</label>
							<span class="add-on icon-terminal"></span>
							<input class="span5 padraoinputheight" id="cnpjemit" name="cnpjemit" value="" type="text">
						</div>
					</div>

					<div class="span6">
						<div class="input-prepend">
							<label>Chave de acesso da NF-e</label>
							<span class="add-on icon-terminal"></span>
							<input class="span5 padraoinputheight" id="chavenfe" name="chavenfe" value="" maxlength="44" type="text" onkeypress="return somenteNumeros(event)">
						</div>
					</div>

				</div>	
				
				<button type="submit" class="btn btn-large btn-block btn-primary">Pesquisar</button>
			</form>
		`;

		psqnfe = $.confirm({
			title: 'Filtro',
			content: ''+htm+'',
			type: 'green',
			typeAnimated: true,
			columnClass: 'col-md-6 col-md-offset-3 col-xs-4 col-xs-offset-8',
			buttons: {
				tryAgain: {
					text: 'Fechar',
					btnClass: 'btn-red',
					action: function(){
						psqnfe.close();
					}
				},
				
			}
		});

	});

});

$(document).on("submit","#formpesquisanotas",function(e){
	
	var form = $(this);	
	var data = form.serialize();
	const filtros = form.serializeArray();
    const datas   = {}; // cria o objeto
	var   conta   = 0;
    // cria o JSON
    $(filtros).each(function(index, obj){
		if(obj.value != '' && obj.name != 'act'){
			datas[obj.name] = obj.value;
			conta++;
		}
    });
	
	if(conta == 0){
		var view = '<div class="message info">Selecione ao menos um campo para fazer o filtro</div>';								
		$(".psq_form_callback").html(view);
		$(".message").effect("bounce");	
		return false;
	}
	
	$.ajax({
		url: '../php/relacionamento2-exec.php',
		data: data,
		type: "post",
		dataType: "json",
		beforeSend: function (load) {
			ajax_load("open");
		},
		success: function (su) {
			ajax_load("close");
			
			if (su.length > 0) {
				ListarNfes(su);
				psqnfe.close();
			}else{
				
				var view = '<div class="message info">Nenhum registro encontrado com esse filtro</div>';								
				$(".psq_form_callback").html(view);
				$(".message").effect("bounce");								
				
				return;
			}

			
		}
	});
	return false;
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

function somenteNumeros(e) {
	var charCode = e.charCode ? e.charCode : e.keyCode;
	// charCode 8 = backspace   
	// charCode 9 = tab
	if (charCode != 8 && charCode != 9) {
		// charCode 48 equivale a 0   
		// charCode 57 equivale a 9
		if (charCode < 48 || charCode > 57) {
			return false;
		}
	}
}

$(document).on('focus', '#dtini',function(){
	$(this).mask("99/99/9999");	
	$(this).datepicker({ 		
		dateFormat: 'dd/mm/yy', 
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'
			        ],
			    dayNamesMin: [
			    'D','S','T','Q','Q','S','S','D'
			    ],
			    dayNamesShort: [
			    'Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'
			    ],
			    monthNames: [  'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro',
			    'Outubro','Novembro','Dezembro'
			    ],
			    monthNamesShort: [
			    'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set',
			    'Out','Nov','Dez'
			    ],
			    nextText: 'Próximo',
			    prevText: 'Anterior',
			beforeShow:function(input) {
				$(input).css({
					"position": "relative",
					"z-index": 999999
				});
			}
	});
});

$(document).on('focus', '#dtfin',function(){
	$(this).mask("99/99/9999");	
	$(this).datepicker({ 		
		dateFormat: 'dd/mm/yy', 
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'
			        ],
			    dayNamesMin: [
			    'D','S','T','Q','Q','S','S','D'
			    ],
			    dayNamesShort: [
			    'Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'
			    ],
			    monthNames: [  'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro',
			    'Outubro','Novembro','Dezembro'
			    ],
			    monthNamesShort: [
			    'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set',
			    'Out','Nov','Dez'
			    ],
			    nextText: 'Próximo',
			    prevText: 'Anterior',
				beforeShow:function(input) {
					$(input).css({
						"position": "relative",
						"z-index": 999999
					});
				}
	});
});
$(document).on('focus', '#cnpjemit',function(){
	$(this).mask("99.999.999/9999-99");
});

$(document).on('blur','#numeroini',function(){
	$("#numerofim").val($(this).val());
});


function VerificaNsuSefaz(){

document.getElementById("carregando").innerHTML = `<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
width="80px" height="80px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
<path fill="#000" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z">
<animateTransform attributeType="xml"
 attributeName="transform"
 type="rotate"
 from="0 25 25"
 to="360 25 25"
 dur="0.6s"
 repeatCount="indefinite"/>
</path>
</svg> <br> Colentando Informações da Sefaz...`;

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState === 4){
			if(this.status === 200){
				console.log(this.response);
				var array = JSON.parse(this.responseText);
				if(array.tipo == '2'){
					var view = '<div class="message error">' + array.msg + '</div>';
					$(".msgnsu").html(view);
					$(".message").effect("bounce");
				}

				getNfesColetados();
			}else{
				console.log(this.status+' - '+this.statusText);
				var view = '<div class="message error">' + this.responseText + '</div>';
				$(".msgnsu").html(view);
				$(".message").effect("bounce");
			}
		}
	};

	xhttp.open("POST",'../php/relacionamento2-exec.php?act=listapornsu',true);
	xhttp.setRequestHeader("Content-Type","application/json");
	xhttp.send();
}

function getNfesColetados(){

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState === 4){
			document.getElementById("carregando").innerHTML = "";

			if(this.status === 200){

				var array = JSON.parse(this.responseText);								

				if(array.length == 0){
					VerificaNsuSefaz();
				}	

				ListarNfes(JSON.parse(this.responseText));
			}else{
				console.log(this.status+' - '+this.statusText);
				var view = '<div class="message error">' + this.responseText + '</div>';
				$(".msgnsu").html(view);
				$(".message").effect("bounce");
			}
		}
	};


	xhttp.open("GET",'../php/relacionamento2-exec.php?act=listanfes',true);
	xhttp.setRequestHeader("Content-Type","application/json");
	xhttp.send();

}


 $(document).on("click","#todoscheckbox",function(){
	console.log("Checkbox mestre foi clicado!");	
	$('input:checkbox').not(this).prop('checked', this.checked);
 }); 
	
 $(document).on("click",".tbnfes tr",function(){
	$(this).children().children()[0].click();
  }); 

  $(document).on("click","input:checkbox",function(e){  
	e.stopPropagation();
  });

function ListarNfes(nfe){

	var str = `		
		<table class="table tbnfes" style="width:100% !important;">		
		<thead>
			<tr>
				<th><label class="text-center"><input type="checkbox" id="todoscheckbox"/></label></th>
				<th>#</th>
				<th>Número NF-e</th>				
				<th>Série</th>
				<th>Empresa</th>
				<th>CNPJ</th>
				<th>I.E.</th>
				<th>Data Emissão</th>
				<th>Valor Total da NF-e</th>
				<th>Situação da NF-e</th>
				<th>Ações</th>
			</tr>
		</thead>
		<tbody>	`;

		for(var i in nfe){
			if(nfe[i].status == 'success'){
				var dis = "";
			}else{
				var dis = "disabled";
			}
			str += `<tr id="${nfe[i].cod}">
					<td><label><input type="checkbox" class="checkboxnfes" value="${nfe[i].nfe_chave}|${nfe[i].nfe_numero}"/></label></td>
					<td style="background: #fff !important;padding-top: 5px !important;"><div style="display:none;">${nfe[i].id_status}</div>${nfe[i].icon}</td>
					<td>${nfe[i].nfe_numero}</td>
					<td>${nfe[i].nfe_serie}</td>
					<td>${nfe[i].nfe_empresa}</td>
					<td>${nfe[i].nfe_cnpj}</td>
					<td>${nfe[i].nfe_ie}</td>
					<td>${nfe[i].nfe_dataemissao}</td>
					<td>${nfe[i].nfe_totalnota}</td>
					<td>${nfe[i].nfe_situacao}</td>
					<td><a href="#" class="btn btn-primary btnlancamentonfe" data-id="${nfe[i].nfe_chave}|${nfe[i].situacao_manifesto}" ${dis}>Lançar NF-e</a></td>					
				</tr>`;
		}

	str += `	
	</tbody>
	</table>
	`; 

	var tabela = document.querySelector('.tabnfes');	
	tabela.innerHTML = str;
	
	var st = setInterval(function(){

		table = $('.tbnfes').dataTable({					
			"bSort" : true,
			"paging":   false,
			"ordering": false,
			/*"info":     false,*/			
			"bDestroy": true,
			"scrollY":  '50vh',
			"scrollX": true,
			"scrollCollapse": true,
			"paging":         false,
			"bFilter": true,
			"responsive": true,
			"language": {
				"search": "Pesquisar:"
			  },
			//dom: 'Bfrtip',
			"order": [],		
			"language":{
				"sEmptyTable": "Nenhuma registro encontrado",
				"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
				"sInfoFiltered": "(Filtrados de _MAX_ registros)",
				"sInfoPostFix": "",
				"sInfoThousands": ".",
				"sLengthMenu": "_MENU_ resultados por página",
				"sLoadingRecords": "Carregando...",
				"sProcessing": "Processando...",
				"sZeroRecords": "Nenhuma registro encontrado",
				"sSearch": "Pesquisar",
				"oPaginate": {
					"sNext": "Próximo",
					"sPrevious": "Anterior",
					"sFirst": "Primeiro",
					"sLast": "Último"
				},
				"oAria": {
					"sSortAscending": ": Ordenar colunas de forma ascendente",
					"sSortDescending": ": Ordenar colunas de forma descendente"
				}
			},
	   });
	  
	   $($.fn.dataTable.tables(true))
		.DataTable()
		.columns.adjust();		
		
		$('.dataTables_scrollHeadInner').css( 'width', '100%' );
		$('.tbnfes').css( 'width', '100%' );
		$('#DataTables_Table_0_wrapper').css( 'top', '-104px' );
		$('.dataTables_filter').hide();
		clearInterval(st);
		//table.columns.adjust().draw();
	},500);
	
	

}

$(document).ready(function(){
	$('#tpmanifest').click(function(){
		$(".tpmanifest").slideToggle('fast');
	});
});

$(document).on('click','.tpmanifest',function(){
	var codmanifest = $(this).attr('data-id');
	var files = '';
	var array = [];
	var contador = 0;
	jQuery(".checkboxnfes:checked").each(function(){
		
		files = this.value;		
		var chave  = files.split('|')[0];
		var numero = files.split('|')[1];
		var tp = $(this).parents('tr').attr('class').split(' ')[0].trim();
		
		array.push({
			'chave':chave,
			'numero':numero,
			'codmanifest':codmanifest
		});

		if(tp == 'success'){
			contador++;
		}

	});
	if(contador > 0){
		$.alert("<h4>Selecione somente aquelas que não foram manifestados!</h4>");
		return false;
	}
	
	if(array != ''){
		
		$.ajax({
			url: '../php/relacionamento2-exec.php',
			data: {act:'manifestnfeclick2',arr:array},
			type: "post",
			dataType: "json",
			beforeSend: function (load) {
				ajax_load("open");				
			},
			success: function (su) {
				ajax_load("close");				
				//console.log(su);
				//console.log(su[0].arr.retEvento.infEvento);
				//console.log(su[0].arr.retEvento.length);
				var res = '<h3>Resposta:</h3>';
				if(su[0].arr.retEvento.length != undefined){
					if(su[0].arr.retEvento.length > 0){
						for(i =0; i <su[0].arr.retEvento.length; i++){
							var event = su[0].arr.retEvento[i].infEvento;
							
							if(event.cStat == '999'){
								res += `<div class="listresp">						
									Sefaz esta temporariamente fora do ar tente novamente daqui alguns minutos, obrigado!
								</div>	`;	
							}else{

								res += `
								<div class="listresp">																
                               		<div class="listresp-top">
										<i class="far fa-check-circle fa-4x"></i>
										<h4>
											<strong>Número Nota</strong>:${su[0].res[i].numero}
											<p>${event.xMotivo}</p>
										</h4>                                    
										<button type="button" class="btn btn-tab" data-id="tab${su[0].res[i].numero}"><i class="fas fa-chevron-down"></i></button>                                    
									</div>					
									<div class="listresp-header" style="display:none;" id="tab${su[0].res[i].numero}">
										<div>Motivo: ${event.xMotivo}</div>
										<div>Evento: ${event.xEvento}</div>
										<div>Chave de acesso: ${event.chNFe}</div>
										<div>Data do evento: ${event.dhRegEvento}</div>
										<div>Número do protocolo: ${event.nProt}</div>
									</div>	
								</div>							
								`;
								getNfesColetados();	
							}	
						}
					}
				}else{
					var event = su[0].arr.retEvento.infEvento;								
					if(event.cStat == '999'){
						res += `<div class="listresp">						
							Sefaz esta temporariamente fora do ar tente novamente daqui alguns minutos, obrigado!
						</div>	`;	
					}else{
						console.log(su[0].res);
						res += `
								<div class="listresp">																
                               		<div class="listresp-top">
										<i class="far fa-check-circle fa-4x"></i>
										<h4>
											<strong>Número Nota:</strong>${su[0].res[0].numero}
											<p>${event.xMotivo}</p>
										</h4>                                    
										<button type="button" class="btn btn-tab" data-id="tab${su[0].res[0].numero}"><i class="fas fa-chevron-down"></i></button>                                    
									</div>					
									<div class="listresp-header" style="display:none;" id="tab${su[0].res[0].numero}">
										<div>Motivo: ${event.xMotivo}</div>
										<div>Evento: ${event.xEvento}</div>
										<div>Chave de acesso: ${event.chNFe}</div>
										<div>Data do evento: ${event.dhRegEvento}</div>
										<div>Número do protocolo: ${event.nProt}</div>
										<a href="#" class="btn btn-primary btnlancamentonfe" data-id="${event.chNFe}|${event.tpEvento} ${event.xEvento}">Lançar NF-e</a>
									</div>	
								</div>							
								`;	
						getNfesColetados();
					}
				}

				boxlanca = $.confirm({
					title: 'Mensagem Sefaz',
					content: ''+res+'',
					type: 'green',
					typeAnimated: true,
					columnClass: 'col-md-6 col-md-offset-3 col-xs-4 col-xs-offset-8',
					buttons: {
						tryAgain: {
							text: 'Fechar',
							btnClass: 'btn-red',
							action: function(){
								boxlanca.close();
							}
						},
						
					}
				});


			}
		});
		return false;
		function ajax_load(action) {
			ajax_load_div = $(".ajax_load");

			if (action === "open") {
				ajax_load_div.fadeIn(200).css("display", "flex");
			}

			if (action === "close") {
				ajax_load_div.fadeOut(200);
			}
		}


	}else{
		$.alert('Selecione uma NF-e para fazer a manifestação!');
	}



});

$(document).on("click",'.btn-tab',function(){
	var id = $(this).attr('data-id');
	$("#"+id).slideToggle();
});

$(document).on("click",'.btnlancamentonfe',function(){
	var chave = $(this).attr('data-id').split('|')[0].trim();
	var mani  = $(this).attr('data-id').split('|')[1].split(' ')[0].trim();
	
	if($(this).attr('disabled') == undefined){	
		$("#por_arquivo").hide();
		$("#por_nsu").hide();
		$("#por_barras").show();
		$("select[name='manif'] option[value='"+mani+"']").attr("selected","selected");
		document.getElementById('file_upload2').value = chave;	
		$(".m-menu-notas li a").removeClass('selecionado');
		$("input[name='rdntoas']").attr('checked',false);
		$(".m-menu-notas li")[1].querySelectorAll('a')[0].click();
		$(".m-menu-notas li")[1].querySelectorAll('a')[0].classList.add('selecionado');
		$(".m-menu-notas li")[1].querySelectorAll('a')[0].classList.toggle('selecionado', true);
				  
		$("#subimp").click();
		//if(boxlanca.close())
		//boxlanca.close();
		
	}else{
		$.alert("<h4>Para lançar a NF-e você deve primeiro efetuar a manifestação!</h4>");
	}
});

$(document).ready(function(){

	$(".checkbox-input").change(function(){		
		if($(this).val() == 1){	
		table.api()
       		.columns(1)
        	.search(this.value)
        	.draw();
		}else if($(this).val() == 2){
			table.api()
			.columns(1)
			.search(this.value)
			.draw();
		}else if($(this).val() == 3){
			
			$.ajax({
				url: '../php/relacionamento2-exec.php',
				data: {act:'pesquisanotas',tipo:this.value},
				type: "post",
				dataType: "json",
				beforeSend: function (load) {
					//ajax_load("open");
				},
				success: function (su) {
					//ajax_load("close");
					
					if (su.length > 0) {
						ListarNfes(su);						
					}
		
					
				}
			});
			return false;
				
		}else{
			table.api()
			.columns(1)
			.search('')
			.draw();
		}
		
	});
});
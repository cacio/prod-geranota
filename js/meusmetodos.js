// JavaScript Document

var datatablecli;
var dlog;


$(document).ready(function(e) {

   	   $forms = $('form[id="formformulapadrao"]');
	$forms.validate({
		rules: {
			 codproduto:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			codmat:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },									
			},
		messages:{
			 codproduto: {
                      required:"Informar um produto",                      
                      },		  
					  
			codmat: {
                      required:"informar materia prima",                      
                      },	  		  
			},
		submitHandler: function(form) {
  		 				
			var $form = $(form);
            var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',
				url: '../php/formula-exec.php',
				data: params,				
				success: function(data){
					alert(data);
					
					$('#locprod').val('');
					$('#codmat').val('');
					$('#codmat').focus();					
					$('.mp').html('');
					$('#kg').val('');
					$('#participacao').val('');
					$('#cunit').val('');
					
					ListaGridFormulacao($("#codproduto").val());
				},
				error: function(data){
					
				}
			})
			return false;
		}
				
	});
	

	/*$(function(){
		$('select').selectric();
	});		*/
	
});

$(document).ready(function(e) {
	$('#btnadd').attr('disabled',true);
    $("#codproduto").blur(function(){
		$("#codprodutos").val($(this).val());
		$('#btnadd').attr('disabled',false);	
	});
});

function ListaGridFormulacao(codpro){
	
	
	$.ajax({
				type: 'POST',
				url: '../php/listagemdeformulcao.php',
				data: {codprod:codpro},	
				success: function(data){
					
					$('button').attr('disabled',false);					
					$(".rm").show();
					$("#listar").html(data);	
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,						
						 "fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(4)', nRow).css( "text-align", "right" );
                                 

                                  return nRow;
				          },
						 "bRetrieve": true,	  							
					});
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
					bootbox.hideAll();
				},
				error: function(data){
					alert(data.status);	
				}
			})
			return false;
	

}

$(document).ready(function(e) {
    var path = window.location.pathname.split('/');
	
	if(path[4] == "atualizar-formulacao.php"){
		
		bootbox.dialog({
			  message: "Estou buscando sua alteração, relaxa e aquarde ;D",
			  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
			});
			
		ListaGridFormulacao($("#codprodutos").val());
			
		
	}
});
function formatadata(data){
	var novadata = data.split(".");
	var nvdt = ""+novadata[0] +"/"+novadata[1]+"/"+novadata[2]+"";
	return nvdt;
}

$(document).ready(function(e) {
	
	$('#remove').click(function(){
		
		var files = '';
		var array = [];
		
		jQuery(".cinput:checked").each(function(){
			
			files = this.value;
			//ids = array.push(files);
			array.push(files);	
			
		});
		
		
		
		if(array == ""){
		
			alert("Selecione um item para ser excluido na coluna 'ação' ! ");
			
		}else{
			
				$.ajax({
						type:'POST',
						async:false, 
						dataType: "json",
						url:"../php/formula-exec.php",
						data:{act:'delete',id: array},
						success: function(data){
							//alert(data.length);
							
							/*for (var i = 0; i < data.length; i++) {
								
								$(".table tbody tr[id='"+data[i].codigo+"']").remove();
								
							}*/
							ListaGridFormulacao($("#codproduto").val());
						}	
					});
					
			return false;					
		
		}     
	});
	
	
    $( "#codproduto" ).autocomplete(
		{	
		 source:'../php/produto-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".cp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".cp").html(ui.item.nom)
		}		
	});
	
	$( ".buscapro" ).autocomplete(
		{	
		 source:'../php/produto-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$("input[name='nomprod']").val(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			//$(".cp").html(ui.item.nom)
		}		
	});

	 $( "#grupo" ).autocomplete(
		{	
		 source:'../php/grupo-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".cp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".cp").html(ui.item.nom)
		}		
	});
	
	$( "#locprod" ).autocomplete(
		{	
		 source:'../php/localproducao-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".lp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".lp").html(ui.item.nom)
		}	
	});
	
	$( "#codmat" ).autocomplete(
		{	
		 source:'../php/produto-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".mp").html(ui.item.nom)						 
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
						
		}	
	});
	
	$( "#cliente" ).autocomplete(
		{	
		 source:'../php/cliente-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".mp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
		}	
	});
	
	$( "#fornecedor" ).autocomplete(
		{	
		 source:'../php/fornecedor-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".mp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
		}	
	});
	
	
	$( "#repre" ).autocomplete(
		{	
		 source:'../php/representante-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".mp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
		}	
	});
	
	$( "#repres" ).autocomplete(
		{	
		 source:'../php/representante-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".nonrep").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".nonrep").html(ui.item.nom)
		}	
	});
	
	$( "#vendedor" ).autocomplete(
		{	
		 source:'../php/vendedor-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".mp").html(ui.item.nom)			
					
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
		}	
	});
	
	$( "#conta" ).autocomplete(
		{	
		 source:'../php/conta-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".mp").html(ui.item.nom)						 
		},
		focus: function( event, ui ) {
			$(".mp").html(ui.item.nom)
						
		}	
	});
	
	$( "#placa" ).autocomplete(
		{	
		 source:'../php/placa-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".pl").html(ui.item.placa)						 
		},
		focus: function( event, ui ) {
			$(".pl").html(ui.item.placa)
						
		}	
	});
	
	$( "#cfop" ).autocomplete(
		{	
		 source:'../php/cfop-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".cf").html(ui.item.nom)						 
		},
		focus: function( event, ui ) {
			$(".cf").html(ui.item.nom)
						
		}	
	});
	$( "#contafc" ).autocomplete(
		{	
		 source:'../php/financonta-exec.php?act=busca2',
		 minLength: 1,
		select: function(event, ui) {
			$(".cfc").html(ui.item.nom+' Cod centro:'+ui.item.centrocusto)						 
		},
		focus: function( event, ui ) {
			$(".cfc").html(ui.item.nom+' Cod centro:'+ui.item.centrocusto)
						
		}	
	});

	$( ".contactb" ).autocomplete(
		{	
		 source:'../php/financonta-exec.php?act=buscaconta',
		 minLength: 1,
		select: function(event, ui) {
			$(".txtconta_"+event.target.id.split('_')[1]+"").html(ui.item.nom);
			$("#contctb_"+event.target.id.split('_')[1]+"").val(ui.item.cod);
			
		},
		focus: function( event, ui ) {
			//$(".cfc").html(ui.item.nom+' Cod centro:'+ui.item.centrocusto)
						
		}	
	});

	$( ".contactbfor" ).autocomplete(
		{	
		 source:'../php/financonta-exec.php?act=buscaconta',
		 minLength: 1,
		select: function(event, ui) {
			$(".txtconta_"+event.target.id.split('_')[1]+"").html(ui.item.nom);
			$("#contctb_"+event.target.id.split('_')[1]+"").val(ui.item.cod);
			
		},
		focus: function( event, ui ) {
			//$(".cfc").html(ui.item.nom+' Cod centro:'+ui.item.centrocusto)
						
		}	
	});

	$( ".histpadrao" ).autocomplete(
		{	
		 source:'../php/historicopadrao-exec.php?act=pesquisa',
		 minLength: 1,
		select: function(event, ui) {
			$(".txthistpadrao_"+event.target.id.split('_')[1]+"").html(ui.item.nom);
			$("#idhistp_"+event.target.id.split('_')[1]+"").val(ui.item.cod);
			
		},
		focus: function( event, ui ) {
			//$(".cfc").html(ui.item.nom+' Cod centro:'+ui.item.centrocusto)
						
		}	
	});

		
	$('.selectize').selectize();	
	jQuery('#tableformula').dataTable();

	var dtc = setInterval(function(){
		datatablecli = $('#dyntableclientes').DataTable( {        
			scrollY:        800,
			scrollCollapse: true,
			paging:         false,
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
			initComplete : function() {
				$("#dyntableclientes_filter").detach().appendTo('#new-search');
			}
		} );
		clearInterval(dtc);
	},900);

});

$(document).on('keypress','#cfop1',function(){
	$(this).autocomplete(
		{	
		 source:'../php/cfop-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {
			$(".cfo").html(ui.item.nom)						 
		},
		focus: function( event, ui ) {
			$(".cfo").html(ui.item.nom)
						
		}	
	});
			
});	

$(document).ready(function(e) {
   	$forms = $('form[id="frmfaturamento"]');
	$forms.validate({
		 debug : true,
			errorElement : "em",
			errorContainer : $("#warning, #summary"),
			errorPlacement : function(error,
					element) {
				error.appendTo(element.parent("td")
						.next("td"));
			},
			success : function(label) {
				label.text("ok!").addClass(
						"success");
			},
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',
				async:false, 
				dataType: "json",	
				url: '../php/relatorios-exec.php',
				data: params,	
				success: function(data){
					
					var info = "<strong>Listagem de Faturamento de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong>";
					var total = 0;
					var table = '<table id="listfaturamento">';
					 
						table += '<caption><h3>ALLES COM. IND. DERIVADOS E TRANSP. LTDA.</h3><h4>'+info+'</h4></caption>';
						table += '<thead>';
						table += '<tr>';
						table += '<th>Emissão</th>';
						table += '<th>Código</th>';
						table += '<th>Razão Social</th>';
						table += '<th>CFOP</th>';
						table += '<th>Documento</th>';
						//table += '<th>Serie</th>';
						table += '<th>R$ Total</th>';
						table += '<th>Vendedor</th>';
						table += '</tr>';
						table += '</thead>';
						table += '<tbody>';	
					for (var i = 0; i < data.length; i++) {
						
						table += '<tr class="'+data[i].codfor+'">';
						table += '<td>'+data[i].dataemi+'</td>';
						table += '<td>'+data[i].codfor+'</td>';
						table += '<td>'+data[i].cliente+'</td>';
						table += '<td>'+data[i].cfop+'</td>';
						table += '<td>'+data[i].numnota+'</td>';
						//table += '<td>'+data[i].serienota+'</td>';
						table += '<td>'+data[i].vltotalnota+'</td>';
						table += '<td>'+data[i].represent+'</td>';
						table += '</tr>';
						
						total = data[i].total;	
						//$('#undefined').css({'display':'none'});
					}
					table += '<TFOOT>';
					table += '<tr>';
						table += '<td></td>';
						table += '<td></td>';
						table += '<td></td>';
						table += '<td></td>';
						table += '<td>Total:</td>';
						//table += '<td>'+data[i].serienota+'</td>';
						table += '<td>'+total+'</td>';
						table += '<td></td>';
						table += '</tr>';
					table += '</TFOOT>';
					
					table += '</tbody>';
					table += '</table>';
					
					
					
					$("#relatoriofatu").html(table);	
					$('#listfaturamento').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"dom": 'T<"clear">lfrtip',						
						 "fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(0)', nRow).css( "text-align", "center" );
                                  $('td:eq(1)', nRow).css( "text-align", "center" ); 
								  $('td:eq(2)', nRow).css( "text-align", "left" ); 
								  $('td:eq(3)', nRow).css( "text-align", "center" ); 
								  $('td:eq(4)', nRow).css( "text-align", "center" ); 
								  $('td:eq(5)', nRow).css( "text-align", "right" ); 
								  $('td:eq(6)', nRow).css( "text-align", "left" ); 

                                  return nRow;
				          },							
					});
					
					
					$('.undefined').css({'display':'none'});
					bootbox.hideAll();
				},
				error: function(data){
					alert(data.status);	
				}
			})
			return false;
		}				
	});	 				
});

function print_r(arr,level) {
var dumped_text = "";
if(!level) level = 0;

//The padding given at the beginning of the line.
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { //Array/Hashes/Objects 
    for(var item in arr) {
        var value = arr[item];

        if(typeof(value) == 'object') { //If it is an array,
            dumped_text += level_padding + "'" + item + "' ...\n";
            dumped_text += print_r(value,level+1);
        } else {
            dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    }
} else { //Stings/Chars/Numbers etc.
    dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
}
 function printDiv(id, pg) {
		var oPrint, oJan;
		oPrint = window.document.getElementById(id).innerHTML;
		oJan = window.open(pg,'','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,fullscreen=1,resizable=yes,width=9'+screen.width+',height='+screen.height+',directories=no,location=no');
		oJan.document.write("<html>");
		oJan.document.write("<head>");
		oJan.document.write("<meta name='viewport' content='width=device-width, initial-scale=1.0'/>");
		oJan.document.write("<link href='../css/bootplus.css' rel='stylesheet' type='text/css' media='all'>");
		oJan.document.write("<link href='../css/jquery.dataTables.css' rel='stylesheet' type='text/css' media='all'>");
		oJan.document.write("<style>");
		oJan.document.write(".pvalor{text-align: right;margin-right:10px;}   thead{display: table-header-group; } tfoot{display: table-footer-group; }");
		oJan.document.write("</style>");
		oJan.document.write("</head>");
		
		oJan.document.write("<body>");	
		oJan.document.write(oPrint);		
		oJan.document.write("</body>");
		oJan.document.write("<html>");
		oJan.window.print();
		//oJan.document.close();
		//oJan.focus();
	}
	
 function printproducao(id) {
	
	$.ajax({
		type:'POST',
		url:"../php/printproducao.php",
		data:{id:id},		
		success: function(data){
		
		$('#relatorio').html(data);
			
		var ids = 'relatorio';
		var oPrint = window.document.getElementById(ids).innerHTML;
				
		var	pg;
		oJan = window.open(pg,'','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,fullscreen=1,resizable=yes,width=9'+screen.width+',height='+screen.height+',directories=no,location=no');
		oJan.document.write("<html>");
		oJan.document.write("<head>");
		oJan.document.write("<meta name='viewport' content='width=device-width, initial-scale=1.0'/>");	

		oJan.document.write("</head>");		
		oJan.document.write("<body>");
		oJan.document.write(oPrint);
		oJan.document.write("</body>");
		oJan.document.write("<html>");
		oJan.window.print();
		
		},
		error: function(data){
					alert(data);	
		}			
			
	});
			
}	
	
$(document).ready(function(e) {
   	$formscp = $('form[id="frmcontaspagarvencimento"]');
	$formscp.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriocontasapagarporvencimento.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>ALLES COM. IND. DERIVADOS E TRANSP. LTDA.</h3><h4><strong>Contas a pagar por vencimento de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,						
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});
$(document).ready(function(e) {
   	$formscr = $('form[id="frmcontasrecebervencimento"]');
	$formscr.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriocontasareceberporvencimento.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>ALLES COM. IND. DERIVADOS E TRANSP. LTDA.</h3><h4><strong>Contas a receber por vencimento de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						 "fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(5)', nRow).css( "text-align", "right" );
                                  $('td:eq(6)', nRow).css( "text-align", "right" ); 
								  $('th:eq(5)', nRow).css( "text-align", "right" );
                                  $('th:eq(6)', nRow).css( "text-align", "right" );                              
                                  return nRow;
				          },												
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmcontaspagaremissao"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriocontasapagarporemissao.php',
				data: params,	
				success: function(data){
					
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Contas a pagar por emissão de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(5)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },
						   "order": []
					   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formscl = $('form[id="frmcontasareceberporcliente"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			cliente: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			cliente: {
                      required:"Selecione um cliente",                      
                      },		  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriocontasareceberporcliente.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Contas a receber por cliente de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</br>"+$(".mp").html()+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,	
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formscl = $('form[id="frmcontasapagarporcliente"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			cliente: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			cliente: {
                      required:"Selecione um cliente",                      
                      },		  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriocontasapagarporcliente.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Contas a pagar por fornecedor de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,	
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(3)', nRow).css( "text-align", "right" );
							      $('td:eq(5)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formscl = $('form[id="frmlistagemporrepresentante"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			repre: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
				comissao: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			repre: {
                      required:"Selecione um repesentante",                      
                      },
				comissao: {
                      required:"Informa um % de comissão",                      
                      },	  		  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemporrepresentante.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem Por Representante de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"<br/> Percentual de  Comissão "+$("#comissao").val()+"%</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(3)', nRow).css( "text-align", "right" );	
								  $('td:eq(6)', nRow).css( "text-align", "right" );							  
                                  return nRow;
				          },				
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formsemi = $('form[id="frmfluxodecaixa"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriofluxodecaixa.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Fluxo de caixa de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(1)', nRow).css( "text-align", "right" );	
								  $('td:eq(2)', nRow).css( "text-align", "right" );
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );							  
                                  return nRow;
				          },						
						   "order": []
					   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemdeproducao"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			codproduto: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			as: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },		
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			codproduto: {
                      required:"Digite um produto",                      
                      },
			as: {
                      required:"Selecione se Analítico/Sintético",                      
                      },		  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdeproducao.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem de produção "+$('select[name="as"] option:selected').text()+" por produto de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</br>"+$(".cp").html()+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.tbl').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
								  $('td:eq(5)', nRow).css( "text-align", "right" );
								  $('td:eq(6)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
					$('.tbls').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
								  $('td:eq(5)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
					$('.tabl').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(0)', nRow).css( "text-align", "center" );	
								  $('td:eq(1)', nRow).css( "text-align", "center" );
								  $('td:eq(2)', nRow).css( "text-align", "center" );
                                  return nRow;
				          },						
						   "order": []
					   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frminventario"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                }	
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      }		  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorioinventario.php',
				data: params,	
				success: function(data){
					
					var cp = $(".cp").html();
					if(cp == ""){
						cp = "TODOS";
					}
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Inventário de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" do grupo "+cp+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },											
						   "order": []
					   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});



$(document).ready(function(e) {
   	$formsemi = $('form[id="frmextratodoproduto"]');
	$formsemi.validate({
	
		rules: {
			
			codproduto: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },		
					
			},
		messages:{
			 
			codproduto: {
                      required:"Digite um produto",                      
                      },
  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorioextratodoproduto.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Extrato do produto "+$(".cp").html()+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					
					bootbox.hideAll();
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },										
						   "order": []
					   													
					});	
					
																				
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

 
$(document).ready(function() {
	
	 $formsemi = $('form[id="formproducao"]');
	$formsemi.validate({
	
		rules: {
			
			codproduto: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
				kg: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
				dtproducao:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
					
					
			},
		messages:{
			 
			codproduto: {
                      required:"Digite um produto",                      
                      },
  		  	kg: {
                      required:"Informa a quantidade",                      
                    },
			dtproducao: {
                      required:"Informa a data da produção",                      
                    },			
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Aquarde enquanto listo sua produção",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/producao-exec.php',
				data: params,	
				success: function(data){
					
					$("#codprod").val($("#codproduto").val());
					$("#kgs").val($("#kg").val());
					$("#dtpro").val($("#dataini").val());
					$("#locals").val($("select[name='local']").val());
					
					
					//alert(data);
					listainfoproducao();			
					
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 
	  
});

function listainfoproducao(){

			$.ajax({
				type: 'POST',			
				url: '../php/listagemdeinformaproducao.php',
				data: {act:'lista'},	
				success: function(data){
					
					
					$("#tableproducaolist").html(data);
					
					bootbox.hideAll();
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },										
						   "order": []
					   													
					});	
					
																				
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;

}

$(document).ready(function(e) {
		$('#listainfoproducao').dataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"iDisplayLength": -1,
			"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
					  $('td:eq(2)', nRow).css( "text-align", "right" );	
					  $('td:eq(3)', nRow).css( "text-align", "right" );
					  $('td:eq(4)', nRow).css( "text-align", "right" );
					  return nRow;
			  },										
			   "order": []
														
		});	
		
																
	var pathArray = window.location.pathname.split('/');
	if(pathArray[4] == 'atualizar-producao.php'){

		$('.dataTables_length').css({'display':'none'});
		$('.dataTables_filter').css({'display':'none'});
		$('.dataTables_paginate').css({'display':'none'});
		$('.dataTables_info').css({'display':'none'});
	
	}
	
	$('.deleterowproducao').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                $(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/producao-exec.php",
						data:{act:'deletar',id: $(this).attr('id')},
						success: function(data){
							
							$(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
});


$(document).ready(function(e) {
   	$formscl = $('form[id="frmlistagemvendedor"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
								
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemporvendendor.php',
				data: params,	
				success: function(data){
					
					var mp = $(".mp").html();
					if(mp == ""){
						mp = "TODOS";
					}
					
					var info = "<h3>"+$('#empresa').val()+"/h3><h4><strong>Listagem Por Vendedor "+$('select[name="as"] option:selected').text()+" de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4><br/> <strong style='float:left;'>Vendedor: "+mp+" </strong>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,	
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(4)', nRow).css( "text-align", "right" );
							      $('td:eq(5)', nRow).css( "text-align", "right" );
								  $('td:eq(6)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formscl = $('form[id="frmlistagemcliente"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
								
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemporcliente.php',
				data: params,	
				success: function(data){
					
					var mp = $(".mp").html();
					if(mp == ""){
						mp = "TODOS";
					}
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem Por cliente "+$('select[name="as"] option:selected').text()+" de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4><br/> <strong style='float:left;'>Cliente: "+mp+" </strong>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,	
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(4)', nRow).css( "text-align", "right" );
							      $('td:eq(5)', nRow).css( "text-align", "right" );
								  $('td:eq(6)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {

	var stv =setInterval(function(){
		$('.tbmovim').dataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "TODOS"]],
			"iDisplayLength": -1,							
			"order": []	,
			"bDestroy": true,
			"bRetrieve": true,
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
		clearInterval(stv);
	},600);

   
	

$(document).ready(function(){			
	var pathArray = window.location.pathname.split('/');
	//	console.log(pathArray);
	var newPathname = "";
	for (i = 0; i < pathArray.length; i++) {
		newPathname += "/";
		newPathname += pathArray[i];
		//alert(pathArray[i]);
		if(pathArray[i] == 'cadastro-lancamento.php'){
			listconta();
			//AutoSuggestHistocio();
		}
	}	
});
var conta = 0;
var instance;
function AutoSuggestHistocio(){
					
		

	$.ajax({
		url: '../php/historicopadrao-exec.php',
		data: {act:'busca'},
		type: "post",
		dataType: "json",		
		success: function (su) {
			//instance.destroy();
							
				
				if(conta > 0){
					console.log('entro');
					instance.destroy();
				}
			
				instance = new AutoSuggest({
				onChange: function (suggestion) {
					const change = suggestion.insertHtml || suggestion.insertText;
					var ids = change.split('-')[0];
					$("#idhist").val(ids);
					//console.log('"' +ids + '" has been inserted into #' + this.id);							
				},						
				suggestions: [
					
					{on: su.sugest.res,values:su.sugest.res}, 
					function(keyword, callback) {
						keyword = keyword.toLowerCase();
							
						var results = [];
						var dataset = this.value || this.textContent;
						var dr = this.value;
						dataset = dataset.toLowerCase().split(/[^a-zA-Z0-9_]+/);
						
						dataset.forEach(function(word) {									
							
							if (
								
								word.length >= 4 &&
								!word.indexOf(keyword) &&
								word !== keyword &&
								results.indexOf(word) === -1
											
							) {
								results.push(word);
							}
						});
	
						setTimeout(function () {
							callback(results);
						}, 1000);
					}
				]
			}, $('#textarea'));
			conta++;
			console.log(conta);
		}
	});
}	

$(document).ready(function(e) {
	//listconta();

   	$formscl = $('form[id="formmovimentacao"]');
	$formscl.validate({
	
		rules: {
			data:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			contadebido: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			valor: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },	
			hist: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },			
			},
		messages:{
			 data: {
                      required:"Selecione uma data.",                      
                      },
			contadebido: {
                      required:"Selecione uma conta",                      
                      },
			valor: {
                      required:"Colca um valor",                      
                      },
			hist: {
                      required:"Descreva o que se refere a movimentação",                      
                      },						  		  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Aquarde enquanto cadastro sua movimentação.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});


			if($('#contacredito').data('selectize').getValue() == ''){
				alert('Informar a Conta Crédito!');
				bootbox.hideAll();
				return false;
			}

			if($('#contadebido').data('selectize').getValue() == ''){
				alert('Informar a Conta Débito!');
				bootbox.hideAll();
				return false;
			}

			if($('#idhistpadrao').data('selectize').getValue() == ''){
				alert('Informar um Histórico Padrão!');
				bootbox.hideAll();
				return false;
			}
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/movi-exec.php',
				data: params,	
				success: function(data){
				//alert(data);
				
				listconta();

				bootbox.hideAll();	
							
				$('#contacredito').data('selectize').setValue("");
				$('#contadebido').data('selectize').setValue("");
				$('#idhistpadrao').data('selectize').setValue("");
				$("#dataini").val('');					
				$("#valor").val('');
				$('#hist').val('');
				//$("#textarea").val('');
				$("#idhist").val('');
				$("#dataini").focus();	
				$("#idm").remove();	
				$("#act").val('inserir');
				$("#btnlanca").html('Adicionar')
				//window.location.reload();

				},
				error: function(data){
					bootbox.hideAll();
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});	
	
});


function listconta(){
	
		
	$.ajax({
		type:"POST",
		url:"../php/movi-exec.php",
		data:{act:'lista'},
		dataType: "json",
		cache:false,
		success: function(data){
			var htm= '';
			for (let index = 0; index < data.length; index++) {
				const element = data[index];
				htm += `
					<tr id="${element.id}">
					<td>${element.data}</td>
					<td>${element.mov_idconta_c}</td>
					<td>${element.mov_idconta_d}</td>
					<td>${element.mov_historico}</td>
					<td style="text-align:right;">${element.mov_valor}</td>
					<td>
						<a href="#" class="btnremovelanc"><i class="icon-remove icon-2x"></i></a>
						<a href="#" class="editlanc"><i class="icon-edit icon-2x"></i></a>
					</td>															
				</tr>
				`;
			}	
			
			$(".tbmovim tbody").html(htm);
			
		},
		error:function(jqXHR, textStatus, errorThrown){
			
			var view = '<div class="message error">' + jqXHR.responseText + '</div>';
			$("#msg").html(view);
			$(".message").effect("bounce");  
			
		}
	});
	
	
}

$(document).on("click",".btnremovelanc",function(e){
	e.preventDefault();
	var cf = confirm('Deseja realmente excluir esse registro ? Sim cliquei em [OK] se Não [Cancelar]');
	if(cf){
		var id = $(this).parents('tr').attr('id');
		$(this).parents('tr').fadeOut(function(){
			$.ajax({
				type:'POST',
				url:"../php/movi-exec.php",
				data:{act:'delete',id: id},
				success: function(data){
					
					$(this).remove();
					
				}	
			});
		});
	}
});

$(document).on("click",".editlanc",function(e){
	e.preventDefault();
	var id = $(this).parents('tr').attr('id');

	console.log(id);
	$.ajax({
		type:"POST",
		url:"../php/movi-exec.php",
		data:{act:'listaum',id:id},
		dataType: "json",
		cache:false,
		success: function(data){
			console.log(data);


			$("#dataini").val(data[0].data);					
			$("#valor").val(data[0].mov_valor);
			$('#contacredito').data('selectize').setValue(data[0].mov_idconta_c);
			$('#contadebido').data('selectize').setValue(data[0].mov_idconta_d)
			$('#idhistpadrao').data('selectize').setValue(data[0].mov_idhistorico);
			$('#hist').val(data[0].mov_historico);
			$("#act").val('alterar');
			$("#btnlanca").html('Alterar');

			$("#formmovimentacao").append('<input type="hidden" name="id" id="idm" value="'+id+'"/>')
						
		},
		error:function(data){
			
			alert(data);
			
		}
	});


});


$(document).ready(function(e) {
   	$formscl = $('form[id="frmlistagemdecaixa"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
							
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  	  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdecaixa.php',
				data: params,	
				success: function(data){
					
					var mp = $(".mp").html();
					if(mp == ""){
						mp = "TODOS";
					}
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem de Caixa de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4><br/> <strong style='float:left;'>Conta: "+mp+" </strong>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,	
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(4)', nRow).css( "text-align", "right" );
							      $('td:eq(5)', nRow).css( "text-align", "right" );
								  $('td:eq(6)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formscl = $('form[id="frmbalancete"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
							
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  	  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriobalancete.php',
				data: params,	
				success: function(data){
					
					var mp = $(".mp").html();
					if(mp == ""){
						mp = "TODOS";
					}
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Balancete "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4><br/> <strong style='float:left;'>Conta: "+mp+" </strong>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,	
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(4)', nRow).css( "text-align", "right" );
							      $('td:eq(5)', nRow).css( "text-align", "right" );
								  $('td:eq(6)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemdecomrpasporproducao"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdecomprasporproducao.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem de compras "+$('select[name="as"] option:selected').text()+" para produção de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</br>"+$(".cp").html()+"</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
					
					
					$('.tbl').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
								  $('td:eq(5)', nRow).css( "text-align", "right" );
								  $('td:eq(6)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
					$('.tbls').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );
								  $('td:eq(5)', nRow).css( "text-align", "right" );
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
					$('.tabl').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(0)', nRow).css( "text-align", "center" );	
								  $('td:eq(1)', nRow).css( "text-align", "center" );
								  $('td:eq(2)', nRow).css( "text-align", "center" );
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
					
					$('.tablr').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                                                               
								  $('td:eq(1)', nRow).css( "text-align", "right" );
								  $('td:eq(2)', nRow).css( "text-align", "right" );
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								
                                 return nRow;
				          },						
						   "order": []
					   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmclientespositivados"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorioclientespositivados.php',
				data: params,	
				success: function(data){
										
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Clientes Positivados  de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								 
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
				
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemdecomissaopordatapagamento"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdecomissaopordatapagamento.php',
				data: params,	
				success: function(data){
										
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem de comissão por data de pagamento de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								 
								 
                                  return nRow;
				          },						
						   "order": []
					   													
					});	
					
				
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemdecomferencia"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdeconferencia.php',
				data: params,	
				success: function(data){
										
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem de conferencia de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								 
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});	
					
				
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmcurvaabc"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriocurvaabc.php',
				data: params,	
				success: function(data){
										
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Curva ABC de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "left" );	
								 
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});	
					
				
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemfofao"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemfofao.php',
				data: params,	
				success: function(data){
				var sel = $('select[name="as"] option:selected').text();
				var pro = $('#codproduto').val();
				
				if(pro == ""){
					pro = "";				
				}else{
					pro = 'Produto :'+$('.cp').html()+'';
				}
				if(sel == "Selecione"){
					
					sel = "";
				}						
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Estatistica: "+sel+" de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" "+pro+"</strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(4)', nRow).css( "text-align", "right" );	
								 
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});
$(document).ready(function(e) {
   	$formsemi = $('form[id="frmbalancetedecontas"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriobalancetedecontas.php',
				data: params,	
				success: function(data){
								
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Balancete de custo de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" </strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(5)', nRow).css( "text-align", "right" );	
								 
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formsemi = $('form[id="frmconferenciaporplaca"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdeconferenciaporplaca.php',
				data: params,	
				success: function(data){
				
				var pl = $('#placa').val();
				var plc;
				
				if(pl == ""){
					plc = "";
				}else{
					plc = "Placa: "+pl+"";
				}
								
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Conferencia Por Placa de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" "+plc+" </strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,														
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
    $(".dtf").change(function(){
		
		var dtini = $('#dataini').val();
		var dtfim = $(this).val();
		
		if(dtini != ""){
			
			$.ajax({
			type: 'POST',
				async:false, 
				dataType: "json",	
				url: '../php/placa-exec.php',
				data: {act:'seleciona',dtini:dtini,dtfim:dtfim},
				success: function(data){
					
					for (var i = 0; i < data.length; i++) {
						
						$('#placa').append('<option>' + (data[i].placa ? data[i].placa : 'Empty') + '</option>');
					}
					$('#placa').selectric('refresh');
				},
				error: function(data){
					alert(data);	
				}	
			
			});
			
			
		}else{
			$('#dataini').focus();
			$(this).val('');
			alert('Ops! Para melhorar a pesquisa primeiro selecione a data inicial!');
		}
		
	});
});



$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemrecebimento"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemdecomissaoderecebimento.php',
				data: params,	
				success: function(data){
				
				var repr;
				var comitx;
				var rp   = $('.mp').html();				
				var comi = $('#comissao').val();
				
				
				
				if(rp == ""){
					repr = "";
				}else{
					repr = "Representante : "+rp+"";
				}
				if(comi == ""){
					comitx = "";
				}else{
					comitx = "Comissão : "+comi+"%";
				}				
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem de comissão de recebimento "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" <br/>"+repr+" "+comitx+"</strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(5)', nRow).css( "text-align", "right" );	
								 
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmlistagemsenar"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemsenar.php',
				data: params,	
				success: function(data){
				
			
			
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Listagem Senar de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" </strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(1)', nRow).css( "text-align", "right" );	
								  $('td:eq(2)', nRow).css( "text-align", "right" );
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmextratocontaplano"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorioextratoporcontaplano.php',
				data: params,	
				success: function(data){
				
				var cn = $('.cf').html();
				var cntx;
				if(cn == ""){
					cntx = "";
				}else{
					cntx = "Contas: "+cn+"";
				}
				
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Extrato p/ conta (Plano) de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4><strong style='float:left;'>"+cntx+" </strong>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );  
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(e) {
   	$formsemi = $('form[id="frmextratoporcentrocombustivel"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorioextratoporcentrocombustivel.php',
				data: params,	
				success: function(data){
				
				var cn = $('.cfc').html();
				var cntx;
				if(cn == ""){
					cntx = "";
				}else{
					cntx = "Contas: "+cn+"";
				}
				
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Extrato p/ centro (Combustivel) de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4><strong style='float:left;'>"+cntx+" </strong>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(2)', nRow).css( "text-align", "right" );	
								  $('td:eq(3)', nRow).css( "text-align", "right" );
								  $('td:eq(4)', nRow).css( "text-align", "right" );  
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});


$(document).ready(function(e) {
   	$formsemi = $('form[id="frmbalancetedecadastrocontas"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },
			  		  
					  		  
					  			
			},
		submitHandler: function(form) {
  		  
		bootbox.dialog({
				  message: "Enquanto o relatorio vai gerando.. relaxa ;D",
				  title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatoriolistagemcadastrodecontas.php',
				data: params,	
				success: function(data){
								
				var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Lista cadastro de contas de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+" </strong></h4>";
															
				$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
					bootbox.hideAll();
					
										
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"fnRowCallback"  : function(nRow,aData,iDisplayIndex) {                               
                                  $('td:eq(6)', nRow).css( "text-align", "right" );	
								 
								 
                                  return nRow;
				          },									
  					    "order": []
					   													
					});
					
									
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

$(document).ready(function(){
	$("#btnformula").click(function(){
		
		$(".dataTables_empty").remove();
		
		var id       = idtable();   
		var idpro    = $("input[name='ID']").val();
		var nomeprod = $("input[name='nomprod']").val();
		var qtdpecas = $("input[name='QTDPECAS']").val();
		var perc     = $("input[name='PERC']").val();
		var htm      = "";

		if(idpro == ""){
			alert("Digite pelo menos um codigo de produto!");
			return false;
		}
		
		htm +='<tr id="'+id+'">'+
				'<td>'+idpro+' - '+nomeprod+'<input type="hidden" name="forml['+id+'][ID]" value="'+idpro+'" /><input type="hidden" name="forml['+id+'][DESC]" value="'+nomeprod+'" /></td>'+
				'<td>'+qtdpecas+'<input type="hidden" name="forml['+id+'][QTDPECAS]" value="'+qtdpecas+'" /></td>'+
				'<td>'+perc+'<input type="hidden" name="forml['+id+'][PERC]" value="'+perc+'" /></td>'+
				'<td><button type="button" class="btn btn-primary btnformularemove">REMOVER</button></td>'+
			  '</tr>';

		$("#tbfomulacao tbody").append(htm);

		$("input[name='ID']").val('');
		$("input[name='nomprod']").val('');
		$("input[name='QTDPECAS']").val('');
		$("input[name='PERC']").val('');
		$("input[name='ID']").focus();

	});
});

function idtable(){
	var tabf = $("#tbfomulacao tbody tr").length + 1;
	return tabf;
}

$(document).on("click",".btnformularemove",function(){

	var cod = $(this).parents('tr').attr('id');
	//alert(cod);
	$("#tbfomulacao tbody tr[id='"+cod+"']").remove();

});

$(document).ready(function(e) {
   	$formscl = $('form[id="frmestoqueprod"]');
	$formscl.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: false,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: false,   //required boolean: true/false                 
                }					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },			
			},
		submitHandler: function(form) {
  		  
		var logd = $.dialog({
					title: '<img src="../images/icon.ico" width="15" /> Sistema',
					closeIcon: false,
					content: 'Aguarde<img src="../images/loader19.gif"/> Aguarde enquanto gero o relatório ;D!',
				});
		  
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorioestoqueproduto.php',
				data: params,	
				success: function(data){
					
					
					var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Estoque Produto CST</strong></h4>";
					
					
					$("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
					//$(".info").html(info);
										
					logd.close();
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"dom": 'Bfrtip',
						"buttons": [
							{ 
							extend: 'copyHtml5',
							className: 'btn',		
							footer: true,							
							},
							{ 
							 extend: 'excelHtml5',
							 className: 'btn',		
							 footer: true,							
							},
							{ extend: 'csvHtml5',className: 'btn', footer: true },							
							{
							   text: 'PDF',
							   className: 'btn',
							   action: function ( e, dt, node, config ) {
								    var ck = $("input[name='rd']:checked").val();
									if(ck == undefined){
										ck="";
									}
									window.location.href = "../php/gerapdf-exec.php?act=pdf&rd="+ck+"";
							   }
						   },
						],
						"deferRender": true,
						"paging":   false,
					    "bFilter": false,
					    "info":     false,	
						"order": []				   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
				},
				error: function(data){
					alert(data);	
				}
			})
			return false;
		}				
	});	 				
});

function imprimepdf(htm){
		
		$.ajax({
			type: 'POST',
				cache:false, 
				url: '../php/gerapdf-exec.php',
				data: {act:'pdf',htm:htm},
				success: function(data){
					alert("feito")
				},
				error: function(data){
					alert(data);	
				}	
			
			});
		
}

function tipo(nr) {
    if (typeof nr == 'string' && nr.match(/(\d+[,.]\d+)/)) return 'decimal';
    else if (typeof nr == 'string' && nr.match(/(\d+)/)) return 'inteiro';
    else if (typeof nr == 'number') return nr % 1 == 0 ? 'inteiro' : 'decimal';
    else return false;
}

function convertevalores(valor2){
	if(valor2.length > 2 && valor2.length <= 6){
			var valstr2 = parseFloat(valor2.replace(",","."));
	}else{
		var valstr2 = parseFloat(valor2.replace(",",".").replace(".",""));
	}
	
	return valstr2.toFixed(2);
}

$(document).ready(function(e) {
	var buttonCommon = {
		exportOptions: {
			format: {
				body: function ( data, row, column, node ) {                                                                         
					
					var dat = data.replace(/<.*?>/g, '');	
					var t = tipo(dat); 	
					if(t == 'decimal'){
						return convertevalores(dat);
					}else{
						return  dat;	
					}	
					//return valor column === 5 ? data.replace( data.replace( /[.]/g, '' ), '' ): data;  
				}
			}
		}
	};
 $formscl = $('form[id="frmarquivodominio"]');
 $formscl.validate({
 
	 rules: {
		  dataini:{           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },
		 datafin: {           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },
						 
		 },
	 messages:{
		  dataini: {
				   required:"Selecione a data inicial.",                      
				   },
		 datafin: {
				   required:"Selecione a data final",                      
				   },
				 
							   
		 },
	 submitHandler: function(form) {
		 
	 bootbox.dialog({
			   message: "Enquanto o relatorio vai gerando.. relaxa ;D",
			   title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
			 });
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatorioarquivodominio.php',
			 data: params,	
			 success: function(data){
				 
				
				 var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Dominio de "+formatadata($("#dataini").val())+" a "+formatadata($("#datafin").val())+"</strong></h4>";
				 
				 
				 $("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 bootbox.hideAll();
				 
				 
				 
				 $('.table').dataTable({
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					"iDisplayLength": -1,
					"dom": 'Bfrtip',						
				   buttons: [
					   $.extend( true, {}, buttonCommon, {
						   extend: 'copyHtml5'
					   } ),
					   $.extend( true, {}, buttonCommon, {
						   extend: 'excelHtml5',
						   footer: true
					   } ),
					   /*$.extend( true, {}, buttonCommon, {
						   extend: 'pdfHtml5'
					   } )*/
					   { extend: 'pdfHtml5', footer: true }
				   ],		
				   "order": []					   													
				});													
				 
				 $('.dataTables_length').css({'display':'none'});
				 $('.dataTables_filter').css({'display':'none'});
				 $('.dataTables_paginate').css({'display':'none'});
				 $('.dataTables_info').css({'display':'none'});
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
	 }				
 });
 
 $formscl = $('form[id="frmarquivocontas"]');
 $formscl.validate({
 
	 rules: {
		  dataini:{           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },
		 datafin: {           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },
						 
		 },
	 messages:{
		  dataini: {
				   required:"Selecione a data inicial.",                      
				   },
		 datafin: {
				   required:"Selecione a data final",                      
				   },
				 
							   
		 },
	 submitHandler: function(form) {
		 
	 bootbox.dialog({
			   message: "Enquanto o relatorio vai gerando.. relaxa ;D",
			   title: "<img src='../images/loading-icon.gif'/> Aquarde..",								  
			 });
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatorioarquivocontas.php',
			 data: params,	
			 success: function(data){
				 
				
				 var info = "<h3>"+$('#empresa').val()+"</h3><h4><strong>Contas</strong></h4>";
				 
				 
				 $("#relatoriofatu").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 bootbox.hideAll();
				 
				 
				 
				 $('.table').dataTable({
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					"iDisplayLength": -1,
					"dom": 'Bfrtip',						
				   buttons: [
					   $.extend( true, {}, buttonCommon, {
						   extend: 'copyHtml5'
					   } ),
					   $.extend( true, {}, buttonCommon, {
						   extend: 'excelHtml5',
						   footer: true
					   } ),
					   /*$.extend( true, {}, buttonCommon, {
						   extend: 'pdfHtml5'
					   } )*/
					   { extend: 'pdfHtml5', footer: true }
				   ],		
				   "order": []					   													
				});													
				 
				 $('.dataTables_length').css({'display':'none'});
				 $('.dataTables_filter').css({'display':'none'});
				 $('.dataTables_paginate').css({'display':'none'});
				 $('.dataTables_info').css({'display':'none'});
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
	 }				
 });
 
 $('#geraarqdominio').click(function(){


	if($("#dataini").val() == ''){
		alert("Selecione uma data inicial");
		return false;
	}

	if($("#datafin").val() == ''){
		alert("Selecione uma data inicial");
		return false;
	}
	
	
	$.ajax({
		type: 'POST',			
		url: '../php/arqdominio-exec.php',
		data: {act:'gerar',dataini:$("#dataini").val(),datafin:$("#datafin").val()},	
		dataType: "json",
		beforeSend(){
			dlog = $.dialog({		
				title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
				content: '<h2><img src="../images/ajax-loader.gif" style="width:58px;" /> Aguarde gerando arquivo...</h2>',
			});	
		},
		success: function(data){
			
			console.log(data[0].arquivo);
			if(data[0].tipo == '1'){
				downloadtext('dominio.TXT',data[0].arquivo);		
				dlog.close();				
			}else{
				dlog.close();
				var view  = '';
				var view2 = '';
				var view3 = '';
				var view4 = '';
				if(data[0].msg != ''){
					view = '<div class="message alert">' + data[0].msg + '</div>';
				}
				
				if(data[0].msg2 != ''){
					view2 = '<div class="message alert">' + data[0].msg2 + '</div>';
				}
				if(data[0].msg3 != ''){
					view3 = '<div class="message alert">' + data[0].msg3 + '</div>';
				}
				
				if(data[0].msg4 != ''){
					view4 = '<div class="message alert">' + data[0].msg4 + '</div>';
				}
				
				$(".rel_form_callback").html(view+''+view2+''+view3+''+view4);
				$(".message").effect("bounce");

			}
		},
		error: function(data){
			dlog.close();
			alert(data);	
		}
	})
	return false;

 });

 $('#geraarqcontas').click(function(){
	
	$.ajax({
		type: 'POST',			
		url: '../php/financonta-exec.php',
		data: {act:'gerar',ck:$("#ckclasf").is(":checked"),codclassfica:$("#codclassfica").val(),reduzini:$("#reduzini").val(),reduzfim:$("#reduzfim").val()},	
		dataType: "json",
		beforeSend(){
			dlog = $.dialog({		
				title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
				content: '<h2><img src="../images/ajax-loader.gif" style="width:58px;" /> Aguarde gerando arquivo...</h2>',
			});	
		},
		success: function(data){
			
			console.log(data[0].arquivo);
		
			downloadtext('contas.TXT',data[0].arquivo);		
			dlog.close();				
		
		},
		error: function(data){
			dlog.close();
			alert(data);	
		}
	})
	return false;

 });

 
			
});

function downloadtext(filename, text) {
	//console.log(filename+' '+text);
  var element = document.createElement('a');
  element.setAttribute('href', ''+text+'');
  element.setAttribute('download', ''+filename+'');

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}

$(document).ready(function(){

	$('.deletehistpadrao').click(function(){
		var conf = confirm('Continue delete?');
	if(conf)
			$(this).parents('tr').fadeOut(function(){
				
				$.ajax({
					type:'POST',
					url:"../php/historicopadrao-exec.php",
					data:{act:'deletar',id: $(this).attr('id')},
					success: function(data){
						
						$(this).remove();
						
					}	
				});
				
				
		});
	return false;
});

$('.deleteformpagto').click(function(){
	var conf = confirm('Continue delete?');
if(conf)
		$(this).parents('tr').fadeOut(function(){
			
			$.ajax({
				type:'POST',
				url:"../php/vinculoconta-exec.php",
				data:{act:'deletar',id: $(this).attr('id')},
				success: function(data){
					
					$(this).remove();
					
				}	
			});
			
			
	});
return false;
});

});
$(".contactb").attr("onkeypress","return enterTabula(this,event)");
$(".contactbfor").attr("onkeypress","return enterTabula(this,event)");
$(".histpadrao").attr("onkeypress","return enterTabula(this,event)");
function enterTabula (f, e) {
	var charCode = (e.keyCode) ? e.keyCode : e.which;
	if (charCode == 13) {
		var i;
		for (i = 0; i < f.form.elements.length; i++) {
			if (f == f.form.elements[i]) {
				break;
			}
		}

		i = (i + 1) % f.form.elements.length;
		for (ii = i; ii < f.form.elements.length; ii++)	{
			if ( (f.form.elements[ii].readOnly!=true) &&
(f.form.elements[ii].type!='button') && (f.form.elements[ii].type!='hidden') ) {
				break;
			}
		}
		//alert('tipo='+f.form.elements[ii].type+'name='+f.form.elements[ii].name)
		f.form.elements[ii].focus();
		f.form.elements[ii].select();
		return false;

	} else
		return true;
}

$(document).ready(function(){
	$(".contactb").click(function(){
		$(this).select();
	});
	$(".contactbfor").click(function(){
		$(this).select();
	});
	
	$(".contactb").keydown(function(e){
		
		var key = e.which || e.keyCode;
		
		if (key == 13) {
			
			var cod     = $(this).attr("id").split('_')[1];
			var codconta = $("#contctb_"+cod+"").val(); 
			$.ajax({
				type: 'POST',
				url: '../php/cliente-exec.php',
				cache:false, 
				dataType: "json",
				data: {act:'alteraconta',codconta:codconta,codcli:cod},	
				success: function(data){
					if(data[0].tipo == '1'){
						$(this).focus();
						$(".msg_"+data[0].idcli+"").html('<i class="icon-check success" style="color: green;"></i>');
						$("#fil_"+data[0].idcli+"").removeClass("vazio");
						$("#fil_"+data[0].idcli+"").addClass("tem");

						/*var sel = setInterval(function(){
							
							$.fn.dataTable.ext.search.pop();
							datatablecli.draw();

							$("input[name='ckfaltam']").change();
							clearInterval(sel);
						},300);*/

					}else{
						alert(data[0].msg);	
						$(this).val('');
						$(this).focus();						
					}
				},
				error: function(data){
					console.log(data.status);	
				}
			});

		}
	

	});

	$(".contactbfor").keydown(function(e){
		
		var key = e.which || e.keyCode;
		
		if (key == 13) {
			
			var cod     = $(this).attr("id").split('_')[1];
			var codconta = $("#contctb_"+cod+"").val(); 
			$.ajax({
				type: 'POST',
				url: '../php/fornecedor-exec.php',
				cache:false, 
				dataType: "json",
				data: {act:'alteraconta',codconta:codconta,codfor:cod},	
				success: function(data){
					if(data[0].tipo == '1'){
						$(this).focus();
						$(".msg_"+data[0].idcli+"").html('<i class="icon-check success" style="color: green;"></i>');
						$("#fil_"+data[0].idcli+"").removeClass("vazio");
						$("#fil_"+data[0].idcli+"").addClass("tem");
						/*var sel = setInterval(function(){
							
							$.fn.dataTable.ext.search.pop();
							datatablecli.draw();

							$("input[name='ckfaltam']").change();
							clearInterval(sel);
						},300);*/
						

					}else{
						alert(data[0].msg);	
						$(this).val('');
						$(this).focus();						
					}
				},
				error: function(data){
					console.log(data.status);	
				}
			});

		}
	

	});

	$(".histpadrao").keydown(function(e){
		
		var key = e.which || e.keyCode;
		
		if (key == 13) {
			
			var cod     = $(this).attr("id").split('_')[1];
			var codhist = $("#idhistp_"+cod+"").val(); 
			$.ajax({
				type: 'POST',
				url: '../php/financonta-exec.php',
				cache:false, 
				dataType: "json",
				data: {act:'updatehist',codhist:codhist,codfinanc:cod},	
				success: function(data){
					if(data[0].tipo == '1'){
						$(this).focus();
						$(".msg_"+data[0].idfinanc+"").html('<i class="icon-check success" style="color: green;"></i>');
						$("#fil_"+data[0].idfinanc+"").removeClass("vazio");
						$("#fil_"+data[0].idfinanc+"").addClass("tem");
						/*var sel = setInterval(function(){
							
							$.fn.dataTable.ext.search.pop();
							datatablecli.draw();

							$("input[name='ckfaltam']").change();
							clearInterval(sel);
						},300);*/
						

					}else{
						alert(data[0].msg);	
						$(this).val('');
						$(this).focus();						
					}
				},
				error: function(data){
					console.log(data.status);	
				}
			});

		}
	

	});

	$("input[name='ckfaltam']").change(function(e){
		var ck = $(this).is(':checked');
		ajax_load_padrao('open');

		var set =  setInterval(function(){
			var filter = function( settings, searchData, index, rowData) {
				var $td = datatablecli.row(index).nodes().to$().find('td:eq(2)');
				//console.log($td.find('.vazio').length);
			  return $td.find('.vazio').length;
			}
	
			if(ck == true){			
				$.fn.dataTable.ext.search.push(filter);
				datatablecli.draw();
				ajax_load_padrao('close');
			}else{
				$.fn.dataTable.ext.search.pop();
				datatablecli.draw();
				ajax_load_padrao('close');
			}

			
			clearInterval(set);
		},300);

		//console.log(ck);
	});

	$('#new-search').keyup(function(e){
		var key = e.which || e.keyCode;		
		if (key == 13) {
			$('#dyntableclientes').dataTable().fnFilter(this.value);
		}
	});


	$(".bt-finccontas").click(function(){

		var cod  =  $(this).parents('tr').attr('id');
		var nome = $('#dyntableclientes tbody tr[id="'+cod+'"] td:eq(1)').html();

		$.ajax({
			type: 'POST',
			url: '../php/financonta-exec.php',
			cache:false, 
			dataType: "json",
			data: {act:'buscaid'},	
			success: function(data){
				
				var htm = `

					<form action="../php/financonta-exec.php" method="post" id="cadfinccontas">
						<input type="hidden" name="act" value="inserir"/>
						<input type="hidden" name="id" value="${data[0].id}"/>
						<input type="hidden" name="codtr" value="${cod}"/>
						<div class="financ_form_callback"></div>
						<div class="input-prepend">
							<label>Código Reduzido:</label>
							<span class="add-on icon-terminal"></span>
							<input type="text" name="codreduz" class="span4 padraoinputheight" id="codreduz" value="${data[0].idred}"/>
						</div>

						<div class="input-prepend">
							<label>Descrição:</label>
							<span class="add-on icon-terminal"></span>
							<input type="text" name="desc" class="span4 padraoinputheight" id="desc" value="${nome}"/>
						</div>

						<div class="input-prepend">
							<label>Código de Classificação:</label>
							<span class="add-on icon-terminal"></span>
							<input type="text" name="codclass" class="span4 padraoinputheight" id="codclass" value="2.1.1.04.001"/>
						</div>
						<input class="btn btn-primary btn-block" type="submit" value="Salvar">
					</form>

				`;

				dlog = $.confirm({
					title: 'Cadastro Financ Contas',
					content: ''+htm+'',
					type: 'green',
					typeAnimated: true,
					columnClass: 'col-md-5 col-md-offset-4',
					buttons: {
						tryAgain: {
							text: 'Fechar',
							btnClass: 'btn-red',
							action: function(){
								dlog.close();
							}
						}
					}
				});


			},
			error: function(data){
				console.log(data.status);	
				dlog.close();
			}
		});

	});

});

$(document).on('submit','form[id="cadfinccontas"]',function(e){

	e.preventDefault();

	var form = $(this);
	var action = form.attr("action");
	var data = form.serialize();

	$.ajax({
		url: action,
		data: data,
		type: "post",
		dataType: "json",
		beforeSend: function (load) {
			ajax_load("open");
		},
		success: function (su) {
			ajax_load("close");

			if (su.message) {
				var view = '<div class="message ' + su.message.type + '">' + su.message.message + '</div>';
				$(".financ_form_callback").html(view);
				$(".message").effect("bounce");

				$(".txtconta_"+su.message.dados.codtr+"").html(su.message.dados.desc);
				$("#contctb_"+su.message.dados.codtr+"").val(su.message.dados.id);
				$("#contctb2_"+su.message.dados.codtr+"").val(su.message.dados.codreduz);

				var teclaEsquerda = $.Event("keydown");
				teclaEsquerda.ctrlKey = false;
				teclaEsquerda.which = 13; 
				$("#contctb2_"+su.message.dados.codtr+"").trigger(teclaEsquerda);
				dlog.close();

				return;
			}
		
		}
	});

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


$(document).ready(function(){

	$("#ckclasf").change(function(){
		var ck = $(this).is(":checked");
		$('#codclassfica').val('');
		if(ck == true){

			$(".codclassfica").removeClass('hide');

		}else{
			$(".codclassfica").addClass('hide');
		}

	});
});

function ajax_load_padrao(action) {
	ajax_load_div = $(".ajax_load");

	if (action === "open") {
		ajax_load_div.fadeIn(200).css("display", "flex");
	}

	if (action === "close") {
		ajax_load_div.fadeOut(200);
	}
}
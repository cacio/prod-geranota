$(function(){
	$("input[name='cnpj']").mask("99.999.999/9999-99");
	$("input[name='gnt-ema']").mask("99.999.999/9999-99");
	$("input[name='cnpjemp']").mask("99.999.999/9999-99");
	$("#telefone").mask("(99)9999-9999");
	$("#cep").mask("99999999");

	$("#btncadconta").click(function(){

		$(".splash").addClass('hidden');
		$(".redefinasenha").addClass('hidden');
		$(".cadconta").removeClass('hidden');
		
	});

	$(".btnredefina").click(function(){

		$(".splash").addClass('hidden');
		$(".cadconta").addClass('hidden');
		$(".redefinasenha").removeClass('hidden');
		
	});
	
	$.validate({
        modules : 'date, security',
        onModulesLoaded : function() {
		    var optionalConfig = {
		      fontSize: '12pt',
		      padding: '4px',
		      bad : 'Muito mal',
		      weak : 'Fraca',
		      good : 'Boa',
		      strong : 'Forte'
		    };

		    $('input[name="pschave"]').displayPasswordStrength(optionalConfig);
		  }
    });

    
});

$(document).on('submit','form[id="fmrcadcli"]',function(){

		var param = $(this).serialize();
		

		$.ajax({
			 type: 'POST',
			 url:"../php/empresa-exec.php",
			 data:param,
			 beforeSend: function(){
				
			 },
			 cache:false,
			 dataType: "json",
			 success: function(data){
				
				alert(data[0].success);
				window.location.reload();
																													
			},
			error: function(jqXHR, exception){
				alert("Ocorreu um Erro inesperado, Por Favor entre em contato com o administrador do sistema!");
			}	
		});

		
		return false;

});

$(document).on('blur','#cep',buscacep);

function buscacep(){
				
	jQuery("#endereco").val("...");
	jQuery("#bairro").val("...");
	jQuery("#cidade").val("...");
	jQuery("#estado").val("...");
	
	var consulta = jQuery("#cep").val();
	
	$.ajax({
		type: 'POST',			
		url: 'http://cep.republicavirtual.com.br/web_cep.php',
		data: {cep:''+consulta+'',formato:'json'},	
		dataType: 'json',
		success: function(data){
				
	    var  rua    = unescape(data.logradouro);
	    var  bairro = unescape(data.bairro);
	    var  cidade = unescape(data.cidade);
        var  uf     = unescape(data.uf);
        
        jQuery("#endereco").val(rua.toLocaleUpperCase());
        jQuery("#bairro").val(bairro.toLocaleUpperCase());
        jQuery("#cidade").val(cidade.toLocaleUpperCase());
        jQuery("#estado").val(uf.toLocaleUpperCase());
		console.log(data);
											
		},
		error: function(data){
			alert(data);	
		}
	});
	
	return false;
		
}
$(document).on('blur','#cnpj',function(){

	consultacnpj($(this).val());

});
function consultacnpj(cnpj){

		//alert(cnpj);
		if(cnpj){
			$.ajax({
				 type: 'POST',
				 url:"../php/empresa-exec.php",
				 data:{'act':'consulta',cnpj:cnpj},
				 beforeSend: function(){
					
				 },
				 cache:false,
				 dataType: "json",
				success: function(data){
						
					if(data[0].tipo == 1){
						alert(data[0].msg);
						$("#cnpj").val('');
						$("#cnpj").select();
					}	

				},
				error: function(jqXHR, exception){
					alert("Ocorreu um Erro inesperado, Por Favor entre em contato com o administrador do sistema!");
				}	
			});
		}

}


$(document).on('submit','form[id="recoverform"]',function(){

	var param = $(this).serialize();

	$.ajax({
		type: 'POST',
		url: this.action,
		data: param,
		dataType: "json",
		// Antes de enviar
		beforeSend: function(){
						
		},
		success: function(data){
		
			if(data[0].tipo == 1){
				
				$('form[id="recoverform"]').html("<div align='center'><img src='../images/sucess.png' /><br/> "+data[0].mensagem+" <div class='form-group m-b-0'><div class='col-sm-12 text-center'><p>VOLTAR PARA O <a href='login.php' class='text-info m-l-5'><b>LOGIN</b></a></p></div></div></div></div>");	

			}else if(data[0].tipo == 2){

				alert(data[0].mensagem);
			}								
		},
		error: function(data){
			alert("Ops!, Algo não ocorreu como esperado mais informações ligar para o agregar para que possamos entender o que quer fazer!");			
		}
	});
	return false;
});


$(document).on('submit','form[id="refinaloginform"]',function(){

	var param = $(this).serialize();

	$.ajax({
		type: 'POST',
		url: this.action,
		data: param,
		dataType: "json",
		// Antes de enviar
		beforeSend: function(){
						
		},
		success: function(data){
		
			if(data[0].tipo == 1){
				
				$('form[id="refinaloginform"]').html("<div align='center'><img src='../images/sucess.png' /><br/> "+data[0].msg+" <div class='form-group m-b-0'><div class='col-sm-12 text-center'><p>VOLTAR PARA O <a href='login.php' class='text-info m-l-5'><b>LOGIN</b></a></p></div></div></div>");	

			}else if(data[0].tipo == 2){

				alert(data[0].msg);
			}								
		},
		error: function(data){
			alert("Ops!, Algo não ocorreu como esperado mais informações ligar para o agregar para que possamos entender o que quer fazer!");			
		}
	});
	return false;

});
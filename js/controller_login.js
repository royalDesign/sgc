$(function(){
	
	var form 		= $('form[name="login"]');
	var email		= $('#email');
	var senha		= $('#senha');
	var debug		= $('.debug');
	var debugSend	= $('#j_send');
	var flagemail   = 0;
	var flagsenha   = 0;

	form.submit(function(){

		if(email.val() == ''){
			email.addClass('is-invalid');
			flagemail   = 0;
		}
		else if(email.val() != ''){
			email.removeClass('is-invalid').addClass('is-valid');
			flagemail   = 1;
		}

		if(senha.val() == ''){
			senha.addClass('is-invalid');
			flagsenha   = 0;
		}else if(senha.val() != ''){
			senha.removeClass('is-invalid').addClass('is-valid');
			flagsenha   = 1;
		}

		if(flagsenha  == 1 && flagemail == 1){
			

			debugSend.attr('disabled', 'disabled');

			 $(this).ajaxSubmit({

			      url:                  'class/controler_log.php',
			      data:                 {login: 'logar'},
			      beforeSubmit:         function(){
			        debugSend.empty().html('<img src="img/load_b.svg" width="20px">Carregando...');
			      },
			      resetForm:        true,
			      error:                function(){
			          debug.empty().removeClass('alert-info, alert-dismissible').addClass('alert-danger,alert-dismissible').html('TESTE');
			      },
			      success:        function(resposta){
			        debug.html(resposta).fadeIn('slow');;       
			      },
			      complete: 		function(){
			      	email.removeClass('is-valid');
			      	senha.removeClass('is-valid');
			      	debugSend.empty().html('<i class="fa fa-sign-in" aria-hidden="true"></i> Entrar');
			      	debugSend.removeAttr('disabled');

			      }

			    });//fim do ajax subimit



		}else{
			debug.addClass('alert-danger alert').html('<h4>Impossível prosseguir!</h4><hr>Todos os campos são obrigatórios.').fadeIn('slow').delay(1500).fadeOut('slow', function(){$(this).removeClass('alert-danger alert')});
		}

		return false;
	});//fim do submit


	
/*===========================================|FORMULÁRIO DE REGISTRO|====================================================*/

	var form 		= $('form[name="registrar"]');
	var nome		= $('#nome');
	var email		= $('#email');
	var senha1		= $('#pass1');
	var senha2		= $('#pass2');
	var debug		= $('.debug');
	var debugSend	= $('#j_send');
	var flagemail   = 0;
	var flagnome    = 0;
	var flagsenha1  = 0;
	var flagsenha2  = 0;
	var flagtermos	= 0;

nome.blur(function(){
	if(nome.val() == ''){
			$('#divNome').addClass('has-error');
			flagnome   = 0;
		}
		else if(nome.val() != ''){
			$('#divNome').removeClass('has-error').addClass('has-success');
			flagnome   = 1;
		}
});

email.blur(function(){
if(email.val() == ''){
			$('#divEmail').addClass('has-error');
			flagemail   = 0;
		}
		else if(email.val() != ''){
			$('#divEmail').removeClass('has-error').addClass('has-success');
			flagemail   = 1;
		}
});

senha1.blur(function(){
if(senha1.val() == ''){
			$('#divSenha1').addClass('has-error');
			flagsenha1   = 0;
		}else if(senha1.val() != ''){
			$('#divSenha1').removeClass('has-error').addClass('has-success');
			flagsenha1   = 1;
		}
});

senha2.blur(function(){
if(senha2.val() == ''){
			$('#divSenha2').addClass('has-error');
			flagsenha2   = 0;
		}else if(senha2.val() != senha1.val()){
			$('#spans2').css("color", "red").text('As senhas não conferem').fadeIn('fast');
			$('#divSenha2').addClass('has-error');
			flagsenha2   = 0;
		}else{
			$('#divSenha2').removeClass('has-error').addClass('has-success');
			$('#spans2').css("color", "red").text('As senhas não conferem').fadeOut('fast');
			flagsenha2   = 1;
		}
});


	form.submit(function(){

		if(nome.val() == ''){
			$('#divNome').addClass('has-error');
			flagnome   = 0;
		}
		else if(nome.val() != ''){
			$('#divNome').removeClass('has-error').addClass('has-success');
			flagnome   = 1;
		}

		if(email.val() == ''){
			$('#divEmail').addClass('has-error');
			flagemail   = 0;
		}
		else if(email.val() != ''){
			$('#divEmail').removeClass('has-error').addClass('has-success');
			flagemail   = 1;
		}

		if(senha1.val() == ''){
			$('#divSenha1').addClass('has-error');
			flagsenha1   = 0;
		}else if(senha1.val() != ''){
			$('#divSenha1').removeClass('has-error').addClass('has-success');
			flagsenha1   = 1;
		}

		if(senha2.val() == ''){
			$('#divSenha2').addClass('has-error');
			flagsenha2   = 0;
		}else if(senha2.val() != '' && senha2.val() == senha1.val()){
			$('#divSenha2').removeClass('has-error').addClass('has-success');
			flagsenha2   = 1;
		}

		if($('input:checked').val() != '1'){			
			$('#spanstermos').css("color", "red").fadeIn('fast');
			flagtermos   = 0;
		}else if($('input:checked').val() == '1'){			
			flagtermos   = 1;
			$('#spanstermos').fadeOut('fast');
		}

		if(flagsenha1  == 1 && flagsenha2  == 1 && flagemail == 1 && flagnome  == 1 && flagtermos  == 1){
			

			debugSend.attr('disabled', 'disabled');

			 $(this).ajaxSubmit({

			      url:                  'class/controler_log.php',
			      data:                 {registro: 'registrar'},
			      beforeSubmit:         function(){
			        debugSend.empty().html('<img src="img/load_b.svg" width="20px">Carregando...');
			      },
			      resetForm:        true,
			      error:                function(){
			          debug.empty().removeClass('alert-info, alert-dismissible').addClass('alert-danger,alert-dismissible').html('TESTE');
			      },
			      success:        function(resposta){
			        debug.html(resposta).fadeIn('slow');;       
			      },
			      complete: 		function(){
			      	email.removeClass('is-valid');
			      	senha.removeClass('is-valid');
			      	debugSend.empty().html('<i class="fa fa-sign-in" aria-hidden="true"></i> Entrar');
			      	debugSend.removeAttr('disabled');
			      	$('#divNome').removeClass('has-success');
			      	$('#divEmail').removeClass('has-success');
			      	$('#divSenha1').removeClass('has-success');
			      	$('#divSenha2').removeClass('has-success');

			      }

			    });//fim do ajax subimit



		}else{
			debug.addClass('alert-danger alert').html('<h4>Impossível prosseguir!</h4><hr>Todos os campos são obrigatórios.').fadeIn('slow').delay(1500).fadeOut('slow', function(){$(this).removeClass('alert-danger alert')});
		}

		return false;
	});//fim do submit
	
});//FINAL DE TODAS AS INSTRUÇÕES da pagina


function valid_fild(valid_type,field){

if(valid_type == 'required'){
  
  if($.trim(field.val()) == ''){
    
    field.focus();    
new PNotify({
    title: 'Campo obrigatório!',
    text: 'Preencha este campo.',
    type: 'error'
    
});
return false;

  }else{
    return true;
  }
  
}else if(valid_type == 'email'){


var emailFilter=/^.+@.+\..{2,}$/;
var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/

var email = field.val();
    
   if(!(emailFilter.test(email))||email.match(illegalChars)){      
field.focus();
new PNotify({
    title: 'E-mail inválido!',
    text: 'Informe um e-mail válido',
    type: 'error'
    
});


      return false;

    }else{
      return true;  
    }
}


}/*END valid_fild*/


function send_form(form,btn,var_control){

btn.attr('disabled', 'disabled');
form.ajaxSubmit({

url:                  'class/controler.php',
data:                 {exec: var_control},
beforeSubmit:         function(){
  btn.empty().html('<img src="img/load_b.svg" width="20px">Aguarde...');
},
resetForm:        true,
error:              function(resposta){
          new PNotify({
          title: 'Erro!',
          text: 'Desculpa, aconteceu um erro inesperado.'+resposta,
          type: 'error'
          
          });
},
success:        function(resposta){
  
  if(resposta == -1){

new PNotify({
          title: 'Login inválido!',
          text: 'E-mail ou senha incorreto',
          type: 'info'
          
          });

}else{
   new PNotify({
          title: 'Sucesso!',
          text: resposta,
          type: 'success'
          
          });
}

   
  

},

complete:     function(){
  
  btn.empty().html('<i class="fa fa-sign-in" aria-hidden="true"></i> Entrar');
  btn.removeAttr('disabled');

}

});//fim do ajax subimit

 return false;
}/*END send_form*/

function logar(){


  var form    = $('form[name="login"]');
  var email   = $('#email');
  var senha   = $('#senha');
  var debug   = $('.debug');
  var btn = $('#j_send');
  

if(valid_fild('email',email) && valid_fild('required',senha)){

send_form(form,btn,'logar');

}else{

form.submit(function(event) {
  return false
});
}
}/*End logar*/

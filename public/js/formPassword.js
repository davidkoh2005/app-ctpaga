var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);

$(function(){
    var $sections = $('.form-section');

    $('#loading').hide();

    
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
    });

    function navigateTo(index){
        $sections.removeClass('current').eq(index).addClass('current');
        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation [type=submit]').toggle(arTheEnd);

    }


    $('.form-navigation .submit').click(function(e){
        e.preventDefault();
        $('#errorPassword').empty();
        var errorValidate = 'La contraseña es inválida, debe tener:';
        var epUpperCase = "(?=.*[A-Z])";                 // should contain at least one upper case
        var epLowerCase = "(?=.*[a-z])";                    // should contain at least one lower case
        var epDigit= "(?=.*?[0-9])";                        // should contain at least one digit
        var epSpecialCharacter = "(?=.*?[-_!@#\$&*~])";  // should contain at least one Special character
        
        password = $('#password').val();
        passwordConfirm = $('#password_confirmation').val();
        if(password.length == 0 || passwordConfirm == 0){
            $('#errorPassword').html('Ingrese la contraseña correctamente');
        }else{
            var regExp = new RegExp(epUpperCase);
            if (!regExp.test(password)){
                errorValidate = errorValidate + '<li>* Una letra mayúscula.</li>';
            }

            regExp = new RegExp(epLowerCase);
            if (!regExp.test(password)){
                errorValidate = errorValidate + '<li>* Una letra minúscula.</li>';
            }

            regExp = new RegExp(epDigit);
            if (!regExp.test(password)){
                errorValidate = errorValidate + '<li>* Un número numérico.</li>';
            }

            regExp = new RegExp(epSpecialCharacter);
            if (!regExp.test(password)){
                errorValidate = errorValidate + '<li>* Un Carácter Especial.</li>';
            }
        
            if (password.length < 6){
                errorValidate = errorValidate + '<li>* Al menos 6 caracteres.</li>';
            }
        
            if (errorValidate == 'La contraseña es inválida, debe tener:'){
                $('#errorPassword').html('La contraseña no coincide');
                if(password == passwordConfirm){
                    $('#errorPassword').empty();
                    $("#password-form").submit();
                    $('.submit').hide();
                    $('#loading').show();
                }else if(password != passwordConfirm){
                    $('#errorPassword').html('La contraseña no coincide');
                }
            }else{
                $('#errorPassword').html('<ul><li>'+errorValidate+'</li></ul>');
            }
        }
        
    });

    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    });

    navigateTo(0);


});
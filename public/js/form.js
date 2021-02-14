var percentage = 0;
var totalGlobal = 0;
var shippingPrice = 0;
var shippingCoin = 0;
var _coinClient = 0;
var _rate = 0;
var _selectSale, selectPayment;

$(function(){
    var $sections = $('.form-section');
    var statusShippingClient = false;
    var clickPayment = false;
    var statusLoading = false;
    var switchPay = false;
    var checkDiscount = false;

    $('#errorCard').hide();
    $('#loading').hide();    

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
    });

    function navigateTo(index){
        $sections.removeClass('current').eq(index).addClass('current');
        if(statusModification)
            $('.form-navigation .previous').toggle(index>0);
        else
            $('.form-navigation .previous').toggle(index>1);

        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation .pay').toggle(index == 0);
        $('.form-navigation .save').toggle(index == 1);
        $('.form-navigation .next').toggle(!arTheEnd && index != 0 && index != 1);

        $('.form-navigation [type=submit]').toggle(arTheEnd);
        
        switch (index) {
            case 0:
                $(".title-sales").text("Ventas");
                break;
            case 1:
                $(".title-sales").text("Modificar Cantidad");
                break;
            case 2:
                $(".title-sales").text("Correo Electrónico");
                break;
            case 3:
                $(".title-sales").text("Envio");
                break;
            case 4:
                $(".title-sales").text("Dirección de Envio");
                break;
            case 5:
                $(".title-sales").text("Información del Pago");
                break;
            case 6:
                $(".title-sales").text("Descuento");
                if($("#switchDiscount").is(':checked')){
                    $("#discount" ).prop( "disabled", $(this).is(':checked'));
                    $('#discount').val('');
                    $('#percentageSelect').val(0);
                    $('.next').show();
                }
                break;
            case 7:
                $(".title-sales").text("Facturación");
                break;
            default: 
                alert("error");
        }


        if(index == 3){
            if(statusShippingClient)
                $('.next').show();
            else
                $('.next').hide();
        }

        if(index == 5){
            if(clickPayment)
                $('.next').show();
            else
                $('.next').hide();
        }
            

        if(index == 6){
            $( "#discount" ).prop( "disabled", checkDiscount);
            if($("#switchDiscount").is(':checked') || $('#percentageSelect').val() != 0)
                $('.next').show();
            else
                $('.next').hide();
        }

        if(index == 7)
            calculateTotal();
    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $('.form-navigation .previous').click(function(){

        if(!statusLoading){
            if(curIndex()==5 && $('#statusShipping').val() =='false')
                navigateTo(curIndex()-3);
            else if(curIndex()==2)
                navigateTo(curIndex()-2);
            else
                navigateTo(curIndex()-1);
        }   
        
    })

    $('.form-navigation .pay').click(function(){
        $('.alert-danger').hide();
        $('.contact-form').parsley().whenValidate({
            group: 'block-' + curIndex()
        }).done(function(){
            navigateTo(curIndex()+2);
        })
    })

    $('.form-navigation .next').click(function(){
        if(curIndex() == 2 && $('#statusShipping').val()=='false'){
            $('.contact-form').parsley().whenValidate({
                group: 'block-' + curIndex()
            }).done(function(){
                navigateTo(curIndex()+3);
            }) 
        }

        if(curIndex() == 5){
            $('#errorCard').hide();
            if(_coinClient == 0 && selectPayment)
                navigateTo(curIndex()+1);
            else{
                if(switchPay){
                    $('.contact-form').parsley().whenValidate({
                        group: 'block-' + curIndex()
                    }).done(function(){
                        navigateTo(curIndex()+1);
                    })
                }else{
                    if(validateDate() && $("input[name='typeCard']:checked").length  == 1){
                        $('.contact-form').parsley().whenValidate({
                            group: 'block-' + curIndex()
                        }).done(function(){
                            navigateTo(curIndex()+1);
                        })
                    }else{
                        $('#errorCard').show();
                        $('.contact-form').parsley().whenValidate({
                            group: 'block-' + curIndex()
                        });
                    }
                }
            }
                

        }
        else{
            $('.contact-form').parsley().whenValidate({
                group: 'block-' + curIndex()
            }).done(function(){
                navigateTo(curIndex()+1);
            })
        }

    })

    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    })

    navigateTo(0);

    $('.shippings').on('click', function(){
        $('#svg-check').remove();
        var checkbox = $(this).find('input[type=radio]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        selectShipping = checkbox.val();
        var svg = $(this).find('#iconChecked');
        svg.append("<svg width='2em' height='2em' viewBox='0 0 16 16' class='bi bi-check-circle-fill' id='svg-check' fill='currentColor'><path fill-rule='evenodd' d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/></svg>");
        
        shippingPrice = $(this).find('#shippingPrice').val();
        shippingCoin = $(this).find('#shippingCoin').val();
        statusShippingClient = true;
        $('#selectShipping').val($(this).find('#shippingDescription').val());
        $('.next').show();
    });

    $('.checkPayment').on('click', function(){
        $('#svg-check').remove();
        var checkbox = $(this).find('input[type=radio]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        selectPayment = checkbox.val();
        var svg = $(this).find('#iconChecked');
        svg.append("<svg width='2em' height='2em' viewBox='0 0 16 16' class='bi bi-check-circle-fill' id='svg-check' fill='currentColor'><path fill-rule='evenodd' d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/></svg>");
        clickPayment = true;
        $('#selectPayment').val($(this).find('#paymentDescription').val());

        if($(this).find('#paymentDescription').val() == "EFECTIVO")
            switchPay = true;
        else 
            switchPay = false;

        $('.next').show();
    });

    $("#switchDiscount").on('click', function(){
        $( "#discount" ).prop( "disabled", $(this).is(':checked'));
        checkDiscount = $(this).is(':checked');
        if($(this).is(':checked')){
            $('#discount').val('');
            $('#percentageSelect').val(0);
            $('.next').show();
        }
        else{
            $('.next').hide();
        }
    });

    $("#switchPay").on('click', function(){
        if($(this).is(':checked')){
            switchPay = true;
            $(".dataPay").hide();
            $("#nameCard").prop('required',false);
        }
        else{
            switchPay = false;
            $(".dataPay").show();
            $("#nameCard").prop('required',true);
        }
    });

    $(".submit").on('click', function(e){
        e.preventDefault();
        statusLoading = false;
        $('.submit').hide();
        $('#loading').show();

        $("#payment-form").submit();
    });


    $('.sales').on('click', function(){
        if(statusModification){
            $('#saleQuantity').text($(this).find('#desingQuantity').text());
            _selectSale = $(this).find('#idSale').val();
            navigateTo(1);
        }
    });
    
    $("#numberCard, #dateMM, #dateYY, #cardCVC").inputFilter(function(value) {
        return /^\d*$/.test(value);    // Allow digits only, using a RegExp
    });

});


(function($) {
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    };
  }(jQuery));

function showTotal(price, rate, coin, coinClient, quantity){
    var result = exchangeRate(price, rate, coin, coinClient);

    if (coinClient == 0)
        return "$ "+formatter.format((result * quantity));
    else
        return "Bs "+formatter.format((result * quantity));

}

function calculateTotal(){
    var resultShipping;
    var resulttotal;
    var total = $("#totalProductService").val();

    total = total.replaceAll(".", "");
    total = total.replaceAll(",", ".");

    total = parseFloat(total);
    var percentage = parseInt($("#percentageSelect").val());
    resultShipping = exchangeRate(shippingPrice, _rate, shippingCoin, _coinClient);

    if(_coinClient == 0)
        $(".showShipping").text("$ "+formatter.format(resultShipping));
    else
        $(".showShipping").text("Bs "+formatter.format(resultShipping));

    $(".showPercentage").text("Descuento: "+percentage+" %");
    $("#priceShipping").val(formatter.format(resultShipping));

    if (_coinClient == 0)
        resulttotal = "Total: $ "+formatter.format((total-((total*percentage)/100)+resultShipping));
    else
        resulttotal = "Total: Bs "+formatter.format((total-((total*percentage)/100)+resultShipping));

    $("#totalAll").val(formatter.format((total-((total*percentage)/100)+resultShipping)));
    $(".totalGlobal").text(resulttotal);

    if(formatter.format((total-((total*percentage)/100)+resultShipping)) == "NaN")
        location.reload(); 
    
}

function addNum(value){
    var result = $('#saleQuantity').text().replace(/^0+/, '');
    $('#saleQuantity').text(result+value);
}

function removeNum(){
    var result = $('#saleQuantity').text().substring(0, $('#saleQuantity').text().length - 1);
    if (result.length == 0)
        $('#saleQuantity').text("0");
    else
    $('#saleQuantity').text(result);
}


function validateDate(){
    var minMonth = new Date().getMonth() + 1;
    var minYear = new Date().getFullYear().toString().substr(2,2);
    minYear = parseInt(minYear);
    var month = parseInt($('#dateMM').val(), 10);
    var year = parseInt($('#dateYY').val(), 10);
    return (month && year && (month >= 1 && month <= 12) && (year == minYear && month >= minMonth) || year > minYear);
}
var percentage = 0;
var totalGlobal = 0;
var shippingPrice = 0;
var shippingCoin = 0;
var _coinClient = 0;
var _rate = 0;
var dataShipping = [];
var _selectSale, selectPayment, applicationId;
var submit = false;
var totalPayment = 0;

$(document).ready(function(){
    var $sections = $('.form-section');
    var statusShippingClient = false;
    var statusLoading = false;
    var switchPay = false;
    var checkDiscount = true;

    $('#showCardForm').hide();
    $('#errorCard').hide();
    $('#loading').hide();    
    _coinClient = $('#coinClient').val();

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
    });

    function navigateTo(index){
        curIndexJS = index;
        $sections.removeClass('current').eq(index).addClass('current');
        $(".form-navigation .next").removeClass('btn-active');

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
                $(".title-sales").text("Descuento");
                break;
            case 6:
                $(".title-sales").text("Facturación");
                break;
            case 7:
                $(".title-sales").text("Método de Pago");
                break;
            default: 
                console.log("error case");
        }


        if(index == 3){
            if(statusShippingClient){
                $(".form-navigation .next").addClass('btn-active');
                $('.next').show();
            }
            else{
                $('.next').hide();
                $(".form-navigation .next").removeClass('btn-active');
            }
        }

        if(index == 4){
            if($('#selectState').val())
                dataShipping.push($('#selectState').val());
            
            if($('#selectMunicipalities').val())
                dataShipping.push($('#selectMunicipalities').val());

            if(dataShipping.length == 6)
                $(".form-navigation .next").addClass('btn-active');
            else
                $(".form-navigation .next").removeClass('btn-active');
        }

        if(index == 7){
            if(_coinClient == 0)
                $('.submit').hide();
            else
                $('.submit').show(); 
        }
            

        if(index == 5){
            $( "#discount" ).prop( "disabled", checkDiscount);
            if(!$("#switchDiscount").is(':checked') || $('#percentageSelect').val() != 0){
                $('.next').show();
                $(".form-navigation .next").addClass('btn-active');
            }
            else{
                $('#percentageSelect').val("0");
                $(".form-navigation .next").removeClass('btn-active');
                $('.next').hide();
            }
        }

        if(index == 6){
            calculateTotal();
            $(".form-navigation .next").addClass('btn-active');
        }
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

    $('#email').keyup( function(){
        if($(this).val().length >0)
            $(".form-navigation .next").addClass('btn-active');
        else
            $(".form-navigation .next").removeClass('btn-active');
    });

    $(".formDataShipping").keyup(function(){
        var idData = $(this).attr('id');
        if(jQuery.inArray( idData, dataShipping )<0 && $(this).val().length >0)
            dataShipping.push(idData);
        else if(jQuery.inArray( idData, dataShipping ) >= 0 && $(this).val().length == 0)
            dataShipping = jQuery.grep(dataShipping, function(value) {
                return value != idData;
            });

        if(dataShipping.length >=3)
            $(".form-navigation .next").addClass('btn-active');
        else
            $(".form-navigation .next").removeClass('btn-active');
        

    });


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
                navigateTo(curIndex()+2);
            }) 
        }

        $('.contact-form').parsley().whenValidate({
            group: 'block-' + curIndex()
        }).done(function(){
            navigateTo(curIndex()+1);
        })

    })

    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    })

    navigateTo(0);

    $('.listShipping').on('click', function(){
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
        $(".form-navigation .next").addClass('btn-active');
        $('.next').show();
    });

    $('.checkPayment').on('click', function(){
        if(!submit){
            $('#errorCard').hide();
            //$('#svg-check').remove();
            $(".checkPayment").removeClass("checkPaymentActive");
            $(this).addClass("checkPaymentActive");

            var checkbox = $(this).find('input[type=radio]');
            checkbox.prop('checked', !checkbox.prop('checked'));
            selectPayment = checkbox.val();
            /* var svg = $(this).find('#iconChecked');
            svg.append("<svg width='2em' height='2em' viewBox='0 0 16 16' class='bi bi-check-circle-fill' id='svg-check' fill='currentColor'><path fill-rule='evenodd' d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/></svg>"); */
            $('#selectPayment').val($(this).find('#paymentDescription').val());

            if($(this).find('#paymentDescription').val() == "EFECTIVO")
                switchPay = true;
            else 
                switchPay = false;

            if($(this).find('#paymentDescription').val() != "CARD")
                $('#showCardForm').hide();
            else
                $('#showCardForm').show();

            if($(this).find('#paymentDescription').val() == "TRANSFERENCIA")
                $("#showTransfers").css({"display":"block"});
            else 
                $("#showTransfers").css({"display":"none"});
            
            if($(this).find('#paymentDescription').val() == "PAGO MOVIL")
                $("#showMobilePayment").css({"display":"block"});
            else 
                $("#showMobilePayment").css({"display":"none"});
            
            $(".amount").val(0);
            $("#numTransfers").val();
            $(".showRemainingAmount").text("Bs "+formatter.format((totalPayment)));
            $(".showTotalPaid").text("Bs "+formatter.format((0)));
            totalPaid = 0;

            if(iTransfers > 1)
                for (var i = 2; i <= iTransfers; i++) {
                    $('#rowTransfers'+i+'').remove();
                    $("#showCountTransfers").text(i);
                }
            
            if(iMobiles > 1)
                for (var i = 2; i <= iMobiles; i++) {
                    $('#rowMobile'+i+'').remove();
                    $("#showCountMobiles").text(i);
                }

            iTransfers = 1;
            iMobiles = 1;

            $("#showCountTransfers").text(iTransfers);
            $("#showCountMobiles").text(iMobiles);
            $(".amount").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
            
            $('.submit').show();
        }
    });

    $("#switchDiscount").on('click', function(){
        $('#iconClose').hide();
        $('#iconDone').hide();  
        $('#iconLoading').hide();
        $( "#discount" ).prop( "disabled", !$(this).is(':checked'));
        checkDiscount = $(this).is(':checked');
        if(!$(this).is(':checked')){
            $('#discount').val('');
            $('#percentageSelect').val("0");
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

        $('#errorCard').hide();
        if(_coinClient == 0 && selectPayment != "CARD"){
            $("#payment-form").submit();
            submit = true;
        }else if(_coinClient == 0 && selectPayment == "CARD")
            onGetCardNonce(e);
        else{
            if(switchPay){
                $("#payment-form").submit();
                submit = true;
            }else{
                if(validateDate() && $("input[name='typeCard']:checked").length  == 1){
                    $('.contact-form').parsley().whenValidate({
                        group: 'block-' + curIndex()
                    }).done(function(){
                        $("#payment-form").submit();
                        submit = true;
                    })
                }else{
                    $('#errorCard').show();
                    $('.contact-form').parsley().whenValidate({
                        group: 'block-' + curIndex()
                    });
                    $('.submit').show();
                    $('#loading').hide();
                }
            }
        }

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


    //square
    if(_coinClient==0){
        //TODO: paste code from step 2.1.1
        const idempotency_key = uuidv4();

        // Create and initialize a payment form object
        const paymentForm = new SqPaymentForm({
            // Initialize the payment form elements

            //TODO: Replace with your sandbox application ID
            applicationId: applicationId,
            inputClass: 'sq-input',
            autoBuild: false,
            // Customize the CSS for SqPaymentForm iframe elements
            inputStyles: [{
                fontSize: '16px',
                lineHeight: '24px',
                padding: '16px',
                placeholderColor: '#a0a0a0',
                backgroundColor: 'transparent',
            }],
            // Initialize the credit card placeholders
            cardNumber: {
                elementId: 'sq-card-number',
                placeholder: 'Card Number'
            },
            cvv: {
                elementId: 'sq-cvv',
                placeholder: 'CVV'
            },
            expirationDate: {
                elementId: 'sq-expiration-date',
                placeholder: 'MM/YY'
            },
            postalCode: {
            elementId: 'sq-postal-code',
            placeholder: 'Postal'
            },
            // SqPaymentForm callback functions
            callbacks: {
                /*
                * callback function: cardNonceResponseReceived
                * Triggered when: SqPaymentForm completes a card nonce request
                */
                cardNonceResponseReceived: function (errors, nonce, cardData) {
                    if (errors) {
                        // Log errors from nonce generation to the browser developer console.
                        $('#errorCard').show();
                        $('.submit').show();
                        $('#loading').hide();
                        return;
                    }

                    $('#errorCard').hide();
                    $("#nonce").val(nonce);
                    $("#idempotency_key").val(idempotency_key);
                    submit = true;
                    $("#payment-form").submit();
                }
            }
        });

    
        paymentForm.build();

        //TODO: paste code from step 2.1.2
        // Generate a random UUID as an idempotency key for the payment request
        // length of idempotency_key should be less than 45
        function uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        // onGetCardNonce is triggered when the "Pay $1.00" button is clicked
        function onGetCardNonce(event) {
            // Don't submit the form until SqPaymentForm returns with a nonce
            event.preventDefault();
            // Request a nonce from the SqPaymentForm object
            paymentForm.requestCardNonce();
        }
    }

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
    totalPayment = 0;

    total = total.replace(/\./g, "");
    total = total.replace(/,/g, ".");

    total = parseFloat(total);

    var percentage = parseInt($("#percentageSelect").val());
    resultShipping = exchangeRate(shippingPrice, _rate, shippingCoin, _coinClient);
    if(_coinClient == 0)
        $("#showShipping").text("$ "+formatter.format(resultShipping));
    else
        $("#showShipping").text("Bs "+formatter.format(resultShipping));

    $(".showPercentage").text("Descuento: "+percentage+" %");
    $("#priceShipping").val(formatter.format(resultShipping));

    if (_coinClient == 0)
        resulttotal = "Total: $ "+formatter.format((total-((total*percentage)/100)+resultShipping));
    else
        resulttotal = "Total: Bs "+formatter.format((total-((total*percentage)/100)+resultShipping));

    $("#totalAll").val(formatter.format((total-((total*percentage)/100)+resultShipping)));
    $("#totalGlobal").text(resulttotal);
    $(".showRemainingAmount").text("Bs "+formatter.format((total-((total*percentage)/100)+resultShipping)));
    $(".totalPayment").text("Bs "+formatter.format((total-((total*percentage)/100)+resultShipping)));
    totalPayment =(total-((total*percentage)/100)+resultShipping);

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
var percentage = 0;
var totalGlobal = 0;
var shippingPrice = 0;
var shippingCoin = 0;
var _coinClient = 0;
var _rate = 0;

$(function(){
    var $sections = $('.form-section');
    var statusShippingClient = false;
    var statusSwitch = false;
    var stripe = Stripe($("#STRIPE_KEY").val());
    var statusCard = false;
    var statusDate = false;
    var statusCVC = false;
    var statusLoading = false;
    $('#errorCard').hide();
    $('#loading').hide();

    var elements = stripe.elements();

    var style = {
        base: {
            fontWeight: 400,
            fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
            fontSize: '16px',
            lineHeight: '1.4',
            color: '#555',
            backgroundColor: '#fff',
            '::placeholder': {
                color: '#888',
            },
        },
        invalid: {
            color: '#eb1c26',
        }
    };

    var cardElement = elements.create('cardNumber', {
        style: style
    });
    cardElement.mount('#card_number');
    
    var exp = elements.create('cardExpiry', {
        'style': style
    });
    exp.mount('#card_expiry');
    
    var cvc = elements.create('cardCvc', {
        'style': style
    });
    cvc.mount('#card_cvc');
    
    // Validate input of the card elements
    var resultContainerCard = document.getElementById('paymentResponseCardNumber');
    cardElement.addEventListener('change', function(event) {
        if (event.error) {
            statusCard = false;
            resultContainerCard.innerHTML = '<p>'+event.error.message+'</p>';
        } else {
            statusCard = true;
            resultContainerCard.innerHTML = '';
        }
    });

    var resultContainerDate = document.getElementById('paymentResponseDate');
    exp.addEventListener('change', function(event) {
        if (event.error) {
            statusDate = false;
            resultContainerDate.innerHTML = '<p>'+event.error.message+'</p>';
        } else {
            statusDate = true;
            resultContainerDate.innerHTML = '';
        }
    });     
    
    var resultContainerCVC = document.getElementById('paymentResponseCVC');
    cvc.addEventListener('change', function(event) {
        if (event.error) {
            statusCVC = false;
            resultContainerCVC.innerHTML = '<p>'+event.error.message+'</p>';
        } else {
            statusCVC = true;
            resultContainerCVC.innerHTML = '';
        }
    });

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
    });

    function navigateTo(index){
        $sections.removeClass('current').eq(index).addClass('current');
        $('.form-navigation .previous').toggle(index>0);
        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation .pay').toggle(index == 0);
        $('.form-navigation .next').toggle(!arTheEnd && index != 0);

        $('.form-navigation [type=submit]').toggle(arTheEnd);

        if(index == 2 || index == 5){
            if(statusShippingClient && $('#percentageSelect').val() != 0)
                $('.next').show();
            else
                $('.next').hide();
        }

        if(index == 5)
            if($("#switchDiscount").is(':checked') || $('#percentageSelect').val() != 0)
                $('.next').show();
            else
                $('.next').hide();

        if(index == 6)
            calculateTotal();
    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $('.form-navigation .previous').click(function(){
        if(!statusLoading){
            if(curIndex()-1 == 0)
            $(".form-sales").text("Ventas");

            navigateTo(curIndex()-1);
        }
    })

    $('.form-navigation .pay').click(function(){
        $('.contact-form').parsley().whenValidate({
            group: 'block-' + curIndex()
        }).done(function(){
            navigateTo(curIndex()+1);
            $(".form-sales").text("Formulario");
        })
    })

    $('.form-navigation .next').click(function(){

        if(curIndex() == 2 || curIndex() == 5){
            if(statusShippingClient)
                $('.contact-form').parsley().whenValidate({
                    group: 'block-' + curIndex()
                }).done(function(){
                    navigateTo(curIndex()+1);
                })
        }else if(curIndex() == 4){
            if(statusCard && statusDate && statusCVC){
                $('#errorCard').hide();
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
        $('.next').show();
    });

    $("#switchDiscount").on('click', function(){
        $( "#discount" ).prop( "disabled", $(this).is(':checked'));
        statusSwitch = $(this).is(':checked');

        if($(this).is(':checked')){
            $('#discount').val('');
            $('#percentageSelect').val(0);
            $('.next').show();
        }
        else
            $('.next').hide();
    });


    $(".submit").on('click', function(e){
        e.preventDefault();
        statusLoading = true;
        $('.submit').hide();
        $('#loading').show();

        if($('#coinClient').val() == 0){
            createToken();
        }
    });

    function createToken() {
        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                statusLoading = false;
                $('#loading').hide();
                $('.submit').show();
                resultContainer.innerHTML = '<p>'+result.error.message+'</p>';
                navigateTo(4);
            } else {
                stripeTokenHandler(result.token);
            }
        });
    }
    

    function stripeTokenHandler(token) {
        $('#stripeToken').val(token.id);
        
        $("#payment-form").submit();
    }

});

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

    resultShipping = exchangeRate(shippingPrice, _rate, shippingCoin, _coinClient)

    if(_coinClient == 0)
        $(".showShipping").text("$ "+formatter.format(resultShipping));
    else
        $(".showShipping").text("Bs "+formatter.format(resultShipping));

    $(".showPercentage").text("Descuento: "+percentage+" %");

    if (_coinClient == 0)
        resulttotal = "Total: $ "+formatter.format((total-((total*percentage)/100)+resultShipping));
    else
        resulttotal = "Total: Bs "+formatter.format((total-((total*percentage)/100)+resultShipping));

    $("#totalAll").val(formatter.format((total-((total*percentage)/100)+resultShipping)));
    $(".totalGlobal").text(resulttotal);
    
}


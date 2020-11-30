var percentage = 0;
var totalGlobal = 0;
var shippingPrice = 0;
var shippingCoin = 0;
var _coinClient = 0;
var _rate = 0;
var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);

$(function(){
    var $sections = $('.form-section');
    var statusShippingClient = false;
    var statusShipping = false;
    var statusSwitch = false;

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
            if(statusShipping && statusShippingClient)
                $('.next').show();
            else
                $('.next').hide();
        }

        if(index == 6)
            calculateTotal();
    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $('.form-navigation .previous').click(function(){
        if(curIndex()-1 == 0)
            $(".form-sales").text("Ventas");

        navigateTo(curIndex()-1);
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
            $('#discount').val('')
            $('.next').show();
        }
        else
            $('.next').hide();
    });

});

function exchangeRate(price, rate, coin, coinClient){
    _coinClient = coinClient;
    _rate = rate;

    var result;
    price = parseFloat(price);

    if(coin == 0 && coinClient == 1)
      result = (parseFloat(price) * rate);
    else if(coin == 1 && coinClient == 0)
      result = (parseFloat(price) / rate);
    else
      result = (parseFloat(price));

    return result;
}

function showPrice(price, rate, coin, coinClient){
    if (price == "FREE")
        return "GRATIS";
    else if (coinClient == 0)
        return "$ "+ formatter.format(exchangeRate(price, rate, coin, coinClient));
    else
        return "Bs "+formatter.format(exchangeRate(price, rate, coin, coinClient));
}

function showPriceValue(price, rate, coin, coinClient){
    c

    if (price = "FREE")
        price = 0;
    
    if (coinClient == 0)
        return formatter.format(exchangeRate(price, rate, coin, coinClient));
    else
        return formatter.format(exchangeRate(price, rate, coin, coinClient));
}

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
        $(".showShipping").append("$ "+formatter.format(resultShipping));
    else
        $(".showShipping").append("Bs "+formatter.format(resultShipping));

    $(".showPercentage").append("Descuento: "+percentage+" %");

    if (_coinClient == 0)
        resulttotal = "Total: $ "+formatter.format((total-((total*percentage)/100)+resultShipping));
    else
        resulttotal = "Total: Bs "+formatter.format((total-((total*percentage)/100)+resultShipping));

    $(".totalGlobal").append(resulttotal);
}


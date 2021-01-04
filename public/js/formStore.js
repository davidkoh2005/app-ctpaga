var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);
var listCart = [];
$(function(){
    var $sections = $('.form-section-store');
    var statusBtn = false;

    $('#videoUSD').hide();
    $('#loading').hide();

    $('#btnFloating').click(function(){
        if(coinClient == 0 && !statusBtn){
            statusBtn = true;
            $('#videoUSD').get(0).play();
        }else if(coinClient == 1 && !statusBtn){
            statusBtn = true;
            $('#videoBs').get(0).play();
        }
    });

    $("#videoUSD").bind("ended", function() {
        $('#videoUSD').hide();
        $('#videoBs').show();
        $('#videoUSD').get(0).load(); 
        coinClient = 1;
        statusBtn = false;
        showProductsServices(categorySelect);
        showTotalBtn();
    });

    $("#videoBs").bind("ended", function() {
        $('#videoBs').hide();
        $('#videoUSD').show(); 
        $('#videoBs').get(0).load();
        coinClient = 0;
        statusBtn = false;
        showProductsServices(categorySelect);
        showTotalBtn();
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
        $('.form-navigation [type=submit]').toggle(index==0);

        if(index == 1){
            $(".form-store").text("Envio");
            $('#totalBtn').hide();
        }else{
            $('#totalBtn').show();
            $('.form-navigation .next').toggle(!arTheEnd);
        }

    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $('.form-navigation .previous').click(function(){
        navigateTo(curIndex()-1);
        showTotalBtn();
    });


    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    });

    navigateTo(0);

    $('.button-shipping').click(function(){
        navigateTo(curIndex()+1);
    });

    showCategories(0)

    $('#btn-products').click(function(){
        $('#btn-services').removeClass('btn-current');
        $('#btn-products').addClass('btn-current');
        showCategories(0);
    });

    $('#btn-services').click(function(){
        $('#btn-products').removeClass('btn-current');
        $('#btn-services').addClass('btn-current');
        showCategories(1);
    });

    $(".submit").on('click', function(){
        if(listCart.length != 0){
            $('.submit').hide();
            $('#loading').show();
            sendData();
        }
    });

    $("#btnFloatingShipping").on('click', function(){
        if(listCart.length != 0){
            sendData();
        }
    });

});


function addCart(productService, type){
    $("#btnFloatingShipping").removeClass("WOW animated bounceIn");
    var statusCart = false;
    var newListCart = [];
    if(listCart.length == 0){
        listCart.push({
            "data": [
                productService
            ],
            "quantity": 1,
            "type": type,
        });
    }else{
        $.each( listCart, function( key, value ) {
            if(value['data'][0]['name'] == productService['name'] && value['data'][0]['id'] == productService['id']){
                value['quantity'] += 1; 
                statusCart = true;
            }

            newListCart.push(value);

        });

        if(!statusCart){
            newListCart.push({
                "data": [
                    productService
                ],
                "quantity": 1,
                "type": type,
            });
        }

        listCart = newListCart;
    }
    alertify.success('Se agrego al carrito');
    showTotalBtn();
    $('#totalBtn').removeClass('statusButton');

}

function showTotalBtn()
{
    total = 0;
    quantity = 0;
    
    if(listCart.length == 0){
        $('#totalBtn').text("Pagar");
    }else{
        $.each( listCart, function( key, value ) {
            total += (exchangeRate(value['data'][0]['price'], rateToday, value['data'][0]['coin'], coinClient ) * value['quantity']);
            quantity += value['quantity'];
        });

        if(coinClient == 0){
            $('#totalBtn').text("Pagar $ "+formatter.format(total));
        }else{
            $('#totalBtn').text("Pagar Bs "+formatter.format(total));
        }
    }

    $('.circleGreen').text(quantity);
    $("#btnFloatingShipping").addClass("WOW animated bounceIn");

}
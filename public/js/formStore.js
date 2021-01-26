var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);
var listCart = [];
$(function(){
    var $sections = $('.form-section-store');
    var statusBtn = false;
    var statusMenu = false;
    var indexPrevios = 1;

    $('#loading').hide();

    $('.menuStore').click(function(){
        if(!statusMenu)
        {   
            indexPrevios = curIndex();
            statusMenu = true;
            $('#barMenu').removeClass("colorGrey");
            $('#barMenu').addClass("colorGreyInverse");
            $('#titleMenu').removeClass("menuStore");
            $('#titleMenu').addClass("menuStoreInverse");
            navigateTo(0);
        }
        else
        {
            statusMenu = false;
            $('#barMenu').removeClass("colorGreyInverse");
            $('#barMenu').addClass("colorGrey");
            $('#titleMenu').removeClass("menuStore");
            $('#titleMenu').addClass("menuStoreInverse");
            $('#totalBtn').show();
            navigateTo(indexPrevios);
        }
    });

    $('#btnFloating').click(function(){

        if(!statusBtn)
        {
            statusBtn = true;
            $('.divisaExpanded').removeClass("hide");
            $('.divisa').css("background-color", "white");
        }
        else
        {
            statusBtn = false;
            $('.divisaExpanded').addClass("hide");
            $('.divisa').css("background-color", "#e6e6e6");
        }
    });

    $('#btnEEUU').click(function(){
        coinClient = 0;
        $('.divisaExpanded').addClass("hide");
        $('.divisa').css("background-color", "#e6e6e6");
        showProductsServices(categorySelect);
        showTotalBtn();
    });

    $('#btnVE').click(function(){
        coinClient = 1;
        $('.divisaExpanded').addClass("hide");
        $('.divisa').css("background-color", "#e6e6e6");
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
        $('.form-navigation .previous').toggle(index>1);
        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation [type=submit]').toggle(index==1);

        if (index != 0){
            statusMenu = false;
            $('#barMenu').removeClass("colorGreyInverse");
            $('#barMenu').addClass("colorGrey");
            $('#titleMenu').removeClass("menuStoreInverse");
            $('#titleMenu').addClass("menuStore");
        }


        switch (index) {
            case 1:
                $("#form-store").text(commerceName);
                break;
            case 2:
                $("#form-store").text("Perfil del vendedor");
                break;
            case 3:
                $("#form-store").text("Método de pago");
                break;
            case 4:
                $("#form-store").text("Envíos");
                break;
            case 5:
                $("#form-store").text("Categorías");
                break;
            default:
                console.log("error");
        }

    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $('.form-navigation .previous').click(function(){
        navigateTo(1);
        showTotalBtn();
    });


    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    });

    navigateTo(1);

    $('.perfilStore').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        navigateTo(2);
    });

    $('.button-store').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        $('#titleMenu').removeClass("menuStoreInverse");
        $('#titleMenu').addClass("menuStore");
        navigateTo(1);
    });

    $('.button-payment').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        $('#titleMenu').removeClass("menuStoreInverse");
        $('#titleMenu').addClass("menuStore");
        navigateTo(3);
    });

    $('.button-shipping').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        $('#titleMenu').removeClass("menuStoreInverse");
        $('#titleMenu').addClass("menuStore");
        navigateTo(4);
    });

    $('.button-whatsapp').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        $('#titleMenu').removeClass("menuStoreInverse");
        $('#titleMenu').addClass("menuStore");
        navigateTo(1);
    });

    $('.button-categories').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        $('#titleMenu').removeClass("menuStoreInverse");
        $('#titleMenu').addClass("menuStore");
        navigateTo(5);
    });

    $('.button-closeMenu').click(function(){
        $('#barMenu').removeClass("colorGreyInverse");
        $('#barMenu').addClass("colorGrey");
        $('#titleMenu').removeClass("menuStoreInverse");
        $('#titleMenu').addClass("menuStore");
        navigateTo(indexPrevios);
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
            $('.submit').addClass("hide");
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
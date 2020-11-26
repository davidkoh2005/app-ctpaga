$(function(){
    var $sections = $('.form-section');

    function navigateTo(index){
        $sections.removeClass('current').eq(index).addClass('current');
        $('.form-navigation .previous').toggle(index>0);
        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation .pay').toggle(index == 0);
        $('.form-navigation .next').toggle(!arTheEnd && index != 0);

        $('.form-navigation [type=submit]').toggle(arTheEnd);
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
        $('.bi-check-circle-fill').removeClass("checked");
        var checkbox = $(this).find('input[type=radio]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        var svg = $(this).find('.bi-check-circle-fill');
        svg.addClass("checked");
     });

});

function showPrice(price, rate, coin, coinClient){
    var result;
    if(coin == 0 && coinClient == 1)
      result = (parseFloat(price) * rate);
    else if(coin == 1 && coinClient == 0)
      result = (parseFloat(price) / rate);
    else
      result = (parseFloat(price));

    if (coinClient == 0)
        return "$ "+result;
    else
        return "Bs "+result;
}
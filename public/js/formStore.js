var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);

$(function(){
    var $sections = $('.form-section-store');
    var statusLoading = false;

    $('#loading').hide();
    
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
        $('.form-navigation [type=submit]').toggle(arTheEnd);

        if(index == 1){
            $(".form-store").text("Envio");
        }else{
            $('.form-navigation .next').toggle(!arTheEnd);
        }

    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }

    $('.form-navigation .previous').click(function(){
        if(curIndex() == 2)
            navigateTo(curIndex()-1);

        if(!statusLoading){
            if(curIndex()-1 == 0)
            $(".form-store").text("Tienda");

            navigateTo(curIndex()-1);
        }
    });

    $('.form-navigation .next').click(function(){

        $('.store-form').parsley().whenValidate({
            group: 'block-' + curIndex()
        }).done(function(){
            if(curIndex() == 0)
                navigateTo(curIndex()+2);
            else
            navigateTo(curIndex()+1);
        })

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

});


function initialText(result)
{
    return result.charAt(0).toUpperCase();
}
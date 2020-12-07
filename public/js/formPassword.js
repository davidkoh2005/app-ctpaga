var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);

$(function(){
    var $sections = $('.form-section');
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
        var arTheEnd = index >= $sections.length -1;
        $('.form-navigation [type=submit]').toggle(arTheEnd);

    }

    function curIndex()
    {
        return $sections.index($sections.filter('.current'));
    }


    $('.form-navigation .submit').click(function(){

        $('.contact-form').parsley().whenValidate({
            group: 'block-' + curIndex()
        }).done(function(){
            alert("entro");
        })

    });

    $sections.each(function(index, section){
        $(section).find(':input').attr('data-parsley-group', 'block-'+index);
    });

    navigateTo(0);


});
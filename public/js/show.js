$(function() {
    var value = 0;

    $('.zoom').on('click', function() {
        $('.imagepreview').attr('src', $(this).find('img').attr('src'));
        $('#imagemodal').modal('show');  
    });	
    
    
    $('#right').on('click', function() {
        value +=90;
        $('.imagepreview').rotate({ animateTo:value});
    });	


    $('#left').on('click', function() {
        value -=90;
        $('.imagepreview').rotate({ animateTo:value});
    });	

    $("#imagemodal").on('hidden.bs.modal', function() {
        value = 0;
        $('.imagepreview').rotate(0);
    });

});
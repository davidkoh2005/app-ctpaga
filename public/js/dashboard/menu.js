$(document).ready( function () {
    $("#nav-"+statusMenu).addClass("active");

    if(statusMenu == "dashboard")
        $("#title-navbar").html("Inicio");
    else if(statusMenu == "balance")
        $("#title-navbar").html("Depositos");
    else if(statusMenu == "commerces")
        $("#title-navbar").html("Comerciantes");
    else if(statusMenu == "transactions")
        $("#title-navbar").html("Transacciones");
    else if(statusMenu == "reportPayment")
        $("#title-navbar").html("Reporte Depósitos");
    else if(statusMenu == "depositHistory")
        $("#title-navbar").html("Historial");
    else if(statusMenu == "rateHistory")
        $("#title-navbar").html("Tasa Historial");
    else if(statusMenu == "delivery")
        $("#title-navbar").html("Delivery");
    else if(statusMenu == "rate")
        $("#title-navbar").html("Tasa Transaccion");
    else if(statusMenu == "auth-delivery")
        $("#title-navbar").html("Delivery Autorizado");
    else if(statusMenu == "settings")
        $("#title-navbar").html("Configuraciones");
    

    $('#nav-balance').click(function() {
        if($('#subMenuDeposits').hasClass("hide"))
            $('#subMenuDeposits').removeClass("hide");
        else
            $('#subMenuDeposits').addClass("hide")
    });

    $('#subMenuDeposits li').click(function() {
        $('#subMenuDeposits').addClass("hide")
    });
});
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
        $("#title-navbar").html("Historial de Depositos");
    else if(statusMenu == "rateHistory")
        $("#title-navbar").html("Tasa Historial");
    
});
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
    
});
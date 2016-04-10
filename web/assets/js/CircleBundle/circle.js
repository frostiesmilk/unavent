/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$("body").on("submit","#SendRequest", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("validateRequestSR").disabled=true; 
    document.getElementById("refuseRequestSR").disabled=true; 
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        document.getElementById("gridSystemModalLabelSR").innerHTML="votre demande a bien été acceptée";
        document.getElementById("refuseRequestSR").innerHTML="ok"; 
        document.getElementById("refuseRequestSR").disabled=false; 
        document.getElementById("validateRequestSR").disabled=false; 
        document.getElementById("validateRequestSR").style.display="none"; 
        document.getElementById("sendRequest").style.display="none";
        document.getElementById("deleteRequest").style.display="inline";
        document.getElementById("refuseRequestDR").innerHTML="non"; 
        document.getElementById("refuseRequestDR").disabled=false; 
        document.getElementById("validateRequestDR").disabled=false;     
        document.getElementById("validateRequestDR").style.display="inline";     });
});

$("body").on("submit","#DeleteRequest", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("validateRequestDR").disabled=true; 
    document.getElementById("refuseRequestDR").disabled=true; 
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        document.getElementById("gridSystemModalLabelDR").innerHTML="votre demande a bien été supprimée";
        document.getElementById("refuseRequestDR").innerHTML="ok"; 
        document.getElementById("refuseRequestDR").disabled=false; 
        document.getElementById("validateRequestDR").disabled=false; 
        document.getElementById("validateRequestDR").style.display="none"; 
        document.getElementById("sendRequest").style.display="inline";
        document.getElementById("deleteRequest").style.display="none";
        document.getElementById("refuseRequestSR").innerHTML="non"; 
        document.getElementById("refuseRequestSR").disabled=false; 
        document.getElementById("validateRequestSR").disabled=false;     
        document.getElementById("validateRequestSR").style.display="inline"; 
    });
});

$("body").on("submit","#Unsubscribe", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("validateRequestU").disabled=true; 
    document.getElementById("refuseRequestU").disabled=true; 
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        document.getElementById("gridSystemModalLabelU").innerHTML="La demande a été acceptée";
        document.getElementById("refuseRequestU").innerHTML="ok"; 
        document.getElementById("refuseRequestU").disabled=false; 
        document.getElementById("validateRequestU").disabled=false;     
        document.getElementById("validateRequestU").style.display="none"; 
        document.getElementById("sendRequest").style.display="inline";
        document.getElementById("unsubscribe").style.display="none";
        document.getElementById("refuseRequestSR").innerHTML="non"; 
        document.getElementById("refuseRequestSR").disabled=false; 
        document.getElementById("validateRequestSR").disabled=false;     
        document.getElementById("validateRequestSR").style.display="inline"; 
    });
});

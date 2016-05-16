/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$("body").on("submit","#ReadNotif", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("ReadNotificationButton-"+$this.attr('class')).disabled=true;
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        $this.trigger("reset"); // on reset le form
        if (document.getElementById("ReadNotificationButton-"+$this.attr('class')).title == "marquer comme non lu" ){
            document.getElementById("spanReadNotificationButton-"+$this.attr('class')).className="glyphicon glyphicon-eye-open";
            document.getElementById("ReadNotificationButton-"+$this.attr('class')).title="marquer comme lu";               
            document.getElementById("uneNotification").style="font-weight:bold";               
        } else {
            document.getElementById("spanReadNotificationButton-"+$this.attr('class')).className="glyphicon glyphicon-eye-close";
            document.getElementById("ReadNotificationButton-"+$this.attr('class')).title="marquer comme non lu";               
            document.getElementById("uneNotification").style="font-weight:normal";                
        }
        document.getElementById("ReadNotificationButton-"+$this.attr('class')).disabled=false;
    });
});

$("body").on("submit","#DeleteNotif", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("DeleteNotificationButton-"+$this.attr('class')).disabled=true;
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        $this.trigger("reset"); // on reset le form
        document.getElementById("uneNotificationTd-"+$this.attr('class')).style="display:none";                

        document.getElementById("DeleteNotificationButton-"+$this.attr('class')).disabled=false;
    });
});
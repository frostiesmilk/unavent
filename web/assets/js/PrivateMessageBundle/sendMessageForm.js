/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$("form[name='send-private-message-form']").on("submit", function(e){
    console.log("petite marie");
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
//        success: function(data){
//            $this.trigger("reset"); // on reset le form
//            alert("Private Message successfuly sent");
//        }
    }).done(function(data, textStatus, jqXHR){ // like success
        $this.trigger("reset"); // on reset le form
        alert("Private Message successfuly sent");
    });
});

$("form[name='send-private-message-with-title-form']").on("submit", function(e){
    console.log("petite marie");
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
//        success: function(data){
//            $this.trigger("reset"); // on reset le form
//            alert("Private Message successfuly sent");
//        }
    }).done(function(data, textStatus, jqXHR){ // like success
        $this.trigger("reset"); // on reset le form
        alert("Private Message successfuly sent");
    });
});
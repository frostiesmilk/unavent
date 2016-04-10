/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Check all messages
 * @param {type} param
 */
$("#checkAllMessages").click(function () {
    $(".messages-stream-element-selector").prop('checked', $(this).prop('checked'));
});


/**
 * All messages - clickable
 */
$(".messages-stream-element-clickable").click(function() {
    window.document.location = $(this).parent().data("href");
});


$("body").on("submit","#readNotRead", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
        if (document.getElementById("messageFromTitle-"+$this.attr('class')).className[0] == 'b') {
            document.getElementById("messageFromTitle-"+$this.attr('class')).className = "messages-stream-element-author";
        } else {
            document.getElementById("messageFromTitle-"+$this.attr('class')).className="bold messages-stream-element-author";
        }
        if (document.getElementById("messageFromSubject-"+$this.attr('class')).className[0] == 'b') {
            document.getElementById("messageFromSubject-"+$this.attr('class')).className = "messages-stream-element-subject";
        } else {
            document.getElementById("messageFromSubject-"+$this.attr('class')).className="bold messages-stream-element-subject";
        }
        document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=false;
    });
});

$("body").on("submit","#readNotReadReceived", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
        if (document.getElementById("messageFromSubmit-"+$this.attr('class')).title == "marquer comme non lu" ){
            document.getElementById("spanmessageFromSubmit-"+$this.attr('class')).className="glyphicon glyphicon-eye-close";
            document.getElementById("messageFromSubmit-"+$this.attr('class')).title="marquer comme lu";     
        } else {
            document.getElementById("spanmessageFromSubmit-"+$this.attr('class')).className="glyphicon glyphicon-eye-open";
            document.getElementById("messageFromSubmit-"+$this.attr('class')).title="marquer comme non lu";               
        }
        document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=false;
    });
});

function incrementDeletedMessage(){
    var messageDeletedNumber = document.getElementById("messageDeletedNumber").innerHTML;
    var nbD=parseInt(messageDeletedNumber);
    nbD++;
    document.getElementById("messageDeletedNumber").innerHTML=nbD.toString();       
}

function decrementDeletedMessage(){
    var messageDeletedNumber = document.getElementById("messageDeletedNumber").innerHTML;
    var nbD=parseInt(messageDeletedNumber);
    nbD--;
    document.getElementById("messageDeletedNumber").innerHTML=nbD.toString();       
}

function decrementReceivedMessage(){
    var messageReceveivedNumber = document.getElementById("messageReceveivedNumber").innerHTML;
    var nbR=parseInt(messageReceveivedNumber);
    nbR--;
    document.getElementById("messageReceveivedNumber").innerHTML=nbR.toString();    
}

function incrementReceivedMessage(){
    var messageReceveivedNumber = document.getElementById("messageReceveivedNumber").innerHTML;
    var nbR=parseInt(messageReceveivedNumber);
    nbR++;
    document.getElementById("messageReceveivedNumber").innerHTML=nbR.toString();    
}

function decrementSentMessage(){
    var messageSentNumber = document.getElementById("messageSentNumber").innerHTML;
    var nbS=parseInt(messageSentNumber);
    nbS--;
    document.getElementById("messageSentNumber").innerHTML=nbS.toString();         
}

function incrementSentMessage(){
    var messageSentNumber = document.getElementById("messageSentNumber").innerHTML;
    var nbS=parseInt(messageSentNumber);
    nbS++;
    document.getElementById("messageSentNumber").innerHTML=nbS.toString();         
}

$("body").on("submit","#deleteReceivedMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
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
            incrementDeletedMessage();
            decrementReceivedMessage();
            document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
    });
});

$("body").on("submit","#deleteOneReceivedMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
            incrementDeletedMessage();
            decrementReceivedMessage();
            document.getElementById("messageFromDelete-"+$this.attr('class')).innerHTML = "message supprimé";
    });
});
$("body").on("submit","#deleteOneReceivedDeletedMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
            document.getElementById("messageFromDelete-"+$this.attr('class')).innerHTML = "message supprimé";
    });
});

// UTILISE
$("body").on("submit","#deleteDeletedMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
    });
    document.getElementById("messageFromDelete-"+$this.attr('class')).innerHTML = "message supprimé";
    document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
});

// UTILISE
$("body").on("submit","#deleteSentMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
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
            incrementDeletedMessage();
            decrementSentMessage();
    });
    document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
});
// UTILISE
$("body").on("submit","#deleteOneSentDeletedMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
            document.getElementById("messageFromDelete-"+$this.attr('class')).innerHTML = "message supprimé";
    });
});
// UTILISE
$("body").on("submit","#deleteOneSentMessage", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
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
            incrementDeletedMessage();
            decrementSentMessage();
            document.getElementById("messageFromDelete-"+$this.attr('class')).innerHTML = "message supprimé";
    });
});
$("body").on("submit","#CancelReceivedDeleted", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
            incrementReceivedMessage();
            document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
    });
});

$("body").on("submit","#CancelSentDeleted", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
            incrementSentMessage();
            document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
    });
});

$("body").on("submit","#CancelOneSentDeleted", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
            incrementSentMessage();
            document.getElementById("messageFromSubmit-"+$this.attr('class')).innerHTML = "message dans 'envoyé'";
    });
});

$("body").on("submit","#CancelOneReceivedDeleted", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("messageFromSubmit-"+$this.attr('class')).disabled=true;
    document.getElementById("messageFromDelete-"+$this.attr('class')).disabled=true;
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
            decrementDeletedMessage();
            incrementReceivedMessage();
            document.getElementById("messageFromSubmit-"+$this.attr('class')).innerHTML = "message dans 'reçus'";
    });
});
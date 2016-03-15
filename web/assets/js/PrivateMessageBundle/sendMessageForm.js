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

$("body").on("submit","#send-wink", function(e){
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
        document.getElementById("send-wink-message").innerHTML=("Votre wink a bien été envoyé");
    });
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
            document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
    });
});

$("body").on("submit","#deleteSentDeletedMessage", function(e){
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
            document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
    });
});

$("body").on("submit","#deleteReceivedDeletedMessage", function(e){
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
            document.getElementById("messageFrom-"+$this.attr('class')).className = "messages-stream-element collapse";
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
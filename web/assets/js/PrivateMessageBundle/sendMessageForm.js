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

$("form[name='send-private-message-with-title-form-received']").on("submit", function(e){
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
        incrementSentMessage();
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


$('#modalAddSubscriber').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text(recipient)
})

$('#modalDeleteRequest').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text(recipient)
})

$('#modalUnsubscribe').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text(recipient)
})
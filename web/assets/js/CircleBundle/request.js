
$("body").on("submit","#RefuseRequest", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("AcceptRequestButton-"+$this.attr('class')).disabled=true; 
    document.getElementById("RefuseRequestButton-"+$this.attr('class')).disabled=true; 
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        document.getElementById("reponse-"+$this.attr('class')).innerHTML="La demande a été refusée";
    });
});

$("body").on("submit","#AcceptRequest", function(e){
    e.preventDefault(); // empêcher le comportement normal: recharger la page
    var $this = $(this); // L'objet jQuery du formulaire
    document.getElementById("AcceptRequestButton-"+$this.attr('class')).disabled=true; 
    document.getElementById("RefuseRequestButton-"+$this.attr('class')).disabled=true; 
    $.ajax({
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        error: function(data){
            alert("error");
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        document.getElementById("reponse-"+$this.attr('class')).innerHTML="La demande a été acceptée";
    });
});

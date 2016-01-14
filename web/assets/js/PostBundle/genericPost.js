/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * When clicking post delete
 * 
 */
$( "a[name='postDeleteButton']" ).on( "click", function() {
    var postId = $( this ).find("input[name=delete-post-id]").val() ;
    var postOriginEntity = $( this ).find("input[name=delete-post-origin-entity]").val() ;
    var postOriginId = $( this ).find("input[name=delete-post-origin-id]").val() ;
    
    //alert(Routing.generate('api_delete_post', {postId: postId}));
    $("#delete-post-form").attr('action', Routing.generate('api_delete_post_custom', {postId: postId}));
    $("#delete-post-id").val(postId);
    $("#delete-post-origin-entity").val(postOriginEntity);
    $("#delete-post-origin-id").val(postOriginId);
});

/**
 * Submitting post deletion
 * @param {type} param
 */
$(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#delete-post-form').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
        var postId = $this.find("#delete-post-id").val();

        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            //dataType: 'json', // JSON
            error: function(json){
                alert("merde "+$this.attr('action')+" "+$this.attr('method'));
            }
        }).done(function(data, textStatus, jqXHR){
            console.log(data);
            var elem = document.getElementById('post-'+postId);
            elem.parentNode.removeChild(elem);
            $('#modal-delete-post').modal("hide");
            alert("Le poste a été supprimé.");
        });

    });
});
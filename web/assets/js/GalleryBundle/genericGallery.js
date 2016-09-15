/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function modalDeleteGallery(){
    $("body").on("click","span[name='deleteGalleryButton']", function(e) {
        var galleryId = $( this ).find("input[name=delete-gallery-id]").val() ;

        $("#delete-gallery-form").attr('action', Routing.generate('api_delete_gallery', {galleryId: galleryId}));
        $("#delete-gallery-id").val(galleryId);
    });
}

function submitDeleteGallery(){
    // Lorsque je soumets le formulaire
    $('#delete-gallery-form').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
        var galleryId = $this.find("#delete-gallery-id").val();

        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            //dataType: 'json', // JSON
            error: function(json){
                alert("Error gallery delete. Please contact the site administrator.");
            }
        }).done(function(data, textStatus, jqXHR){
            console.log(data);
        });
        
        var elem = document.getElementById('gallery-'+galleryId);
        elem.parentNode.removeChild(elem); // delete post from view
        $('#modal-delete-gallery').modal("hide");// close modal
    });
}

function formDeletePhoto(){
    // open options
    $("body").on("click","span[name=trigger-delete-photo-form]", function(e) {
        e.preventDefault();        
        $(this).parent().find("form[name=delete-photo-form]").attr("style", "display: inline-block;");
    });
    
    // cancel delete options
    $("body").on("click",".cancel-delete-photo-form", function(e) {
        e.preventDefault();
        $(this).parent().attr("style", "display: none;");
    });
}

function submitDeletePhoto(){
    // Lorsque je soumets le formulaire
    $("form[name='delete-photo-form']").on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
        var photoId = $this.find("input[name='delete-photo-id']").val();
        
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            //dataType: 'json', // JSON
            error: function(json){
                alert("Error photo delete. Please contact the site administrator.");
            }
        }).done(function(data, textStatus, jqXHR){
            console.log(data);
        });
        
        document.getElementById('photo-'+photoId).remove(); // delete photo from view
    });
}

$(function() {
    modalDeleteGallery();
    submitDeleteGallery();
    formDeletePhoto();
    submitDeletePhoto();
});
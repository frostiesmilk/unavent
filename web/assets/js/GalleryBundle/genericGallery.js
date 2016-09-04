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

function formDeletePhoto(){console.log("hsdfola");
    $("body").on("click","span[name=trigger-delete-photo-form]", function(e) {
        e.preventDefault();
        console.log("hola");
        console.log($(this).parent().find("form[name=delete-photo-form]").html());
        
        $(this).parent().find("form[name=delete-photo-form]").attr("style", "input: inline-block;");
        
//        var galleryId = $( this ).find("input[name=delete-gallery-id]").val() ;
//
//        $("#delete-gallery-form").attr('action', Routing.generate('api_delete_gallery', {galleryId: galleryId}));
//        $("#delete-gallery-id").val(galleryId);
    });
}

function submitDeletePhoto(){
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

$(function() {
    modalDeleteGallery();
    submitDeleteGallery();
    formDeletePhoto();console.log("hsdfsdfola");
    submitDeletePhoto();
});
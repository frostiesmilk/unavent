/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Manage post deletion
 */
$( "a[name='postDeleteButton']" ).on( "click", function() {
    var postId = $( this ).find("input[name=delete-post-id]").val() ;
    var postOriginEntity = $( this ).find("input[name=delete-post-origin-entity]").val() ;
    var postOriginId = $( this ).find("input[name=delete-post-origin-id]").val() ;
    
    $("#delete-post-id").val(postId);
    $("#delete-post-origin-entity").val(postOriginEntity);
    $("#delete-post-origin-id").val(postOriginId);
});
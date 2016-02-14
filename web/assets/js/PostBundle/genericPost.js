/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * When clicking POST delete
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
 * Submitting POST deletion
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

/**
 * When clicking COMMENT delete
 * 
 */
$( "a[name='commentDeleteButton']" ).on( "click", function() {
    var commentId = $( this ).find("input[name=delete-comment-id]").val() ;
    
    //alert(Routing.generate('api_delete_comment', {commentId: commentId}));
    $("#delete-comment-form").attr('action', Routing.generate('api_delete_comment', {commentId: commentId}));
    $("#delete-comment-id").val(commentId);
});

/**
 * Submitting COMMENT deletion
 */
$(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#delete-comment-form').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
        var commentId = $this.find("#delete-comment-id").val();

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
            var elem = document.getElementById('comment-'+commentId);
            elem.parentNode.removeChild(elem);
            $('#modal-delete-comment').modal("hide");
            alert("Le commentaire a été supprimé.");
        });

    });
    
});


/**
* Pressing like button
*/
$( "form[name='form-like']" ).on( "submit", function(e) { 
       e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

       var $this = $(this); // L'objet jQuery du formulaire
       var postId = $this.find("input[name='post-id']").val();

       if($this.find("input[name='_method']").length){ // delete like
            console.log("deleting like");
           
            // smooth display, change with no data control
            $this.find("button[name='addLike']").removeAttr("hidden");
            $this.find("button[name='deleteLike']").attr("hidden", "hidden");
            updateLikeDisplay(postId, "delete");
            
            $.ajax({ 
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                //dataType: 'json', // JSON
                error: function(json){
                    alert("merde "+$this.attr('action')+" "+$this.attr('method'));
                }
            }).done(function(data, textStatus, jqXHR){ // like success
                $this.find("input[name='_method']").remove(); // remove deletion function
                $this.attr('action', Routing.generate('api_post_like_post'));
            });
       }else{ // add like
            console.log("adding like");
            
            // smooth display, change with no data control
            $this.find("button[name='deleteLike']").removeAttr("hidden");
            $this.find("button[name='addLike']").attr("hidden", "hidden"); 
            updateLikeDisplay(postId, "add");
            
            $.ajax({ 
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: {postId: postId},
                //dataType: 'json', // JSON
                error: function(json){
                    alert("merde "+$this.attr('action')+" "+$this.attr('method'));
                }
            }).done(function(data, textStatus, jqXHR){ // like success
                console.log(data);
                $this.append('<input name="_method" value="DELETE" type="hidden">'); // add deletion function
                
                var likeId = data.likeId;
                $this.attr('action', Routing.generate('api_delete_like', {likeId: likeId}));
            });
       }

       
   });

/**
 * 
 * @param {type} postId
 * @param {type} action
 * @returns {undefined}
 */
function updateLikeDisplay(postId, action){
    
    var likeCountNumber = parseInt(document.querySelector("#post-rating-display-"+postId+" [name='post-like-count']").innerHTML);
    //var likeCountLabel = document.querySelector("#post-rating-display-"+postId+" .post-like-label").innerText;
    console.log("updateLikeDisplay "+likeCountNumber);
    if(action=="add"){
        document.querySelector("#post-rating-display-"+postId+" [name='post-like-count']").innerHTML = likeCountNumber+1;
        if(likeCountNumber==1){
            document.querySelector("#post-rating-display-"+postId+" [name='post-like-label']").innerHTML = " personnes aiment";
        }
    }else if(action=="delete"){
        document.querySelector("#post-rating-display-"+postId+" [name='post-like-count']").innerHTML = likeCountNumber-1;
        if(likeCountNumber==2){
            document.querySelector("#post-rating-display-"+postId+" [name='post-like-label']").innerHTML = " personne aime";
        }
    }
    
}

/**
 * Create new post
 */
$("#generic-new-post").on("submit", function(e){
    e.preventDefault();
    
    var $this = $(this); // L'objet jQuery du formulaire
    
    $.ajax({ 
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        //dataType: 'json', // JSON
        error: function(json){
            alert("merde "+$this.attr('action')+" "+$this.attr('method'));
        }
//        ,
//        success: function(data){
//            console.log("inajax data: "+data);
//        }
//    });
    }).done(function(data, textStatus, jqXHR){ // like success
       console.log("data.commentForm: "+data["commentForm"]);
       console.log("textStatus: "+textStatus);
       console.log("jqXHR: "+jqXHR.toString());
       document.getElementById("generic-new-post").reset();
       alert("post created");
    });
});

/**
 * Create new comment
 */
$("form[name='form-new-comment']").on("submit", function(e){
    e.preventDefault();
    
    var $this = $(this); // L'objet jQuery du formulaire
    
    $.ajax({ 
        url: $this.attr('action'),
        type: $this.attr('method'),
        data: $this.serialize(),
        //dataType: 'json', // JSON
        error: function(json){
            alert("merde "+$this.attr('action')+" "+$this.attr('method'));
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        console.log(data);
        $this.trigger("reset");
        alert("comment created");
    });
});


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * When clicking POST delete
 * 
 */
//$( "a[name='postDeleteButton']" ).on( "click", function() {
$("body").on("click","a[name='postDeleteButton']", function(e) {
    var postId = $( this ).find("input[name=delete-post-id]").val() ;
    var postOriginId = $( this ).find("input[name=delete-post-origin-id]").val() ;
    
    $("#delete-post-form").attr('action', Routing.generate('api_delete_post_custom', {postId: postId}));
    $("#delete-post-id").val(postId);
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
function modalDeleteComment(e){
    var commentId = e.querySelector("input[name=delete-comment-id]").value ;     
    console.log("Comment delete clicked: "+commentId);
    document.getElementById("delete-comment-form").setAttribute("action", Routing.generate('api_delete_comment', {commentId: commentId}) );
    document.getElementById("delete-comment-id").value = commentId;
}

/**
 * Submitting COMMENT deletion
 */
document.querySelector("#delete-comment-form").onsubmit = function(e){
    e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

    var $thisForm = $("#delete-comment-form"); // objet actuel
    var commentId = document.getElementById("delete-comment-id").value;

    $.ajax({
        url: $thisForm.attr('action'),
        type: $thisForm.attr('method'),
        data: $thisForm.serialize(),
        //dataType: 'json', // JSON
        error: function(json){
            alert("merde "+$thisForm.attr('action')+" "+$thisForm.attr('method'));
        }
    }).done(function(data, textStatus, jqXHR){
        console.log(data);
        var elem = document.getElementById('comment-'+commentId);
        elem.parentNode.removeChild(elem);
        $('#modal-delete-comment').modal("hide");
    });
};

/**
* Pressing like button
*/
$("body").on("submit","form[name='form-like']", function(e) {
    e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

    var $this = $(this); // L'objet jQuery du formulaire
    var postId = $this.find("input[name='post-id']").val();
    console.log("postId: "+postId);
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
//$("#generic-new-post").on("submit", function(e){
//    e.preventDefault();
//    
//    var $this = $(this); // L'objet jQuery du formulaire
//    
//    $.ajax({ 
//        url: $this.attr('action'),
//        type: $this.attr('method'),
//        data: $this.serialize(),
//        //dataType: 'json', // JSON
//        error: function(json){
//            alert("merde "+$this.attr('action')+" "+$this.attr('method'));
//        }
////        ,
////        success: function(data){
////            console.log("inajax data: "+data);
////        }
////    });
//    }).done(function(data, textStatus, jqXHR){ // like success
//       console.log("data.commentForm: "+data.commentForm);
//       console.log("textStatus: "+textStatus);
//       console.log("jqXHR: "+jqXHR.toString());
//       document.getElementById("generic-new-post").reset();
//       alert("post created");
//    });
//});

/**
 * Create new comment
 */
$("body").on("submit","form[name='form-new-comment']", function(e) {
    e.preventDefault();
    
    var $this = $(this); // L'objet jQuery du formulaire
    var postId = $this.find("[name='post-id']").val();
    
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
        
        var list = document.getElementById("comments-for-post-"+postId);
        var li = document.createElement("li");
        li.setAttribute("id", "comment-"+data.commentId);
        li.setAttribute("class","media generic-blog-post-comment");
        
        
        var htmlComment = document.querySelector("#comments-for-post-"+postId+" [name='comment-empty']");
        console.log("#comments-for-post-"+postId+" [name='comment-empty'] : "+htmlComment.innerHTML);
        li.innerHTML = htmlComment.innerHTML;
        
        var commentDateHTML = li.querySelector(".generic-blog-post-comment-time span");
        var commentDate = data.datetimeCreated;
        commentDateHTML.innerHTML = moment(commentDate).format("[le] L [à] LT"); 
        
        var deleteCommentIdHTML = li.querySelector("[name='delete-comment-id']");
        deleteCommentIdHTML.setAttribute("value", data.commentId);
        
        var messageCommentHTML = li.querySelector(".generic-blog-post-comment-content p");
        messageCommentHTML.innerHTML = data.commentMessage;
        
        list.appendChild(li);
        
        alert("comment created");
        
    });
});

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
            alert("error "+$this.attr('action')+" "+$this.attr('method'));
        }
    }).done(function(data, textStatus, jqXHR){ // like success
        console.log(data);
        $this.trigger("reset");
        
        var list = document.querySelector("[name='generic-blog-posts-list']");
        var li = document.createElement("li");
        li.setAttribute("id", "post-"+data.postId);
        li.setAttribute("class","generic-blog-post");
        
        
        var htmlPost = document.querySelector("#post-0");
        console.log("new post list : "+htmlPost.innerHTML);
        li.innerHTML = htmlPost.innerHTML;
        
        var deletePostIdHTML = li.querySelector("[name='delete-post-id']");
        deletePostIdHTML.setAttribute("value", data.postId);
        
        var postDateHTML = li.querySelector(".generic-blog-post-time span");
        var postDate = data.datetimeCreated;
        postDateHTML.innerHTML = moment(postDate).format("[le] L [à] LT"); 
        
        var postLikeSpanId = li.querySelector(".generic-blog-post-rating");
        postLikeSpanId.setAttribute("id", "post-rating-display-"+data.postId);
        
        var postLikeForm = li.querySelector("form[name='form-like']");
        postLikeForm.querySelector("input[name='post-id']").setAttribute("value", data.postId);               
        
        var messagePostHTML = li.querySelector(".generic-blog-post-content p");
        messagePostHTML.innerHTML = data.postMessage;
        
        var buttonDeleteLikePost = li.querySelector("button[name='deleteLike']");
        var buttonDeleteLikePostEnterVal = buttonDeleteLikePost.getAttribute("onmouseenter").replace("tbd", data.postId);
        var buttonDeleteLikePostLeaveVal = buttonDeleteLikePost.getAttribute("onmouseleave").replace("tbd", data.postId);
        buttonDeleteLikePost.setAttribute("onmouseenter", buttonDeleteLikePostEnterVal);
        buttonDeleteLikePost.setAttribute("onmouseleave", buttonDeleteLikePostLeaveVal);
        buttonDeleteLikePost.querySelector("span").setAttribute("id", "postDeleteLike"+data.postId);
        
        var buttonAddLikePost = li.querySelector("button[name='addLike']");
        var buttonAddLikePostEnterVal = buttonAddLikePost.getAttribute("onmouseenter").replace("tbd", data.postId);
        var buttonAddLikePostLeaveVal = buttonAddLikePost.getAttribute("onmouseleave").replace("tbd", data.postId);
        buttonAddLikePost.setAttribute("onmouseenter", buttonAddLikePostEnterVal);
        buttonAddLikePost.setAttribute("onmouseleave", buttonAddLikePostLeaveVal);
        buttonAddLikePost.querySelector("span").setAttribute("id", "postLike"+data.postId);
        
        var postComments = li.querySelector("#comments-for-post-0");
        postComments.setAttribute("id", "comments-for-post-"+data.postId);
        
        var postCommentForm = li.querySelector("form[name='form-new-comment']");
        postCommentForm.setAttribute("action", Routing.generate("api_post_comment", {postId: data.postId}));
        postCommentForm.querySelector("input[name='post-id']").setAttribute("value", data.postId);
        
        var PostCommentFormWidget = li.querySelector("div.generic-blog-post-new-comment-field");
        PostCommentFormWidget.innerHTML = data.commentForm;
        
        list.insertBefore(li, list.firstChild);        
    });
});


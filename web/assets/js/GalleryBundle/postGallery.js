function nextImage(parent){
    afficheGallery(parent);
}

function previousImage(parent){
    afficheGallery(parent);
}
function afficheGallery(myDiv) {
    var src=myDiv.style.backgroundImage.split('"')[1]; 
    var srcBig= src.split('/media/cache/post_message_picture_thumb')[0] + src.split('/media/cache/post_message_picture_thumb')[1];
    document.getElementById("imageSlide").src=srcBig;
        
    document.getElementById("nextPic").onclick = function() {
        if(myDiv.nextElementSibling == null){
            nextImage(myDiv.parentNode.firstElementChild);
        }	
        nextImage(myDiv.nextElementSibling);
    };

    document.getElementById("previousPic").onclick = function() {
        if(myDiv.previousElementSibling == null){
            previousImage(myDiv.parentNode.lastElementChild);
        }	
        previousImage(myDiv.previousElementSibling);
    };   
}
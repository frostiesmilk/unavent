function nextImage(parent){
    afficheGallery(parent.firstElementChild);
}

function previousImage(parent){
    afficheGallery(parent.firstElementChild);
}
function afficheGallery(myDiv) {
//    var src=myDiv.style.backgroundImage; 
    var src=myDiv.src;
//    document.getElementById("imageSlide").src=src.split('"')[1];
    document.getElementById("imageSlide").src=src;//('"')[1];
    var parent= myDiv.parentNode;
        
    document.getElementById("nextPic").onclick = function() {
        if(parent.nextElementSibling == null){
            nextImage(parent.parentNode.firstElementChild);
        }	
        nextImage(parent.nextElementSibling);
    };

    document.getElementById("previousPic").onclick = function() {
        if(parent.previousElementSibling == null){
            previousImage(parent.parentNode.lastElementChild);
        }	
        previousImage(parent.previousElementSibling);
    };   
}
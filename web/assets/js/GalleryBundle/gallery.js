function nextImage(parent){
    afficheGallery(parent.firstElementChild);
}

function previousImage(parent){
    afficheGallery(parent.firstElementChild);
}
function afficheGallery(myDiv) {
//    var src=myDiv.style.backgroundImage; 
    var src = myDiv.parentElement.querySelector('img[name="photo-large"]').src;
//    document.getElementById("imageSlide").src=src.split('"')[1];
    document.getElementById("imageSlide").src=src;//('"')[1];
    var parent= myDiv.parentNode.parentNode.parentNode;

    document.getElementById("nextPic").onclick = function() {
        if(parent.nextElementSibling == null){
            nextImage(parent.parentNode.firstElementChild.firstElementChild.firstElementChild);
        }
        nextImage(parent.nextElementSibling.firstElementChild.firstElementChild);
    };

    document.getElementById("previousPic").onclick = function() {
        if(parent.previousElementSibling == null){
            previousImage(parent.parentNode.lastElementChild.firstElementChild.firstElementChild);
        }	
        previousImage(parent.previousElementSibling.firstElementChild.firstElementChild);
    };   
}
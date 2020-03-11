jQuery(document).ready(function(){
    console.log("main.js: loaded!");
    jQuery(".meta .share").click(function(){
        var url = jQuery(this).data('url');
        jQuery(".share-url").val(url);
        jQuery(".share-url-copy").html("Copy");
        jQuery(".share-buttons .facebook").attr("href","https://www.facebook.com/sharer/sharer.php?u="+url);
        jQuery(".share-buttons .twitter").attr("href","https://twitter.com/intent/tweet?source="+url+"&text="+url);
        jQuery(".share-buttons .reddit").attr("href","http://www.reddit.com/submit?url="+url);
        jQuery(".share-buttons .linkedin").attr("href","http://www.linkedin.com/shareArticle?mini=true&url="+url+"&title=&summary=&source="+url);
        jQuery(".share-buttons .email").attr("href","mailto:?subject=&body="+url);
        jQuery(".share-buttons").removeClass("hide");
    });

    jQuery(".share-buttons-box .close").click(function(){
        jQuery(".share-buttons").addClass("hide");
    });

    jQuery(".share-url-copy").click(function(e){
        e.preventDefault();
        var copyText = document.getElementById("share-url");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        jQuery(".share-url-copy").html("Copied!");
    });
});

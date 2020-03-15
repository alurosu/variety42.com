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

    jQuery(".like").click(function(){
        var joke = jQuery(this);
        vote(joke, "up");
    });

    jQuery(".dislike").click(function(){
        var joke = jQuery(this);
        vote(joke, "down");
    });

    function vote(joke, direction) {
        if (!joke.hasClass("disabled")) {
            if (direction=="up")
                joke.addClass("disabled").next().addClass("disabled");
            else
                joke.addClass("disabled").prev().addClass("disabled");
            var url = "/data/php/vote.php?id="+joke.data('id')+"&url="+joke.data('url')+"&vote="+direction;
            joke.addClass("active");
            $.get(url, function(data) {
                if (data.success) {
                    var count = parseInt(joke.find("span").html())+1;
                    joke.find("span").html(count);
                } else {
                    alert("You voted for this joke in the last 24h.");
                    joke.removeClass("active");
                }
            });
        }
    }
});

jQuery(document).mouseup(function(e) {
    var shareBox = $(".share-buttons-box");
    if (!shareBox.is(e.target) && shareBox.has(e.target).length === 0) {
        jQuery(".share-buttons").addClass("hide");
    }
});

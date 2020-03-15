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

    jQuery(".error-box .close").click(function(){
        jQuery(".error").addClass("hide");
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

    jQuery("#search_form").submit(function(e) {
        e.preventDefault();
        window.location.href = jQuery(this).attr("action")+jQuery('#search_input').val();
    });
    jQuery("#search_form svg").click(function() {
        jQuery("#search_form").submit();
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
                    jQuery(".error .message").html("You voted for this joke in the last 24h.");
                    jQuery(".error").removeClass("hide");
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

    var errorBox = $(".error-box");
    if (!errorBox.is(e.target) && errorBox.has(e.target).length === 0) {
        jQuery(".error").addClass("hide");
    }
});

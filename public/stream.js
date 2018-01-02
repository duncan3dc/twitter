var twitter = {
    status      :   1,
    showImages  :   localStorage.getItem("showImages"),
    types       :   JSON.parse(localStorage.getItem("types")),
    delay       :   localStorage.getItem("delay"),
    hidden      :   [],
}


jQuery.fn.twitterPost = function() {

    $(".actionPost",this).click(function(e) {
        $(".actionPost",$(this).parent()).removeClass("active")
        var post = $(this).attr("data-post")
        var status = $(this).attr("data-status")

        var html = $("<div>").append($("#postContainer_" + post).clone()).html()
        twitter.hidden.push(html)
        if(twitter.hidden.length == 1) {
            $("#undo").show()
        }

        $("#postContainer_" + post).slideUp(300,function() {
            $(this).remove()
            twitter.updatePostCount()
        })

        $.ajax({
            url         :   "http://localhost:3001/update-post",
            type        :   "post",
            data        :   {post : post, status : status},
            dataType    :   "json",
            success     :   function(data) {
                $("#savedCounter").html(data.saved)
                $("#unreadCounter").html(data.unread)
            }
        })
        e.stopPropagation()
    }).addClass("active")

    if($("img.postImage, video",this).length > 0) {
        $("img.postImage, video",this).css("display",twitter.showImages)
        $(this).click(function() {
            var display = ($("img.postImage, video",this).css("display") == "none") ? "inline" : "none"
            $("img.postImage, video",this).css("display",display)
        })
    }

    return this

}


$(document).ready(function() {

    $(document).keypress(function(event) {
        if(event.keyCode == 46) {
            $(".actionPost.active").first().click()
        }
    })

    $(".setStatus").click(function() {
        twitter.setStatus($(this).data("status"))
    })

    $("#undo").click(function() {
        var html = twitter.hidden.pop()
        var div = $(html)
        $("#stream-items-id").prepend(div)
        div.hide().twitterPost().slideDown()
        if(twitter.hidden.length < 1) {
            $("#undo").hide()
        }
        $.ajax({
            url         :   "http://localhost:3001/update-post",
            type        :   "post",
            data        :   {
                post        :   div.data("post"),
                status      :   twitter.status
            },
            dataType    :   "json",
            success     :   function(data) {
                $("#savedCounter").html(data.saved)
                $("#unreadCounter").html(data.unread)
            }
        })
    })

    $.ajax({
        url         :   "http://localhost:3001/get-user-data",
        type        :   "get",
        dataType    :   "json",
        success     :   function(data) {
            for(var type in data.userdata) {
                $("#userdata_" + type).text(data.userdata[type])
            }
        }
    })

    $(".types")
        .change(function() {
            twitter.types = []
            $(".types").each(function() {
                 if ($(this).prop("checked")) {
                     twitter.types.push($(this).val())
                 }
            })
            localStorage.setItem("types", JSON.stringify(twitter.types))
        }).each(function() {
            if (twitter.types.indexOf($(this).val()) > -1) {
                $(this).prop("checked", true)
            }
        })

    $("#delay")
        .val(twitter.delay)
        .keyup(function() {
            twitter.delay = $(this).val()
            localStorage.setItem("delay",twitter.delay)
        })

    $("#showImages").prop("checked",(twitter.showImages == "inline")).click(function() {
        twitter.showImages = $(this).prop("checked") ? "inline" : "none"
        localStorage.setItem("showImages",twitter.showImages)
        $("img.postImage, video").css("display",twitter.showImages)
    })

    $.ajax({
        url         :   "http://localhost:3001/get-hashtags",
        type        :   "get",
        dataType    :   "json",
        success     :   function(data) {
            var hashtag = ""
            for(var i in data.hashtags) {
                hashtag = data.hashtags[i]
                $("<li class='trend-item js-trend-item'>")
                    .append("<a class='js-nav' href='https://twitter.com/search?q=" + hashtag + "&ampsrc=tren'>#" + hashtag + "</a>")
                    .appendTo("#hashtags")
            }
        }
    })
})

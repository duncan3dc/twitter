var twitter = {
    status      :   1,
    showImages  :   localStorage.getItem("showImages"),
    types       :   JSON.parse(localStorage.getItem("types")),
    delay       :   localStorage.getItem("delay"),
    timeout     :   false,
    loading     :   false,
    postStack   :   [],

    setStatus   :   function(status) {
        if(twitter.loading) {
            setTimeout("twitter.setStatus(" + status + ")",100)
            return false
        }

        twitter.status = status
        twitter.postStack = []
        $("#undo").hide()

        $(".setStatus").parent().removeClass("active")
        $(".setStatus[data-status='" + twitter.status + "']").parent().addClass("active")

        $("#loadingPosts").show()
        $("div.postContainer").remove()
        clearTimeout(twitter.timeout)
        twitter.loadPosts()
    },

    checkPosts  :   function() {
        if($("div.postContainer").length < 20) {
            twitter.loadPosts()
        } else {
            $.get("/getUnreadCount",function(json) {
                var data = JSON.parse(json)
                $("#unreadCounter").html(data.unread)
            })
            twitter.timeout = setTimeout(twitter.checkPosts,10 * 1000)
        }
    },

    loadPosts   :   function() {
        if(twitter.loading) {
            return false
        }
        twitter.loading = true

        $("#loadingPosts").slideDown()

        var posts = []
        $("div.postContainer").each(function() {
            posts[posts.length] = $(this).data("post")
        })

        $.ajax({
            url         :   "/get-posts",
            type        :   "post",
            data        :   {
                status      :   twitter.status,
                types       :   twitter.types,
                delay       :   twitter.delay,
                posts       :   posts
            },
            dataType    :   "json",
            error       :   function() {
                $("#loadingPosts").hide()
                twitter.timeout = setTimeout(twitter.checkPosts,60 * 1000)
            },
            success     :   function(data) {
                $("#loadingPosts").hide()
                if(data.posts.length > 0) {
                    for(var i in data.posts) {
                        if($("#postContainer_" + data.posts[i].id).length > 0) {
                            continue
                        }
                        var post = $(data.posts[i].html)
                        $("#loadingPosts").before(post)
                        post.twitterPost()
                    }
                    $("#unreadCounter").html(data.unread)
                    twitter.updatePostCount()
                }
                twitter.timeout = setTimeout(twitter.checkPosts,10 * 1000)
                twitter.loading = false
            }
        })

    },

    updatePostCount :   function() {
        var title = "Twitter"

        var posts = $("div.postContainer").length
        if(posts > 0) {
            title += " (" + posts + ")"
        }

        document.title = title
    }

}


jQuery.fn.twitterPost = function() {

    $("img.actionPost",this).click(function(e) {
        $("img.actionPost",$(this).parent()).removeClass("active")
        var post = $(this).attr("data-post")
        var status = $(this).attr("data-status")

        var html = $("<div>").append($("#postContainer_" + post).clone()).html()
        twitter.postStack.push(html)
        if(twitter.postStack.length == 1) {
            $("#undo").show()
        }

        $("#postContainer_" + post).slideUp(300,function() {
            $(this).remove()
            twitter.updatePostCount()
        })

        $.ajax({
            url         :   "/update-post",
            type        :   "post",
            data        :   {post : post, status : status},
            dataType    :   "json",
            success     :   function(data) {
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
            $("img.actionPost.active").first().click()
        }
    })

    $(".setStatus").click(function() {
        twitter.setStatus($(this).data("status"))
    })

    $("#undo").click(function() {
        var html = twitter.postStack.pop()
        var div = $(html)
        $("#stream-items-id").prepend(div)
        div.hide().twitterPost().slideDown()
        if(twitter.postStack.length < 1) {
            $("#undo").hide()
        }
        $.ajax({
            url         :   "/update-post",
            type        :   "post",
            data        :   {
                post        :   div.data("post"),
                status      :   twitter.status
            },
            dataType    :   "json",
            success     :   function(data) {
                $("#unreadCounter").html(data.unread)
            }
        })
    })

    $.ajax({
        url         :   "/get-user-data",
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
        url         :   "/get-hashtags",
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

    twitter.setStatus(twitter.status)

})

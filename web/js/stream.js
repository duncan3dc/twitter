var twitter = {
    status      :   1,
    showImages  :   localStorage.getItem("showImages"),
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
            $.get("ajax.php?action=getUnreadCount",function(json) {
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
            url         :   "ajax.php?action=getPosts",
            type        :   "post",
            data        :   {
                status      :   twitter.status,
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
            url         :   "ajax.php?action=updatePost",
            type        :   "post",
            data        :   {post : post, status : status},
            dataType    :   "json",
            success     :   function(data) {
                $("#unreadCounter").html(data.unread)
            }
        })
        e.stopPropagation()
    }).addClass("active")

    return this
}


$(document).ready(function() {

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
            url         :   "ajax.php?action=updatePost",
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
        url         :   "ajax.php?action=getUserData",
        type        :   "get",
        dataType    :   "json",
        success     :   function(data) {
            for(var type in data.userdata) {
                $("#userdata_" + type).text(data.userdata[type])
            }
        }
    })

    twitter.setStatus(twitter.status)

})

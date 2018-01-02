import React, { Component } from 'react';

class Stream extends Component {

  constructor(props) {
    super(props);
    this.state = {
      status      :   1,
      showImages  :   localStorage.getItem("showImages"),
      types       :   JSON.parse(localStorage.getItem("types")),
      delay       :   localStorage.getItem("delay"),
      timeout     :   false,
      loading     :   false,
      posts       :   [],
      hidden      :   [],
    };
  }

  setStatus(status) {

    if (this.state.loading) {
//      setTimeout("this.setStatus(" + status + ")",100);
      return false;
    }

    this.setState({
      status :  status,
      hidden :  [],
    })

    //$("#undo").hide();

    //$(".setStatus").parent().removeClass("active");
    //$(".setStatus[data-status='" + this.state.status + "']").parent().addClass("active");

    //$("#loadingPosts").show();
    //$("div.postContainer").remove();
    //clearTimeout(this.state.timeout);
    this.loadPosts();
  }

  checkPosts() {
    if (this.state.posts.length < 20) {
      this.loadPosts();
      return;
    }

    fetch("http://localhost:3001/getUnreadCount")
      .then(res => res.json())
      .then((data) => {
        //$("#savedCounter").html(data.saved)
        //$("#unreadCounter").html(data.unread)
      })

    var timeout = setTimeout(this.checkPosts, 10 * 1000);
    this.setState({timeout: timeout});
  }

  loadPosts() {
    if (this.state.loading) {
      return false;
    }

    this.setState({loading: true});

    //$("#loadingPosts").slideDown();

    var posts = [];
    //$("div.postContainer").each(function() {
      //posts[posts.length] = $(this).data("post")
    //})

    var body = new FormData();
    body.set("status", this.state.status);
//    body.set("types", this.state.types);
//    body.set("delay", this.state.delay);
    body.set("posts", posts);
    fetch("http://localhost:3001/get-posts", {
      method: "POST",
      body: body
    })
      .then(res => res.json())
      .then(
        (data) => {
          //$("#loadingPosts").hide()
          if (data.posts.length > 0) {
            var posts = [];
            for(var i in data.posts) {
              //if($("#postContainer_" + data.posts[i].id).length > 0) {
                //continue;
              //}
              posts[posts.length] = data.posts[i].html
              //var post = $(data.posts[i].html);
              //$("#loadingPosts").before(post);
              //post.twitterPost();
            }
            this.setState({posts: posts});
            //$("#savedCounter").html(data.saved);
            //$("#unreadCounter").html(data.unread);
            this.updatePostCount();
          }
          this.setState({
            timeout: setTimeout(this.checkPosts, 10 * 1000),
            loading: false,
          });
        },
        (error) => {
          alert(error);
          //$("#loadingPosts").hide();
          var timeout = setTimeout(this.checkPosts, 60 * 1000);
          this.setState(timeout: timeout);
        }
      )
  }

  updatePostCount() {
    var title = "Twitter";

    var posts = this.state.posts.length;
    if (posts > 0) {
      title += " (" + posts + ")";
    }

    document.title = title;
  }

  componentDidMount() {
    this.setStatus(this.state.status)
  }

  render() {
    const posts = this.state.posts.map((html) =>
        <div dangerouslySetInnerHTML={{__html: html}} />
    );

    return (
<div className="stream-container">
  <div className="stream home-stream">
    <div className="stream-items" id="stream-items-id">
      {posts}
      <div id="loadingPosts">
        <i className="fa fa-spinner fa-spin" alt="Loading Tweets..."></i>
        <div id="loadingPostsLabel">Loading Tweets...</div>
      </div>
      <div id="streamTail"></div>
    </div>
  </div>
</div>
    );
  }
}

export default Stream;

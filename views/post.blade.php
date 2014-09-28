<div id='postContainer_{{ $post->id }}' data-post='{{ $post->id }}' class='postContainer js-stream-item stream-item stream-item expanding-stream-item'>
    <div class='tweet original-tweet js-stream-tweet'>
        <div class='content'>
            <img class='actionPost' data-post='{{ $post->id }}' data-status='0' src='/images/x.gif'>
            <img class='actionPost' data-post='{{ $post->id }}' data-status='2' src='/images/save.png'>
            <div class='stream-item-header'>
                <small class='time'>
                    @if ($post->retweet)
                        {{ date("d/m/y H:i:s", strtotime($data["created_at"])) }}
                        <br>
                    @endif
                    <a href='{{ $post->link }}'>
                        {{ date("d/m/y H:i:s", $post->date) }}
                    </a>
                </small>
                <a class='account-group js-account-group js-action-profile js-user-profile-link js-nav' href='{{ $post->hostname . $post->username }}'>
                    <img class='avatar js-action-profile-avatar' src='{{ $post->avatar }}'>
                    <strong class='fullname'>{{{ $post->fullname }}}</strong>
                    <span class='username'>@{{{ $post->username }}}</span>
                </a>
            </div>
            <p class='js-tweet-text'>
                {{ preg_replace_callback("/  +/", function($match) { return str_replace(" ", "&nbsp;", $match[0]); }, $post->text) }}
            </p>
            <div class='stream-item-footer'>
                <div class='context'>
                    <span class='with-icn'>
                        @if ($post->retweet)
                            <span class='js-retweet-text'>
                                Retweeted by
                                {{{ $post->retweet["user"]["name"] }}}
                                (<a class='pretty-link js-user-profile-link' href='{{ $post->hostname . $post->retweet["user"]["screen_name"] }}'>
                                    @{{{ $post->retweet["user"]["screen_name"] }}}
                                </a>)
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

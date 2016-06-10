<div id='postContainer_{{ $post->id }}' data-post='{{ $post->id }}' class='postContainer js-stream-item stream-item stream-item expanding-stream-item'>
    <div class='tweet original-tweet js-stream-tweet'>
        <div class='content'>
            <i class='actionPost fa fa-trash-o' data-post='{{ $post->id }}' data-status='0'></i>
            <i class='actionPost fa fa-floppy-o' data-post='{{ $post->id }}' data-status='2'></i>
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
                <a href='{{ $post->getUserLink($post->username) }}'>
                    <img class='avatar' src='{{ $post->avatar }}'>
                    <strong class='fullname'>{{ $post->fullname }}</strong>
                    <span class='username'>&commat;{{ $post->username }}</span>
                </a>
            </div>
            <p class='js-tweet-text'>
                {!! $post->safespace($post->getHtml()) !!}

                @if ($post->quoted)
                    <div class='tweet quoted-tweet'>
                        <div class='content'>
                            <div class='stream-item-header'>
                                <small class='time'>
                                    <a href='{{ $post->hostname . $post->quoted["user"]["screen_name"] . "/status/" . $post->quoted["id_str"] }}'>
                                        {{ date("d/m/y H:i:s", strtotime($post->quoted["created_at"])) }}
                                    </a>
                                </small>
                                <a href='{{ $post->hostname . $post->quoted["user"]["screen_name"] }}'>
                                    <img class='avatar' src='{{ $post->quoted["user"]["profile_image_url"] }}'>
                                    <strong class='fullname'>{{ $post->quoted["user"]["name"] }}</strong>
                                    <span class='username'>&commat;{{ $post->quoted["user"]["screen_name"] }}</span>
                                </a>
                            </div>
                            <p class='js-tweet-text'>
                                {!! $post->safespace($post->getQuotedHtml()) !!}
                            </p>
                        </div>
                    </div>
                @endif
            </p>
            <div class='stream-item-footer'>
                <div class='context'>
                    <span class='with-icn'>
                        @if ($post->retweet)
                            <span class='js-retweet-text'>
                                Retweeted by
                                {{ $post->getUserLink($post->retweet["user"]["name"]) }}
                                (<a href='{{ $post->hostname . "/" . $post->retweet["user"]["screen_name"] }}'>&commat;{{ $post->retweet["user"]["screen_name"] }}</a>)
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

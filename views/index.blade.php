@namespace(duncan3dc\Twitter)

<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, user-scalable=yes'>
    <link rel='shortcut icon' href='/images/favicon.ico'>

    <link rel='stylesheet' type='text/css' href='/css/t1_core.css'>
    <link rel='stylesheet' type='text/css' href='/css/user-style-duncan3dc.css'>
    <link rel='stylesheet' type='text/css' href='/css/stream.css'>
    <link rel='stylesheet' type='text/css' href='/css/greasemonkey.css'>

    <script type='text/javascript' src='/js/jquery-2.1.1.js'></script>
    <script type='text/javascript' src='/js/stream.js'></script>
</head>
<body>

<div id='doc'>

    <div class='topbar js-topbar'>
        <div id='banners' class='js-banners'></div>
        <div class='global-nav' data-section-term='top_nav'>
            <div class='global-nav-inner'>
                <div class='container'>
                    <ul class='nav js-global-actions' id='global-actions'>
                        <li class='home'><a class='js-hover js-nav setStatus' data-status='1'>Home</a></li>
                        <li class='home'><a class='js-hover js-nav setStatus' data-status='2'>Saved</a></li>
                    </ul>
                    <div class='pull-right'>
                        <form class='form-search' action='https://twitter.com/search' method='get'>
                            <input class='search-input' id='search-query' placeholder='Search' name='q' autocomplete='off' spellcheck='false' type='text'>
                            <input disabled='disabled' class='search-input search-hinting-input' id='search-query-hint' autocomplete='off' spellcheck='false' type='text'>
                            <input type='submit' value='Search' style='visibility:hidden;'>
                        </form>
                        <i class='topbar-divider'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id='page-outer'>
        <div id='page-container' class='wrapper wrapper-home white'>
            <div class='dashboard'>
                <div class='module mini-profile'>
                    <div class='flex-module profile-summary js-profile-summary'>
                        <a href='https://twitter.com/duncan3dc' class='account-summary account-summary-small js-nav' data-nav='profile'>
                            <div class='content'>
                                <div class='account-group js-mini-current-user' data-user-id='39368939' data-screen-name='duncan3dc'>
                                    <img class='avatar size32' src='/images/profile.jpg' alt='Craig Duncan' data-user-id='39368939'>
                                    <b class='fullname'>Craig Duncan</b>
                                    <small class='metadata'>View my profile page</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class='js-mini-profile-stats-container'>
                        <ul class='stats js-mini-profile-stats' data-user-id='39368939'>
                            <li>
                                <a class='js-nav' href='https://twitter.com/duncan3dc' data-element-term='tweet_stats' data-nav='profile'>
                                    <strong id='userdata_tweets'>0</strong> Tweets
                                </a>
                            </li>
                            <li>
                                <a class='js-nav' href='https://twitter.com/following' data-element-term='following_stats' data-nav='following'>
                                    <strong id='userdata_following'>0</strong> Following
                                </a>
                            </li>
                            <li>
                                <a class='js-nav' href='https://twitter.com/followers' data-element-term='follower_stats' data-nav='followers'>
                                    <strong id='userdata_followers'>0</strong> Followers
                                </a>
                            </li>
                            <li>
                                <a class='js-nav'>
                                    <strong id='unreadCounter'>0</strong> Unread
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class='home-tweet-box tweet-box component tweet-user'></div>
                </div>
                <div class='module trends'>
                    <div class='flex-module trends-container'>
                        <div class='flex-module-inner'>

                            <h4>Types: </h4>
                            @foreach (App::getTypes() as $type)
                                <input type='checkbox' class='types' value='{{ $type }}'>{{ $type }}<br>
                            @endforeach

                            <br><br>

                            <h4>Delay (minutes): </h4>
                            <input type='text' id='delay' value='0'>

                            <br><br>

                            <h4>Show Images: </h4>
                            <input type='checkbox' id='showImages'>
                        </div>
                    </div>
                </div>
                <div class='module trends'>
                    <div class='flex-module trends-container'>
                        <div class='flex-module-header'>
                            <h3><span class='js-trend-location'>Trends</span></h3>
                        </div>
                        <div class='flex-module-inner'>
                            <ul class='trend-items js-trends' id='hashtags'></ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class='content-main js-timeline-from-cache' id='timeline'>
                <div class='content-header'>
                    <div class='header-inner'>
                        <img id='undo' src='/images/undo.png' title='Undo'>
                        <h2 class='js-timeline-title'>Tweets</h2>
                    </div>
                </div>
                <div class='stream-container'>
                    <div class='stream home-stream'>
                        <div class='stream-items' id='stream-items-id'>
                            <div id='loadingPosts'>
                                <img src='/images/loadingPosts.gif' alt='Loading Tweets...'>
                                <div id='loadingPostsLabel'>Loading Tweets...</div>
                            </div>
                            <div id='streamTail'></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>

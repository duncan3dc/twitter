<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, user-scalable=yes'>
    <link rel='shortcut icon' href='/images/favicon.ico'>

    <link rel='stylesheet' type='text/css' href='/css/font-awesome.css'>
    <link rel='stylesheet' type='text/css' href='/css/t1_core.css'>
    <link rel='stylesheet' type='text/css' href='/css/user-style-duncan3dc.css'>
    <link rel='stylesheet' type='text/css' href='/css/stream.css'>
    <link rel='stylesheet' type='text/css' href='/css/greasemonkey.css'>

    <script type='text/javascript' src='/js/jquery-2.1.4.js'></script>
</head>
<body>

<div id='doc'>

    <div class='topbar js-topbar'>
        <div id='banners' class='js-banners'></div>
        <div class='global-nav' data-section-term='top_nav'></div>
    </div>

    <div id='page-outer'>
        <div id='page-container' class='wrapper wrapper-home'>
            <div class='content-main js-timeline-from-cache' id='timeline'>
                <div class='content-header'>
                    <div class='header-inner'>
                        <h2 class='js-timeline-title'>Twitter</h2>
                    </div>
                </div>
                <div class='stream-container'>
                    <div class='stream home-stream'>
                        <div class='stream-items'>
                            <div class="stream-item">
                                <form action='/' method='post'>
                                    <table id='login'>

                                        <tr>
                                            <td><label for='username'>Username:</label></td>
                                            <td><input type='text' name='username'></td>
                                        </tr>

                                        <tr>
                                            <td><label for='password'>Password:</label></td>
                                            <td><input type='password' name='password'></td>
                                        </tr>

                                        <tr class='center'>
                                            <td colspan='2'><input type='submit' value='Login'></td>
                                        </tr>

                                    </table>
                                </form>
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

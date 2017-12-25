import React, { Component } from 'react';

import "./css/t1_core.css";
import "./css/user-style-duncan3dc.css";
import "./css/stream.css";

class App extends Component {
  render() {
    return (
<div id="doc">

    <div className="topbar js-topbar">
        <div id="banners" className="js-banners"></div>
        <div className="global-nav" data-section-term="top_nav">
            <div className="global-nav-inner">
                <div className="container">
                    <ul className="nav js-global-actions" id="global-actions">
                        <li className="home"><a className="js-hover js-nav setStatus" data-status="1">Home</a></li>
                        <li className="home"><a className="js-hover js-nav setStatus" data-status="2">Saved</a></li>
{/*
                        @if (!Env::getVar("auto-login"))
                            <li className="home"><a className="js-hover js-nav" href="/logout">Logout</a></li>
                        @endif
*/}
                    </ul>
                    <div className="pull-right">
                        <form className="form-search" action="https://twitter.com/search" method="get">
                            <input className="search-input" id="search-query" placeholder="Search" name="q" type="text" />
                            <input disabled="disabled" className="search-input search-hinting-input" id="search-query-hint" type="text" />
                            <input type="submit" value="Search" style={{visibility:"hidden"}} />
                        </form>
                        <i className="topbar-divider"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="page-outer">
        <div id="page-container" className="wrapper wrapper-home white">
            <div className="dashboard">
                <div className="module mini-profile">
                    <div className="flex-module profile-summary js-profile-summary">
                        <a href="https://twitter.com/duncan3dc" className="account-summary account-summary-small js-nav" data-nav="profile">
                            <div className="content">
                                <div className="account-group js-mini-current-user" data-user-id="39368939" data-screen-name="duncan3dc">
                                    <img className="avatar size32" src="/images/profile.jpg" alt="Craig Duncan" data-user-id="39368939" />
                                    <b className="fullname">Craig Duncan</b>
                                    <small className="metadata">View my profile page</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div className="js-mini-profile-stats-container">
                        <ul className="stats js-mini-profile-stats" data-user-id="39368939">
                            <li>
                                <a className="js-nav" href="https://twitter.com/duncan3dc" data-element-term="tweet_stats" data-nav="profile">
                                    <strong id="userdata_tweets">0</strong> Tweets
                                </a>
                            </li>
                            <li>
                                <a className="js-nav" href="https://twitter.com/following" data-element-term="following_stats" data-nav="following">
                                    <strong id="userdata_following">0</strong> Following
                                </a>
                            </li>
                            <li>
                                <a className="js-nav">
                                    <strong id="savedCounter">0</strong> Saved
                                </a>
                            </li>
                            <li>
                                <a className="js-nav">
                                    <strong id="unreadCounter">0</strong> Unread
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div className="home-tweet-box tweet-box component tweet-user"></div>
                </div>
                <div className="module trends">
                    <div className="flex-module trends-container">
                        <div className="flex-module-inner">

                            <h4>Types: </h4>
{/*
                            @foreach (App::getTypes() as $type)
                                <input type="checkbox" className="types" value="{{ $type }}" />{{ $type }}<br />
                            @endforeach
*/}
                            <br /><br />

                            <h4>Delay (minutes): </h4>
                            <input type="text" id="delay" value="0" />

                            <br /><br />

                            <h4>Show Images: </h4>
                            <input type="checkbox" id="showImages" />
                        </div>
                    </div>
                </div>
                <div className="module trends">
                    <div className="flex-module trends-container">
                        <div className="flex-module-header">
                            <h3><span className="js-trend-location">Trends</span></h3>
                        </div>
                        <div className="flex-module-inner">
                            <ul className="trend-items js-trends" id="hashtags"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <div className="content-main js-timeline-from-cache" id="timeline">
                <div className="content-header">
                    <div className="header-inner">
                        <i id="undo" className="fa fa-undo" title="Undo"></i>
                        <h2 className="js-timeline-title">Tweets</h2>
                    </div>
                </div>
                <div className="stream-container">
                    <div className="stream home-stream">
                        <div className="stream-items" id="stream-items-id">
                            <div id="loadingPosts">
                                <i className="fa fa-spinner fa-spin" alt="Loading Tweets..."></i>
                                <div id="loadingPostsLabel">Loading Tweets...</div>
                            </div>
                            <div id="streamTail"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
    );
  }
}

export default App;

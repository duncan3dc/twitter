import React, { Component } from 'react';

class TopBar extends Component {
  render() {
    return (
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
    );
  }
}

export default TopBar;

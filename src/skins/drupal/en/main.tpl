{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Layout
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div id="page-wrapper">
  <div id="page">

    <widget class="\XLite\View\TopMessage" />

    <div id="header-area">
        <div id="logo">
          <a href="{buildURL()}" title="{t(#Home#)}" rel="home"><img src="images/logo.png" alt="{t(#Home#)}" /></a>
        </div>

      <div id="header-bar">

        <ul class="account-links inline">
          <li class="account-link-1 first"><a href="{buildURL(#login#)}" class="log-in">{t(#Log in#)}</a></li>
          <li class="account-link-2 last"><a href="{buildURL(#register#)}" class="register">{t(#Register#)}</a></li>
        </ul>

        <div id="search">
          <widget class="\XLite\View\Form\Product\Search\Customer\Simple" />
        </div>

      </div>

      <div id="header">
        <widget class="\XLite\View\Minicart" displayMode="horizontal" />
      </div>

    </div>

    <widget class="\XLite\View\Menu\Customer\Top" />

    <div id="breadcrumb">
      <widget class="\XLite\View\Location" />
    </div>

    <div id="main-wrapper">
      <div id="main" class="clearfix">

        <div id="sidebar-first" class="column sidebar">
          <div class="section">
            {displayViewListContent(#sidebar.first#)}
          </div>
        </div>

        <div id="content" class="column">
          <div class="section">
            <a id="main-content"></a>
            <widget template="center.tpl" />
          </div>
        </div>

      </div>
    </div>

    <div id="footer-area">

      <widget class="\XLite\View\Menu\Customer\Footer" />

      <div id="footer">
        <div class="section">
          {displayViewListContent(#sidebar.footer#)}
        </div>
      </div>

    </div>

  </div>
</div>

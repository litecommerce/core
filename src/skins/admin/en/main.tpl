{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Main template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget class="\XLite\View\TopMessage" />

<div id="page-container"{if:!auth.isLogged()} class="login-page"{end:}>

  <div id="content">

    <div id="main">

      <widget template="center.tpl" />
    
    </div><!-- [/main] -->
  
    <div id="sub-section"></div>
  
  </div><!-- [/content] -->


  <div id="header">

    <div class="logo"></div>

    <div class="sw-version">
      <span class="current" IF="!auth.isLogged()">{t(#LiteCommerce shopping cart software#)}</span>
      <span class="current" IF="auth.isLogged()">{t(#v.#)} {getCurrentCoreVersion()}</span>
      <span IF="auth.isLogged()&isCoreUpgradeAvailable()" class="upgrade-note">
        <a href="#">{t(#Upgrade available#)}</a>
      </span>
    </div>

    <widget class="\XLite\View\TopMenu" />
    <widget class="\XLite\View\TopLinks" />
    <h1 IF="!auth.isLogged()">{t(#Administration Zone#)}</h1>

  </div><!-- [/header] -->


  <div id="footer">

    <div class="left">
      <widget class="\XLite\View\PoweredBy" />
    </div>

    <div class="right">
    </div>

    <div class="clear"></div>

  </div><!-- [/footer] -->

</div><!-- [/page-container] -->

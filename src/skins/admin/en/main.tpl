{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Main template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div IF="isDeveloperMode()" id="profiler-messages"></div>

<widget class="\XLite\View\TopMessage" />

<div id="page-wrapper">

  <div id="header-wrapper">
    <list name="admin.main.page.header_wrapper" />

    <div id="header">

      <list name="admin.main.page.header" />

    </div><!-- [/header] -->
  </div><!-- [/header-wrapper] -->

  <div id="page-container"{if:!auth.isLogged()} class="login-page"{end:}>

    <div id="content">
      <div id="content-header">

        <div id="main">

          <list name="admin.main.page.content.center" />

        </div><!-- [/main] -->

        <div id="sub-section"></div>

      </div>
    </div><!-- [/content] -->

  </div><!-- [/page-container] -->

</div><!-- [/page-wrapper] -->

<div id="footer">

  <div class="left">
    <widget class="\XLite\View\PoweredBy" />
  </div>

  <div class="right"></div>

  <div class="clear"></div>

</div><!-- [/footer] -->


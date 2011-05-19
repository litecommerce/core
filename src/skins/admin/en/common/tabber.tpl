{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tabber template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<h1 IF="head">{head:h}</h1>

<div class="tabbed-content-wrapper">

  <div class="tabs-container">

    <div class="page-tabs">

      <ul>
        <li FOREACH="getTabberPages(),tabPage" class="tab{if:tabPage.selected}-current{end:}">
          <a href="{tabPage.url:h}">{tabPage.title}</a>
        </li>
      </ul>

    </div>
    <div class="clear"></div>

    <div class="tab-content">
      <widget template="{getParam(#body#)}" />
    </div>

  </div>

</div>

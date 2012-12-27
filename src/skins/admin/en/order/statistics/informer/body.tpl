{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order summary mini informer (for dashboard)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div IF="getTabs()" class="js-tabs order-stats-informer-tabs" style="{getBlockStyle()}">

  <div class="tabs">
    <ul class="tabs primary">
      <li FOREACH="getTabs(),index,tab" class="{getTabClass(tab)}">
        <span id="link-{tab.id:h}">{t(tab.name)}</span>
      </li>
    </ul>
  </div>

  <div FOREACH="getTabs(),tab" id="{tab.id:h}" class="tab-container" style="{getTabStyle(tab)}">
    <a name="{tab.id:h}"></a>
    {if:tab.template}
      <widget template="{tab.template}" />

    {elseif:tab.widget}
      <widget class="{tab.widget}" />

    {elseif:tab.list}
      <list name="{tab.list}" />

    {else:}
      No content
    {end:}
  </div>

</div>

<div class="clear"></div>


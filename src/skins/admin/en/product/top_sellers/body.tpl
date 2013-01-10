{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Top selling products block template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{if:!isEmptyStats()}

<div class="top-sellers">

  {foreach:getOptions(),period,name}
  <div id="period-{period:h}" class="block-container" {if:!isDefaultPeriod(period)}style="display: none;"{end:}>
    <widget class="\XLite\View\ItemsList\Model\Product\Admin\TopSellers" period="{period}" products_limit="5"/>
  </div>
  {end:}

  <div class="period-box">
    <span class="label">{t(#For the period#)}</span>
    <span class="field">
      <select name="period">
        {foreach:getOptions(),period,name}
        <option value="{period}" {if:isDefaultPeriod(period)}selected="selected"{end:}>{name}</option>
        {end:}
      </select>
    </span>
  </div>

</div>

{else:}

<div class="empty-list">{t(#No products sold yet#)}</div>

{end:}


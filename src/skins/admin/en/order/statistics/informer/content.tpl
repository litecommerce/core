{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order summary mini informer (for dashboard)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="orders-stats">
  <div class="tab-content-title">{t(#Orders#)}</div>
  <div class="value" IF="tab.orders.value">{tab.orders.value}<span class="{getDeltaType(tab,#orders#)}"></span></div>
  <div class="value" IF="!tab.orders.value">&mdash;</div>
  <div class="prev" IF="isDisplayPrevValue(tab)">{getPrevValue(tab,#orders#)}</div>
</div>

<div class="revenue-stats">
  <div class="tab-content-title">{t(#Revenue#)}</div>
  <div class="value">{formatValue(tab.revenue.value)}<span class="{getDeltaType(tab,#revenue#)}"></span></div>
  <div class="prev" IF="isDisplayPrevValue(tab)">{getPrevValue(tab,#revenue#)}</div>
</div>

<div class="lifetime-stats" IF="isLifetimeTab(tab)">{t(#Sale statistics from the opening of the store#)}</div>
<div class="no-orders" IF="isEmptyStats()">{t(#No order have been placed yet#)}</div>

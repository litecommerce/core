{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search conditions block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="orders.search.base", weight="20")
 *}
<div class="search-orders-box">
  <div class="search-orders-conditions">
    <a IF="getTotalCount()" href="javascript:void(0);" onclick="javascript:core.toggleText(this,'Hide filter options','#advanced_search_order_options');">Show filter options</a>
  </div>

  <div id="advanced_search_order_options" style="display:none;">
    <widget class="\XLite\View\Form\Order\Search" name="order_search_form" />
      <table cellspacing="0" class="form-table search-orders">
      {displayViewListContent(#orders.search.conditions#)}
      </table>
    <widget name="order_search_form" end />
    {displayViewListContent(#orders.search.panel#)}
  </div>
</div>


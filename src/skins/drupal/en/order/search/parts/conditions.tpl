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
{if:getTotalCount()}
  <widget class="XLite_View_Form_Order_Search" name="order_search_form" />

    <table cellspacing="0" class="form-table search-orders">
      {displayViewListContent(#orders.search.conditions#)}
    </table>

  <widget name="order_search_form" end />
{end:}

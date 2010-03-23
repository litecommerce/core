{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order items short list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellspacing="0" class="form-table">
  <tr FOREACH="getItems(),i,item">
    <td class="name"><a href="{item.getURL()}">{item.name}</a></td>
    <td class="price">{price_format(item,#price#):h}</td>
    <td class="qty">qty:</td>
    <td class="quantity">{item.amount}</td>
    <td IF="i=#0#" class="total">Grand total: <strong>{price_format(getOrder(),#total#):h}</strong></td>
    <td IF="i=#1#&isMoreLinkVisible()" class="more"><a href="{buildURL(#order#,##,_ARRAY_(#order_id#^order.order_id))}" class="dynamic dynamic-{getMoreLinkClassName()}"><span>{order.getItemsCount()} items</span><img src="images/spacer.gif" alt="" /></a></td>
  </tr>
</table>

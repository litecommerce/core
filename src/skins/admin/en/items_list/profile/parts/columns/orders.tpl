{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.profile.search.columns", weight="50")
 *}

<td class="orders table-label">
{if:profile.orders_count}<a href="{buildURL(#order_list#,##,_ARRAY_(#mode#^#search#,#login#^profile.login))}">{profile.orders_count}</a>{else:}n/a{end:}
</td>

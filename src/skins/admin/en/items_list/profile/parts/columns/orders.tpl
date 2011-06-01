{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="itemsList.profile.search.columns", weight="50")
 *}

<td class="orders">
{if:profile.orders_count}<a href="{buildURL(#order_list#,##,_ARRAY_(#mode#^#search#,#login#^profile.login))}">{profile.orders_count}</a>{else:}n/a{end:}
</td>

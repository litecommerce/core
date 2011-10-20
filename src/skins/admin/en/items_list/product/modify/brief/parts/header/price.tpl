{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item price
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="itemsList.product.modify.brief.admin.header", weight="60")
 *}

<th class="price"><widget template="items_list/sort.tpl" title='Price <span class="currency">({getCurrencySymbol()})</span>' sortByColumn="{%static::SORT_BY_MODE_PRICE%}" /></th>

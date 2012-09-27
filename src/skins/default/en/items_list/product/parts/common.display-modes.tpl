{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list display mode selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.grid.customer.header", weight="20")
 * @ListChild (list="itemsList.product.list.customer.header", weight="20")
 * @ListChild (list="itemsList.product.table.customer.header", weight="20")
 *}

<ul class="display-modes grid-list" IF="isDisplayModeSelectorVisible()">
  <li FOREACH="displayModes,key,name" class="{getDisplayModeLinkClassName(key)}">
    <a href="{getActionURL(_ARRAY_(#displayMode#^key))}" class="{key}">{t(name)}</a>
  </li>
</ul>

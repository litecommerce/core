{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item price
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.modify.brief.admin.columns", weight="70")
 *}

<td>
  <input type="text" class="inventory{if:!product.inventory.getEnabled()} input-disabled{end:}" size="10" value="{product.inventory.getAmount():r}" name="{getNamePostedData(#amount#,product.getProductId())}" disabled="{!product.inventory.getEnabled()}" />
</td>

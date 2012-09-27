{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item body
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.list.customer.body", weight="40")
 *}

<td class="body">
  <div class="quick-look-cell">
    <list name="quicklook.info" type="nested" />
    <list name="info" type="nested" product="{product}" />
  </div>
</td>

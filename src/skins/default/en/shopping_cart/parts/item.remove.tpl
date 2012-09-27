{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item : main
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="cart.item", weight="10")
 *}
<td class="item-remove delete-from-list">
  <widget class="\XLite\View\Form\Cart\Item\Delete" name="itemRemove{item.getItemId()}" item="{item}" />
    <div><widget class="\XLite\View\Button\Image" label="Delete item" style="remove" jsCode="return true;" /></div>
  <widget name="itemRemove{item.getItemId()}" end />
</td>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.modify.list", weight="1010")
 *}
<tr>
  <td class="name-attribute">{t(#Full description#)}</td>
  <td class="star"></td>
  <td class="value-attribute">
    <widget class="\XLite\View\FormField\Textarea\Advanced" fieldName="{getNamePostedData(#description#)}" cols="45" rows="6" value="{product.description:h}" fieldOnly="true" />
  </td>
</tr>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="product.modify.list", weight="1015")
 *}
<tr>
  <td class="name-attribute">{t(#OpenGraph meta tags#)}</td>
  <td class="star"></td>
  <td class="value-attribute">
    <widget class="\XLite\View\FormField\Textarea\Simple" fieldName="{getNamePostedData(#ogMeta#)}" cols="45" rows="6" value="{product.ogMeta:h}" />
    <widget class="\XLite\View\FormField\Input\Checkbox" fieldName="{getNamePostedData(#useOGGenerator#)}" cols="45" rows="6" isChecked="{product.useOGGenerator}" fieldId="useOGGeneratorFF" fieldOnly="true" /> <label for="useOGGeneratorFF">Use Open graph tags autogeneration</label>
  </td>
</tr>


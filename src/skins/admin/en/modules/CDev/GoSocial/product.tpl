{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<tr>
  <td class="name-attribute">{t(#Open Graph meta tags#)}</td>
  <td class="star"></td>
  <td class="value-attribute og-tags">
    <div>
      <widget class="\XLite\View\FormField\Textarea\Simple" fieldName="{getNamePostedData(#ogMeta#)}" cols="140" rows="8" value="{product.getOpenGraphMetaTags():h}" attributes="{getOpenGraphTextareaAttributes()}" help="{t(#These Open Graph meta tags were generated automatically based on general product information.#)}" />
      <div class="clear"></div>
    </div>
    <div class="control">
      <widget class="\XLite\View\FormField\Input\Checkbox" fieldName="{getNamePostedData(#useCustomOG#)}" isChecked="{product.useCustomOG}" fieldId="useCustomOGFF" fieldOnly="true" />
      <label for="useCustomOGFF">{t(#Define OG meta tags manually#)}</label>
    </div>
  </td>
</tr>


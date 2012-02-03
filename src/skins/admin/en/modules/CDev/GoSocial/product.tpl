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
    <div class="control">
      <widget class="\XLite\Module\CDev\GoSocial\View\FormField\Select\UseCustomOpenGraph" fieldName="{getNamePostedData(#useCustomOG#)}" value="{product.getUseCustomOG()}" fieldOnly="true" />
    </div>
    <div{if:!product.useCustomOG} style="display: none;"{end:} class="og-textarea">
      <widget class="\XLite\View\FormField\Textarea\Simple" fieldName="{getNamePostedData(#ogMeta#)}" cols="140" rows="8" value="{product.getOpenGraphMetaTags(false):h}" help="{t(#These Open Graph meta tags were generated automatically based on general product information.#)}" />
      <div class="clear"></div>
    </div>
  </td>
</tr>


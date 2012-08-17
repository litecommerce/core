{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.modify.list", weight="1040")
 *}

<tr>
  <td class="name-attribute">{t(#Clean URL#)}</td>
  <td class="star"></td>
  <td class="value-attribute">
    <widget class="XLite\View\FormField\Input\Text\CleanURL" fieldName="{getNamePostedData(#cleanURL#)}" value="{product.getCleanURL()}" maxlength="{getCleanURLMaxLength()}" label="{t(#Clean URL#)}" fieldOnly="true" disabled="{product.getAutogenerateCleanURL()}" />
    <widget class="XLite\View\FormField\Input\Checkbox\AutogenerateCleanURL" fieldName="{getNamePostedData(#autogenerateCleanURL#)}" value="{product.getAutogenerateCleanURL()}" label="{t(#Autogenerate Clean URL#)}" />
  </td>
</tr>

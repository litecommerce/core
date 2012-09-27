{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category clean URL
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="category.modify.list", weight="1000")
 *}

<tr IF="!isRoot()">
  <td>{t(#Clean URL#)}</td>
  <td class="star"></td>
  <td>
    <widget class="XLite\View\FormField\Input\Text\CleanURL" fieldName="{getNamePostedData(#cleanURL#)}" value="{category.getCleanURL()}" maxlength="{getCleanURLMaxLength()}" label="{t(#Clean URL#)}" fieldOnly="true" disabled="{category.getAutogenerateCleanURL()}" />
    <widget class="XLite\View\FormField\Input\Checkbox\AutogenerateCleanURL" fieldName="{getNamePostedData(#autogenerateCleanURL#)}" value="{category.getAutogenerateCleanURL()}" label="{t(#Autogenerate Clean URL#)}" />
  </td>
</tr>

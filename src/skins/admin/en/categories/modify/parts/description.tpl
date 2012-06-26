{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category description
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.21
 *
 * @ListChild (list="category.modify.list", weight="300")
 *}

<tr>
  <td>{t(#Description#)}</td>
  <td class="star"></td>
  <td>
    <widget class="\XLite\View\FormField\Textarea\Advanced" fieldName="{getNamePostedData(#description#)}" cols="50" rows="10" value="{category.getDescription()}" />
  </td>
</tr>

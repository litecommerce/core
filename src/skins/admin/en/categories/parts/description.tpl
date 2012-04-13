{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category description
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="category.modify.list", weight="30")
 *}

<tr>
  <td valign="top">{t(#Description#)}</td>
  <td>&nbsp;</td>
  <td><widget class="\XLite\View\FormField\Textarea\Advanced" fieldName="description" cols="50" rows="10" value="{category.description}" fieldOnly="true" /></td>
</tr>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product class new element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="productClasses.list.columns.new", weight="100")
 *}

<td class="new-product-class">
  <widget class="\XLite\View\FormField\Input\Text\Advanced" fieldOnly=true fieldName="{getNamePostedData(#new_name#)}" value="" label="New product class" />
</td>
<td>&nbsp;</td>

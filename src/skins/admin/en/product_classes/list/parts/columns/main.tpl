{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product classes list item
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product_classes.main_input", weight="100")
 *}

<td class="input-name">
  <widget class="\XLite\View\FormField\Input\Text\Advanced" fieldOnly=true fieldName="{getNamePostedData(#name#,id))}" value="{className}" />
</td>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product classes list item
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productClasses.list.columns", weight="100")
 *}
<td class="input-name">
<widget class="\XLite\View\FormField\Input\Text\Advanced" fieldName="{getNamePostedData(#name#,class.getId())}" value="{class.getName()}" />
</td>

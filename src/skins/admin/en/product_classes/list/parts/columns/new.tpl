{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product class new element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productClasses.list.columns.new", weight="100")
 *}
<td class="new-product-class">
<a href="javascript:void(0);" onclick="javascript: core.addNewProductClass(this);">
{t(#New product class#)}
</a>
<input type="text" name="{getNamePostedData(#new_name#)}" value="" />
</td>
<td>&nbsp;</td>

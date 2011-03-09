{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Remove button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productClasses.list.columns", weight="200")
 * @ListChild (list="productClasses.list.columns.remove", weight="100")
 *}
<td class="remove-product-class">
<a href="javascript:void(0);" onclick="javascript:removeProductClass(this,'{class.getId()}')"><img src="" alt="{t(#Remove#)}" /></a>
</td>

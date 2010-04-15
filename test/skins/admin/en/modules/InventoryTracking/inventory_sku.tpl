{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tr IF="!newInventory">
    <td valign=top>SKU</td>
    <td valign=top><input type="text" name="optdata[inventory_sku]" value="{ivt.inventory_sku}"></td>
</tr>

<tr IF="newInventory">
    <td colspan=2>SKU</td>
    <td><input type="text" name="inventory_sku" value="{product.sku}"></td>
</tr>

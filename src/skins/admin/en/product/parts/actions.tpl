{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element (actions). Actions start after 10000 weight
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="product.modify.list", weight="10010")
 *}
<tr>
<td>
  <widget class="\XLite\View\Button\Submit" label="Add" IF="isNew()" />
  <widget class="\XLite\View\Button\Submit" label="Update" IF="!isNew()" />
</td> 
<td>&nbsp;</td>
</tr>

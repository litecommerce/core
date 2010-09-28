{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item : thumbnail
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.item", weight="20")
 *}
<td class="item-thumbnail" IF="item.hasThumbnail()"><a href="{item.getURL()}"><widget class="\XLite\View\Image" image="{item.getThumbnail()}" alt="{item.getName()}" maxWidth="80" maxHeight="80" centerImage="0" /></a></td>
<td class="item-thumbnail" IF="!item.hasThumbnail()">&nbsp;</td>

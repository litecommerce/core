{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item body
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.product.list.customer.body", weight="40")
 *}

<td class="body">
  <div class="quick-look-cell">{displayListPart(#quick_look.info#)}</div>
  {displayListPart(#info#,_ARRAY_(#product#^product))}
</td>

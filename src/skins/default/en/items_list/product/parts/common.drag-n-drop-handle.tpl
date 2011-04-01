{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Overlapping box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="itemsList.product.grid.customer.info", weight="0")
 * @ListChild (list="itemsList.product.list.customer.info", weight="0")
 *}

<div class="drag-n-drop-handle">
  <span IF="!product.inventory.isOutOfStock()">{t(#Drag and drop me to the bag#)}</span>
  <span IF="product.inventory.isOutOfStock()">{t(#Product is out of stock#)}</span>
</div>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Main element (input)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.quantity-box", weight="20")
 *}

<input
  type="text"
  value="{getBoxValue()}"
  class="{getClass()} validate[required,custom[integer],min[{getMinQuantity()}]{if:product.inventory.enabled},max[{getMaxQuantity()}]{end:}]"
  id="{getBoxId()}"
  name="{getBoxName()}"
  title="{t(getBoxTitle())}" />

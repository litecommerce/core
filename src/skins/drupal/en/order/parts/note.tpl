{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order customer note
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="order.childs", weight="40")
 *}
<div IF="order.notes" class="customer-note">
  <strong>Customer note:</strong>
  <div>{order.notes}</div>
</div>

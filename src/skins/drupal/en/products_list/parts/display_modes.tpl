{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list display mode selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productsList.head", weight="10")
 *}
<div IF="isDisplayModeAdjustable()" class="display-modes">
  View as:
  <ul>
    <li FOREACH="displayModes,key,name" class="{getDisplayModeLinkClassName(key)}">
      <a href="{getActionUrl(_ARRAY_(#displayMode#^key))}" class="{key}">{name}</a>
    </li>
  </ul>
</div>

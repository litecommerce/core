{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Inline container
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *}

<div class="{getContainerClass()}">
  <div IF="hasSeparateView()" class="view"><widget template="{getViewTemplate()}" /></div>
  <div IF="isEditable()" class="field"><widget template="{getFieldTemplate()}" /></div>
</div>

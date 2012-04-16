{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Panel
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.18
 *}

<ul class="admin-panel">
  <li FOREACH="getItems(),item">
      <widget template="{item.template}" item="{item}" />
  </li>
</ul>
<div class="clear"></div>
<hr />

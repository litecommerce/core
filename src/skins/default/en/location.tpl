{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Breadcrumbs
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<ul class="breadcrumb">
  {foreach:getNodes(),index,node}
    <li IF="!#0#=index" class="location-node">
      <span class="separator">&raquo;</span>
    </li>
    {node.display()}
  {end:}
</ul>

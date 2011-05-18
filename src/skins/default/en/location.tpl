{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Breadcrumbs
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<ul class="breadcrumb">
  {foreach:getNodes(),index,node}
    <li IF="!#0#=index" class="location-node">
      <span class="separator">&raquo;</span>
    </li>
    {node.display()}
  {end:}
</ul>

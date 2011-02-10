{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common node
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="location-node{if:getSubnodes()} expandable{end:}">

  {if:getLink()}<a href="{getLink()}" class="location-title">{end:}{getName()}{if:getLink()}</a>{end:}

  <ul class="location-subnodes" IF="getSubnodes()">
    <li FOREACH="getSubnodes(),node"><a href="{node.getLink()}">{node.getName()}</a></li>
  </ul>

</div>

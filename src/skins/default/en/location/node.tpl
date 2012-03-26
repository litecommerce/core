{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common node
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<li class="location-node{if:getSubnodes()} expandable{end:}{if:isLast()} last{end:}">

  <a IF="getLink()" href="{getLink()}" class="location-title">{t(getName())}</a>
  <span IF="!getLink()" class="location-text">{t(getName())}</span>

  <ul class="location-subnodes" IF="getSubnodes()">
    <li FOREACH="getSubnodes(),node">
      <a href="{node.getLink()}" IF="!node.getName()=getName()">{t(node.getName())}</a>
      <a href="{node.getLink()}" IF="node.getName()=getName()" class="current">{t(node.getName())}</a>
    </li>
  </ul>

</li>

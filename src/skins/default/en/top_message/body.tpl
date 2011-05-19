{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Top messages
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div id="status-messages" {if:isHidden()} style="display: none;"{end:}>

  <a href="#" class="close" title="{t(#Close#)}"><img src="images/spacer.gif" alt="{t(#Close#)}" /></a>

  <ul>
    <li FOREACH="getTopMessages(),data" class="{getType(data)}">
      <em IF="getPrefix(data)">{getPrefix(data)} </em>{getText(data)}
    </li>
  </ul>

</div>

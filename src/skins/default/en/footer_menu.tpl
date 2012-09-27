{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Top menu
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul id="secondary-menu" class="clearfix">
  {foreach:getItems(),i,item}
    <li {displayItemClass(i):h}><a href="{item.url}" {if:item.active}class="active"{end:}>{item.label}</a></li>
  {end:}
</ul>

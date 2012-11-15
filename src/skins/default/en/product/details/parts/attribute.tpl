{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
{if:getAttributeGroup()}
<li><h3>{getTitle()}</h3>
  <ul>
{end:}
<li FOREACH="getAttrList(),a">
  <div><strong>{a.name}</strong></div>
  <span class="{a.class}">{a.value:nl2br}</span>
</li>
{if:getAttributeGroup()}
  </ul>
</li>
{end:}

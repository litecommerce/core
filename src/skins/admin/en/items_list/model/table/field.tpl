{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Common field output
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="plain-value">
  {if:column.link}
    <a href="{buildEntityURL(entity,column)}" class="link">{getColumnValue(column,entity)}</a>
  {else:}
    {getColumnValue(column,entity)}
  {end:}
  <img IF="column.noWrap" src="images/spacer.gif" class="right-fade" alt="" />
</div>

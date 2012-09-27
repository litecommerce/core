{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Membership selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<select name="{getParam(#field#)}">
  <option FOREACH="getZones(),zone" value="{zone.getZoneId()}" selected="{isSelectedZone(zone)}">{zone.getZoneName()}</option>
</select>

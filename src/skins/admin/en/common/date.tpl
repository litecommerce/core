{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Date selector template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<select name="{getField()}Month">
  <option FOREACH="getMonths(),k,v" value="{k}" selected="{v}">{getMonthString(k)}</option>
</select>

<select name="{getField()}Day">
  <option FOREACH="getDays(),k,v" value="{k}" selected="{v}">{k}</option>
</select>

<select name="{getField()}Year">
  <option FOREACH="getYears(),k,v" value="{k}" selected="{v}">{k}</option>
</select>

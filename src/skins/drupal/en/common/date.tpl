{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select date
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select id='{getField()}Month' name="{getField()}Month" IF="!hide_months" style="width: 120px;">
	<option FOREACH="getMonths(),k,v" value="{k}" selected="{v=#selected#}">{getMonthString(k)}</option>
</select>

<select id='{getField()}Day' name="{getField()}Day" IF="!hide_days" style="width: 120px;">
	<option FOREACH="getDays(),k,v" value="{k}" selected="{v=#selected#}">{k}</option>
</select>

<select id='{getField()}Year' name="{getField()}Year" IF="!hide_years" style="width: 80px;">
	<option FOREACH="getYears(),k,v" value="{k}" selected="{v=#selected#}">{k}</option>
</select>

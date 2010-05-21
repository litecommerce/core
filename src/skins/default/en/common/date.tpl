{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select id='{field}Month' name="{field}Month" IF="!hide_months">
	<option value="1" selected="{month=1}"><span IF="showMonthsNumbers">(01) </span>January</option>
	<option value="2" selected="{month=2}"><span IF="showMonthsNumbers">(02) </span>February</option>
	<option value="3" selected="{month=3}"><span IF="showMonthsNumbers">(03) </span>March</option>
	<option value="4" selected="{month=4}"><span IF="showMonthsNumbers">(04) </span>April</option>
	<option value="5" selected="{month=5}"><span IF="showMonthsNumbers">(05) </span>May</option>
	<option value="6" selected="{month=6}"><span IF="showMonthsNumbers">(06) </span>June</option>
	<option value="7" selected="{month=7}"><span IF="showMonthsNumbers">(07) </span>July</option>
	<option value="8" selected="{month=8}"><span IF="showMonthsNumbers">(08) </span>August</option>
	<option value="9" selected="{month=9}"><span IF="showMonthsNumbers">(09) </span>September</option>
	<option value="10" selected="{month=10}"><span IF="showMonthsNumbers">(10) </span>October</option>
	<option value="11" selected="{month=11}"><span IF="showMonthsNumbers">(11) </span>November</option>
	<option value="12" selected="{month=12}"><span IF="showMonthsNumbers">(12) </span>December</option>
</select>

<select id='{field}Day' name="{field}Day" IF="!hide_days">
	<option FOREACH="days,v" value="{v}" selected="{day=v}">{v}</option>
</select>

<select id='{field}Year' name="{field}Year" IF="!hide_years">
	<option FOREACH="years,v" value="{v}" selected="{year=v}">{v}</option>
</select>

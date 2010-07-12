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
<tr>
    <td nowrap>Search period:</td>
    <td>
        <widget template="modules/EcommerceReports/period_js.tpl">
        <select name="search_period" id="search_period" onChange="SetPeriod()">
            <option value="-1" selected="search_period=#-1#">Whole period</option>
            <option value="0" selected="search_period=#0#">Today</option>
            <option value="1" selected="search_period=#1#">Yesterday</option>
            <option value="2" selected="search_period=#2#">Current week</option>
            <option value="3" selected="search_period=#3#">Previous week</option>
            <option value="4" selected="search_period=#4#">Current month</option>
            <option value="5" selected="search_period=#5#">Previous month</option>
            <option value="6" selected="search_period=#6#">Custom period</option>
        </select>
    </td>
</tr>
<tbody id="custom_dates" style="display:none">
<tr>
    <td nowrap>Date from:</td>
    <td nowrap><widget class="\XLite\View\Date" template="modules/EcommerceReports/form_date.tpl" field="startDate" id_prefix="search_"></td>
</tr>
<tr>
    <td nowrap>Date through:</td>
    <td nowrap><widget class="\XLite\View\Date" template="modules/EcommerceReports/form_date.tpl" field="endDate" id_prefix="search_"></td>
</tr>
</tbody>
<widget template="modules/EcommerceReports/period_init_js.tpl">

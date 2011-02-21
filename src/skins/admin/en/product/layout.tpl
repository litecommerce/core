{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table>
<tr>
    <td colspan=2>
        {if:invalid_field_order}
            <span class="admin-head">Field order:&nbsp;</span>
            <span class="validate-error-message">&lt;&lt;&nbsp;Duplicate field: &quot;{invalid_field_name}&quot;</span></td>
        {else:}
            <span class="admin-head">Field order:</span></td>
        {end:}
</tr>
{*<tr FOREACH="xlite.factory.\XLite\Model\Product.importFields,id,fields">
    <td style="width:1;">{id}:</td>
    <td style="width:99%;">
        <select name="product_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{isOrderFieldSelected(id,field,value)}">{field}</option>
        </select>
    </td>
</tr>*}

{* FIXME: temporary *}
<tr><td colspan="2">--------- Not available right now ---------</td></tr>

<tr>
    <td colspan="2">
    <widget class="\XLite\View\Button\Regular" label="Save field order" jsCode="javascript: document.data_form.action.value='layout'; document.data_form.submit();" />
    </td>
</tr>
</table>

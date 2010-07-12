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
<tr FOREACH="partnerFields,field" valign="{valign(field.field_type)}">
    <td align="right">{field.name:h}</td>
    <td>{if:field.required}<font class=Star>*</font>{else:}&nbsp;{end:}</td>
    <td>
        <!-- text input -->
        <input IF="field.field_type=#Text#" type=text name="{formField}[{field.field_id}]" value="{value}" size="32"/>

        <!-- textarea -->    
        <textarea IF="field.field_type=#Textarea#" name="{formField}[{field.field_id}]" cols="{field.cols}" rows="{field.rows}">{value:r}</textarea>

        <!-- SelectBox -->
        <select IF="field.field_type=#SelectBox#" class=FixedSelect name="{formField}[{field.field_id}]">
            <option FOREACH="field.fieldOptions,fop" value="{fop:h}" selected="{value=fop}">{fop:h}</option>
        </select>
        
        <!-- Radio button -->
        <table IF="field.field_type=#Radio button#" border=0 cellpadding=0 cellspacing=0>
        <tr FOREACH="field.fieldOptions,fidx,fop">
            <td valign=top><input type=radio name="{formField}[{field.field_id}]" value="{fop:h}" checked="{value=fop}"></td>
            <td>{fop:h}</td>
        </tr>
        </table>

        <!-- checkbox -->
        <input IF="field.field_type=#CheckBox#&get" type=checkbox name="{formField}[{field.field_id}]" value="checked" checked="{!field.value=##}"/>
        <input IF="field.field_type=#CheckBox#&!get" type=checkbox name="{formField}[{field.field_id}]" value="checked" checked="{!value=##}"/>

    </td>
    <td>&nbsp;<widget class="\XLite\Module\Affiliate\View\PartnerFieldValidator" fields="{partnerFields}" field="{formField}" field_id="{field.field_id}"></td>
</tr>

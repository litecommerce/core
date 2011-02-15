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
This page allows to export product options into CSV file.<hr />

<p>
<form action="admin.php" method=post name=data_form>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="export_product_options" />

<table>
<tr>
    <td colspan=2><span class=admin-head>Field order:</span></td>
</tr>
<tr FOREACH="xlite.factory.\XLite\Module\CDev\ProductOptions\Model\ProductOption.getImportFields(#product_options_layout#),id,fields">
    <td style="width:1;">{id}:</td>
    <td style="width:99%;">
        <select name="product_options_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="value=id">{field}</option>
        </select>
    </td>
</tr>
</table>
<p>
Field delimiter:<br /><widget template="common/delimiter.tpl"><br />
<br />
<widget class="\XLite\View\Button\Submit" label=" Export " />

</form>

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
<table border=0>
<tr>
    <td colspan=2><font class=AdminHead>Field order:</font></td>
</tr>
<tr FOREACH="xlite.factory.\XLite\Model\ProductInventory.getImportFields(#inventory_layout#),id,fields">
    <td width=1>{id}:</td>
    <td width=99%>
        <select name="inventory_layout[{id}]">
            <option FOREACH="fields,field,value" value="{field}" selected="{value}">{field}</option>
        </select>
    </td>
</tr>
</table>

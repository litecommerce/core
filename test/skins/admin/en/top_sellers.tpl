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
<p align=justify>This section displays 10 top-selling products for today, this week and this month.</p>

<br>

<p>
<table border=0 cellpadding=0 cellspacing=0 width="80%">
<tr>
    <td class=AdminHead>Top 10 products</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td bgcolor=#dddddd>
    <table cellpadding=3 cellspacing=1 border=0 width="100%">
    <tr class=TableHead bgcolor=#ffffff>
        <th>&nbsp;</th>
        <th>Today</th>
        <th>This week</th>
        <th>This month</th>
    </tr>
    <tr foreach="counter,ind,cidx" class="{getRowClass(ind,#DialogBox#,#TableRow#)}">
        <td align=center>{inc(cidx)}.</td>
        <td><a href="admin.php?target=product&product_id={getTopProduct(#today#,cidx,#id#)}">{truncate(getTopProduct(#today#,cidx,#name#),50):h}</a></td>
        <td><a href="admin.php?target=product&product_id={getTopProduct(#week#,cidx,#id#)}">{truncate(getTopProduct(#week#,cidx,#name#),50):h}</a></td>
        <td><a href="admin.php?target=product&product_id={getTopProduct(#month#,cidx,#id#)}">{truncate(getTopProduct(#month#,cidx,#name#),50):h}</a></td>
    </tr>
    </table>
</td>
</tr>
</table>
</p>

<br>

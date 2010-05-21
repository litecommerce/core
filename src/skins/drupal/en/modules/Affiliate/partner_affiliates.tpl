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
<p align=justify>This section displays commission statistics for the tree of your affiliates. For ease of perception store partners who bring affiliate commissions to you are marked in <b>bold</b>.</p>

<br>

<table border=0 cellpadding=5 cellspacing=1>
<tr class=TableHead>
    <td>Level</td>
    <td>Partner</td>
    <td>Partner commissions</td>
    <td>Affiliate commissions</td>
    <td>Branch commissions</td>
</tr>
<tr>
    <td align=center>1</td>
    <td>{root.billing_firstname} {root.billing_lastname} &lt;{root.login:h}&gt;</td>
    <td align=right>{price_format(root.partnerCommissions):h}</td>
    <td align=right>{price_format(root.affiliateCommissions):h}</td>
    <td align=right>{price_format(root.branchCommissions):h}</td>
</tr>
<tr FOREACH="root.affiliates,aidx,a" class="{getRowClass(aidx,##,#TableRow#)}"> 
    <td align=center>{a.level}</td>
    <td nowrap valign=middle>
        <img src="images/spacer.gif" width="{mul(a.level,20)}" height=12 alt="">
        {if:a.relative}<b>{end:}
        &lt;affiliate&gt;
        {if:a.relative}</b>{end:}
    </td>
    <td align=right>{price_format(a.partnerCommissions):h}</td>
    <td align=right>{price_format(a.affiliateCommissions):h}</td>
    <td align=right>{price_format(a.branchCommissions):h}</td>
</tr>
</table>

<p align=justify><hr>In the above tree 'Partner commissions' is an amount earned by you or your affiliate on personally referred sales, 'Affiliate commissions' is an amount earned by you or your affiliates on the sales referred by your or their respective affiliates from level 2 to the maximum allowed number of partnership tiers, and 'Branch commissions' is the total amount of commissions earned by all affiliates of yours or your affiliates.</p>

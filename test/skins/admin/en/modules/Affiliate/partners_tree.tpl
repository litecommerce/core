<p align=justify>This section displays a tree of partner affiliates and their commissions. Select a partner from the list and click on 'Search'.</p>
<br>

<form action="admin.php" method=GET>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>

<table border=0 cellpadding=3 cellspacing=2>
<tr valign=top>
    <td>Partner:</td>
    <td><widget class="XLite_Module_Affiliate_View_PartnerSelect" allOption=0></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=Search></td>
</tr>
</table>

</form>

<br>

<table IF="search&partner_id&root.affiliates" border=0 cellpadding=5 cellspacing=1>
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
        <img src="images/modules/Affiliate/tree_end.gif" width="19" border="0" align=absmiddle>
        {if:a.relative}<b>{end:}
        {a.billing_firstname} {a.billing_lastname} &lt;{a.login:h}&gt;
        {if:a.relative}</b>{end:}
    </td>
    <td align=right>{price_format(a.partnerCommissions):h}</td>
    <td align=right>{price_format(a.affiliateCommissions):h}</td>
    <td align=right>{price_format(a.branchCommissions):h}</td>
</tr>
</table>

<span IF="search&partner_id&root.affiliates">
<br>
<p align="justify"><hr>In the above tree 'Partner commissions' is an amount earned by the partner on the sales referred by him, 'Affiliate commissions' is an amount earned by the partner on the sales referred by his affiliates from level 2 to the maximum number of partnership tiers (defined under Affiliate module general settings), and 'Branch commissions' is the total amount of commissions earned by all affiliates of the partner.<br>For ease of perception store partners who bring affiliate commissions to the primary partner are marked in <b>bold</b>.</p>
</span>

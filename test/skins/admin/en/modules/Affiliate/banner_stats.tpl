<p align=justify>This section displays banner system efficiency statistics. The statistics include clicks, banner exposures and clicks to exposures ratio.</p>

<br>

<form name=banner_stats_form action="admin.php" method=GET>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}" />
<table border=0 cellpadding=3>
<tr>
    <td>Period from:</td>
    <td><widget class="XLite_View_Date" field="startDate"></td>
</tr>    
<tr>
    <td>Period to:</td>
    <td><widget class="XLite_View_Date" field="endDate"></td>
</tr>    
<tr>
    <td valign=top>Partner:</td>
    <td><widget class="XLite_Module_Affiliate_View_PartnerSelect" ></td>
</tr>
<tr>
    <td>Sort by:</td>
    <td>
        <table border=0>
        <tr>
            <td><input type=radio name=sort_by value=views checked="sort_by=#views#"></td><td>Views</td><td>&nbsp;</td><td><input type=radio name=sort_by value=clicks checked="sort_by=#clicks#"></td><td>Clicks</td>
        </tr>    
        </table>
    </td>
</tr>
<tr>
    <td>Banner category:</td>
    <td>
        <table border=0>
        <tr>
            <td><input type=checkbox name=default_banner value="1" checked="default_banner=#1#"></td><td>Home page</td><td>&nbsp;&nbsp;<input type=checkbox name=product_banner value="1" checked="product_banner=#1#"></td><td>Product</td><td>&nbsp;&nbsp;<input type=checkbox name=direct_link value="1" checked="direct_link=#1#"></td><td>Direct link</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=Search></td>
<tr>
</table>
</form>

<br>
<table IF="stats" border=0 width="80%" cellpadding=5 cellspacing=1>
<tr class=TableHead>
    <td width="100%">Banner</td>
    <td>Views</td>
    <td>Clicks</td>
    <td nowrap>Click rate</td>
</tr>
<tr FOREACH="stats,key,stat" class="{getRowClass(key,#TableRow#,##)}">
    <td>
    {if:stat.product}
        {if:stat.product.deleted}
        &lt;&lt; Deleted product &gt;&gt;
        {else:}
        Product <a href="admin.php?target=product&product_id={stat.product.product_id}"><u>&quot;{stat.product.name:h}&quot;</u></a>
        {end:}
    {end:}
    {if:stat.banner}
        {if:stat.banner.deleted}
        &lt;&lt; Deleted banner &gt;&gt;
        {else:}
        Banner <a href="admin.php?target=banner&mode=modify&type={stat.banner.type}&banner_id={stat.banner.banner_id}"><u>&quot;{stat.banner.name:h}&quot;</u></a>
        {end:}
    {end:}
    {if:stat.direct_link}
        Direct link
    {end:}
    </td>
    <td align=right>{stat.views}</td>
    <td align=right>{stat.clicks}</td>
    <td align=right>{stat.rate}</td>
</tr>
<tr>
    <td align=right class=AdminHead>Total:</td>
    <td align=right>{statsTotal.views}</td>
    <td align=right>{statsTotal.clicks}</td>
    <td align=right>{statsTotal.rate}</td>
</tr>
</table>

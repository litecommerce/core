<p align=justify>This section displays statistics on sales for the specified period.</p> 

<br>

<a name=report_form></a>
<form name=ecommerce_report_form action="admin.php#graphic" method=POST>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>
<input type=hidden name=action value=get_data>

<table border=0 cellpadding=3>

<widget template="modules/EcommerceReports/period_form.tpl">

<tr>
    <td>Step:</td>
    <td>
        <select name=stat_step>
            <option value=day selected="stat_step=#day#">Day</option>
            <option value=week selected="stat_step=#week#">Week</option>
            <option value=month selected="stat_step=#month#">Month</option>
            <option value=quarter selected="stat_step=#quarter#">Quarter</option>
            <option value=year selected="stat_step=#year#">Year</option>
        </select>
    </td>
</tr>

<widget template="modules/EcommerceReports/categories_form.tpl">

<widget template="modules/EcommerceReports/product_form.tpl">

<tr>
    <td>Show:</td>
    <td>
    <table border=0 cellpadding=0>
    <tr>
        <td><input type=radio name=show value="" checked="show=##"></td><td> Sum</td>
        <td>&nbsp;&nbsp;<input type=radio name=show value=quantity checked="show=#quantity#"><td> Quantity</td>
        <td>&nbsp;&nbsp;<input type=radio name=show value=number checked="show=#number#"><td> Number of orders</td>
    </td>
    </table>
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
        <input type=submit name=search value=Search>
        &nbsp;
        <input type=submit name=export  value=" Export to MS Excel ">
        &nbsp;
        &nbsp;
        <input type=button name=clear value="Clear form" onclick="document.location='admin.php?target=sales_dynamics'">
    </td>
</tr>
</table>

</form>

<br>

<!-- flash movie -->
{if:search}

{if:rawItemsNumber}

<a name="graphic"></a>

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="550" height="400" id="graphic" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="skins/admin/en/modules/EcommerceReports/graphic.swf" />
<param name="quality" value="high" />
<embed src="skins/admin/en/modules/EcommerceReports/graphic.swf" quality="high" width="550" height="400" name="graphic" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>

<br><br><br>
<a href="#report_form"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> New search..</a>

{else:}
<b>No sales records found.</b>
{end:}

{end:}

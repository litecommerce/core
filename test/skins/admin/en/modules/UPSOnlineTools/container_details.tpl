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
<!-- [begin] -->
<HTML>
<HEAD>
    <title>LiteCommerce online store builder</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; {charset}">
    <meta name="ROBOTS" content="NOINDEX">
    <meta name="ROBOTS" content="NOFOLLOW">
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</HEAD>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" {if:mode=#print#}OnLoad="window.print();"{end:}>
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
<TR>
<TD valign=top>
    <TABLE border="0" width="100%" cellpadding="0" cellspacing="0" valign=top>
    <TR class="displayPageHeader" height="18">
        <TD align=left class="displayPageHeader" valign=middle width="50%">&nbsp;&nbsp;&nbsp;LiteCommerce</TD>
        <TD align=right class="displayPageHeader" valign=middle width="50%">Version: {config.Version.version}&nbsp;&nbsp;&nbsp;</TD>
    </TR>
    </TABLE>
</TD>
</TR>
<TR>
<TD height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<TR>
<TD class="displayPageHeader" height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<tr>
    <td>&nbsp;</td>
</tr>
</TABLE>

<!-- [main_view] -->
<table border=0 cellpadding=4 cellspacing=0 width="100%">
	<tr>
	<td>&nbsp;</td>
	<td width="100%">

{if:hasUPSValidContainers()}
<script type="text/javascript" language="JavaScript 1.2">
function openDescription()
{
	desc_href = document.getElementById('description_href');
	if (desc_href) {
		desc_href.style.display = 'none';
	}

	desc = document.getElementById('description');
	if (desc) {
		desc.style.display = '';
	}
}
</script>

<div id="description_href">
<a href="javascript: void(0);" OnClick="openDescription();"><u><b>See description notes...</b></u></a>
</div>
<div id="description" style="DISPLAY: none;">
<ul>
	<li>This section displays the position of the ordered items in one or more containers.</li>
	<li>The weight of each container is the total weight of all the included items.</li>
	<li>Each container consists of layers - at least one or more depending of the number and size of the included items.</li>
	<li>The items in a layer are color-coded for easy distinction and marked with corresponding ID numbers from the table at the top of the page. Use these numbers to find product information.</li>
</ul>
<hr>
</div>
<p>

<b>The position of the {countUPSOrderItems()} ordered item(s) for order #{order.order_id:h} in {countUPSContainers()} container(s):</b>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="CenterBorder">
<table cellspacing="1" cellpadding="2" border="0">
	<tr>
		<th class="TableHead"><b>Id</b></th>
		<th class="TableHead"><b>Name</b></th>
		<th class="TableHead"><b>Amount</b></th>
		<th align="middle" class="TableHead"><b>Width</b><br><i>(inches)</i></th>
		<th align="middle" class="TableHead"><b>Length</b><br><i>(inches)</i></th>
		<th align="middle" class="TableHead"><b>Height</b><br><i>(inches)</i></th>
		<th align="middle" class="TableHead"><b>Weight</b><br><i>({xlite.config.General.weight_unit}{if:!config.General.weight_unit=#lbs#} / lbs{end:})</i></th>
		<th class="TableHead"><b>Handle with care</b></th>
	</tr>
	<tr FOREACH="getUPSOrderItems(),k,v" class="{getRowClass(k,#DialogBox#,#TableRow#)}">
		<td>{v.global_id:h}</td>
		<td>{v.name:h}</td>
		<td>{v.amount:h}</td>
		<td>{v.ups_width:h}</td>
		<td>{v.ups_length:h}</td>
		<td>{v.ups_height:h}</td>
		<td>{v.weight:h}{if:!config.General.weight_unit=#lbs#} / {if:v.weight_lbs}{v.weight_lbs:h}{else:}???{end:}{end:}</td>
		<td align="middle">{if:v.ups_handle_care}Yes{else:}No{end:}</td>
	</tr>
</table>
		</td>
	</tr>
</table>

<p>
<hr>

{foreach:upscontainers,container_id,container}
<table border="0" cellpadding="3" cellspacing="0">

	{* display container *}
	<tr>
		<td rowspan="2" align="middle" valign="top">

{* Container details *}
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left"><b>Container: #{container.container_id:h}<b></td>
	</tr>
	<tr>
		<td align="left"><b>Type:</b> {getUPSContainerName(container.container_type):h}</td>
	</tr>
	<tr IF="container.additional_handling">
		<td align="left"><b>Additional handling</b></td>
	</tr>
	<tr>
		<td height="35">&nbsp;</td>
	</tr>
	<tr>
		<td align="middle">{displayContainer(container_id):h}</td>
	</tr>
</table>
{* /Container details *}

		</td>
		<td rowspan="2" width="40">&nbsp;</td>

{* Container info *}
		<td valign="top">
<table border="0" cellpadding="1" cellspacing="2">
	<tr>
		<td colspan="5"><b>Weight:</b> {container.weight:h} (lbs)</td>
	</tr>
	<tr>
		<td>Width: {container.width:h} (inches)</td>
		<td>&nbsp;&nbsp;</td>
		<td>Length: {container.length:h} (inches)</td>
		<td>&nbsp;&nbsp;</td>
		<td>Height: {container.height:h} (inches)</td>
	</tr>
	<tr>
		<td colspan="5">Declared value: {price_format(container.declared_value)}</td>
	</tr>
</table>
		</td>
{* /Container info *}

	</tr>

	<tr>
	{* display container levels *}
		<td>
{if:container.levels}
<table border=0 cellpadding="5" cellspacing="0">
    <tr FOREACH="split(container.levels,4),levels">
        <td FOREACH="levels,level_id,level">
        <table border=0 IF="level">
            <tr>
                <td align="right" width="50%">Layer:</td>
                <td>&nbsp;</td>
                <td align="left" width="50%">#{UPSInc1(level.level_id):h}<td>
            </tr>
            <tr>
                <td colspan=3 valign=top>{displayLevel(container_id,level):h}</td>
            </tr>
        </table>
		{if:!level}&nbsp;{end:}
        </td>
    </tr>
</table>
{end:}
		</td>
	</tr>
</table>

<hr>


<!--
<table border=0 cellpadding="3" cellspacing="0">
	<tr>
		<td align="right">Container:</td>
		<td>&nbsp;</td>
		<td align="left">#{container.container_id:h}</td>
	</tr>
	<tr>
		<td align="right">Type:</td>
		<td>&nbsp;</td>
		<td align="left">{getUPSContainerName(container.container_type):h}</td>
	</tr>
	<tr>
		<td align="right">Additional Handling:</td>
		<td>&nbsp;</td>
		<td align="left">{container.additional_handling:h}</td>
	</tr>
	<tr>
		<td align="right">Declared value:</td>
		<td>&nbsp;</td>
		<td align="left">{price_format(container.declared_value)}</td>
	</tr>
	<tr>
		<td align="right">Weight:</td>
		<td>&nbsp;</td>
		<td align="left">{container.weight:h} (lbs)</td>
	</tr>
	<tr id="container_details_href_{container_id}">
		<td></td>
		<td colspan="2"><a href="javascript: void(0);" OnClick="showContainerDetails('{container_id}');">details... <img src="images/item_open.gif" border=0></a></td>
	</tr>
<tbody id="container_details_{container_id}" style="DISPLAY: none;">
	<tr>
		<td align="right">Width:</td>
		<td>&nbsp;</td>
		<td align="left">{container.width:h} (inches)</td>
	</tr>
	<tr>
		<td align="right">Length:</td>
		<td>&nbsp;</td>
		<td align="left">{container.length:h} (inches)</td>
	</tr>
	<tr>
		<td align="right">Height:</td>
		<td>&nbsp;</td>
		<td align="left">{container.height:h} (inches)</td>
	</tr>
</tbody>
</table>

{if:container.levels}
<table border=0 cellpadding="5" cellspacing="0">
	<tr>
		<td FOREACH="container.levels,level_id,level">
		<table border=0>
			<tr>
				<td align="right" width="50%">Layer:</td>
				<td>&nbsp;</td>
				<td align="left" width="50%">#{level.level_id:h}<td>
			</tr>
			<tr>
				<td colspan=3 valign=top>{displayLevel(container_id,level_id):h}</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
{end:}
<hr>
<p>
-->
{end:}

{else:}
Containers not defined.
{end:}

	</td>
	<td>&nbsp;</td>
	</tr>
</table>
<!-- [/main_view] -->

<br>

</BODY>
</HTML>
<!-- [/end] -->

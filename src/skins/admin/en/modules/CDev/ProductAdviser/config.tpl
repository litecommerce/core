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
<script language="Javascript">
<!--

function UpdateSettings()
{
	document.options_form.submit();
}

-->
</script>

<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/ProductAdviser/module_logo.gif" border=0></td>
	<td>
    Use this page to configure the "ProductAdviser" module settings.<br>Complete the required fields below and press the "Update" button.
    <P><B>Note:</B> setting a numeric option to "0" (zero) turns the corresponding functionality off.
	</td>
</tr>
</table>

<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<tbody FOREACH="options,id,option">
<tbody IF="!option.orderby=#1000#">
<tr>
    <widget template="modules/CDev/{page}/config_section_header.tpl" id="{id}">
</tr>
<tr id="option_{option.name}">
    <TD width="50%">{option.comment:h}: </TD>
    <TD width="50%">
    <widget template="modules/CDev/{page}/settings.tpl" option="{option}">
    </TD>
</tr>
</tbody>
</tbody>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Update"></TD>
<TD>&nbsp;</TD></TR>
</table>

</form>

<script language="Javascript">
<!--

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function UpdateBuyNow(elm)
{
	visibleBox("option_rp_bulk_shopping", elm.checked);
}

-->
</script>

{if:!config.CDev.ProductAdviser.rp_template=#grid#}
<script language="Javascript">
<!--
visibleBox("option_rp_columns", false);
-->
</script>
{end:}

{if:!config.CDev.ProductAdviser.rp_template=#list#}
<script language="Javascript">
<!--
visibleBox("option_rp_show_descr", false);
-->
</script>
{end:}

{if:!config.CDev.ProductAdviser.pab_template=#grid#}
<script language="Javascript">
<!--
visibleBox("option_pab_columns", false);
-->
</script>
{end:}

{if:!config.CDev.ProductAdviser.pab_template=#list#}
<script language="Javascript">
<!--
visibleBox("option_pab_show_descr", false);
-->
</script>
{end:}

{if:!config.CDev.ProductAdviser.rp_show_buynow=#N#}
<script language="Javascript">
<!--
visibleBox("option_rp_bulk_shopping", false);
-->
</script>
{else:}
<script language="Javascript">
<!--
visibleBox("option_rp_bulk_shopping", true);
-->
</script>
{end:}

{if:!config.CDev.ProductAdviser.related_products_enabled=#N#}
<script language="Javascript">
<!--
visibleBox("option_rp_template", false);
visibleBox("option_rp_columns", false);
visibleBox("option_rp_show_descr", false);
visibleBox("option_rp_show_price", false);
visibleBox("option_rp_show_buynow", false);
visibleBox("option_rp_bulk_shopping", false);
-->
</script>
{end:}

{if:!config.CDev.ProductAdviser.products_also_buy_enabled=#N#}
<script language="Javascript">
<!--
visibleBox("option_pab_template", false);
visibleBox("option_pab_columns", false);
visibleBox("option_pab_show_descr", false);
visibleBox("option_pab_show_price", false);
visibleBox("option_pab_show_buynow", false);
-->
</script>
{end:}

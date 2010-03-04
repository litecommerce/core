<script language="Javascript" type="text/javascript">
<!--

function MarkRP2Add(elm,product_id)
{
	var Element = document.getElementById("rp_bulk_"+product_id);
    if (Element) {
    	Element.value = ((elm.checked) ? "1" : "0");
    }
}

function BulkAdd2Cart()
{
	document.bulk_rp_shopping_form.submit();
}

-->
</script>

<div IF="showBulkAddForm">
<form name="bulk_rp_shopping_form" action="{shopUrl(#cart.php#)}" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="rp_bulk">
<span FOREACH="pager.pageData,RP">
<input type="hidden" name="rp_bulk[{RP.product.product_id}]" id="rp_bulk_{RP.product.product_id}" value="0" IF="!RP.product.checkHasOptions()" />
</span>
</form>
</div>

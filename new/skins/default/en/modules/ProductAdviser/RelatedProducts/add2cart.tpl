<script language="Javascript" type="text/javascript">
<!--

function Add2Cart(product_id,with_po)
{
	if (with_po) {
		document.location = "cart.php?target=product&action=buynow&product_id="+product_id;
	} else {
    	document.add_to_cart.product_id.value = product_id;
    	document.add_to_cart.submit();
	}
}

-->
</script>

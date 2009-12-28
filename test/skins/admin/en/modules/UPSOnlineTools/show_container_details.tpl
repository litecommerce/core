{if:hasUPSValidContainers()}
{if:widget.style=#button#}
<input class="ProductDetailsTitle" type="button" value="Container details" onClick="window.open('admin.php?target=order&mode=container_details&order_id={order.order_id}')">
{else:}
<br>
<b><a href="admin.php?target=order&mode=container_details&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Show container details</a></b>
{end:}
{end:}

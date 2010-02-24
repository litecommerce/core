{* SVN $Id$ *}
<tr>
  <td>Quantity</td>
  <td>
<script type="text/javascript">
if (typeof(window.productQuantityData) == 'undefined') {
  var productQuantityData = {};
}

if (typeof(productQuantityData[{product.product_id}]) == 'undefined') {
  productQuantityData[{product.product_id}] = {};
}

productQuantityData[{product.product_id}].minAmount = 1;
productQuantityData[{product.product_id}].maxAmount = {product.inventory.amount}; 
</script>
    <a href="javascript:void(0);" id="product_quantity_{product.product_id}_lower" class="disabled" onclick="javascript: updateProductQuantity({product.product_id}, -1); return false;">[-]</a>
    <input id="product_quantity_{product.product_id}" size="3" name="amount" value="1" onchange="javascript: updateProductQuantity({product.product_id});" />
    <a href="javascript:void(0);" id="product_quantity_{product.product_id}_upper" onclick="javascript: updateProductQuantity({product.product_id}, 1); return false;">[+]</a>
    <span>{product.inventory.amount} item(s) available</span>
  </td>
</tr>

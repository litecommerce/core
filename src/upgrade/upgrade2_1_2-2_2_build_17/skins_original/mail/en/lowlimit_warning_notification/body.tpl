{* E-mail sent to admin when product quantity reaches low stock limit *}
<html>
<body>
<p>Warning message from {config.Company.company_name}

<p>
Product ID# {product.product_id}<br>
SKU: {product.sku:h}<br>
Product name: {product.name:h}<br>
<widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" IF="product.productOptions"/>
<br>
{amount} item(s) in stock<br>
<br>

<p>{signature:h}
</body>
</html>

{* E-mail sent to admin when pin code  quantity reaches low available limit *}
<html>
<body>
<p>Warning message from {config.Company.company_name}

<p>
Product ID# {product.product_id}<br>
SKU: {product.sku:h}<br>
Product name: {product.name:h}<br>

Free pin codes: {free_pins:h}<br>

<p>{signature:h}
</body>
</html>

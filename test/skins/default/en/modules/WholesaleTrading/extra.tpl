<widget template="modules/WholesaleTrading/purchase_limit.tpl" visible="{product.purchaseLimit.min|product.purchaseLimit.max}">
<widget template="modules/WholesaleTrading/wholesale_pricing.tpl" visible="{product.isPriceAvailable()&product.hasWholesalePricing()}">
<widget template="modules/WholesaleTrading/amount.tpl" visible="product.isPriceAvailable()">



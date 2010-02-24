{* SVN $Id$ *}
<widget template="modules/WholesaleTrading/purchase_limit.tpl" visible="{product.purchaseLimit.min|product.purchaseLimit.max}" IF="product.purchaseLimit" />
<widget template="modules/WholesaleTrading/wholesale_pricing.tpl" visible="{product.isPriceAvailable()&product.hasWholesalePricing()}">
<widget class="XLite_Module_WholesaleTrading_View_Amount">



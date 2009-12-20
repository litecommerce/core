{if:target=#SpecialOffers#}<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=SpecialOffers" class="NavigationPath">Special offers</a></span>{end:}
{if:target=#SpecialOffer#)}<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=SpecialOffers" class="NavigationPath">Special offers</a>&nbsp;::&nbsp;Special offer {if:mode=##}Type{else:}Details{end:}</span>{end:}
{if:target=#DiscountCoupons#)}<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=DiscountCoupons" class="NavigationPath">Discount coupons</a></span>{end:}
{if:target=#discount_coupon#)}
	<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=DiscountCoupons" class="NavigationPath">Discount coupons</a></span>
	<span class="NavigationPath">&nbsp;::&nbsp;Discount coupon {dc.coupon}</span>
{end:}

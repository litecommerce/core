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
{if:target=#SpecialOffers#}<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=SpecialOffers" class="NavigationPath">Special offers</a></span>{end:}
{if:target=#SpecialOffer#)}<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=SpecialOffers" class="NavigationPath">Special offers</a>&nbsp;::&nbsp;Special offer {if:mode=##}Type{else:}Details{end:}</span>{end:}
{if:target=#DiscountCoupons#)}<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=DiscountCoupons" class="NavigationPath">Discount coupons</a></span>{end:}
{if:target=#discount_coupon#)}
	<span class="NavigationPath">&nbsp;::&nbsp;<a href="admin.php?target=DiscountCoupons" class="NavigationPath">Discount coupons</a></span>
	<span class="NavigationPath">&nbsp;::&nbsp;Discount coupon {dc.coupon}</span>
{end:}

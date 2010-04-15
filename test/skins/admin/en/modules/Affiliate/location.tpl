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
<span IF="target=#partners#">&nbsp;::&nbsp;<a href="admin.php?target=partners" class="NavigationPath">Partners</a></span>
<span IF="target=#affiliate_plans#">&nbsp;::&nbsp;<a href="admin.php?target=affiliate_plans" class="NavigationPath">Affiliate plans</a></span>
<span IF="target=#plan_commissions#">&nbsp;::&nbsp;<a href="admin.php?target=affiliate_plans" class="NavigationPath">Affiliate plans</a>&nbsp;::&nbsp;<font class=NavigationPath>{affiliatePlan.title:h}</font></span>
<span IF="target=#partner_form#">&nbsp;::&nbsp;<a href="admin.php?target=partner_form" class="NavigationPath">Partner registration form</a></span>
<span IF="target=#decline_partner#">&nbsp;::&nbsp;<font class="NavigationPath">Confirm decline partner</font></span>
<span IF="target=#partner_payments#">&nbsp;::&nbsp;<a href="admin.php?target=partner_payments" class="NavigationPath">Partner payments</a></span>
<span IF="target=#partner_orders#">&nbsp;::&nbsp;<a href="admin.php?target=partner_orders" class="NavigationPath">Partner orders</a></span>
<span IF="target=#banners#">&nbsp;::&nbsp;<a href="admin.php?target=banners" class="NavigationPath">Banners</a></span>
<span IF="target=#banner#&mode=#add#">&nbsp;::&nbsp;<a href="admin.php?target=banners" class="NavigationPath">Banners</a>&nbsp;::&nbsp;<a href="admin.php?target=banner&type={type}&mode={mode}" class="NavigationPath">Add {type} banner</a></span>
<span IF="target=#banner#&mode=#modify#">&nbsp;::&nbsp;<a href="admin.php?target=banners" class="NavigationPath">Banners</a>&nbsp;::&nbsp;<a href="admin.php?target=banner&type={type}&mode={mode}&banner_id={banner.banner_id}" class="NavigationPath">Modify banner &quot;{banner.name:h}&quot;</a></span>
<span IF="target=#banner_stats#">&nbsp;::&nbsp;<a href="admin.php?target=banner_stats" class="NavigationPath">Banner statistics</a></span>
<span IF="target=#sales_stats#">&nbsp;::&nbsp;<a href="admin.php?target=sales_stats" class="NavigationPath">Referred sales</a></span>
<span IF="target=#top_performers#">&nbsp;::&nbsp;<a href="admin.php?target=top_performers" class="NavigationPath">Top performers</a></span>
<span IF="target=#partners_tree#">&nbsp;::&nbsp;<a href="admin.php?target=partners_tree" class="NavigationPath">Affiliate tree</a></span>

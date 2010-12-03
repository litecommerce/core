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
<widget target="partners" template="common/dialog.tpl" head="Partners" body="modules/CDev/Affiliate/partners.tpl">
<widget target="affiliate_plans" mode="" template="common/dialog.tpl" head="Affiliate plans" body="modules/CDev/Affiliate/affiliate_plans.tpl">
<widget target="affiliate_plans" mode="delete" template="common/dialog.tpl" head="Delete affiliate plan &quot;{affiliatePlan.title:h}&quot;" body="modules/CDev/Affiliate/confirm_delete_plan.tpl">
<widget target="plan_commissions" template="common/dialog.tpl" head="&quot;{affiliatePlan.title:h}&quot; plan" body="modules/CDev/Affiliate/plan_commissions.tpl">
<widget target="partner_form" template="common/dialog.tpl" head="Partner profile" body="modules/CDev/Affiliate/partner_form.tpl">
<widget target="decline_partner" template="common/dialog.tpl" head="Confirm decline partner" body="modules/CDev/Affiliate/decline_partner.tpl">
<widget target="partner_orders" template="common/dialog.tpl" head="Partner orders" body="modules/CDev/Affiliate/partner_orders.tpl">
<widget target="partner_payments" template="common/dialog.tpl" head="Partner payments" body="modules/CDev/Affiliate/partner_payments.tpl">
<widget target="banners" template="common/dialog.tpl" head="Banners" body="modules/CDev/Affiliate/banners.tpl">
<widget target="banner" mode="add" template="common/dialog.tpl" head="Add {type} banner" body="modules/CDev/Affiliate/banner.tpl">
<widget target="banner" mode="modify" template="common/dialog.tpl" head="{banner.name:h}" body="modules/CDev/Affiliate/banner.tpl">
<widget target="banner_stats" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget target="sales_stats" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget target="top_performers" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget target="partners_tree" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">

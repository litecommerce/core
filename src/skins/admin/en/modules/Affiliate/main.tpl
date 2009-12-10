<widget target="partners" template="common/dialog.tpl" head="Partners" body="modules/Affiliate/partners.tpl">
<widget target="affiliate_plans" mode="" template="common/dialog.tpl" head="Affiliate plans" body="modules/Affiliate/affiliate_plans.tpl">
<widget target="affiliate_plans" mode="delete" template="common/dialog.tpl" head="Delete affiliate plan &quot;{affiliatePlan.title:h}&quot;" body="modules/Affiliate/confirm_delete_plan.tpl">
<widget target="plan_commissions" template="common/dialog.tpl" head="&quot;{affiliatePlan.title:h}&quot; plan" body="modules/Affiliate/plan_commissions.tpl">
<widget target="partner_form" template="common/dialog.tpl" head="Partner profile" body="modules/Affiliate/partner_form.tpl">
<widget target="decline_partner" template="common/dialog.tpl" head="Confirm decline partner" body="modules/Affiliate/decline_partner.tpl">
<widget target="partner_orders" template="common/dialog.tpl" head="Partner orders" body="modules/Affiliate/partner_orders.tpl">
<widget target="partner_payments" template="common/dialog.tpl" head="Partner payments" body="modules/Affiliate/partner_payments.tpl">
<widget target="banners" template="common/dialog.tpl" head="Banners" body="modules/Affiliate/banners.tpl">
<widget target="banner" mode="add" template="common/dialog.tpl" head="Add {type} banner" body="modules/Affiliate/banner.tpl">
<widget target="banner" mode="modify" template="common/dialog.tpl" head="{banner.name:h}" body="modules/Affiliate/banner.tpl">
<widget target="banner_stats" class="CTabber" body="{pageTemplate}" switch="target">
<widget target="sales_stats" class="CTabber" body="{pageTemplate}" switch="target">
<widget target="top_performers" class="CTabber" body="{pageTemplate}" switch="target">
<widget target="partners_tree" class="CTabber" body="{pageTemplate}" switch="target">


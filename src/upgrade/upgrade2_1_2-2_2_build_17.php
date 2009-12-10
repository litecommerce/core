
<?php

// BEGIN
func_refresh_start();

echo "Upgrading ..<br>
";

// STEP 1: Patch skins {{{

// assume all new kernel/include/template/loader files were uploaded successfully

$skins = array($admin = "skins/admin/en", $default = "skins/default/en", $mail = "skins/mail/en");

foreach ($skins as $skin) {
    echo "Patching skin $skin ..<br>
";
    patchSkin($skin);
}    
// }}}

// STEP 2: Copy new & admin skins {{{
$this->createDir("skins/admin/en/db");
$this->createDir("skins/admin/en/help");
$this->createDir("skins/admin/en/images/menu");
$this->createDir("skins/admin/en/js");
$this->createDir("skins/admin/en/images/sidebar_boxes");
$this->copyFile("skins_original/admin/index.tpl", "skins/admin/index.tpl");
$this->copyFile("skins_original/admin/en/access_denied.tpl", "skins/admin/en/access_denied.tpl");
$this->copyFile("skins_original/admin/en/card_types.tpl", "skins/admin/en/card_types.tpl");
$this->copyFile("skins_original/admin/en/change_skin.tpl", "skins/admin/en/change_skin.tpl");
$this->copyFile("skins_original/admin/en/countries.tpl", "skins/admin/en/countries.tpl");
$this->copyFile("skins_original/admin/en/general_settings.tpl", "skins/admin/en/general_settings.tpl");
$this->copyFile("skins_original/admin/en/image_files.tpl", "skins/admin/en/image_files.tpl");
$this->copyFile("skins_original/admin/en/import_users.tpl", "skins/admin/en/import_users.tpl");
$this->copyFile("skins_original/admin/en/license.tpl", "skins/admin/en/license.tpl");
$this->copyFile("skins_original/admin/en/location.tpl", "skins/admin/en/location.tpl");
$this->copyFile("skins_original/admin/en/login.tpl", "skins/admin/en/login.tpl");
$this->copyFile("skins_original/admin/en/main.tpl", "skins/admin/en/main.tpl");
$this->copyFile("skins_original/admin/en/memberships.tpl", "skins/admin/en/memberships.tpl");
$this->copyFile("skins_original/admin/en/menu.tpl", "skins/admin/en/menu.tpl");
$this->copyFile("skins_original/admin/en/menu_item.tpl", "skins/admin/en/menu_item.tpl");
$this->copyFile("skins_original/admin/en/modules.tpl", "skins/admin/en/modules.tpl");
$this->copyFile("skins_original/admin/en/modules_body.tpl", "skins/admin/en/modules_body.tpl");
$this->copyFile("skins_original/admin/en/orders_stats.tpl", "skins/admin/en/orders_stats.tpl");
$this->copyFile("skins_original/admin/en/product_popup.tpl", "skins/admin/en/product_popup.tpl");
$this->copyFile("skins_original/admin/en/recent_login.tpl", "skins/admin/en/recent_login.tpl");
$this->copyFile("skins_original/admin/en/searchStat.tpl", "skins/admin/en/searchStat.tpl");
$this->copyFile("skins_original/admin/en/states.tpl", "skins/admin/en/states.tpl");
$this->copyFile("skins_original/admin/en/style.css", "skins/admin/en/style.css");
$this->copyFile("skins_original/admin/en/summary.tpl", "skins/admin/en/summary.tpl");
$this->copyFile("skins_original/admin/en/top_sellers.tpl", "skins/admin/en/top_sellers.tpl");
$this->copyFile("skins_original/admin/en/welcome.tpl", "skins/admin/en/welcome.tpl");
$this->copyFile("skins_original/admin/en/wysiwyg.tpl", "skins/admin/en/wysiwyg.tpl");
$this->copyFile("skins_original/admin/en/authentication/image.tpl", "skins/admin/en/authentication/image.tpl");
$this->copyFile("skins_original/admin/en/catalog/body.tpl", "skins/admin/en/catalog/body.tpl");
$this->copyFile("skins_original/admin/en/catalog/image.tpl", "skins/admin/en/catalog/image.tpl");
$this->copyFile("skins_original/admin/en/categories/add_modify_body.tpl", "skins/admin/en/categories/add_modify_body.tpl");
$this->copyFile("skins_original/admin/en/categories/body.tpl", "skins/admin/en/categories/body.tpl");
$this->copyFile("skins_original/admin/en/categories/category_extra_fields.tpl", "skins/admin/en/categories/category_extra_fields.tpl");
$this->copyFile("skins_original/admin/en/categories/delete.tpl", "skins/admin/en/categories/delete.tpl");
$this->copyFile("skins_original/admin/en/categories/delete_all.tpl", "skins/admin/en/categories/delete_all.tpl");
$this->copyFile("skins_original/admin/en/common/delimiter.tpl", "skins/admin/en/common/delimiter.tpl");
$this->copyFile("skins_original/admin/en/common/invoice.tpl", "skins/admin/en/common/invoice.tpl");
$this->copyFile("skins_original/admin/en/common/membership_validator.tpl", "skins/admin/en/common/membership_validator.tpl");
$this->copyFile("skins_original/admin/en/common/popup_product_list.tpl", "skins/admin/en/common/popup_product_list.tpl");
$this->copyFile("skins_original/admin/en/common/qualifier.tpl", "skins/admin/en/common/qualifier.tpl");
$this->copyFile("skins_original/admin/en/common/select_category.tpl", "skins/admin/en/common/select_category.tpl");
$this->copyFile("skins_original/admin/en/common/select_country.tpl", "skins/admin/en/common/select_country.tpl");
$this->copyFile("skins_original/admin/en/common/select_membership.tpl", "skins/admin/en/common/select_membership.tpl");
$this->copyFile("skins_original/admin/en/common/select_state.tpl", "skins/admin/en/common/select_state.tpl");
$this->copyFile("skins_original/admin/en/common/shipping_method_validator.tpl", "skins/admin/en/common/shipping_method_validator.tpl");
$this->copyFile("skins_original/admin/en/common/sidebar_box.tpl", "skins/admin/en/common/sidebar_box.tpl");
$this->copyFile("skins_original/admin/en/common/tabber.tpl", "skins/admin/en/common/tabber.tpl");
$this->copyFile("skins_original/admin/en/common/uploaded_file_validator.tpl", "skins/admin/en/common/uploaded_file_validator.tpl");
$this->copyFile("skins_original/admin/en/css_editor/edit_style.tpl", "skins/admin/en/css_editor/edit_style.tpl");
$this->copyFile("skins_original/admin/en/css_editor/style_list.tpl", "skins/admin/en/css_editor/style_list.tpl");
$this->copyFile("skins_original/admin/en/db/backup.tpl", "skins/admin/en/db/backup.tpl");
$this->copyFile("skins_original/admin/en/db/restore.tpl", "skins/admin/en/db/restore.tpl");
$this->copyFile("skins_original/admin/en/help/body.tpl", "skins/admin/en/help/body.tpl");
$this->copyFile("skins_original/admin/en/help/head.tpl", "skins/admin/en/help/head.tpl");
$this->copyFile("skins_original/admin/en/help/image.tpl", "skins/admin/en/help/image.tpl");
$this->copyFile("skins_original/admin/en/image_editor/edit.tpl", "skins/admin/en/image_editor/edit.tpl");
$this->copyFile("skins_original/admin/en/images/close_sidebar_box.gif", "skins/admin/en/images/close_sidebar_box.gif");
$this->copyFile("skins_original/admin/en/images/dialog_bg1.gif", "skins/admin/en/images/dialog_bg1.gif");
$this->copyFile("skins_original/admin/en/images/head_line.gif", "skins/admin/en/images/head_line.gif");
$this->copyFile("skins_original/admin/en/images/item_close.gif", "skins/admin/en/images/item_close.gif");
$this->copyFile("skins_original/admin/en/images/item_open.gif", "skins/admin/en/images/item_open.gif");
$this->copyFile("skins_original/admin/en/images/keys.gif", "skins/admin/en/images/keys.gif");
$this->copyFile("skins_original/admin/en/images/logo_aspe.gif", "skins/admin/en/images/logo_aspe.gif");
$this->copyFile("skins_original/admin/en/images/logo_aspe_cc.gif", "skins/admin/en/images/logo_aspe_cc.gif");
$this->copyFile("skins_original/admin/en/images/no_image.gif", "skins/admin/en/images/no_image.gif");
$this->copyFile("skins_original/admin/en/images/open_sidebar_box.gif", "skins/admin/en/images/open_sidebar_box.gif");
$this->copyFile("skins_original/admin/en/images/zebra.gif", "skins/admin/en/images/zebra.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_catalog.gif", "skins/admin/en/images/menu/icon_catalog.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_categories.gif", "skins/admin/en/images/menu/icon_categories.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_design.gif", "skins/admin/en/images/menu/icon_design.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_general.gif", "skins/admin/en/images/menu/icon_general.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_modules.gif", "skins/admin/en/images/menu/icon_modules.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_orders.gif", "skins/admin/en/images/menu/icon_orders.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_products.gif", "skins/admin/en/images/menu/icon_products.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_quick_start.gif", "skins/admin/en/images/menu/icon_quick_start.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_statistics.gif", "skins/admin/en/images/menu/icon_statistics.gif");
$this->copyFile("skins_original/admin/en/images/menu/icon_users.gif", "skins/admin/en/images/menu/icon_users.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/authentication.gif", "skins/admin/en/images/sidebar_boxes/authentication.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/catalog.gif", "skins/admin/en/images/sidebar_boxes/catalog.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/help.gif", "skins/admin/en/images/sidebar_boxes/help.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/look_feel.gif", "skins/admin/en/images/sidebar_boxes/look_feel.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/maintenance.gif", "skins/admin/en/images/sidebar_boxes/maintenance.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/management.gif", "skins/admin/en/images/sidebar_boxes/management.gif");
$this->copyFile("skins_original/admin/en/images/sidebar_boxes/settings.gif", "skins/admin/en/images/sidebar_boxes/settings.gif");
$this->copyFile("skins_original/admin/en/js/README", "skins/admin/en/js/README");
$this->copyFile("skins_original/admin/en/js/billing_shipping.js", "skins/admin/en/js/billing_shipping.js");
$this->copyFile("skins_original/admin/en/js/select_states_begin.js", "skins/admin/en/js/select_states_begin.js");
$this->copyFile("skins_original/admin/en/js/select_states_begin_js.tpl", "skins/admin/en/js/select_states_begin_js.tpl");
$this->copyFile("skins_original/admin/en/js/select_states_end.js", "skins/admin/en/js/select_states_end.js");
$this->copyFile("skins_original/admin/en/js/select_states_end_js.tpl", "skins/admin/en/js/select_states_end_js.tpl");
$this->copyFile("skins_original/admin/en/look_feel/body.tpl", "skins/admin/en/look_feel/body.tpl");
$this->copyFile("skins_original/admin/en/look_feel/image.tpl", "skins/admin/en/look_feel/image.tpl");
$this->copyFile("skins_original/admin/en/maintenance/image.tpl", "skins/admin/en/maintenance/image.tpl");
$this->copyFile("skins_original/admin/en/management/body.tpl", "skins/admin/en/management/body.tpl");
$this->copyFile("skins_original/admin/en/management/image.tpl", "skins/admin/en/management/image.tpl");
$this->copyFile("skins_original/admin/en/order/export_xls.tpl", "skins/admin/en/order/export_xls.tpl");
$this->copyFile("skins_original/admin/en/order/list.tpl", "skins/admin/en/order/list.tpl");
$this->copyFile("skins_original/admin/en/order/recent_orders.tpl", "skins/admin/en/order/recent_orders.tpl");
$this->copyFile("skins_original/admin/en/order/search.tpl", "skins/admin/en/order/search.tpl");
$this->copyFile("skins_original/admin/en/order/search_form.tpl", "skins/admin/en/order/search_form.tpl");
$this->copyFile("skins_original/admin/en/payment_methods/body.tpl", "skins/admin/en/payment_methods/body.tpl");
$this->copyFile("skins_original/admin/en/product/add.tpl", "skins/admin/en/product/add.tpl");
$this->copyFile("skins_original/admin/en/product/add_notification.tpl", "skins/admin/en/product/add_notification.tpl");
$this->copyFile("skins_original/admin/en/product/export.tpl", "skins/admin/en/product/export.tpl");
$this->copyFile("skins_original/admin/en/product/export_fields.tpl", "skins/admin/en/product/export_fields.tpl");
$this->copyFile("skins_original/admin/en/product/extra_fields.tpl", "skins/admin/en/product/extra_fields.tpl");
$this->copyFile("skins_original/admin/en/product/extra_fields_form.tpl", "skins/admin/en/product/extra_fields_form.tpl");
$this->copyFile("skins_original/admin/en/product/fields_layout.tpl", "skins/admin/en/product/fields_layout.tpl");
$this->copyFile("skins_original/admin/en/product/import.tpl", "skins/admin/en/product/import.tpl");
$this->copyFile("skins_original/admin/en/product/import_fields.tpl", "skins/admin/en/product/import_fields.tpl");
$this->copyFile("skins_original/admin/en/product/info.tpl", "skins/admin/en/product/info.tpl");
$this->copyFile("skins_original/admin/en/product/inventory_layout.tpl", "skins/admin/en/product/inventory_layout.tpl");
$this->copyFile("skins_original/admin/en/product/layout.tpl", "skins/admin/en/product/layout.tpl");
$this->copyFile("skins_original/admin/en/product/links.tpl", "skins/admin/en/product/links.tpl");
$this->copyFile("skins_original/admin/en/product/product_list.tpl", "skins/admin/en/product/product_list.tpl");
$this->copyFile("skins_original/admin/en/product/product_list_form.tpl", "skins/admin/en/product/product_list_form.tpl");
$this->copyFile("skins_original/admin/en/product/search.tpl", "skins/admin/en/product/search.tpl");
$this->copyFile("skins_original/admin/en/product/update_inventory.tpl", "skins/admin/en/product/update_inventory.tpl");
$this->copyFile("skins_original/admin/en/profile/body.tpl", "skins/admin/en/profile/body.tpl");
$this->copyFile("skins_original/admin/en/settings/body.tpl", "skins/admin/en/settings/body.tpl");
$this->copyFile("skins_original/admin/en/settings/image.tpl", "skins/admin/en/settings/image.tpl");
$this->copyFile("skins_original/admin/en/shipping/charges.tpl", "skins/admin/en/shipping/charges.tpl");
$this->copyFile("skins_original/admin/en/shipping/charges_form.tpl", "skins/admin/en/shipping/charges_form.tpl");
$this->copyFile("skins_original/admin/en/shipping/methods.tpl", "skins/admin/en/shipping/methods.tpl");
$this->copyFile("skins_original/admin/en/shipping/zones.tpl", "skins/admin/en/shipping/zones.tpl");
$this->copyFile("skins_original/admin/en/tax/add.tpl", "skins/admin/en/tax/add.tpl");
$this->copyFile("skins_original/admin/en/tax/calculator.tpl", "skins/admin/en/tax/calculator.tpl");
$this->copyFile("skins_original/admin/en/tax/options.tpl", "skins/admin/en/tax/options.tpl");
$this->copyFile("skins_original/admin/en/tax/rates.tpl", "skins/admin/en/tax/rates.tpl");
$this->copyFile("skins_original/admin/en/tax/schemas.tpl", "skins/admin/en/tax/schemas.tpl");
$this->copyFile("skins_original/admin/en/template_editor/advanced_edit.tpl", "skins/admin/en/template_editor/advanced_edit.tpl");
$this->copyFile("skins_original/admin/en/template_editor/advanced_form.tpl", "skins/admin/en/template_editor/advanced_form.tpl");
$this->copyFile("skins_original/admin/en/template_editor/basic.tpl", "skins/admin/en/template_editor/basic.tpl");
$this->copyFile("skins_original/admin/en/template_editor/extra_page.tpl", "skins/admin/en/template_editor/extra_page.tpl");
$this->copyFile("skins_original/admin/en/template_editor/extra_page_remove.tpl", "skins/admin/en/template_editor/extra_page_remove.tpl");
$this->copyFile("skins_original/admin/en/template_editor/extra_pages_list.tpl", "skins/admin/en/template_editor/extra_pages_list.tpl");
$this->copyFile("skins_original/admin/en/template_editor/mail_edit.tpl", "skins/admin/en/template_editor/mail_edit.tpl");
$this->copyFile("skins_original/admin/en/template_editor/mail_list.tpl", "skins/admin/en/template_editor/mail_list.tpl");
$this->copyFile("skins_original/admin/en/users/search.tpl", "skins/admin/en/users/search.tpl");
$this->copyFile("skins_original/admin/en/users/search_form.tpl", "skins/admin/en/users/search_form.tpl");
$this->copyFile("skins_original/admin/en/users/search_results.tpl", "skins/admin/en/users/search_results.tpl");
$this->copyFile("skins_original/admin/images/Newsletters_menu_news_body.gif", "skins/admin/images/Newsletters_menu_news_body.gif");
$this->copyFile("skins_original/admin/images/account.gif", "skins/admin/images/account.gif");
$this->copyFile("skins_original/admin/images/authentication.gif", "skins/admin/images/authentication.gif");
$this->copyFile("skins_original/admin/images/buy_now.gif", "skins/admin/images/buy_now.gif");
$this->copyFile("skins_original/admin/images/category_empty.gif", "skins/admin/images/category_empty.gif");
$this->copyFile("skins_original/admin/images/checkout_credit_card.gif", "skins/admin/images/checkout_credit_card.gif");
$this->copyFile("skins_original/admin/images/checkout_echeck.gif", "skins/admin/images/checkout_echeck.gif");
$this->copyFile("skins_original/admin/images/checkout_offline.gif", "skins/admin/images/checkout_offline.gif");
$this->copyFile("skins_original/admin/images/common_button.gif", "skins/admin/images/common_button.gif");
$this->copyFile("skins_original/admin/images/common_button2.gif", "skins/admin/images/common_button2.gif");
$this->copyFile("skins_original/admin/images/common_date.gif", "skins/admin/images/common_date.gif");
$this->copyFile("skins_original/admin/images/common_select_membership.gif", "skins/admin/images/common_select_membership.gif");
$this->copyFile("skins_original/admin/images/common_submit.gif", "skins/admin/images/common_submit.gif");
$this->copyFile("skins_original/admin/images/extra_fields.gif", "skins/admin/images/extra_fields.gif");
$this->copyFile("skins_original/admin/images/js_select_states_begin_js.gif", "skins/admin/images/js_select_states_begin_js.gif");
$this->copyFile("skins_original/admin/images/js_select_states_end_js.gif", "skins/admin/images/js_select_states_end_js.gif");
$this->copyFile("skins_original/admin/images/modules_AdvancedSearch_advanced_search.gif", "skins/admin/images/modules_AdvancedSearch_advanced_search.gif");
$this->copyFile("skins_original/admin/images/modules_AdvancedSearch_select_category.gif", "skins/admin/images/modules_AdvancedSearch_select_category.gif");
$this->copyFile("skins_original/admin/images/modules_Affiliate_menu_body.gif", "skins/admin/images/modules_Affiliate_menu_body.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_main.gif", "skins/admin/images/modules_Egoods_main.gif");
$this->copyFile("skins_original/admin/images/modules_FeaturedProducts_featuredProducts_icons.gif", "skins/admin/images/modules_FeaturedProducts_featuredProducts_icons.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_add.gif", "skins/admin/images/modules_GiftCertificates_add.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_add_gift_certificate.gif", "skins/admin/images/modules_GiftCertificates_add_gift_certificate.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_checkout.gif", "skins/admin/images/modules_GiftCertificates_checkout.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_gift_certificate_info.gif", "skins/admin/images/modules_GiftCertificates_gift_certificate_info.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_item.gif", "skins/admin/images/modules_GiftCertificates_item.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_select_ecard.gif", "skins/admin/images/modules_GiftCertificates_select_ecard.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_totals.gif", "skins/admin/images/modules_GiftCertificates_totals.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_all_new.gif", "skins/admin/images/modules_Newsletters_all_new.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_bulletin.gif", "skins/admin/images/modules_Newsletters_bulletin.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_confirm_message.gif", "skins/admin/images/modules_Newsletters_confirm_message.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_failed.gif", "skins/admin/images/modules_Newsletters_failed.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_news_subscribe.gif", "skins/admin/images/modules_Newsletters_news_subscribe.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_newsletters.gif", "skins/admin/images/modules_Newsletters_newsletters.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_subscribe_confirmed.gif", "skins/admin/images/modules_Newsletters_subscribe_confirmed.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_subscription_form.gif", "skins/admin/images/modules_Newsletters_subscription_form.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_unsubscribe_confirmed.gif", "skins/admin/images/modules_Newsletters_unsubscribe_confirmed.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_unsubscription_failed.gif", "skins/admin/images/modules_Newsletters_unsubscription_failed.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_view_news.gif", "skins/admin/images/modules_Newsletters_view_news.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_OutOfStock_notify_form.gif", "skins/admin/images/modules_ProductAdviser_OutOfStock_notify_form.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_PriceNotification_category_button.gif", "skins/admin/images/modules_ProductAdviser_PriceNotification_category_button.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_PriceNotification_notify_form.gif", "skins/admin/images/modules_ProductAdviser_PriceNotification_notify_form.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_PriceNotification_product_button.gif", "skins/admin/images/modules_ProductAdviser_PriceNotification_product_button.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_ProductsAlsoBuy_icons.gif", "skins/admin/images/modules_ProductAdviser_ProductsAlsoBuy_icons.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_ProductsAlsoBuy_list.gif", "skins/admin/images/modules_ProductAdviser_ProductsAlsoBuy_list.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_ProductsAlsoBuy_table.gif", "skins/admin/images/modules_ProductAdviser_ProductsAlsoBuy_table.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_RecentlyViewed_body.gif", "skins/admin/images/modules_ProductAdviser_RecentlyViewed_body.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_RelatedProducts_bulk_products.gif", "skins/admin/images/modules_ProductAdviser_RelatedProducts_bulk_products.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_RelatedProducts_icons.gif", "skins/admin/images/modules_ProductAdviser_RelatedProducts_icons.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_RelatedProducts_list.gif", "skins/admin/images/modules_ProductAdviser_RelatedProducts_list.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_RelatedProducts_table.gif", "skins/admin/images/modules_ProductAdviser_RelatedProducts_table.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_main.gif", "skins/admin/images/modules_ProductAdviser_main.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_new_arrivals.gif", "skins/admin/images/modules_ProductAdviser_new_arrivals.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_notify_me.gif", "skins/admin/images/modules_ProductAdviser_notify_me.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_recently_viewed.gif", "skins/admin/images/modules_ProductAdviser_recently_viewed.gif");
$this->copyFile("skins_original/admin/images/modules_ProductOptions_options_validation_js.gif", "skins/admin/images/modules_ProductOptions_options_validation_js.gif");
$this->copyFile("skins_original/admin/images/modules_ProductOptions_selected_options_js.gif", "skins/admin/images/modules_ProductOptions_selected_options_js.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_bonus_list.gif", "skins/admin/images/modules_Promotion_bonus_list.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_coupon_failed.gif", "skins/admin/images/modules_Promotion_coupon_failed.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_discount_coupon.gif", "skins/admin/images/modules_Promotion_discount_coupon.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_add_error.gif", "skins/admin/images/modules_WholesaleTrading_add_error.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_amount.gif", "skins/admin/images/modules_WholesaleTrading_amount.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_extra.gif", "skins/admin/images/modules_WholesaleTrading_extra.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_membership_register.gif", "skins/admin/images/modules_WholesaleTrading_membership_register.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_purchase_limit.gif", "skins/admin/images/modules_WholesaleTrading_purchase_limit.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_totals.gif", "skins/admin/images/modules_WholesaleTrading_totals.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_update_error.gif", "skins/admin/images/modules_WholesaleTrading_update_error.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_wholesale_pricing.gif", "skins/admin/images/modules_WholesaleTrading_wholesale_pricing.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_add.gif", "skins/admin/images/modules_WishList_add.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_common_button.gif", "skins/admin/images/modules_WishList_common_button.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_item.gif", "skins/admin/images/modules_WishList_item.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_message.gif", "skins/admin/images/modules_WishList_message.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_send_to_friend.gif", "skins/admin/images/modules_WishList_send_to_friend.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_send_wishlist.gif", "skins/admin/images/modules_WishList_send_wishlist.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_wishlist.gif", "skins/admin/images/modules_WishList_wishlist.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_wishlist_note.gif", "skins/admin/images/modules_WishList_wishlist_note.gif");
$this->copyFile("skins_original/admin/images/order_order.gif", "skins/admin/images/order_order.gif");
$this->copyFile("skins_original/admin/images/pages_links.gif", "skins/admin/images/pages_links.gif");
$this->copyFile("skins_original/default/en/images/lite_box.gif", "skins/default/en/images/lite_box.gif");
$this->copyFile("skins_original/default/en/checkout/no_payment.tpl", "skins/default/en/checkout/no_payment.tpl");
$this->copyFile("skins_original/default/en/js/billing_shipping.js", "skins/default/en/js/billing_shipping.js");
$this->copyFile("skins_original/default/en/js/select_states_begin.js", "skins/default/en/js/select_states_begin.js");
$this->copyFile("skins_original/default/en/js/select_states_begin_js.tpl", "skins/default/en/js/select_states_begin_js.tpl");
$this->copyFile("skins_original/default/en/js/select_states_end.js", "skins/default/en/js/select_states_end.js");
$this->copyFile("skins_original/default/en/js/select_states_end_js.tpl", "skins/default/en/js/select_states_end_js.tpl");
// }}}

// STEP 3: Patch etc/config.php config file {{{
//patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade2_1_2-2_2_build_17.sql", $this->db->connection, true);
// }}}

// END
func_refresh_end();

// FUNCTIONS {{{

function patchSkin($skin)
{
    if ($handle = opendir($skin)) {
        while (false !== ($file = readdir($handle))) {
            if ($file{0} != ".") {
                $path = $skin . '/' . $file;
                if (is_dir($path)) {
                    patchSkin($path);
                } elseif (is_file($path) && substr($file, -4) != ".bak" && substr($file, -4) != ".gif") {
                    patchFile($path);
                }
            }
        }
    }
    closedir($handle);
}

function patchFile($template, $backup = true)
{
    echo "<b>Patching file $template</b><br>
";
    $path = "upgrade/upgrade2_1_2-2_2_build_17/";
    $source = file_get_contents($template);
    $commonPatch = $path . "common.php";
    // backup original file
    if ($backup && !file_exists($template . ".bak")) {
        echo "Creating file backup $template.bak<br>
";
        $fd = fopen($template . ".bak", "wb") or die("Can't create backup file for $template: permission denied");
        fwrite($fd, $source);
        fclose($fd);
        @chmod($template . ".bak", 0666);
    }
    // apply common patch
    echo "Applying common patch $commonPatch ..<br>
";
    include $commonPatch;
    // apply file patch
    $patch = $path . $template . ".php";
    if (is_readable($patch)) {
        echo "Applying file patch $patch ...<br>
";
        include $patch;
    }
    echo "Writing patched file ..<br>
";
    $fn = fopen($template, "wb") or die("Can't create result file for $template: permission denied");
    fwrite($fn, $source);
    fclose($fn);
    @chmod($template, 0666);
}

function strReplace($search, $replace, $source, $file = __FILE__, $line = __LINE__)
{
    static $hunk;
    if (!isset($hunk)) $hunk = array();
    if (!isset($hunk[$file])) $hunk[$file] = 1;

    echo "Hunk #" . $hunk[$file]++ . " ... ";
    if ($replace != '' && strpos($source, $replace) !== false) {
        echo "[<font color=blue>ALREADY PATCHED</font>]<br>
";
    } elseif ($replace != '' && strpos($source, $search) === false) {
        echo "[<font color=red>FAILED at $file, line $line</font>]<br>
";
        // save reject here?
    } else {
        $source = str_replace($search, $replace, $source);
        echo "[<font color=green>OK</font>]<br>
";
    }
    return $source;
}

// }}}

?>

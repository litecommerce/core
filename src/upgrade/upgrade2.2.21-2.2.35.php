
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
$this->copyFile("skins_original/.htaccess", "skins/.htaccess");
$this->copyFile("skins_original/admin/en/change_skin.tpl", "skins/admin/en/change_skin.tpl");
$this->copyFile("skins_original/admin/en/general_settings.tpl", "skins/admin/en/general_settings.tpl");
$this->copyFile("skins_original/admin/en/image_files.tpl", "skins/admin/en/image_files.tpl");
$this->copyFile("skins_original/admin/en/location.tpl", "skins/admin/en/location.tpl");
$this->copyFile("skins_original/admin/en/login.tpl", "skins/admin/en/login.tpl");
$this->copyFile("skins_original/admin/en/main.tpl", "skins/admin/en/main.tpl");
$this->copyFile("skins_original/admin/en/memberships.tpl", "skins/admin/en/memberships.tpl");
$this->copyFile("skins_original/admin/en/summary.tpl", "skins/admin/en/summary.tpl");
$this->copyFile("skins_original/admin/en/categories/add_modify_body.tpl", "skins/admin/en/categories/add_modify_body.tpl");
$this->copyFile("skins_original/admin/en/categories/body.tpl", "skins/admin/en/categories/body.tpl");
$this->copyFile("skins_original/admin/en/common/delimiter.tpl", "skins/admin/en/common/delimiter.tpl");
$this->copyFile("skins_original/admin/en/common/invoice.tpl", "skins/admin/en/common/invoice.tpl");
$this->copyFile("skins_original/admin/en/common/membership_validator.tpl", "skins/admin/en/common/membership_validator.tpl");
$this->copyFile("skins_original/admin/en/common/qualifier.tpl", "skins/admin/en/common/qualifier.tpl");
$this->copyFile("skins_original/admin/en/css_editor/edit_style.tpl", "skins/admin/en/css_editor/edit_style.tpl");
$this->copyFile("skins_original/admin/en/css_editor/colorpicker/colorpicker.html", "skins/admin/en/css_editor/colorpicker/colorpicker.html");
$this->copyFile("skins_original/admin/en/db/restore.tpl", "skins/admin/en/db/restore.tpl");
$this->copyFile("skins_original/admin/en/image_editor/edit.tpl", "skins/admin/en/image_editor/edit.tpl");
$this->copyFile("skins_original/admin/en/look_feel/body.tpl", "skins/admin/en/look_feel/body.tpl");
$this->copyFile("skins_original/admin/en/payment_methods/body.tpl", "skins/admin/en/payment_methods/body.tpl");
$this->copyFile("skins_original/admin/en/product/export.tpl", "skins/admin/en/product/export.tpl");
$this->copyFile("skins_original/admin/en/product/export_fields.tpl", "skins/admin/en/product/export_fields.tpl");
$this->copyFile("skins_original/admin/en/product/fields_layout.tpl", "skins/admin/en/product/fields_layout.tpl");
$this->copyFile("skins_original/admin/en/product/import.tpl", "skins/admin/en/product/import.tpl");
$this->copyFile("skins_original/admin/en/product/import_fields.tpl", "skins/admin/en/product/import_fields.tpl");
$this->copyFile("skins_original/admin/en/product/layout.tpl", "skins/admin/en/product/layout.tpl");
$this->copyFile("skins_original/admin/en/product/product_list_form.tpl", "skins/admin/en/product/product_list_form.tpl");
$this->copyFile("skins_original/admin/en/product/products_delete.tpl", "skins/admin/en/product/products_delete.tpl");
$this->copyFile("skins_original/admin/en/shipping/methods.tpl", "skins/admin/en/shipping/methods.tpl");
$this->copyFile("skins_original/admin/en/shipping/zones.tpl", "skins/admin/en/shipping/zones.tpl");
$this->copyFile("skins_original/admin/en/tax/add.tpl", "skins/admin/en/tax/add.tpl");
$this->copyFile("skins_original/admin/en/tax/calculator.tpl", "skins/admin/en/tax/calculator.tpl");
$this->copyFile("skins_original/admin/en/tax/rates.tpl", "skins/admin/en/tax/rates.tpl");
$this->copyFile("skins_original/admin/en/tax/schemas.tpl", "skins/admin/en/tax/schemas.tpl");
$this->copyFile("skins_original/admin/en/template_editor/basic.tpl", "skins/admin/en/template_editor/basic.tpl");
$this->copyFile("skins_original/admin/en/users/search_results.tpl", "skins/admin/en/users/search_results.tpl");
$this->copyFile("skins_original/admin/images/checkout_no_payment.gif", "skins/admin/images/checkout_no_payment.gif");
$this->copyFile("skins_original/admin/images/common_range_validator.gif", "skins/admin/images/common_range_validator.gif");
$this->copyFile("skins_original/admin/images/help_pages_links.gif", "skins/admin/images/help_pages_links.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_common_select_status.gif", "skins/admin/images/modules_AOM_common_select_status.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_common_statuses.gif", "skins/admin/images/modules_AOM_common_statuses.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_invoice_gc.gif", "skins/admin/images/modules_AOM_invoice_gc.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_invoice_options.gif", "skins/admin/images/modules_AOM_invoice_options.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_invoice_promotion.gif", "skins/admin/images/modules_AOM_invoice_promotion.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_invoice_so.gif", "skins/admin/images/modules_AOM_invoice_so.gif");
$this->copyFile("skins_original/admin/images/modules_AOM_invoice_wsale.gif", "skins/admin/images/modules_AOM_invoice_wsale.gif");
$this->copyFile("skins_original/admin/images/modules_CardinalCommerce_credit_card.gif", "skins/admin/images/modules_CardinalCommerce_credit_card.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_egood_invoice.gif", "skins/admin/images/modules_Egoods_egood_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_file_access_denied.gif", "skins/admin/images/modules_Egoods_file_access_denied.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_file_not_found.gif", "skins/admin/images/modules_Egoods_file_not_found.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_free_downloads.gif", "skins/admin/images/modules_Egoods_free_downloads.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_invoice.gif", "skins/admin/images/modules_Egoods_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_Egoods_pin_invoice.gif", "skins/admin/images/modules_Egoods_pin_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_FlyoutCategories_main_flat.gif", "skins/admin/images/modules_FlyoutCategories_main_flat.gif");
$this->copyFile("skins_original/admin/images/modules_FlyoutCategories_main_footer.gif", "skins/admin/images/modules_FlyoutCategories_main_footer.gif");
$this->copyFile("skins_original/admin/images/modules_FlyoutCategories_main_side.gif", "skins/admin/images/modules_FlyoutCategories_main_side.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_ecards.gif", "skins/admin/images/modules_GiftCertificates_ecards.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_gc_validator.gif", "skins/admin/images/modules_GiftCertificates_gc_validator.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_invoice.gif", "skins/admin/images/modules_GiftCertificates_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_invoice_item.gif", "skins/admin/images/modules_GiftCertificates_invoice_item.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_print_invoice.gif", "skins/admin/images/modules_GiftCertificates_print_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_GiftCertificates_print_invoice_label.gif", "skins/admin/images/modules_GiftCertificates_print_invoice_label.gif");
$this->copyFile("skins_original/admin/images/modules_HSBC_checkout.gif", "skins/admin/images/modules_HSBC_checkout.gif");
$this->copyFile("skins_original/admin/images/modules_InventoryTracking_exceeding.gif", "skins/admin/images/modules_InventoryTracking_exceeding.gif");
$this->copyFile("skins_original/admin/images/modules_InventoryTracking_product_quantity.gif", "skins/admin/images/modules_InventoryTracking_product_quantity.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_all_news.gif", "skins/admin/images/modules_Newsletters_all_news.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_menu_news_body.gif", "skins/admin/images/modules_Newsletters_menu_news_body.gif");
$this->copyFile("skins_original/admin/images/modules_Newsletters_newsfeed.gif", "skins/admin/images/modules_Newsletters_newsfeed.gif");
$this->copyFile("skins_original/admin/images/modules_PayPalPro_retrieve_profile.gif", "skins/admin/images/modules_PayPalPro_retrieve_profile.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_NewArrivals_body.gif", "skins/admin/images/modules_ProductAdviser_NewArrivals_body.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_OutOfStock_add_to_cart.gif", "skins/admin/images/modules_ProductAdviser_OutOfStock_add_to_cart.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_OutOfStock_cart_item.gif", "skins/admin/images/modules_ProductAdviser_OutOfStock_cart_item.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_OutOfStock_checkout_item.gif", "skins/admin/images/modules_ProductAdviser_OutOfStock_checkout_item.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_OutOfStock_product_quantity.gif", "skins/admin/images/modules_ProductAdviser_OutOfStock_product_quantity.gif");
$this->copyFile("skins_original/admin/images/modules_ProductAdviser_RelatedProducts_add2cart.gif", "skins/admin/images/modules_ProductAdviser_RelatedProducts_add2cart.gif");
$this->copyFile("skins_original/admin/images/modules_ProductOptions_options_exception.gif", "skins/admin/images/modules_ProductOptions_options_exception.gif");
$this->copyFile("skins_original/admin/images/modules_ProductOptions_product_option_modifier.gif", "skins/admin/images/modules_ProductOptions_product_option_modifier.gif");
$this->copyFile("skins_original/admin/images/modules_ProductOptions_product_quantity.gif", "skins/admin/images/modules_ProductOptions_product_quantity.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_bonus_points.gif", "skins/admin/images/modules_Promotion_bonus_points.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_checkout.gif", "skins/admin/images/modules_Promotion_checkout.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_invoice.gif", "skins/admin/images/modules_Promotion_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_invoice_discount.gif", "skins/admin/images/modules_Promotion_invoice_discount.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_order_offers.gif", "skins/admin/images/modules_Promotion_order_offers.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_print_invoice_discount.gif", "skins/admin/images/modules_Promotion_print_invoice_discount.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_print_invoice_discount_label.gif", "skins/admin/images/modules_Promotion_print_invoice_discount_label.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_print_invoice_label.gif", "skins/admin/images/modules_Promotion_print_invoice_label.gif");
$this->copyFile("skins_original/admin/images/modules_Promotion_print_invoice_total.gif", "skins/admin/images/modules_Promotion_print_invoice_total.gif");
$this->copyFile("skins_original/admin/images/modules_SnsIntegration_tracker.gif", "skins/admin/images/modules_SnsIntegration_tracker.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_expanded_options.gif", "skins/admin/images/modules_WholesaleTrading_expanded_options.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_invoice.gif", "skins/admin/images/modules_WholesaleTrading_invoice.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_membership_exp_date.gif", "skins/admin/images/modules_WholesaleTrading_membership_exp_date.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_membership_payed_membership_added.gif", "skins/admin/images/modules_WholesaleTrading_membership_payed_membership_added.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_profile_form.gif", "skins/admin/images/modules_WholesaleTrading_profile_form.gif");
$this->copyFile("skins_original/admin/images/modules_WholesaleTrading_wholesaler_details.gif", "skins/admin/images/modules_WholesaleTrading_wholesaler_details.gif");
$this->copyFile("skins_original/admin/images/modules_WishList_mini_cart_body.gif", "skins/admin/images/modules_WishList_mini_cart_body.gif");
$this->copyFile("skins_original/default/en/help/pages_links.tpl", "skins/default/en/help/pages_links.tpl");
$this->copyFile("skins_original/default/en/help/pages_links_def.tpl", "skins/default/en/help/pages_links_def.tpl");
// }}}

// STEP 3: Patch etc/config.php config file {{{
//patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade2.2.21-2.2.35.sql", $this->db->connection, true);
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
    $path = "upgrade/upgrade2.2.21-2.2.35/";
    $source = file_get_contents($template);
    $commonPatch = $path . "common.php";
    $patch = $path . $template . ".php";
    if ((is_readable($commonPatch) && filesize($commonPatch) > 0 ) || is_readable($patch)) {
        echo "<b>Patching file $template</b><br>";

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
        echo "Applying common patch $commonPatch ..<br>";

        include $commonPatch;
        // apply file patch
        if (is_readable($patch)) {
            echo "Applying file patch $patch ...<br>";

            include $patch;
        }
        echo "Writing patched file ..<br>
";
        $fn = fopen($template, "wb") or die("Can't create result file for $template: permission denied");
        fwrite($fn, $source);
        fclose($fn);
        @chmod($template, 0666);
    }
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

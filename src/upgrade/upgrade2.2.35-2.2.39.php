
<?php

// BEGIN
func_refresh_start();

echo "Upgrading ..<br>
";

// STEP 1: Patch skins {{{

// assume all new kernel/include/template/loader files were uploaded successfully

$skins = array($admin = "skins/admin/en", $default = "skins/default/en");

foreach ($skins as $skin) {
    echo "Patching skin $skin ..<br>
";
    patchSkin($skin);
}    
patchFile("skins/.htaccess");
// }}}

// STEP 2: Copy new & admin skins {{{
$this->copyFile("skins_original/admin/en/add_ip.tpl", "skins/admin/en/add_ip.tpl");
$this->copyFile("skins_original/admin/en/change_skin.tpl", "skins/admin/en/change_skin.tpl");
$this->copyFile("skins_original/admin/en/customer_zone_warning.tpl", "skins/admin/en/customer_zone_warning.tpl");
$this->copyFile("skins_original/admin/en/general_settings.tpl", "skins/admin/en/general_settings.tpl");
$this->copyFile("skins_original/admin/en/image_files.tpl", "skins/admin/en/image_files.tpl");
$this->copyFile("skins_original/admin/en/import_users.tpl", "skins/admin/en/import_users.tpl");
$this->copyFile("skins_original/admin/en/location.tpl", "skins/admin/en/location.tpl");
$this->copyFile("skins_original/admin/en/login.tpl", "skins/admin/en/login.tpl");
$this->copyFile("skins_original/admin/en/main.tpl", "skins/admin/en/main.tpl");
$this->copyFile("skins_original/admin/en/memberships.tpl", "skins/admin/en/memberships.tpl");
$this->copyFile("skins_original/admin/en/modules.tpl", "skins/admin/en/modules.tpl");
$this->copyFile("skins_original/admin/en/modules_body.tpl", "skins/admin/en/modules_body.tpl");
$this->copyFile("skins_original/admin/en/powered_by_litecommerce.tpl", "skins/admin/en/powered_by_litecommerce.tpl");
$this->copyFile("skins_original/admin/en/summary.tpl", "skins/admin/en/summary.tpl");
$this->copyFile("skins_original/admin/en/waiting_ips.tpl", "skins/admin/en/waiting_ips.tpl");
$this->copyFile("skins_original/admin/en/wysiwyg.tpl", "skins/admin/en/wysiwyg.tpl");
$this->copyFile("skins_original/admin/en/add_ip/failure.tpl", "skins/admin/en/add_ip/failure.tpl");
$this->copyFile("skins_original/admin/en/add_ip/success.tpl", "skins/admin/en/add_ip/success.tpl");
$this->copyFile("skins_original/admin/en/catalog/body.tpl", "skins/admin/en/catalog/body.tpl");
$this->copyFile("skins_original/admin/en/categories/add_modify_body.tpl", "skins/admin/en/categories/add_modify_body.tpl");
$this->copyFile("skins_original/admin/en/categories/body.tpl", "skins/admin/en/categories/body.tpl");
$this->copyFile("skins_original/admin/en/common/invoice.tpl", "skins/admin/en/common/invoice.tpl");
$this->copyFile("skins_original/admin/en/common/ip_validator.tpl", "skins/admin/en/common/ip_validator.tpl");
$this->copyFile("skins_original/admin/en/common/membership_validator.tpl", "skins/admin/en/common/membership_validator.tpl");
$this->copyFile("skins_original/admin/en/common/popup_product_list.tpl", "skins/admin/en/common/popup_product_list.tpl");
$this->copyFile("skins_original/admin/en/common/select_category.tpl", "skins/admin/en/common/select_category.tpl");
$this->copyFile("skins_original/admin/en/common/select_status.tpl", "skins/admin/en/common/select_status.tpl");
$this->copyFile("skins_original/admin/en/common/uploaded_file_validator.tpl", "skins/admin/en/common/uploaded_file_validator.tpl");
$this->copyFile("skins_original/admin/en/db/backup.tpl", "skins/admin/en/db/backup.tpl");
$this->copyFile("skins_original/admin/en/db/restore.tpl", "skins/admin/en/db/restore.tpl");
$this->copyFile("skins_original/admin/en/images/code.gif", "skins/admin/en/images/code.gif");
$this->copyFile("skins_original/admin/en/images/tab_a1.gif", "skins/admin/en/images/tab_a1.gif");
$this->copyFile("skins_original/admin/en/images/tab_a2.gif", "skins/admin/en/images/tab_a2.gif");
$this->copyFile("skins_original/admin/en/images/tab_bg_a.gif", "skins/admin/en/images/tab_bg_a.gif");
$this->copyFile("skins_original/admin/en/images/tab_bg_p.gif", "skins/admin/en/images/tab_bg_p.gif");
$this->copyFile("skins_original/admin/en/images/tab_p1.gif", "skins/admin/en/images/tab_p1.gif");
$this->copyFile("skins_original/admin/en/images/tab_p2.gif", "skins/admin/en/images/tab_p2.gif");
$this->copyFile("skins_original/admin/en/look_feel/body.tpl", "skins/admin/en/look_feel/body.tpl");
$this->copyFile("skins_original/admin/en/maintenance/body.tpl", "skins/admin/en/maintenance/body.tpl");
$this->copyFile("skins_original/admin/en/order/export_xls.tpl", "skins/admin/en/order/export_xls.tpl");
$this->copyFile("skins_original/admin/en/order/list.tpl", "skins/admin/en/order/list.tpl");
$this->copyFile("skins_original/admin/en/order/order.tpl", "skins/admin/en/order/order.tpl");
$this->copyFile("skins_original/admin/en/order/recent_orders.tpl", "skins/admin/en/order/recent_orders.tpl");
$this->copyFile("skins_original/admin/en/payment_methods/body.tpl", "skins/admin/en/payment_methods/body.tpl");
$this->copyFile("skins_original/admin/en/product/add.tpl", "skins/admin/en/product/add.tpl");
$this->copyFile("skins_original/admin/en/product/extra_fields_form.tpl", "skins/admin/en/product/extra_fields_form.tpl");
$this->copyFile("skins_original/admin/en/product/import.tpl", "skins/admin/en/product/import.tpl");
$this->copyFile("skins_original/admin/en/product/import_fields.tpl", "skins/admin/en/product/import_fields.tpl");
$this->copyFile("skins_original/admin/en/product/info.tpl", "skins/admin/en/product/info.tpl");
$this->copyFile("skins_original/admin/en/product/links.tpl", "skins/admin/en/product/links.tpl");
$this->copyFile("skins_original/admin/en/product/product_list.tpl", "skins/admin/en/product/product_list.tpl");
$this->copyFile("skins_original/admin/en/product/search.tpl", "skins/admin/en/product/search.tpl");
$this->copyFile("skins_original/admin/en/profile/body.tpl", "skins/admin/en/profile/body.tpl");
$this->copyFile("skins_original/admin/en/shipping/charges.tpl", "skins/admin/en/shipping/charges.tpl");
$this->copyFile("skins_original/admin/en/shipping/charges_form.tpl", "skins/admin/en/shipping/charges_form.tpl");
$this->copyFile("skins_original/admin/en/shipping/methods.tpl", "skins/admin/en/shipping/methods.tpl");
$this->copyFile("skins_original/admin/en/tax/add.tpl", "skins/admin/en/tax/add.tpl");
$this->copyFile("skins_original/admin/en/tax/options.tpl", "skins/admin/en/tax/options.tpl");
$this->copyFile("skins_original/admin/en/tax/rates.tpl", "skins/admin/en/tax/rates.tpl");
$this->copyFile("skins_original/default/en/powered_by_litecommerce.tpl", "skins/default/en/powered_by_litecommerce.tpl");
$this->copyFile("skins_original/default/en/common/captcha_validator.tpl", "skins/default/en/common/captcha_validator.tpl");
$this->copyFile("skins_original/default/en/common/spambot_arrest.tpl", "skins/default/en/common/spambot_arrest.tpl");
$this->copyFile("skins_original/mail/en/htaccess_notify/body.tpl", "skins/mail/en/htaccess_notify/body.tpl");
$this->copyFile("skins_original/mail/en/htaccess_notify/subject.tpl", "skins/mail/en/htaccess_notify/subject.tpl");
$this->copyFile("skins_original/mail/en/new_ip_notify_admin/body.tpl", "skins/mail/en/new_ip_notify_admin/body.tpl");
$this->copyFile("skins_original/mail/en/new_ip_notify_admin/subject.tpl", "skins/mail/en/new_ip_notify_admin/subject.tpl");
// }}}

// STEP 3: Patch etc/config.php config file {{{
patchFile("etc/config.php");
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade2.2.35-2.2.39.sql", $this->db->connection, true);
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
    $path = "upgrade/upgrade2.2.35-2.2.39/";
    $source = file_get_contents($template);
    $commonPatch = $path . "common.php";
    $patch = $path . $template . ".php";
    if ((is_readable($commonPatch) && filesize($commonPatch) > 0 ) || is_readable($patch)) {
        echo "<b>Patching file $template</b><br>";

        // backup original file
        if ($backup && !file_exists($template . ".bak")) {
            echo "Creating file backup $template.bak<br>
";
            $fd = @fopen($template . ".bak", "wb");
            if (!$fd) {
				func_refresh_end();
				die("<font color=red><b>Can't create backup file for $template: permission denied!</b></font>");
            }
            @fwrite($fd, $source);
            @fclose($fd);
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
        $fn = @fopen($template, "wb");
		if (!$fn) {
			func_refresh_end();
			die("<font color=red><b>Can't create result file for $template: permission denied!</b></font>");
        }
        @fwrite($fn, $source);
        @fclose($fn);
        @chmod($template, 0666);
    }
}

function strReplace($search, $replace, $source, $file = __FILE__, $line_ = __LINE__)
{
    static $hunk;
    if (!isset($hunk)) $hunk = array();
    if (!isset($hunk[$file])) $hunk[$file] = 1;

    echo "Hunk #" . $hunk[$file]++ . " ... ";

    $nl_source = (strpos($source, "
") !== false) ? "
" : "
";
    $source_lines = (array) explode($nl_source, $source);
    $__source_lines = array();
    $search_lines = (array) explode("
", $search);
    $__search_lines = array();
    $replace_lines = (array) explode("
", $replace);
    $__replace_lines = array();

    foreach($source_lines as $ind => $line){
        $line = str_replace("	", " ", $line);
        $line = preg_replace("/(s){2,}/", " ", $line);
        $line = trim($line);
        $__source_lines[$ind] = $line;
    }

    foreach($search_lines as $ind => $line){
        $line = str_replace("	", " ", $line);
        $line = preg_replace("/(s){2,}/", " ", $line);
        $line = trim($line);
        $__search_lines[$ind] = $line;
    }

    if($source_lines[count($source_lines) - 1] == ""){
        unset($source_lines[count($source_lines) - 1]);
        unset($__source_lines[count($__source_lines) - 1]);
    }

    $count = count($search_lines);
    $congruent = 0;
    $fisrt_line = 0;
    $find = false;

    foreach($__source_lines as $ind => $__line){
        $__search_line = $__search_lines[$congruent];

        if($congruent > 0 && $__line != $__search_line){
            $congruent = 0;
            $__search_line = $__search_lines[0];
        }

        if($__line == $__search_line){
            $congruent++;
        }

        if($congruent == $count){
            $fisrt_line = $ind + 1 - $count;
            $find = true;
            break;
        }
    }

    if(!$find){
        $congruent = 0;
        $find = false;
        foreach($replace_lines as $ind => $line){
            $line = str_replace("	", " ", $line);
            $line = preg_replace("/(s){2,}/", " ", $line);
            $line = trim($line);
            $__replace_lines[$ind] = $line;
        }

        $count = count($replace_lines);

        foreach($__source_lines as $ind => $__line){
            $__replace_line = $__replace_lines[$congruent];
            if($congruent > 0 && $__line != $__replace_line){
                $congruent = 0;
                $__replace_line = $__replace_lines[0];
            }

            if($__line == $__replace_line){
                $congruent++;
            }
                
            if($congruent == $count){
                $find = true;
                break;
            }
        }

        if($find){
            echo "[<font color=blue>ALREADY PATCHED</font>]<br />";                
        } else {
            echo "[<font color=red>FAILED at $file, line $line_</font>]<br />";
        }

    } else {
        $source = "";
        for($i = 0; $i < count($source_lines); $i++){
            $line = $source_lines[$i];
            if($i == $fisrt_line){
                foreach($replace_lines as $replace_line){
                    $source .= ($replace_line . $nl_source);
                }
                $i += ($count - 1);
            } else {
                $source .= ($line . $nl_source);
            }
        }

        echo "[<font color=green>OK</font>]<br>";
    }

    return $source;
}

// }}}

?>

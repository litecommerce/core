<?php

// BEGIN
func_refresh_start();

echo "Upgrading ..<br>\n";

// STEP 1: Patch skins {{{

// assume all new kernel/include/template/loader files were uploaded successfully

$skins = array($admin = "skins/admin/en", $default = "skins/default/en");

foreach ($skins as $skin) {
    echo "Patching skin $skin ..<br>\n";
    patchSkin($skin);
}    
// }}}

// STEP 2: Copy v2.1 skins {{{

// customer's zone skin
foreach (array(
"/default/en/authentication.tpl",
"/default/en/news.tpl",
"/default/en/phones.tpl",
"/default/en/pages_links.tpl",
) as $file) {
    $this->copyFile("skins_original$file", "skins$file");
}

// newly added components
$this->copyFile("schemas/templates/3-columns_classic/default/en/buy_now.tpl", "skins/default/en/buy_now.tpl");
$this->copyFile("schemas/templates/3-columns_classic/default/en/common/button.tpl", "skins/default/en/common/button.tpl");
$this->copyFile("schemas/templates/3-columns_classic/default/en/common/submit.tpl", "skins/default/en/common/submit.tpl");

// admin zone skins
foreach (array(
"/admin/en/common/button.tpl",
"/admin/en/common/submit.tpl",
"/admin/en/images/icon_error.gif",
"/admin/en/images/icon_information.gif",
"/admin/en/images/icon_warning.gif",
"/admin/en/images/logo_asp.gif",
"/admin/en/orders_stats.tpl",
"/admin/en/product/export_fields.tpl",
"/admin/en/product/extra_fields.tpl",
"/admin/en/product/extra_fields_form.tpl",
"/admin/en/product/fields_layout.tpl",
"/admin/en/product/import_fields.tpl",
"/admin/en/stats.tpl",
"/admin/en/top_sellers.tpl",
"/admin/en/authentication/body.tpl",
"/admin/en/common/column_list.tpl",
"/admin/en/general_settings.tpl",
"/admin/en/image_editor/edit.tpl",
"/admin/en/modules.tpl",
"/admin/en/payment_methods/body.tpl",
"/admin/en/searchStat.tpl",
"/admin/en/tax/options.tpl",
"/admin/en/tax/schemas.tpl",
) as $file) {
    $this->copyFile("skins_original$file", "skins$file");
}
// }}}

// STEP 3: Patch etc/config.php config file {{{
//patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade2.0.0-2.1.0.sql", $this->db->connection, true);
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
    echo "<b>Patching file $template</b><br>\n";
    $path = "upgrade/upgrade2.0.0-2.1.0/";
    $source = file_get_contents($template);
    $commonPatch = $path . "common.php";
    // backup original file
    if ($backup && !file_exists($template . ".bak")) {
        echo "Creating file backup $template.bak<br>\n";
        $fd = fopen($template . ".bak", "wb") or die("Can't create backup file for $template: permission denied");
        fwrite($fd, $source);
        fclose($fd);
        @chmod($template . ".bak", 0666);
    }
    // apply common patch
    echo "Applying common patch $commonPatch ..<br>\n";
    include $commonPatch;
    // apply file patch
    $patch = $path . $template . ".php";
    if (is_readable($patch)) {
        echo "Applying file patch $patch ...<br>\n";
        include $patch;
    }
    echo "Writing patched file ..<br>\n";
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
        echo "[<font color=blue>ALREADY PATCHED</font>]<br>\n";
    } elseif ($replace != '' && strpos($source, $search) === false) {
        die("[<font color=red>FAILED at $file, line $line</font>]<br>\n");
        // save reject here?
    } else {
        $source = str_replace($search, $replace, $source);
        echo "[<font color=green>OK</font>]<br>\n";
    }
    return $source;
}

// }}}

?>

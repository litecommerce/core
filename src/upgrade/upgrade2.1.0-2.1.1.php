
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
// }}}

// STEP 2: Copy new & admin skins {{{
$this->copyFile("skins_original/admin/en/general_settings.tpl", "skins/admin/en/general_settings.tpl");
$this->copyFile("skins_original/admin/en/location.tpl", "skins/admin/en/location.tpl");
$this->copyFile("skins_original/admin/en/main.tpl", "skins/admin/en/main.tpl");
$this->copyFile("skins_original/admin/en/modules.tpl", "skins/admin/en/modules.tpl");
$this->copyFile("skins_original/admin/en/searchStat.tpl", "skins/admin/en/searchStat.tpl");
$this->copyFile("skins_original/admin/en/stats.tpl", "skins/admin/en/stats.tpl");
$this->copyFile("skins_original/admin/en/categories/add_modify_body.tpl", "skins/admin/en/categories/add_modify_body.tpl");
$this->copyFile("skins_original/admin/en/common/invoice.tpl", "skins/admin/en/common/invoice.tpl");
$this->copyFile("skins_original/admin/en/common/pager.tpl", "skins/admin/en/common/pager.tpl");
$this->copyFile("skins_original/admin/en/maintenance/body.tpl", "skins/admin/en/maintenance/body.tpl");
$this->copyFile("skins_original/admin/en/management/body.tpl", "skins/admin/en/management/body.tpl");
$this->copyFile("skins_original/admin/en/product/search.tpl", "skins/admin/en/product/search.tpl");
$this->copyFile("skins_original/admin/en/profile/body.tpl", "skins/admin/en/profile/body.tpl");
$this->copyFile("skins_original/admin/en/shipping/zones.tpl", "skins/admin/en/shipping/zones.tpl");
// }}}

// STEP 3: Patch etc/config.php config file {{{
//patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade2.1.0-2.1.1.sql", $this->db->connection, true);
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
    $path = "upgrade/upgrade2.1.0-2.1.1/";
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

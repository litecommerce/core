
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
$this->copyFile("skins_original/admin/en/common/tabber.tpl", "skins/admin/en/common/tabber.tpl");
$this->copyFile("skins_original/admin/en/order/list.tpl", "skins/admin/en/order/list.tpl");
$this->copyFile("skins_original/admin/en/product/add.tpl", "skins/admin/en/product/add.tpl");
$this->copyFile("skins_original/admin/en/product/import.tpl", "skins/admin/en/product/import.tpl");
$this->copyFile("skins_original/admin/en/product/info.tpl", "skins/admin/en/product/info.tpl");
$this->copyFile("skins_original/admin/en/product/search.tpl", "skins/admin/en/product/search.tpl");
$this->copyFile("skins_original/admin/en/tax/calculator.tpl", "skins/admin/en/tax/calculator.tpl");
$this->copyFile("skins_original/admin/en/users/search_results.tpl", "skins/admin/en/users/search_results.tpl");
// }}}

// STEP 3: Patch etc/config.php config file {{{
//patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade2.1.1-2.1.2.sql", $this->db->connection, true);
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
    $path = "upgrade/upgrade2.1.1-2.1.2/";
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

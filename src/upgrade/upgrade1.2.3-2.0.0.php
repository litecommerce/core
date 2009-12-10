<?php

// BEGIN
func_refresh_start();

echo "Upgrading ..<br>\n";

// STEP 1: Patch skins {{{

// assume all new kernel/include/template/loader files were uploaded successfully

patchFile("cart.html");

$skins = array($default = "skins/default/en", $mail = "skins/mail/en");

foreach ($skins as $skin) {
    echo "Patching skin $skin ..<br>\n";
    patchSkin($skin);
}    
// }}}

// STEP 2: Copy v2 skins {{{

// customer's zone skin
foreach (array(
'/default/en/category_empty.tpl',
'/default/en/common/email_validator.tpl',
'/default/en/common/password_validator.tpl',
'/default/en/common/range_validator.tpl',
'/default/en/common/required_validator.tpl',
'/default/en/common/state_validator.tpl',
'/default/en/images/sideicon_gift.gif'
) as $file) {
    $this->copyFile("skins_original$file", "skins$file");
}

// admin zone skins
copyRecursive("skins_original/admin", "skins/admin");
// }}}

// STEP 3: Patch etc/config.php config file {{{
patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
$this->connection =& $this->db; // v2 compatibility
$this->patchSQL("UPDATE xlite_config SET value='category_subcategories.tpl' WHERE name='subcategories_look'");
$this->patchSQL("REPLACE INTO xlite_config values ('date_format','Date format', '%m/%d/%Y', 'General', 225, 'select');");
$this->patchSQL("REPLACE INTO xlite_config values ('time_format','Time format', '%H:%M', 'General', 230, 'select');");
$this->patchSQL("REPLACE INTO xlite_payment_methods VALUES ('fax_ordering','Fax Ordering','Fax: (555) 555-5555','offline','',40,1);");
$this->patchSQL("REPLACE INTO xlite_payment_methods VALUES ('money_ordering','Money Order','US Banks Only','offline','',45,1);");
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
    $path = "upgrade/upgrade1.2.3-2.0.0/";
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
        $source = str_replace($search, $replace, $source);
        echo "[<font color=blue>ALREADY PATCHED</font>]<br>\n";
    } elseif ($replace != '' && strpos($source, $search) === false) {
        echo "[<font color=red>FAILED at $file, line $line</font>]<br>\n";
        // save reject here?
    } else {
        $source = str_replace($search, $replace, $source);
        echo "[<font color=green>OK</font>]<br>\n";
    }
    return $source;
}

// }}}

?>

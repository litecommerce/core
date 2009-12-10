#!/usr/bin/perl
$_ = shift @ARGV;
($fromVer, $toVer) = split(/-/);
$fromVerDash = $fromVer; 
$fromVerDash =~ tr/./_/;
$toVerDash = $toVer;
$toVerDash =~ tr/./_/;

$manifest = <<EOT
type=upgrade
description=LiteCommerce version $fromVer to version $toVer upgrade
readme=UPGRADE.readme
from_ver=$fromVer
to_ver=$toVer
remove_files=
EOT
;
$separator = "";

open CVSOUTPUT, "cvs rdiff -s -r version_$fromVerDash -r version_$toVerDash x-lite 2> /dev/null | ";

open FILELIST, ">upgrade/file-list$fromVer-$toVer";

open UPGRADESCRIPT, ">upgrade/upgrade$fromVer-$toVer.php";

$upgradeDir = "upgrade/upgrade$fromVer-$toVer";
mkdir $upgradeDir;

print UPGRADESCRIPT <<EOT

<?php

// BEGIN
func_refresh_start();

echo "Upgrading ..<br>\n";

// STEP 1: Patch skins {{{

// assume all new kernel/include/template/loader files were uploaded successfully

\$skins = array(\$admin = "skins/admin/en", \$default = "skins/default/en");

foreach (\$skins as \$skin) {
    echo "Patching skin \$skin ..<br>\n";
    patchSkin(\$skin);
}    
// }}}

// STEP 2: Copy new & admin skins {{{
EOT
;
print "Skins that need to be patched: \n";
print "===============================\n";
while (<CVSOUTPUT>) {
    /^File (\S*) (changed|is new|is removed)/ || next;
    $operation = $2;
    if ($1 =~ /\/modules\//) {
        next;
    }
    $found = 0;
    foreach(@ARGV) {
        $found = $found || $1 =~ /^x-lite\/$_/;
    }
    $found || next;

    $sourceFile = $1;
    $sourceFile =~ s/x-lite\///;
    if ($operation eq "is removed") {
        $manifest .= $separator.$sourceFile;
        $separator = "; ";
        # remove from skins_original too
        if ($sourceFile =~ /^skins(.*)/) {
            $manifest .= $separator."skins_original".$1;
        }
    } else {
        if ($sourceFile =~ /^skins(.*)/) {
            $destFile = "skins_original".$1;

            # определять, какие скины копировать, какие - пачить
            if ($sourceFile =~ /^skins\/admin\// || $operation eq "is new") {
                # копировать 
                print UPGRADESCRIPT "\$this->copyFile(\"$destFile\", \"$sourceFile\");\n";
            } else {
                # пачить
                $patchScript = "$upgradeDir/$sourceFile.php\n";
                print "$patchScript\n";
                $dirname = $patchScript;
                if ($dirname =~ /\//) {
                    $dirname =~ s/\/[^\/]+$//;
                    `mkdir -p $dirname > /dev/null`;
                }
                `cvs diff -u -r version_$fromVerDash -r version_$toVerDash $sourceFile > $patchScript 2>/dev/null`;
#                $destFile = "";
            }
        } else {
            $destFile = $sourceFile;
        }
        if ($destFile ne "") {
            $dirname = $destFile;
            if ($dirname =~ /\//) {
                $dirname =~ s/\/[^\/]+$//;
                `mkdir -p $upgradeDir/$dirname > /dev/null`;
            }
            `cp $sourceFile $upgradeDir/$destFile > /dev/null`;
            print FILELIST $destFile."\n";
        }
    }

}
open MANIFEST, ">upgrade/MANIFEST$fromVer-$toVer";
print MANIFEST "$manifest\n";

print UPGRADESCRIPT <<EOT
// }}}

// STEP 3: Patch etc/config.php config file {{{
//patchFile("etc/config.php", false);
// }}}

// STEP 4: Patch SQL database {{{
query_upload("upgrade/upgrade$fromVer-$toVer.sql", \$this->db->connection, true);
// }}}

// END
func_refresh_end();

// FUNCTIONS {{{

function patchSkin(\$skin)
{
    if (\$handle = opendir(\$skin)) {
        while (false !== (\$file = readdir(\$handle))) {
            if (\$file{0} != ".") {
                \$path = \$skin . '/' . \$file;
                if (is_dir(\$path)) {
                    patchSkin(\$path);
                } elseif (is_file(\$path) && substr(\$file, -4) != ".bak" && substr(\$file, -4) != ".gif") {
                    patchFile(\$path);
                }
            }
        }
    }
    closedir(\$handle);
}

function patchFile(\$template, \$backup = true)
{
    \$path = "upgrade/upgrade$fromVer-$toVer/";
    \$source = file_get_contents(\$template);
    \$commonPatch = \$path . "common.php";
    \$patch = \$path . \$template . ".php";
    if ((is_readable(\$commonPatch) && filesize(\$commonPatch) > 0 ) || is_readable(\$patch)) {
        echo "<b>Patching file \$template</b><br>";\n
        // backup original file
        if (\$backup && !file_exists(\$template . ".bak")) {
            echo "Creating file backup \$template.bak<br>\n";
            \$fd = fopen(\$template . ".bak", "wb") or die("Can't create backup file for \$template: permission denied");
            fwrite(\$fd, \$source);
            fclose(\$fd);
            \@chmod(\$template . ".bak", 0666);
        }
        // apply common patch
        echo "Applying common patch \$commonPatch ..<br>";\n
        include \$commonPatch;
        // apply file patch
        if (is_readable(\$patch)) {
            echo "Applying file patch \$patch ...<br>";\n
            include \$patch;
        }
        echo "Writing patched file ..<br>\n";
        \$fn = fopen(\$template, "wb") or die("Can't create result file for \$template: permission denied");
        fwrite(\$fn, \$source);
        fclose(\$fn);
        \@chmod(\$template, 0666);
    }
}

function strReplace(\$search, \$replace, \$source, \$file = __FILE__, \$line_ = __LINE__)
{
    static \$hunk;
    if (!isset(\$hunk)) \$hunk = array();
    if (!isset(\$hunk[\$file])) \$hunk[\$file] = 1;

    echo "Hunk #" . \$hunk[\$file]++ . " ... ";

    \$nl_source = (strpos(\$source, "\r\n") !== false) ? "\r\n" : "\n";
    \$source_lines = (array) explode(\$nl_source, \$source);
    \$__source_lines = array();
    \$search_lines = (array) explode("\n", \$search);
    \$__search_lines = array();
    \$replace_lines = (array) explode("\n", \$replace);
    \$__replace_lines = array();

    foreach(\$source_lines as \$ind => \$line){
        \$line = str_replace("\t", " ", \$line);
        \$line = preg_replace("/(\s){2,}/", " ", \$line);
        \$line = trim(\$line);
        \$__source_lines[\$ind] = \$line;
    }

    foreach(\$search_lines as \$ind => \$line){
        \$line = str_replace("\t", " ", \$line);
        \$line = preg_replace("/(\s){2,}/", " ", \$line);
        \$line = trim(\$line);
        \$__search_lines[\$ind] = \$line;
    }

    if(\$source_lines[count(\$source_lines) - 1] == ""){
        unset(\$source_lines[count(\$source_lines) - 1]);
        unset(\$__source_lines[count(\$__source_lines) - 1]);
    }

    \$count = count(\$search_lines);
    \$congruent = 0;
    \$fisrt_line = 0;
    \$find = false;

    foreach(\$__source_lines as \$ind => \$__line){
        \$__search_line = \$__search_lines[\$congruent];

        if(\$congruent > 0 && \$__line != \$__search_line){
            \$congruent = 0;
            \$__search_line = \$__search_lines[0];
        }

        if(\$__line == \$__search_line){
            \$congruent++;
        }

        if(\$congruent == \$count){
            \$fisrt_line = \$ind + 1 - \$count;
            \$find = true;
            break;
        }
    }

    if(!\$find){
        \$congruent = 0;
        \$find = false;
        foreach(\$replace_lines as \$ind => \$line){
            \$line = str_replace("\t", " ", \$line);
            \$line = preg_replace("/(\s){2,}/", " ", \$line);
            \$line = trim(\$line);
            \$__replace_lines[\$ind] = \$line;
        }

        \$count = count(\$replace_lines);

        foreach(\$__source_lines as \$ind => \$__line){
            \$__replace_line = \$__replace_lines[\$congruent];
            if(\$congruent > 0 && \$__line != \$__replace_line){
                \$congruent = 0;
                \$__replace_line = \$__replace_lines[0];
            }

            if(\$__line == \$__replace_line){
                \$congruent++;
            }
                
            if(\$congruent == \$count){
                \$find = true;
                break;
            }
        }

        if(\$find){
            echo "[<font color=blue>ALREADY PATCHED</font>]<br />";                
        } else {
            echo "[<font color=red>FAILED at \$file, line \$line_</font>]<br />";
        }

    } else {
        \$source = "";
        for(\$i = 0; \$i < count(\$source_lines); \$i++){
            \$line = \$source_lines[\$i];
            if(\$i == \$fisrt_line){
                foreach(\$replace_lines as \$replace_line){
                    \$source .= (\$replace_line . \$nl_source);
                }
                \$i += (\$count - 1);
            } else {
                \$source .= (\$line . \$nl_source);
            }
        }

        echo "[<font color=green>OK</font>]<br>";
    }

    return \$source;
}

// }}}

?>
EOT
;
open COMMON_PHP, ">$upgradeDir/common.php";
close COMMON_PHP;

$sqlPatch = "upgrade/upgrade$fromVer-$toVer.sql";
print "\nSaving sql/ CVS patch to $sqlPatch...\n";
`cvs diff -R -r version_$fromVerDash -r version_$toVerDash sql > $sqlPatch`;
`find $upgradeDir -type d ! -name CVS -exec cvs add {} \\;`;
`cvs add \`find $upgradeDir -type f ! -path "*CVS/*"\``;
`cvs add upgrade/MANIFEST$fromVer-$toVer upgrade/upgrade$fromVer-$toVer.php upgrade/upgrade$fromVer-$toVer.sql`


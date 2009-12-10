<?php
include_once "includes/functions.php";
//define("TEST_MODE", true);

// variables {{{
	//$ioncube = "/u/ioncube/ioncube-6.0 --without-loader-check --optimize max --erase-target ";
	$ioncube = "./ion_caller.php -o \"--without-loader-check --optimize max --erase-target\" ";
	$enc_source = array(
		"classes/base/Object.php",
		"var/run/classes/base/Object.php",
		"classes/base/LObject.php",
		"var/run/classes/base/LObject.php",
		"compat/gmp.php",
		"classes/kernel/Config.php",
		"var/run/classes/kernel/Config.php"
	);
	$trial_dir = "trial";
	$predistr_dir = $trial_dir . "/predistr";
	$dist_dir = $trial_dir . "/dist";
	$files_dir = $trial_dir . "/files";
	$preamble = $files_dir . "/preamble.php";
	//$ioncube_preamble = "/u/ioncube/ioncube-6.0 --optimize max --erase-target --preamble-file=" . $preamble . " ";
	$ioncube_preamble = "./ion_caller.php -o \"--optimize max --erase-target\" -p " . $preamble . " ";
	$avaible_modules = array(
        "MultiCurrency",		// 302
        "GreetVisitor",			// 690
        "DetailedImages",		// 700
        "Bestsellers",			// 740
        "FeaturedProducts",		// 750
        "MultiCategories",		// 760
        "WholesaleTrading",		// 1495
        "InventoryTracking",	// 1500
        "ProductOptions",		// 2000
        "Promotion",			// 3000
        "Egoods",				// 5000
        "GiftCertificates",		// 4000
        "ShowcaseOrganizer",	// 4500
	);
	$preamble_use_files = array(
		"./cart.php",
		"./admin.php",
	);	
	$exclude_enc = array(
		"./index.php",
		"./cleanup.php"
	);

	$encoded_files_list = array();
	$populate_encoded_files_list = false;
// }}}

// functions {{{

// build_cache {{{
function build_cache($list = "classes/classes.lst")
{
	$classes = file($list);
	$exclude = array(
		'log_client',
		'log_debug',
		'log_file',
		'log_null',
	);
	foreach ($classes as $class_line) {
		$classname = split(':', $class_line);
		if (!in_array($classname[0], $exclude)) { 
print $classname[0] . "...";		
			func_new($classname[0]);
print "[done]\n";			
		}	
	}
}
// }}}

// file_merge {{{
function file_merge($filename1, $filename2, $out_file = "")
{
	global $trial_dir;
	$out_fname = ($out_file == "") ? ($trial_dir . "/temp.php") : ($out_file);
	
	if (($__file = fopen($out_fname, "w")) == null) {
		return "";
	}
	fwrite($__file, join("", array_merge(file($filename1), file($filename2))));
	fclose($__file);
	return $out_fname;
}
// }}}

// is_php {{{
function is_php($file)
{
	if (is_file($file)) {
		$info = pathinfo($file);
		$ext = $info['extension'];
		return ($ext == "php");
	}
	return false;
}
// }}}

// _encode {{{
function _encode($dir, $file)
{
	global $ioncube;
	global $predistr_dir;
	global $enc_source;
	global $files_dir;
	global $ioncube_preamble;
	global $preamble_use_files;
	global $exclude_enc;

	if (in_array($dir . "/" . $file, $exclude_enc)) {
		return;
	}
	
	if (filetype($dir . "/" . $file) == 'file' && is_php($dir . "/" . $file)) {
		$unlink_file = false;
		mkdirRecursive($predistr_dir . "/" . $dir);
		print "encoding file " . $dir . "/" . $file . "...";
		if (in_array($dir . "/" . $file, $enc_source)) {
			$filename = file_merge($files_dir . "/_license.php", $dir . "/" . $file);
			$unlink_file = true;
		} else {
			$filename = $dir . "/" . $file;
		}

		global $encoded_files_list;
		global $populate_encoded_files_list;
		if ($populate_encoded_files_list) {
			if (!in_array($filename, $encoded_files_list)) {
				switch ($filename) {
					case "trial/temp.php":
					break;

					default:
						$encoded_files_list[] = $dir . "/" . $file;
					break;
				}
			}
		}

		if (in_array($filename, $preamble_use_files)) {
		    if (!defined("TEST_MODE")) {
				//passthru($ioncube_preamble . $filename . " -o " . $predistr_dir . "/" . $dir . "/" . $file);
				passthru($ioncube_preamble . " -s " . $filename . " -d " .  $predistr_dir . "/" . $dir . "/" . $file);
			} else {
				passthru("cp " . $filename . " " . $predistr_dir . "/" . $dir . "/" . $file);
			}
		} else {
		    if (!defined("TEST_MODE")) {
				//passthru($ioncube . $filename . " -o " . $predistr_dir . "/" . $dir . "/" . $file);
				passthru($ioncube . " -s " . $filename . " -d " . $predistr_dir . "/" . $dir . "/" . $file);
			} else {
				passthru("cp " . $filename . " " . $predistr_dir . "/" . $dir . "/" . $file);
			}
		}	
		print "[done]\n";
		if ($unlink_file === true) {
			@unlink($filename);
		}	
	}
}
// }}}

// encode_dir_r {{{
function encode_dir_r($dir)
{
	//global $ioncube;
	$dh = opendir($dir);
	while ( ($file = readdir($dh)) != false ) {
		if (filetype($dir . "/" . $file) == 'dir' && $file != '.' && $file != '..' && $file != 'modules') {
			encode_dir_r($dir . "/" . $file);
		} else {
			_encode($dir, $file);
		}
	}
	closedir($dh);
}
// }}}

// encode_dir {{{
function encode_dir($dir)
{
	//global $ioncube;
	$dh = opendir($dir);
	while ( ($file = readdir($dh)) != false ) {
		_encode($dir, $file);
	}
	closedir($dh);
}
// }}}

// copy_dir_files {{{
function copy_dir_files($src, $dest)
{
	if (($dh = opendir($src)) == false) {
		return;
	}
	while ( ($file = readdir($dh)) != false ) {
		if (filetype($src . "/" . $file) == 'file' && !is_file($dest . "/" . $file)) {
			copy($src . "/" . $file, $dest . "/" . $file);
		}
	}
	closedir($dh);
	
}
// }}}

// set_modules {{{
function set_modules()
{
	global $avaible_modules;
	_connect();
	$res = mysql_query("select name from xlite_modules where enabled=1") or die(mysql_error());
	$enabled_modules = array();
	while ($row = mysql_fetch_row($res)) {
		$enabled_modules[] = $row[0];	
	}	
	mysql_query("update xlite_modules set enabled=0") or die(mysql_error());
	foreach($avaible_modules as $a_module) {
		mysql_query("update xlite_modules set enabled=1 where name='" . $a_module . "';") or die(mysql_error());
	}
	return $enabled_modules;
}
// }}}

 // restore_modules {{{
function restore_modules($enabled_modules)
{
	@mysql_query("update xlite_modules set enabled=0") or die(mysql_error());
	foreach($enabled_modules as $module) {
		@mysql_query("update xlite_modules set enabled=1 where name='" . $module . "';") or die(mysql_error());
	}
	_disconnect();
}
// }}} 

// _connect() {{{
function _connect()
{
	$options_main  = parse_ini_file("./etc/config.php", true);
	if (file_exists("./etc/config.local.php")) {
		$options_local = @parse_ini_file("./etc/config.local.php", true);
		$options       = @array_merge($options_main, $options_local);
	} else {
		$options = $options_main;
	}
	mysql_connect($options["database_details"]["hostspec"], $options["database_details"]["username"], $options["database_details"]["password"]) or die(mysql_error());
	mysql_select_db($options["database_details"]["database"]) or die(mysql_error());
}
// }}}

// _disconnect() {{{
function _disconnect()
{
	mysql_close();
}
// }}}

// clean_skins {{{
function clean_skins()
{
	global $avaible_modules;
	global $predistr_dir;
	_clean($avaible_modules, $predistr_dir . "/skins/admin/en/modules");
	_clean($avaible_modules, $predistr_dir . "/skins/default/en/modules");
}
// }}}

// _clean {{{
function _clean($modules, $dir)
{
	$dh = opendir($dir);
	while ( ($file = readdir($dh)) != false ) {
		if (filetype("$dir/$file") == 'dir' && !in_array($file, $modules) && $file{0} != ".") {
			exec('rm -rf ' . $dir . "/" . $file);
		}	
	}
	closedir($dh);
}
// }}}

// create_avaible_modules_array {{{
function create_avaible_modules_array()
{
	global $avaible_modules;
	global $files_dir;

	$modules_array = '$avaible_modules = array(';
	foreach ($avaible_modules as $a_module) {
		$modules_array .= '"' . $a_module . '", ';
	}
	$modules_array .= ");";
	
	$content = join("", file($files_dir . "/license.php"));
	$content = str_replace("<AVAIBLE_MODULES_ARRAY>", $modules_array, $content);
	if (! $fh = (fopen($files_dir . "/_license.php", "w"))) {
		die ("Error: unable to create license.php");
	}
	fwrite($fh, $content);
	fclose($fh);
}
// }}}

// pre_clean {{{
function pre_clean()
{
	global $predistr_dir;
	global $dist_dir;
	
	passthru('rm -rf ' . $predistr_dir);
	passthru('rm -rf ' . $dist_dir);
	passthru('rm -rf var/run/classes/');
}
// }}}

function encode_all() // {{{
{
	global $avaible_modules;
	global $predistr_dir;
	create_avaible_modules_array();
	global $populate_encoded_files_list;
	$populate_encoded_files_list = true;
	foreach ($avaible_modules as $a_module) {
		mkdirRecursive($predistr_dir . '/classes/modules/' . $a_module);
		copyRecursive('classes/modules/' . $a_module, $predistr_dir . '/classes/modules/' . $a_module);
		encode_dir_r("classes/modules/" . $a_module);
		encode_dir_r("var/run/classes/modules/" . $a_module);
	}
	encode_dir_r("classes");
	encode_dir_r("var/run/classes");
	encode_dir_r("compat");
	encode_dir_r("includes");
	$populate_encoded_files_list = false;
	encode_dir(".");
}
// }}}

function make_install_md5()
{
	global $files_dir;
	global $encoded_files_list;
	global $predistr_dir;

	$installer_script = $files_dir . "/install.php";
    $installer_script_body = @file_get_contents($installer_script);
    if ($installer_script_body === false) {
    	die("Unable to read $installer_script\n");
    }

    if (strpos($installer_script_body, "\$essentialFiles = array();") === false) {
    	die("Unable to find pattern for patching\n");
    }

    $enc_sources = array();
    foreach($encoded_files_list as $efile) {
    	$enc_sources[$efile] = ltrim(md5(@file_get_contents($predistr_dir . "/" . $efile)), "0");
    }
    $efile = "classes/kernel/font.png";
	$enc_sources[$efile] = ltrim(md5(@file_get_contents($predistr_dir . "/" . $efile)), "0");

    $enc_sources_str = array();
    foreach($enc_sources as $efile => $efileMD5) {
    	$enc_sources_str[] = "                \"" . $efile . "\" => \"" . $efileMD5 . "\"";
    }

    $enc_sources_str = implode(",\n", $enc_sources_str);
    $enc_sources_str = "\$essentialFiles = array(\n" . $enc_sources_str . "\n            );";

    $installer_script_body = str_replace("\$essentialFiles = array();", $enc_sources_str, $installer_script_body);
    $installer_script_body = str_replace("\$is_trial = false;", "\$is_trial = true;", $installer_script_body);

    $handle = @fopen($installer_script, "wb") or die("Unable to open $installer_script\n");
    fwrite($handle, $installer_script_body);
    fclose($handle);
}

function copy_all() // {{{
{
	global $predistr_dir;
	global $files_dir;
	global $ioncube;
	global $ioncube_preamble;

	copy ("index.php", $predistr_dir . "/index.php");
	copy ("COPYRIGHT.TRIAL", $predistr_dir . "/COPYRIGHT");
	print "Copying sql..\n";
	copyRecursive("sql", $predistr_dir . "/sql");
	print "Copying etc..\n";
	copyRecursive("etc", $predistr_dir . "/etc");
	print "Copying lib..\n";
	copyRecursive("lib", $predistr_dir . "/lib");
	print "Copying lib5..\n";
	copyRecursive("lib5", $predistr_dir . "/lib5");
	print "Copying bin..\n";
	copyRecursive("bin", $predistr_dir . "/bin");
	print "Copying catalog..\n";
	copyRecursive("catalog", $predistr_dir . "/catalog");
	print "Copying files..\n";
	copyRecursive("files", $predistr_dir . "/files");
	print "Copying images..\n";
	copyRecursive("images", $predistr_dir . "/images");
	print "Copying skins..\n";
	copyRecursive("skins", $predistr_dir . "/skins");
	clean_skins();
	print "Copying schemas..\n";
	copyRecursive("schemas", $predistr_dir . "/schemas");
	print "Copying quickstart..\n";
	copyRecursive("quickstart", $predistr_dir . "/quickstart");
	print "Copying tools..\n";
	copyRecursive("tools", $predistr_dir . "/tools");

	copy("https_check.php", $predistr_dir . "/https_check.php");

	copy_dir_files(".", $predistr_dir);
	copy("classes/classes.lst", $predistr_dir . "/classes/classes.lst");
	copy("classes/kernel/font.png", $predistr_dir . "/classes/kernel/font.png");

	copy ($files_dir . "/Makefile", $predistr_dir . "/Makefile");

	file_merge($files_dir . "/_license.php", $files_dir . "/decoration.php", $files_dir . "/_decoration.php");
	if (!defined("TEST_MODE")) {
		//passthru($ioncube . $files_dir . "/_decoration.php -o " . $predistr_dir . "/includes/decoration.php");
		passthru($ioncube . " -s " . $files_dir . "/_decoration.php " . " -d " . $predistr_dir . "/includes/decoration.php");
	} else {
		passthru("cp " . $files_dir . "/_decoration.php " . $predistr_dir . "/includes/decoration.php");
	}

	unlink($predistr_dir . "/skins/admin/en/maintenance/body.tpl");
	copy($files_dir . "/maintence_body.tpl", $predistr_dir . "/skins/admin/en/maintenance/body.tpl");
	unlink($predistr_dir . "/skins/admin/en/modules.tpl");
	copy($files_dir . "/modules.tpl", $predistr_dir . "/skins/admin/en/modules.tpl");
	unlink($predistr_dir . "/skins/admin/en/modules_body.tpl");
	copy($files_dir . "/modules_body.tpl", $predistr_dir . "/skins/admin/en/modules_body.tpl");
	unlink($predistr_dir . "/skins/admin/en/main.tpl");
	copy($files_dir . "/main.tpl", $predistr_dir . "/skins/admin/en/main.tpl");
	unlink($predistr_dir . "/loader.php");
	copy($files_dir . "/loader.php", $predistr_dir . "/loader.php");
	if (!defined("TEST_MODE")) {
		//passthru($ioncube . "var/run/classes/modules/ProductOptions/kernel/ProductOption.php -o " . $predistr_dir . "/var/run/classes/modules/ProductOptions/kernel/ProductOption.php");
		passthru($ioncube . " -s " . "var/run/classes/modules/ProductOptions/kernel/ProductOption.php " . " -d " . $predistr_dir . "/var/run/classes/modules/ProductOptions/kernel/ProductOption.php");
	} else {
		passthru("cp " . "var/run/classes/modules/ProductOptions/kernel/ProductOption.php " . $predistr_dir . "/var/run/classes/modules/ProductOptions/kernel/ProductOption.php");
	}
	update_lc_image("$predistr_dir/skins/admin/en/menu.tpl", "http://www.litecommerce.com/img/lc.gif", "http://www.litecommerce.com/img/lc_trial.gif");

	print "encoding file " . $files_dir . "/install.php" . "...";
	copy("./install.php", $files_dir . "/install.php");
	make_install_md5();
	if (!defined("TEST_MODE")) {
		//passthru($ioncube_preamble . $files_dir . "/install.php -o " . $predistr_dir . "/install.php");
		passthru($ioncube_preamble . " -s " . $files_dir . "/install.php " . " -d " . $predistr_dir . "/install.php");
	} else {
		passthru("cp " . $files_dir . "/install.php " . $predistr_dir . "/install.php");
	}
	print "[done]\n";
}
// }}}

function post_clean() // {{{
{
	global $files_dir;
	global $predistr_dir;
	//unlink($files_dir . "/_license.php");
	unlink("$predistr_dir/cleanup.php");
	//unlink("$files_dir/preamble.php");
	//unlink("$files_dir/_decoration.php");
	//passthru('rm -rf var/run/classes');
}
// }}}

function make_distrib() // {{{
{
	chdir($predistr_dir);
	passthru("make x-lite");
	passthru("make installer");
	passthru("make post-clean");
}
// }}}

function create_preamble() // {{{
{
	global $preamble;
	$preamble_file_content = join("", file('tools/ioncube/loader.php'));
	$preamble_file_content = str_replace("<?php", "", $preamble_file_content);
	$preamble_file_content = str_replace("?>", "", $preamble_file_content);

	$fh = fopen($preamble, "w");
	if (!$fh) {
		die ("Could not open preamble file for writing");
	}
	fwrite($fh, $preamble_file_content);
	fclose($fh); 
}
// }}}

function update_lc_image($filename, $old_image, $new_image) // {{{
{
	$content = join("", file($filename));
	$content = str_replace($old_image, $new_image, $content);
	unlink($filename);
	$fh = fopen($filename, "w");
	fwrite($fh, $content);
	fclose($fh);
}
// }}}

// }}}

// script body {{{
include_once "includes/prepend.php";
pre_clean();

$enabled_modules = set_modules();
create_preamble();

$_SERVER["HTTP_HOST"] = "crtdev.local";

print "\n\nBuilding classes cache for Admin's zone...\n\n";
// generating classes cache for admin.php {{{
$GLOBALS["XLITE_SELF"] = "admin.php";
$xlite =& func_new("XLite");
$xlite->set("adminZone", true);
$layout =& func_get_instance("Layout");
$layout->set("skin", "admin");
$xlite->initFromGlobals();
build_cache();
foreach ($avaible_modules as $a_module) {
	build_cache('classes/modules/' . $a_module . '/classes.lst');
}
// }}} 
print "[done]\n";

if (isset($xlite)) {
	unset ($xlite);
}
if (isset($instances)) {
	unset ($instances);
}
if (isset($xlite_defined_classes)) {
	unset ($xlite_defined_classes); 
}
if (isset($xlite_class_deps)) {
	unset ($xlite_class_deps); 
}
if (isset($xlite_class_decorators)) {
	unset ($xlite_class_decorators); 
}
if (isset($xlite_class_files_state)) {
	unset ($xlite_class_files_state);
}

print "\n\nBuilding classes cache for Customer's zone...\n\n";
// generating classes cache for cart.php {{{
$GLOBALS["XLITE_SELF"] = "cart.php";
$xlite =& func_new("XLite");
$xlite->set("adminZone", false);
$xlite->initFromGlobals();
foreach ($avaible_modules as $a_module) {
	build_cache('classes/modules/' . $a_module . '/classes.lst');
}
build_cache();
// }}}
print "[done]\n";

restore_modules($enabled_modules);
encode_all();
copy_all();
post_clean();

// }}}
?>

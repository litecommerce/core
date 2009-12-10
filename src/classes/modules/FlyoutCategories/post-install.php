<?php

/*
* FlyoutCategories module post-install script
*
* @version $Id: post-install.php,v 1.1 2006/12/08 14:23:05 osipov Exp $
*/

$cfg =& func_new("Config");
$cfg->createOption("FlyoutCategories", "scheme", 0);

?>

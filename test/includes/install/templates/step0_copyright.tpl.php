<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/*
 * Output a Shows Terms & Conditions page body
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}

?>

<CENTER>
<BR><BR><BR>

<?php

message('You are about to install LiteCommerce shopping system.<BR>This installation wizard will guide you through the installation process.')

?>

<BR><BR><BR>

<?php
    
if (COPYRIGHT_EXISTS) {

?>

<TEXTAREA name="copyright" cols="80" rows="22" style="font-family: monospace; FONT-SIZE: 9pt; border: 1px solid #888888; border-right-width: 0px;" readonly>
<?php
    readfile(COPYRIGHT_FILE);
?>
</TEXTAREA>

<P>
<?php

    if (!is_null(get_authcode())) {

?>

<TABLE border=0>
<TR>
 <TD><input type=radio name="params[force_current]" value="<?php print get_step("check_cfg") ?>">
 <TD><b>Perform new installation</b></TD>
</TR>
<TR>
 <TD><input type=radio name="params[force_current]" value="<?php print get_step("cfg_install_dirs") ?>" checked></TD>
 <TD><b>Re-install skin files</b></TD>
</TR>
<TR>
 <TD><input type=radio name="params[force_current]" value="<?php print get_step("cfg_create_admin") ?>"></TD>
 <TD><b>Configure primary administrator account</b></TD>
</TR>
<TR><TD colspan=2><b>Auth code: </b><INPUT type=text name="params[auth_code]" size=20><BR><FONT size=1>( required for protection from unauthorized<BR> use of installation script )</FONT></TD></TR>
</TABLE>
<P>

<?php

    } else {

?>

<input type=hidden name="params[new_installation]" value="<?php print get_step("check_cfg") ?>">

<?php

    }

?>

<label><INPUT type=checkbox name="agree" onClick="this.blur(); setNextButtonDisabled(!this.checked);" /> I accept the License Agreement</label>

<?php

} else {

    $error = true;

?>

<P class="install_error">Could not find license agreement file.<br>Aborting installation.</P>

<?php

}

?>

<BR><BR>

</CENTER>

<BR>


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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
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

    
if (COPYRIGHT_EXISTS) {

?>

<div id="copyright_notice">

<?php

    ob_start();

    require COPYRIGHT_FILE;

    $tmp = ob_get_contents();

    ob_end_clean();

    echo nl2br(htmlspecialchars($tmp));

?>

</div>

<?php

    if (!is_null(get_authcode())) {

?>

<input type="hidden" name="params[force_current]" value="<?php print get_step("check_cfg") ?>" />

<br />

<table align="center">

    <tr>
        <td>
            <span id="auth-code" class="field-label"><?php echo xtr('Auth code'); ?>:</span>
            <input type="text" name="params[auth_code]" size="10" title="<?php echo xtr('Auth code'); ?>" />
        </td>

        <td class="field-notice">
            <span class="field-notice"><?php echo xtr('Prevents unauthorized use of installation script'); ?></span>
        </td>

    </tr>

</table>

<?php

    } else {

?>

<input type="hidden" name="params[new_installation]" value="<?php print get_step("check_cfg") ?>" />

<?php

    }

?>

<br />

<span class="checkbox-field">
<input type="checkbox" id="agree" name="agree" onclick="javascript:setNextButtonDisabled(!this.checked);" />
<label for="agree"><?php echo xtr('I accept the License Agreement'); ?></label>
</span>

<?php

} else {

    $error = true;

?>

    <div class="install_error"><?php echo xtr('Could not find license agreement file.<br />Aborting installation.'); ?></div>

<?php

}

?>



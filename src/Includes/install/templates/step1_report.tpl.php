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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */


/**
 * LiteCommerce (standalone edition) web installation wizard: Report page
 *
 * @package LiteCommerce
 */

if (!defined('XLITE_INSTALL_MODE')) {
	die('Incorrect call of the script. Stopped.');
}


global $requirements;

if (!empty($requirements) && is_array($requirements)) {

    $report = make_check_report($requirements);

?>

<div id="report-layer" class="report-layer" style="display:none;">

    <div id="report-window" class="report-window">

<a class="report-close" href="#" onclick="javascript: document.getElementById('report-layer').style.display='none'; return false;"><img src="<?php echo $skinsDir; ?>images/spacer.gif" width="10" height="10" border="0" alt="" /><span class="report-close" style="display: none;">close</span></a>


<form method="post" name="report_form" action="https://secure.qtmsoft.com/customer.php">

<input type="hidden" name="target" value="customer_info" />
<input type="hidden" name="action" value="install_feedback_report" />
<input type="hidden" name="product_type" value="LC3" />

<div class="report-title"><?php echo xtr('Technical problems report'); ?></div>

<br />
<br />

<?php echo xtr('ask_send_report_text'); ?>

<br />
<br />

<textarea name="report" class="report-details" rows="5" cols="70" readonly="readonly"><?php echo $report; ?></textarea>

<br />
<br />

<div class="section-title"><?php echo xtr('Email (optional):'); ?></div>

<input type="text" name="user_email" value="" size="30" />

<br />
<br />

<div class="section-title"><?php echo xtr('Additional comments'); ?></div>

<textarea name="user_note" class="report-notes" rows="4" cols="70"></textarea>

<br />
<br />

<div style="text-align: center;">
    <input type="submit" value="<?php echo xtr('Send report'); ?>" />
</div>

</form>

</div>

<div class="clear"></div>

</div>

<?php

}

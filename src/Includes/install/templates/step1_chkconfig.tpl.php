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
 * Output a configuration checking page body
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}

function get_lc_loopback_description()
{
    return xtr('lc_loopback_description', array(':host' => $_SERVER["HTTP_HOST"]));
}

function get_lc_php_version_description()
{
    return xtr('lc_php_version_description', array(':phpver' => phpversion()));
}

function get_lc_php_disable_functions_description()
{
    return xtr('lc_php_disable_functions_description');
}

function get_lc_php_memory_limit_description()
{
    return xtr('lc_php_memory_limit_description');
}

function get_lc_php_mysql_support_description()
{
    return xtr('lc_php_mysql_support_description');
}

function get_lc_php_pdo_mysql_description()
{
    return xtr('lc_php_pdo_mysql_description');
}

function get_lc_file_permissions_description($requirements)
{
    return $requirements['lc_file_permissions']['description'];
}

function get_lc_php_file_uploads_description()
{
    return xtr('lc_php_file_uploads_description');
}

function get_lc_php_upload_max_filesize_description()
{
    return xtr('lc_php_upload_max_filesize_description');
}

function get_lc_mem_allocation_description()
{
    return xtr('lc_mem_allocation_description');
}

function get_lc_recursion_test_description()
{
    return xtr('lc_recursion_test_description');
}

function get_lc_php_gdlib_description()
{
    return xtr('lc_php_gdlib_description');
}

function get_lc_php_phar_description()
{
    return xtr('lc_php_phar_description');
}

function get_lc_https_bouncer_description()
{
    return xtr('lc_https_bouncer_description');
}

function get_lc_xml_support_description()
{
    return xtr('lc_xml_support_description');
}

function get_lc_docblocks_support_description()
{
    return xtr('lc_docblocks_support_description');
}

?>

<div class="requirements-report">

<div class="requirements-list">

<?php

$reqsNotes = array();

// Go through steps list...
foreach ($steps as $stepData) {

    // Index for colouring table rows
    $colorNumber = '1';

?>

    <div class="section-title"><?php echo $stepData['title']; ?></div>

<?php

    // Go through requirements list of current step...
    foreach ($stepData['requirements'] as $reqName) {

        $reqData = $requirements[$reqName];
        $errorsFound = ($errorsFound || (!$reqData['status'] && $reqData['critical']));
        $warningsFound = ($warningsFound || (!$reqData['status'] && !$reqData['critical']));

?>

    <div class="list-row color-<?php echo $colorNumber; ?>">
        <div class="field-left"><?php echo $reqData['title']; ?> ... <?php echo $reqData['value']; ?></div>
        <div class="field-right">
<?php
        
        echo isset($reqData['skipped']) ? status_skipped() : status($reqData['status'], $reqName);

        if (!$reqData['status']) {
?>

            <img id="failed-image-<?php echo $reqName; ?>" class="link-expanded" style="display: none;" src="<?php echo $skinsDir; ?>images/arrow_red.png" alt="" />

<?php
        }
?>
        </div>
    </div>

<?php

        $colorNumber = ('2' === $colorNumber) ? '1' : '2';

        // Prepare data for requirement notes displaying
        $label = $reqName . '_description';
        $labelText = null;
        $funcname = 'get_' . $label;

        if (function_exists($funcname)) {
            $labelText = $funcname($requirements);
        
        } else {
            
            $labelText = xtr($label);
            
            if ($label === $labelText) {
                $labelText = null;
            }
        }

        if (!is_null($labelText)) {
            $reqsNotes[] = array(
                'reqname' => $reqName,
                'title'   => $stepData['error_msg'],
                'text'    => $labelText,
            );
        }

    } // foreach ($stepData['requirements']...

} // foreach ($steps...

?>


</div>

<div class="requirements-notes">

<div id="detailsElement"></div>

<div id="status-report" class="status-report-box" style="display: none;">

    <div class="status-report-box-text">
        <?php echo xtr('requirements_failed_text'); ?>
    </div>

    <input type="button" class="small-button" value="<?php echo xtr('Report'); ?>" onclick="javascript: document.getElementById('report-layer').style.display = 'block';" />

</div>

<?php

foreach ($reqsNotes as $reqNote) {

?>

    <div id="<?php echo $reqNote['reqname']; ?>" style="display: none">
        <div class="error-title"><?php echo $reqNote['title']; ?></div>
        <div class="error-text"><?php echo $reqNote['text']; ?></div>
    </div>

<?php

}

?>

<div class="requirements-success" style="display: none;" id="test_passed_icon">
   <img class="requirements-success-image" src="<?php echo $skinsDir; ?>images/passed_icon.png" border="0" alt="" />
   <br />
   Passed
</div>

</div>

<div class="clear"></div>

</div>


<script type="text/javascript">
    var first_code = '<?php echo ($first_error) ? $first_error : ''; ?>';
    showDetails(first_code);
</script>

<?php
    
    if (!$requirements['lc_file_permissions']['status']) {

?>

<P>
<?php $requirements['lc_file_permissions']['description'] ?>
</P>

<?php

    }

	// Save report to file if errors found
	if ($errorsFound) {

?>
        
        <script type="text/javascript">visibleBox("status-report", true);</script>

<?php

	}

    if (!$errorsFound && $warningsFound) {

?>

<div class="requirements-warning-text"><?php echo xtr('requirement_warning_text'); ?></div>

<span class="checkbox-field">
    <input type="checkbox" id="continue" onclick="javascript: setNextButtonDisabled(!this.checked);" />
    <label for="continue"><?php echo xtr('Yes, I want to continue the installation.'); ?></label>
</span>

<?php 
    }
?>

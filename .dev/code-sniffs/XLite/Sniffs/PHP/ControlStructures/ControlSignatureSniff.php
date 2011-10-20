<?php
/**
 * Verifies that control statements conform to their coding standards.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_Standards_AbstractPatternSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractPatternSniff not found');
}

/**
 * Verifies that control statements conform to their coding standards.
 *
 * TODO - переделать для детализации ошибок по каждому требованию, а не по группе
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.2.0RC1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_PHP_ControlStructures_ControlSignatureSniff extends XLite_AbstractPatternSniff
{

	protected $patReqCodes = array(
        'do {EOL...} while (...);EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6'),
        'while (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6'),
        'for (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6'),
        'if (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6', 'REQ.PHP.2.5.8', 'REQ.PHP.2.5.9', 'REQ.PHP.2.5.10', 'REQ.PHP.2.5.11'),
        'foreach (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6'),
        '} else if (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6', 'REQ.PHP.2.5.8', 'REQ.PHP.2.5.9', 'REQ.PHP.2.5.10', 'REQ.PHP.2.5.11'),
        '} elseif (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6', 'REQ.PHP.2.5.8', 'REQ.PHP.2.5.9', 'REQ.PHP.2.5.10', 'REQ.PHP.2.5.11'),
        '} else {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6', 'REQ.PHP.2.5.8', 'REQ.PHP.2.5.9', 'REQ.PHP.2.5.10', 'REQ.PHP.2.5.11'),
        'do {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6'),
		'switch (...) {EOL' => array('REQ.PHP.2.5.1', 'REQ.PHP.2.5.2', 'REQ.PHP.2.5.4', 'REQ.PHP.2.5.5', 'REQ.PHP.2.5.6')
	);

    /**
     * Constructs a XLite_Sniffs_PHP_ControlStructures_ControlSignatureSniff.
     */
    public function __construct()
    {
        parent::__construct(true);

    }//end __construct()


    /**
     * Returns the patterns that this test wishes to verify.
     *
     * @return array(string)
     */
    protected function getPatterns()
    {
        return array(
                'do {EOL...} while (...);EOL',
                'while (...) {EOL',
                'for (...) {EOL',
                'if (...) {EOL',
                'foreach (...) {EOL',
                '} else if (...) {EOL',
                '} elseif (...) {EOL',
                '} else {EOL',
                'do {EOL',
               );

    }//end getPatterns()


}//end class

?>

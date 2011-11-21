<?php
/**
 * XLite_Sniffs_PHP_Formatting_EvalSniff.
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

/**
 * XLite_Sniffs_PHP_Formatting_EvalSniff.
 *
 * Verifies that inline control statements are not present.
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
class XLite_Sniffs_PHP_Formatting_EvalSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_EVAL, T_STRING);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$stackPtr]['code'] === T_EVAL) {
	        $phpcsFile->addError(
    	        $this->getReqPrefix('REQ.PHP.3.11.1')
        	    . 'Использование eval() запрещено',
            	$stackPtr
	        );

		} elseif ($tokens[$stackPtr]['code'] === T_STRING) {
			if ($tokens[$stackPtr]['content'] === 'create_function') {

	            $phpcsFile->addError(
            	    $this->getReqPrefix('REQ.PHP.3.11.2')
        	        . 'Использование create_function() запрещено',
    	            $stackPtr
	            );
			}
		}
    }

}

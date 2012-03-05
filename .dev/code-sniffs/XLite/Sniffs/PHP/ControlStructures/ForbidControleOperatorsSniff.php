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
class XLite_Sniffs_PHP_ControlStructures_ForbidControleOperatorsSniff extends XLite_ReqCodesSniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CONTINUE, T_BREAK);

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


		if ($tokens[$stackPtr]['code'] == T_CONTINUE) {
            $phpcsFile->addWarning(
                $this->getReqPrefix('WRN.PHP.2.5.4')
                . 'Использование ' . $tokens[$stackPtr]['content'] . ' не рекомендовано',
                $stackPtr
            );

		} elseif ($tokens[$stackPtr]['code'] == T_BREAK && T_SWITCH != end($tokens[$stackPtr]['conditions'])) {
            $phpcsFile->addWarning(
   	            $this->getReqPrefix('WRN.PHP.2.5.4')
       	        . 'Использование ' . $tokens[$stackPtr]['content'] . ' не рекомендовано',
           	    $stackPtr
           );
		}
    }

}

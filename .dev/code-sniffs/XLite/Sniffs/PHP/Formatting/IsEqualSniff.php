<?php
/**
 * XLite_Sniffs_PHP_Formatting_IsEqualSniff.
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
 * XLite_Sniffs_PHP_Formatting_IsEqualSniff.
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
class XLite_Sniffs_PHP_Formatting_IsEqualSniff extends XLite_ReqCodesSniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
			T_IS_EQUAL, T_IS_IDENTICAL,
			T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL,
			T_GREATER_THAN, T_LESS_THAN,
			T_IS_SMALLER_OR_EQUAL, T_IS_GREATER_OR_EQUAL
		);

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

		$next = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true);

		if ($tokens[$next]['code'] === T_VARIABLE)
			return;

		$prev = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
		if ($tokens[$prev]['code'] !== T_VARIABLE)
			return;

        $phpcsFile->addWarning(
	        $this->getReqPrefix('WRN.PHP.2.8.2') . 'При сравнении всегда ставьте константы слева',
            $stackPtr
        );
    }

}//end class

?>

<?php
/**
 * XLite_Sniffs_PHP_ControlStructures_TernarySniff.
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
 * XLite_Sniffs_PHP_ControlStructures_TernarySniff.
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
class XLite_Sniffs_PHP_ControlStructures_TernarySniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_INLINE_THEN
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

		$flast = end($tokens[$stackPtr]['conditions']);
		$slast = $phpcsFile->findPrevious(T_SEMICOLON, $stackPtr - 1, $flast);
		$last = max($flast, $slast);

		$pos = $phpcsFile->findPrevious(array(T_EQUAL, T_RETURN), $stackPtr, $last);
		if (!$pos)
			return;

		$bool = $phpcsFile->findNext(
			array(T_BOOLEAN_AND, T_BOOLEAN_OR, T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR),
			$pos + 1,
			$stackPtr - 1
		);

		if (!$bool)
			return;

		$cp = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, $pos - 1, true);
		if ($cp && $tokens[$cp]['code'] === T_CLOSE_PARENTHESIS && $tokens[$tokens[$cp]['parenthesis_opener'] - 1]['code'] !== T_STRING)
			return;

		$phpcsFile->addWarning(
			$this->getReqPrefix('WRN.PHP.2.5.1') . 'Рекомендуется условие тернарных операторов заключать в скобки, тем самым отделяя его от остального кода, если условия является составным',
			$stackPtr
		);

    }//end process()


}//end class

?>

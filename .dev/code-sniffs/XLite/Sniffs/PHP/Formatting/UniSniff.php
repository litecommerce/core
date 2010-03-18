<?php
/**
 * XLite_Sniffs_PHP_Formatting_UniSniff.
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
 * XLite_Sniffs_PHP_Formatting_UniSniff.
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
class XLite_Sniffs_PHP_Formatting_UniSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
   {
        return array(T_INC, T_DEC);

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

		$prev = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 2, null, true);

		if (
			($tokens[$prev]['line'] === $tokens[$stackPtr]['line'] || $tokens[$stackPtr + 1]['code'] !== T_SEMICOLON)
			&& $tokens[$prev]['code'] != T_OBJECT_OPERATOR
			&& $tokens[$prev]['code'] != T_DOUBLE_COLON
		) {
			$isFor = false;
			if (isset($tokens[$stackPtr]['nested_parenthesis'])) {
				end($tokens[$stackPtr]['nested_parenthesis']);
				$k = key($tokens[$stackPtr]['nested_parenthesis']);
				$isFor = isset($tokens[$k]['parenthesis_owner']) && $tokens[$tokens[$k]['parenthesis_owner']]['code'] == T_FOR;
			}

			if (!$isFor) {
	            $phpcsFile->addError(
    	            $this->getReqPrefix('REQ.PHP.3.9.1') . 'Унарные операторы должны вызываться на отдельной строчке кода',
        	        $stackPtr
            	);
			}
		}

    }

}//end class

?>

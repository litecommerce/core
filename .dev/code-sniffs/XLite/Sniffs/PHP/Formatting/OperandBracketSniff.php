<?php
/**
 * XLite_Sniffs_PHP_Formatting_OperandBracketSniff.
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
 * XLite_Sniffs_PHP_Formatting_OperandBracketSniff.
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
class XLite_Sniffs_PHP_Formatting_OperandBracketSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_ECHO,
				T_PRINT,
				T_EXIT
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

		if ($tokens[$stackPtr + 1]['code'] !== T_WHITESPACE || $tokens[$stackPtr + 1]['content'] !== ' ') {
			$phpcsFile->addError(
				$this->getReqPrefix('REQ.PHP.2.6.8')
				. 'Оператор "' .$tokens[$stackPtr]['content']. '" должен иметь 1 пробел между ним и открывающейся скобкой',
				$stackPtr
			);

		} elseif ($tokens[$stackPtr + 2]['code'] !== T_OPEN_PARENTHESIS) {

			$phpcsFile->addError(
				$this->getReqPrefix('REQ.PHP.2.6.1')
				. 'Аргументы функции "' .$tokens[$stackPtr]['content']. '" должены быть обрамлены скобками',
				$stackPtr
			);
		}

    }//end process()

}//end class

?>

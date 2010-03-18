<?php
/**
 * XLite_Sniffs_PHP_ControlStructures_InlineControlStructureSniff.
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

if (class_exists('Generic_Sniffs_ControlStructures_InlineControlStructureSniff', true) === false) {
    $error = 'Class Generic_Sniffs_ControlStructures_InlineControlStructureSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * XLite_Sniffs_PHP_ControlStructures_InlineControlStructureSniff.
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
class XLite_Sniffs_PHP_ControlStructures_InlineControlStructureSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_IF,
                T_ELSE,
				T_ELSEIF,
                T_FOREACH,
                T_WHILE,
                T_DO,
                T_SWITCH,
                T_FOR,
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

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            // Ignore the ELSE in ELSE IF. We'll process the IF part later.
            if (($tokens[$stackPtr]['code'] === T_ELSE) && ($tokens[($stackPtr + 2)]['code'] === T_IF)) {
				$phpcsFile->addError($this->getReqPrefix('REQ.PHP.2.5.11') . '"else if" block is found', $stackPtr);
                return;
            }

            if ($tokens[$stackPtr]['code'] === T_WHILE) {
                // This could be from a DO WHILE, which doesn't have an opening brace.
                $lastContent = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                if ($tokens[$lastContent]['code'] === T_CLOSE_CURLY_BRACKET) {
                    $brace = $tokens[$lastContent];
                    if (isset($brace['scope_condition']) === true) {
                        $condition = $tokens[$brace['scope_condition']];
                        if ($condition['code'] === T_DO) {
                            return;
                        }
                    }
                }
            }

            // This is a control structure without an opening brace,
            // so it is an inline statement.
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.2.5.6') . 'Inline control structures are not allowed', $stackPtr);

        } elseif (in_array($tokens[$stackPtr]['code'], array(T_IF, T_ELSEIF, T_FOREACH, T_SWITCH))) {

			$hasEqual = $phpcsFile->findNext(T_EQUAL, $tokens[$stackPtr]['parenthesis_opener'], $tokens[$stackPtr]['parenthesis_closer']);
			if ($hasEqual !== false) {
				$phpcsFile->addError($this->getReqPrefix('REQ.PHP.2.5.7') . 'Запрещено использовать присваивание в управляющий структурах', $stackPtr);
			}
        }

    }//end process()


}//end class

?>

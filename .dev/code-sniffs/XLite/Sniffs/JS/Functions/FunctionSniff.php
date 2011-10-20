<?php

/**
 * XLite_Sniffs_JS_Functions_FunctionSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * XLite_Sniffs_JS_Functions_FunctionSniff.
 *
 * Checks functions definitions
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_JS_Functions_FunctionSniff extends XLite_ReqCodesSniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('JS');


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_FUNCTION);

    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
		$tokens = $phpcsFile->getTokens();

		$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

		$parenthesis_opener = $tokens[$stackPtr]["parenthesis_opener"];

		$error = false;

		if ($next == $parenthesis_opener) {
			// "function (some) { " pattern
			if ($tokens[$stackPtr]["column"] + 9 != $tokens[$next]["column"]) {
				$error = "There must be only one space between '(' and 'function' keyword";
			}

		} else {
			// "function func(some) { " pattern
			if ($tokens[$next + 1]["type"] != "T_OPEN_PARENTHESIS") {
				$error = "There must be no space between name of function and '(' ";
			}

		}

		if ($error === false && isset($tokens[$stackPtr]["scope_opener"])) {
			$scope_opener = $tokens[$stackPtr]["scope_opener"];
			$parenthesis_closer = $tokens[$stackPtr]["parenthesis_closer"];
			if ($tokens[$scope_opener]["line"] != $tokens[$stackPtr]["line"]) {
				$error = "'{' must be on same line with 'function' keyword";
			} elseif ($tokens[$scope_opener]["column"] != $tokens[$parenthesis_closer]["column"] + 2) {
				$error = "There must be only one space between ')' and '{'";
			}
		}

		if ($error !== false) {
			$phpcsFile->addError($this->getReqPrefix("REQ.JS.3.9") . $error, $stackPtr);
		}

    }//end process()

}//end class

?>

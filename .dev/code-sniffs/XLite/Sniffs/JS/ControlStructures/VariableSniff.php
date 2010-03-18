<?php

/**
 * XLite_Sniffs_JS_ControlStructures_VariableSniff.
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
 * XLite_Sniffs_JS_ControlStructures_VariableSniff.
 *
 * Check JS variables. 
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_JS_ControlStructures_VariableSniff extends XLite_ReqCodesSniff
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
        return array(T_VAR);

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

		$next_var = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true);

 		if ($next_var && $tokens[$next_var]["type"] == "T_STRING") {
			$name_var = $tokens[$next_var]["content"];
			$var_line = $tokens[$next_var]["line"];
			$next = $phpcsFile->findNext(T_WHITESPACE, $next_var + 1, null, true);
			$error = false;

			$only_one = "'Var' statement must contain only one variable";

			if ($tokens[$stackPtr]["line"] != $var_line) {
				$error = "'Var' statement must be on one line";

			} elseif ($tokens[$next]["type"] == "T_SEMICOLON") {
				$error = "Variable '$name_var' must be initialized";

			} elseif ($tokens[$next]["type"] == "T_COMMA") {
				$error = $only_one;

			}

			if ($error === false) {
				do {
					$next_var = $phpcsFile->findNext(T_WHITESPACE, $next_var + 1, null, true);
					$token = $tokens[$next_var];
				} while ($token["line"] == $var_line && !in_array($token["type"], array("T_COMMA", "T_SEMICOLON", "T_OPEN_SQUARE_BRACKET")));
				if ($token["type"] == "T_COMMA") {
					$error = $only_one;
				}
			}

			if ($error !== false) {
				$phpcsFile->addError($this->getReqPrefix('REQ.JS.3.7') . $error, $stackPtr);
			}
		}

		return;

    }//end process()

}//end class

?>

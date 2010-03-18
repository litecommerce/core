<?php

/**
 * XLite_Sniffs_JS_RestrictedSnif.
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
 * XLite_Sniffs_JS_RestrictedSniff.
 *
 * Defines several restricted statements
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_JS_RestrictedSniff extends XLite_ReqCodesSniff
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
        return array(T_STRING, T_LABEL);

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

		$restricted_statements = array (
			"with" => "REQ.JS.3.10.3",
			"eval" => "REQ.JS.3.13.5",
		);

		$restricted_statements_new = array (
            "Function" => "REQ.JS.3.13.5",
			"Object" => "REQ.JS.3.13.1",
			"Array" => "REQ.JS.3.13.1"
        );

		$recommendations = array (
			"Function" => "function",
            "Object" => "{}",
            "Array" => "[]"
		);

		$content = strtolower($tokens[$stackPtr]["content"]);

		$error = false;

		if ($tokens[$stackPtr]["type"] == "T_LABEL") {
			$error = $this->getReqPrefix("REQ.JS.3.10.3") . "Labels are restricted";
        }

		if ($content == "new" && $error === false) {
			$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
			if ($next !== false && in_array($tokens[$next]["content"], array_keys($restricted_statements_new))) {
				$statement = $tokens[$next]["content"];
				$recommendation = $recommendations[$statement];
				$error = $this->getReqPrefix($restricted_statements_new[$statement]) . "'new $statement' statement is not allowed. Use '$recommendation' instead"; 
			}

		} else {
			if (in_array($content, array_keys($restricted_statements))) {
				$error = $this->getReqPrefix($restricted_statements[$content]) . "'$content' statement is not allowed";
			}

		}

		if ($error !== false) {
			$phpcsFile->addError($error, $stackPtr);
		}

    }//end process()

}//end class

?>

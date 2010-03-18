<?php

/**
 * XLite_Sniffs_JS_ControlStructures_ReturnSniff.
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
 * XLite_Sniffs_JS_ControlStructures_ReturnSniff.
 *
 * Check "return" operator. 
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_JS_ControlStructures_ReturnSniff extends XLite_ReqCodesSniff
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
        return array(T_RETURN);

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
/*

must not be like 'return(<something>);' pattern

must have empty line before previous operator if not only in the code block

*/
		$tokens = $phpcsFile->getTokens();

		// Return must have preceeding empty line if it is not only operator in the current block. 

		if (empty($tokens[$stackPtr]["conditions"])) {
			$phpcsFile->addError($this->getReqPrefix('REQ.JS.3.10.4') . "Return operator was used in global context. Must be used in functions", $stackPtr);

			return;
		}

		$last_block = end($tokens[$stackPtr]["conditions"]);
		$last_block_pos = key($tokens[$stackPtr]["conditions"]);
		$go_to_next_step = false;

		// Check if "short if" structure is used (without curly brackets {})
		$short_if_using = $phpcsFile->findPrevious(T_IF, ($stackPtr - 1), $last_block_pos + 1, false);

		if ($short_if_using && empty($tokens[$short_if_using]["scope_opener"])) {
			$more_code = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), $tokens[$short_if_using]["parenthesis_closer"] + 1, true);
			if (!$more_code) {
				// "return" operator is in the "short if" structure - it is checked in another verification class
				$go_to_next_step = true;
			}
		}

		if (!$go_to_next_step && !empty($tokens[$last_block_pos]["scope_opener"])) {
			// Check if it is the only operator in the block
			$more_code = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), $tokens[$last_block_pos]["scope_opener"] + 1, true);

			if (!$more_code) {
				if ($tokens[$tokens[$last_block_pos]["scope_opener"]]["line"] + 1 !== $tokens[$stackPtr]["line"])
					$phpcsFile->addError($this->getReqPrefix('REQ.JS.3.10.4') . "Return operator must be on the next line after curly open bracket if it is the only operator in the code block", $stackPtr);
			} else {
				if ($tokens[$more_code]["line"] + 2 !== $tokens[$stackPtr]["line"]) {
					$phpcsFile->addError($this->getReqPrefix('REQ.JS.3.10.4') . "Return operator must have one empty line if it is not the only operator in the code block", $stackPtr);
				}
			}
		}

		// Check if return starts and ends with ")"
		$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

		if ($next && $tokens[$next]["type"] == "T_OPEN_PARENTHESIS") {
			$close_pos = $tokens[$next]["parenthesis_closer"] + 1;
			$next_operator_pos = $phpcsFile->findNext(T_WHITESPACE, $close_pos, null, true);
			if ($next_operator_pos) {
				$allow_operators = array("+", "-", "/", "*", ".", "&", "&&", "|", "||", "==", "!=", "===", "!==", "<", ">", "<=", ">=", ">>", ">>>", "<<", "<<<");
				if (!in_array($tokens[$next_operator_pos]["content"], $allow_operators)) {
					$phpcsFile->addError($this->getReqPrefix('REQ.JS.3.10.4') . "Redundant parentheses in return operator", $stackPtr);
				}
			}
		}

		return;

    }//end process()

}//end class

?>

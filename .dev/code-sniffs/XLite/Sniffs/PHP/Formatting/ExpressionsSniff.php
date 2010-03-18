<?php
/**
 * XLite_Sniffs_PHP_Formatting_ExpressionsSniff.
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
 * XLite_Sniffs_PHP_Formatting_ExpressionsSniff.
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
class XLite_Sniffs_PHP_Formatting_ExpressionsSniff extends XLite_ReqCodesSniff
{

	var $singleElm = array(
                T_INLINE_THEN,
                T_EQUAL,
                T_BOOLEAN_AND, T_BOOLEAN_OR,
                T_STRING_CONCAT,
                T_BITWISE_OR, T_BITWISE_AND, T_SL, T_SR,
                T_LOGICAL_XOR, T_LOGICAL_AND, T_LOGICAL_OR,
				T_STRING_CONCAT
	);

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return $this->singleElm;

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

		if (in_array($tokens[$stackPtr]['code'],  $this->singleElm)) {
			$this->checkSingle($phpcsFile, $stackPtr, $tokens);
		}

	}

	public function checkSingle(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens) {

		if ($tokens[$stackPtr]['code'] === T_BITWISE_AND && $tokens[$stackPtr + 1]['code'] === T_VARIABLE) {
			return;
		}

		$rc = array('REQ.PHP.2.8.1');
		switch ($tokens[$stackPtr]['code']) {
			case T_STRING_CONCAT:
				$rc[] = 'REQ.PHP.2.9.3';
				break;

		}

		$lp = $tokens[$stackPtr - 1];
		if ($lp['code'] !== T_WHITESPACE || (substr($lp['content'], 0, 1) !== ' ' && $tokens[$stackPtr - 2]['content'] !== "\n")) {
			$prev = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr - 1, null, true);
			$rq = array();
            $phpcsFile->addError(
                $this->getReqPrefix($rc)
                . 'Законченое выражение должно отделяться одним пробелом справа',
                $prev
            );
		}

		$rp = $tokens[$stackPtr + 1];
		if ($rp['code'] !== T_WHITESPACE || (substr($rp['content'], 0, 1) !== ' ' && $rp['content'] !== "\n")) {
            $next = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $stackPtr - 1, null, true);
			if ($tokens[$next + 1]['code'] !== T_COLON) {
	            $phpcsFile->addError(
    	            $this->getReqPrefix($rc)
        	        . 'Законченое выражение должно отделяться одним пробелом слева',
            	    $next
	            );
			}
		}

    }

}//end class

?>

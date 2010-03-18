<?php
/**
 * XLite_Sniffs_PHP_Formatting_MultilineBracketsSniff.
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
 * XLite_Sniffs_PHP_Formatting_MultilineBracketsSniff.
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
class XLite_Sniffs_PHP_Formatting_MultilineBracketsSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_OPEN_PARENTHESIS
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

		if (!$this->isMultiLine($phpcsFile, $stackPtr)) {
			return;
		}

		$open = $tokens[$stackPtr];
		$close = $tokens[$tokens[$stackPtr]['parenthesis_closer']];

        $posP = $stackPtr - 1;
        do {
            $prevExp = $phpcsFile->findPrevious(T_WHITESPACE, $posP);
            $posP = $prevExp - 1;
        } while (substr($tokens[$prevExp]['content'], -1) != "\n");

		$prevWS = $phpcsFile->findNext(T_WHITESPACE, $prevExp + 1, null, true);

		$column = $tokens[$prevWS]['column'];

		if ($column !== $close['column']) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.2.6.2')
                . 'Закрывающая скобка должна быть выравнена по открывающей скобке либо по функции, к которому относится выражение',
                $tokens[$stackPtr]['parenthesis_closer']
            );
		}

		$nextContent = false;
		$pos = $stackPtr + 1;
		while ($tokens[$pos]['code'] === T_WHITESPACE) {
			$pos++;
		}

		$str = $phpcsFile->getTokensAsString($stackPtr + 1, $pos - $stackPtr);
		if (!preg_match("/^[ ]*\n[ ]*/Ss", $str)) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.2.6.4')
                . 'Первое выражение в многострочной комбинации должно начинаться с новой строки',
                $pos + 1
            );
		}

		$expPos = $pos + 1;
		$nlPos = $pos + 1;
		while (true) {

			// Find next new-line
			$nlPos = $phpcsFile->findNext(T_WHITESPACE, $nlPos, $tokens[$stackPtr]['parenthesis_closer'], false, "\n");
			if ($nlPos === false)
				break;

			$expPos = $phpcsFile->findNext(T_WHITESPACE, $nlPos + 1, $tokens[$stackPtr]['parenthesis_closer'], true);
			if ($expPos === false)
				break;

			$prevNE = $phpcsFile->findPrevious(T_WHITESPACE, $expPos - 1, null, true);
			if ($tokens[$prevNE]['code'] === T_OPEN_PARENTHESIS) {
				$nlPos = $tokens[$prevNE]['parenthesis_closer'];
				continue;
			}

			if ($tokens[$expPos]['code'] == T_OPEN_CURLY_BRACKET) {
				$nlPos = $tokens[$expPos]['bracket_closer'];
				continue;
			}

			if (
				!in_array (
					$tokens[$expPos]['code'],
					array(
						T_BOOLEAN_AND, T_BOOLEAN_OR,
						T_STRING_CONCAT,
						T_BITWISE_OR, T_BITWISE_AND, T_SL, T_SR,
						T_LOGICAL_XOR, T_LOGICAL_AND, T_LOGICAL_OR,
						T_INLINE_THEN
					)
				)
				&& $tokens[$prevNE]['code'] !== T_COMMA
			) {

	            $phpcsFile->addError(
    	            $this->getReqPrefix('REQ.PHP.2.6.5')
        	        . 'Второе и последующие выражения переносятся с логическими операторами',
            	    $expPos
	            );
			}
			$nlPos++;
		}


    }//end process()

	public function isMultiLine(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        return $tokens[$stackPtr]['line'] !== $tokens[$tokens[$stackPtr]['parenthesis_closer']]['line'];
	}

}//end class

?>

<?php
/**
 * XLite_Sniffs_PHP_Formatting_ReturnSniff.
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
 * XLite_Sniffs_PHP_Formatting_ReturnSniff.
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
class XLite_Sniffs_PHP_Formatting_ReturnSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
   {
        return array(T_RETURN);

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
		static $cache = array();

        $tokens = $phpcsFile->getTokens();

		$pos = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);

		if ($tokens[$pos]['line'] > $tokens[$stackPtr]['line'] - 2) {

			if (in_array($tokens[$pos]['code'], array(T_OPEN_CURLY_BRACKET, T_COMMENT))) {
				return;
			}

            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.2.11.1')
                . 'Перед return должна быть одна пустая строка',
                $stackPtr
            );
		}

		$fPos = $phpcsFile->findPrevious(T_FUNCTION, $stackPtr - 1);

		if ($fPos !== false) {

			if (!isset($cache[$phpcsFile->getFilename()]))
				$cache[$phpcsFile->getFilename()] = array();

			if (!isset($cache[$phpcsFile->getFilename()][$fPos])) {

				$cache[$phpcsFile->getFilename()][$fPos] = true;

				$returns = array();
				$pos = $tokens[$fPos]['scope_opener'] + 1;
				do {
					$pos = $phpcsFile->findNext(T_RETURN, $pos, $tokens[$fPos]['scope_closer'] - 1);
					if ($pos !== false) {
						$returns[] = $pos;
						$pos++;
					}

				} while ($pos !== false);

				if (count($returns) > 1) {

					array_pop($returns);

					foreach ($returns as $rPos) {
		            	$phpcsFile->addWarning(
    		    	        $this->getReqPrefix('WRN.PHP.2.11.1')
        			        . 'Рекомендуется иметь 1 return на 1 функцию',
    	    	    	    $rPos
		        	    );
					}
				}
			}
		}

    }

}//end class

?>

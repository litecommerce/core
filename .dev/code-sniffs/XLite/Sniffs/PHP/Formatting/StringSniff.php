<?php
/**
 * XLite_Sniffs_PHP_Formatting_StringSniff.
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
 * XLite_Sniffs_PHP_Formatting_StringSniff.
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
class XLite_Sniffs_PHP_Formatting_StringSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
   {
        return array(T_CONSTANT_ENCAPSED_STRING);

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

		if (
			substr($tokens[$stackPtr]['content'], 0, 1) === '"'
			&& $tokens[$stackPtr - 1]['code'] !== T_CONSTANT_ENCAPSED_STRING
			&& $tokens[$stackPtr + 1]['code'] !== T_CONSTANT_ENCAPSED_STRING
		) {
			$str = str_replace(
				array('\n', '\r', '\t'),
				array('','',''),
				substr($tokens[$stackPtr]['content'], 1, -1)
			);

			// Check - is property ?
			$isProperty = false;
			if (
				isset($tokens[$stackPtr]['conditions'])
				&& $tokens[$stackPtr]['conditions']
				&& 1 == count($tokens[$stackPtr]['conditions'])
			) {	
				reset($tokens[$stackPtr]['conditions']);
				$key = key($tokens[$stackPtr]['conditions']);
				$isProperty = $tokens[$key]['code'] == T_CLASS;
			}

			if (strlen($str) > 0 && !$isProperty) {
	            $phpcsFile->addError(
    	            $this->getReqPrefix('REQ.PHP.2.9.1') . 'Строка обрамляется одинарными кавычками',
        	        $stackPtr
            	);
			}
		}

    }

}//end class

?>

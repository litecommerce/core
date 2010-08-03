<?php
/**
 * XLite_Sniffs_Whitespace_MaxIndentSniff.
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
 * XLite_Sniffs_Whitespace_MaxIndentSniff.
 *
 * Checks that control structures are structured correctly, and their content
 * is indented correctly.
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
class XLite_Sniffs_PHP_WhiteSpace_MaxIndentSniff extends XLite_ReqCodesSniff
{

    /**
     * The number of spaces code should be indented.
     *
     * @var int
     */
    protected $indent = 4;

	/*
	 * Max allowed indents
	 *
	 * @var int
	 */
	protected $maxIndents = 7;

   /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_WHITESPACE);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

		if (
			preg_match('/^[ ]+$/Ss', $tokens[$stackPtr]['content'])
			&& strlen($tokens[$stackPtr]['content']) > $this->indent * $this->maxIndents
			&& $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
			&& $tokens[$stackPtr - 1]['content'] === "\n"
		) {
            $phpcsFile->addWarning(
				$this->getReqPrefix('WRN.PHP.2.4.1') . 'Рекомендуется делать не более ' . $this->maxIndents . ' уровней отступов',
				$stackPtr
			);
		}
    }


}//end class

?>

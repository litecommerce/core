<?php
/**
 * XLite_Sniffs_PHP_Formatting_EvalSniff.
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
 * XLite_Sniffs_PHP_Formatting_EvalSniff.
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
class XLite_Sniffs_PHP_Formatting_ForbidFunctionsSniff extends XLite_ReqCodesSniff
{

	/**
	 * Forbid functions list
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $forbidFunctions = array(
		'php_version'   => 'Используйте константу PHP_VERSION',
		'php_uname'     => 'Используйте константу PHP_OS',
		'php_sapi_name' => 'Используйте константу PHP_SAPI',
	);

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

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

		if (isset($this->forbidFunctions[$tokens[$stackPtr]['content']])) {

	    	$phpcsFile->addError(
           		$this->getReqPrefix('?')
        	    . 'Использование ' . $tokens[$stackPtr]['content'] . '() запрещено (' . $this->forbidFunctions[$tokens[$stackPtr]['content']] . ')',
    	        $stackPtr
	        );
		}
    }

}

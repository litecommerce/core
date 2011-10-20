<?php
/**
 * XLite_Sniffs_CSS_LowerCaseHTMLTagsSniff.
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
 * XLite_Sniffs_CSS_LowerCaseHTMLTagsSniff.
 *
 * Ensure that all style definitions are in lowercase.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_LowerCaseHTMLTagsSniff extends XLite_ReqCodesSniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('CSS');


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_CURLY_BRACKET);

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
        $tokens   = $phpcsFile->getTokens();

        $endTokens = array(
                      T_CLOSE_CURLY_BRACKET,
                      T_COMMENT,
                      T_OPEN_TAG,
                     );

        $foundContent = false;
        $currentLine  = $tokens[$stackPtr]['line'];
        for ($i = ($stackPtr - 1); $i >= 0; $i--) {
            if (in_array($tokens[$i]['code'], $endTokens) === true) {
                break;
            }

            if ($tokens[$i]['line'] === $currentLine) {
                if ($tokens[$i]['code'] === T_STRING && $tokens[$i-1]['code'] !== T_STRING_CONCAT && $tokens[$i-1]['code'] !== T_HASH) {
					// found html tag
					if ($tokens[$i]['content'] != strtolower($tokens[$i]['content'])) {
 		               $error = "Имена HTML-тэгов пишутся в нижнем регистре. Нужно: " . strtolower($tokens[$i]['content']) . ", найдено: " . $tokens[$i]['content'];
        		       $phpcsFile->addError($this->getReqPrefix('REQ.CSS.1.0.1') . $error, $i);
					}
                }
                continue;
            }
			$currentLine  = $tokens[$i]['line'];

		}//end for

    }//end process()

}//end class
?>

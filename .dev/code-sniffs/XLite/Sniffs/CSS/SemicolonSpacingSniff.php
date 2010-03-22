<?php
/**
 * XLite_Sniffs_CSS_SemicolonSpacingSniff.
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
 * XLite_Sniffs_CSS_SemicolonSpacingSniff.
 *
 * Ensure each style definition has a semi-colon and it is spaced correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_SemicolonSpacingSniff extends XLite_NameSniff
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
        return array(T_STYLE);

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

		$curPropertyValueToken = $phpcsFile->findNext(T_STRING, ($stackPtr + 1));
        if ( $this->isCSSPseudoClass( $tokens[$curPropertyValueToken]['content'] ) )
            return;

        $semicolon = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr + 1));
        if ($semicolon === false || $tokens[$semicolon]['line'] !== $tokens[$stackPtr]['line']) {
            $error = 'Описание каждого свойства должно завершаться точкой с запятой';
            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.9') . $error, $stackPtr);
            return;
        }

        if ($tokens[($semicolon - 1)]['code'] === T_WHITESPACE) {
            $length  = strlen($tokens[($semicolon - 1)]['content']);
            $error = "Не должно быть пробелов/отступов в конце описания свойства перед точкой с запятой. Найдено: $length";
            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.11') . $error, $stackPtr);
        }

    }//end process()

}//end class
?>

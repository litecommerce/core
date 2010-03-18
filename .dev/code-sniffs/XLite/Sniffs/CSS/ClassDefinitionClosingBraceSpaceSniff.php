<?php
/**
 * XLite_Sniffs_CSS_ColonSpacingSniff.
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
 * XLite_Sniffs_CSS_ClassDefinitionClosingBraceSpaceSniff.
 *
 * Ensure there is a single blank line after the closing brace of a class definition.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_ClassDefinitionClosingBraceSpaceSniff extends XLite_ReqCodesSniff
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
        return array(T_CLOSE_CURLY_BRACKET);

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

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if ($next === false) {
            return;
        }

        if ($tokens[$next]['code'] !== T_CLOSE_TAG) {
            $found = (($tokens[$next]['line'] - $tokens[$stackPtr]['line']) - 1);
            if ($found !== 1) {
                $error = "Обязательно наличие пустой строки после закрывающей фигурной скобки описания свойств класса.";
				if ($found > 1) 
					$error .= " Найдено $found строк(и)";
                $phpcsFile->addError($this->getReqPrefix('REQ.CSS.?') . $error, $stackPtr);
            }
        }

        $prev  = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);

        if ($prev !== false) {

            $num   = ($tokens[$stackPtr]['line'] - $tokens[$prev]['line'] - 1);
			if ($num > 0) {
				$error = "Недопустимо наличие пустых строк перед закрывающей скобкой. Найдено $num строк";
            	$phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.8') . $error, $stackPtr);
            }

			if ($tokens[$stackPtr]['line'] === $tokens[$stackPtr-1]['line']) {
				$error = "Закрывающая фигурная скобка на отдельной строке и выровнена по названию класса.";
            	$phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.8') . $error, $stackPtr);
			}
        }

    }//end process()


}//end class

?>

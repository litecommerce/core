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
 * XLite_Sniffs_CSS_ColonSpacingSniff.
 *
 * Ensure there is no space before a colon and one space after it.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_ColonSpacingSniff extends XLite_NameSniff
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
        return array(T_COLON);

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

        if ($tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
            $error = 'Не должно быть пробелов/отступов перед двоеточием после названия свойств';
            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.12') . $error, $stackPtr);
        }

        if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
            $error = 'Нет отступа между названием свойства и его значением.';
            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.3') . $error, $stackPtr);
        } else {
            $content = $tokens[($stackPtr + 1)]['content'];
            if (strpos($content, $phpcsFile->eolChar) === false) {
                $length  = strlen($content);
                if ($length !== 1) {
                    $error = "Необходимо разделять название свойства и его значение одним пробелом. Найден(о) $length";
                    $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.3') . $error, $stackPtr);
                }
            } else {
                $error = 'Необходимо разделять название свойства и его значение одним пробелом. Обнаружена новая строка';
                $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.3') . $error, $stackPtr);
            }
        }//end if

    }//end process()

}//end class
?>

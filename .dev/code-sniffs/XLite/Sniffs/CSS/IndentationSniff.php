<?php
/**
 * XLite_Sniffs_CSS_IndentationSniff.
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
 * XLite_Sniffs_CSS_IndentationSniff.
 *
 * Ensures styles are indented 4 spaces.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_IndentationSniff extends XLite_ReqCodesSniff
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
        return array(T_OPEN_TAG);

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

        $numTokens   = (count($tokens) - 2);
        $currentLine = 0;
        $indentLevel = 0;
        for ($i = 1; $i < $numTokens; $i++) {
            if ($tokens[$i]['code'] === T_COMMENT) {
                // Dont check the indent of comments.
                continue;
            }

            if ($tokens[$i]['code'] === T_OPEN_CURLY_BRACKET) {
                $indentLevel++; 
            } else if ($tokens[$i]['code'] === T_CLOSE_CURLY_BRACKET) {
                $indentLevel--;
            }

            if ($tokens[$i]['line'] === $currentLine) {
                continue;
            }

            // We started a new line, so check indent.
            if ($tokens[$i]['code'] === T_WHITESPACE) {
                $content     = str_replace($phpcsFile->eolChar, '', $tokens[$i]['content']);
                $foundIndent = strlen($content);
            } else {
                $foundIndent = 0;
            }
            
			if ($tokens[$i]['code'] === T_OPEN_CURLY_BRACKET || $tokens[$i]['code'] === T_CLOSE_CURLY_BRACKET) { 
				$i++;
				continue; 
			}

            $expectedIndent = ($indentLevel * 2);
            if ($expectedIndent > 0 && strpos($tokens[$i]['content'], $phpcsFile->eolChar) !== false) {
                $error = 'Наличие пустых строк недопустимо в описаниях свойств';
                $phpcsFile->addError($this->getReqPrefix('?') . $error, $i);
            } else if ($foundIndent !== $expectedIndent && $tokens[$i+1]['code'] !== T_OPEN_CURLY_BRACKET && $tokens[$i+1]['code'] !== T_CLOSE_CURLY_BRACKET) {
                $error = "Неправильная величина отступа. Необходимо $expectedIndent пробела, найдено $foundIndent";
                $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.1') . $error, $i);
            }

            $currentLine = $tokens[$i]['line'];
        }//end foreach

    }//end process()

}//end class
?>

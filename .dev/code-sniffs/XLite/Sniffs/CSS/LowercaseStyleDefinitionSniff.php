<?php
/**
 * XLite_Sniffs_CSS_LowercaseStyleDefinitionSniff.
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
 * XLite_Sniffs_CSS_LowercaseStyleDefinitionSniff.
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
class XLite_Sniffs_CSS_LowercaseStyleDefinitionSniff extends XLite_ReqCodesSniff
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
        $tokens = $phpcsFile->getTokens();

        $start  = ($stackPtr + 1);

        if (!isset($tokens[$stackPtr]['bracket_closer'])) {
            $end = $phpcsFile->findNext(T_CLOSE_CURLY_BRACKET, $stackPtr + 1);
        } else {
            $end = $tokens[$stackPtr]['bracket_closer'];
        }

        for ($i = $start; $i <= $end; $i++) {
            if ($tokens[$i]['code'] === T_STRING || $tokens[$i]['code'] === T_STYLE) {
                $expected = strtolower($tokens[$i]['content']);
                if ($expected !== $tokens[$i]['content']) {
                    $found = $tokens[$i]['content'];
					if ($tokens[$i]['code'] === T_STYLE) {
                    	$error = "Имена свойств пишутся в нижнем регистре. Необходимо: $expected, найдено: $found";
                    	$phpcsFile->addError($this->getReqPrefix('REQ.CSS.1.0.2') . $error, $i);
					} else {
						# Find the name of the property and skip checking for the font-family
						$curPropertyToken = $phpcsFile->findPrevious(T_STYLE, $i, null, false);
						if (strtolower($tokens[$curPropertyToken]['content']) == 'font-family')
							continue;	
                    	$error = "Значения свойств пишутся в нижнем регистре. Необходимо: $expected, найдено $found";
                    	$phpcsFile->addError($this->getReqPrefix('REQ.CSS.1.0.3') . $error, $i);
					}
                }
            }
        }//end for

    }//end process()

}//end class
?>

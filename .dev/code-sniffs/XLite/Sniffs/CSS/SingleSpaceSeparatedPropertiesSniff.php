<?php
/**
 * XLite_Sniffs_CSS_SingleSpaceSeparatedPropertiesSniff.
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
 * XLite_Sniffs_CSS_SingleSpaceSeparatedPropertiesSniff.
 *
 * Ensure that style properties are separated with one space from each other.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_SingleSpaceSeparatedPropertiesSniff extends XLite_NameSniff
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

		$start = $phpcsFile->findNext(array(T_STRING, T_COLOUR), ($stackPtr+1));
        if ($start === false ||  $this->isCSSPseudoClass( $tokens[$start]['content'] ) )
            return;
		
		$end = $phpcsFile->findNext(T_SEMICOLON, $stackPtr+1);
		if (!$end)
			return;

		# Look for the next definition, if exists		
		$isMulti = $phpcsFile->findNext(T_STRING, $start+1, $end); 
		if (!$isMulti)
			return;

		# Check spacing
		for ( $i = $start; $i < $end; $i++ ) {
			if ($tokens[$i]['code'] === T_WHITESPACE) {
				if ($tokens[$i+1]['column'] - $tokens[$i]['column'] > 1) {
					$error = 'Если у свойства несколько значений, необходимо разделить их одинарными пробелами.';
					$phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.10') . $error, $i);
				}
			}
		}
    }//end process()

}//end class
?>

<?php
/**
 * XLite_Sniffs_CSS_RequiredDimensionsSniff.
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
 * XLite_Sniffs_CSS_RequiredDimensionsSniff.
 *
 * Ensure that style properties are defined in the standard-required dimensions.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_RequiredDimensionsSniff extends XLite_NameSniff
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

	private $relativeDimsStyles = array(
		'font-size',
		'line-height'
	);

	private $allowedDims = array(
		'px', 'em', 'ex', '%'
	);

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

		$style = $tokens[$stackPtr]['content'];
		$start = $phpcsFile->findNext(T_COLON, $stackPtr);
		$end = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
		if (!$start || !$end)
			return;

		# Get the full description value
		$value = '';
		for ($i=$start+1; $i<$end; $i++) {
			if ($tokens[$i]['code'] === T_LNUMBER) {
				# Next to number follows the dimension
				if ($tokens[$i+1]['code'] === T_WHITESPACE || $tokens[$i+1]['code'] === T_SEMICOLON)
					continue;

				$dim = $tokens[$i+1]['content'];

				if (!in_array($dim, $this->allowedDims)) {
                    $error = 'Запрещены к использованию размерности, кроме px, em, ex, %';
                    $phpcsFile->addError($this->getReqPrefix('REQ.CSS.3.0.1') . $error, $i);
				} elseif (in_array($style, $this->relativeDimsStyles) && !in_array($dim, array('em', 'ex', '%'))) {

					/* TODO - research
                    $error = 'Размерность шрифта требуется задавать в em/ex';
                    $phpcsFile->addError($this->getReqPrefix('REQ.CSS.3.0.1') . $error, $i);
					*/
					
				}
			}
		}
    }//end process()

}//end class
?>

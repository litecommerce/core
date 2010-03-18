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
 * XLite_Sniffs_CSS_ClassDefinitionOpeningBraceSpaceSniff.
 *
 * Ensure there is a single space before the opening brace in a class definition
 * and the content starts on the next line.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_CSS_ClassDefinitionOpeningBraceSpaceSniff extends XLite_ReqCodesSniff
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

		$currentLine  = $tokens[$stackPtr]['line'];
		$classCount = 1;
		
		$lastSelector = $phpcsFile->findPrevious(T_STRING, $stackPtr);
		$lastSelectorLine = $tokens[$lastSelector]['line'];
		$multipleClasses = false;

        for ($i = ($stackPtr - 1); $i >= 0; $i--) {

			if ($tokens[$i]['code'] === T_COMMA) {
				// several selectors found
				$multipleClasses = true;

				$nextDefinition = $phpcsFile->findNext(T_STRING, ($i+1));
				if ($nextDefinition !== false && $tokens[$nextDefinition]['line'] === $tokens[$i]['line']) {
		       		$error = 'Если в селекторе несколько классов, идущих через запятую, каждый подселектор находится на следующей строчке';
    	    		$phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.6') . $error, $stackPtr);
				}
				if ($lastSelectorLine === $currentLine) {
	            	$error = 'Если в селекторе несколько классов, идущих через запятую, открывающая фигурная скобка находится на следующей строчке';
	    	        $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.7') . $error, $stackPtr);
				}
			}

			if ($tokens[$i]['code'] === T_CLOSE_CURLY_BRACKET) {
				break;
			}
		}//end for

		if ($lastSelectorLine !== $currentLine && $tokens[$stackPtr]['column'] > 1) {
           	$error = 'Открывающая фигурная скобка, начинающаяся с отдельной строки, должна быть выровнена по названию класса';
           	$phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.8') . $error, $stackPtr);
		}
        
		if ($multipleClasses === false) {
			if ($lastSelectorLine !== $currentLine) {
            	$error = 'Если в селекторе один класс, открывающая фигурная скобка на той же строке что и селектор.';
            	$phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.5') . $error, $stackPtr);

			} else if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
            	$error = 'Если в селекторе один класс должен быть 1 пробел между названием класса и открывающей фигурной скобкой.';
	            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.4') . $error, $stackPtr);

			} else {
            	$content = $tokens[($stackPtr - 1)]['content'];
	            if ($content !== ' ') {
    	            $length = strlen($content);
        	        if ($length === 1) {
            	        $length = 'tab';
                	}

	            	$error = 'Если в селекторе один класс должен быть 1 пробел между названием класса и открывающей фигурной скобкой. Найдено: ' . $length;
    	            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.4') . $error, $stackPtr);
        	    }
			}
        }//end if

        $next = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        if ($next !== false && $tokens[$next]['line'] !== ($tokens[$stackPtr]['line'] + 1)) {
            $num   = ($tokens[$next]['line'] - $tokens[$stackPtr]['line'] - 1);
			if ($num > 0) {
            	$error = "Недопустимо наличие пустых строк после открывающей скобки. Найдено $num ";
			} else {
	            $error = "Описание свойств должно начинаться с новой строки после фигурной скобки";
			}
            $phpcsFile->addError($this->getReqPrefix('REQ.CSS.2.0.8') . $error, $stackPtr);
        }

    }//end process()


}//end class

?>

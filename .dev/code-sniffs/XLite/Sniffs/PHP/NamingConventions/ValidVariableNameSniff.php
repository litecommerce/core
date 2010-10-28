<?php
/**
 * XLite_Sniffs_PHP_NamingConventions_ValidVariableNameSniff.
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

if (class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff', true) === false) {
    $error = 'Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * XLite_Sniffs_PHP_NamingConventions_ValidVariableNameSniff.
 *
 * Checks the naming of member variables.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.2.0RC1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_PHP_NamingConventions_ValidVariableNameSniff extends XLite_AbstractVariableSniff
{

	protected $allowVars = array(
		'_GET', '_POST', '_SERVER', '_COOKIE', 'GLOBALS', '_REQUEST', '_FILES',
	);

    /**
     * Processes class member variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {

		// FIXME - incompatible with the "camel case" notation
		return;

        $tokens = $phpcsFile->getTokens();

        $memberProps = $phpcsFile->getMemberProperties($stackPtr);
        if (empty($memberProps) === true) {
            return;
        }

        $memberName     = ltrim($tokens[$stackPtr]['content'], '$');
        $isPublic       = ($memberProps['scope'] === 'private') ? false : true;
        $scope          = $memberProps['scope'];
        $scopeSpecified = $memberProps['scope_specified'];

        // If it's a private member, it must have an underscore on the front.
        if ($isPublic === false && $memberName{0} !== '_') {
            $error = "Private member variable \"$memberName\" must be prefixed with an underscore";
            $phpcsFile->addError($this->getReqPrefix('?') . $error, $stackPtr);
            return;
        }

        // If it's not a private member, it must not have an underscore on the front.
        if ($isPublic === true && $scopeSpecified === true && $memberName{0} === '_') {
            $error = ucfirst($scope)." member variable \"$memberName\" must not be prefixed with an underscore";
            $phpcsFile->addError($this->getReqPrefix('?') . $error, $stackPtr);
            return;
        }

		$this->processVariable($phpcsFile, $stackPtr);

    }//end processMemberVar()


    /**
     * Processes normal variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

		$var   = ltrim($tokens[$stackPtr]['content'], '$');	
        if (in_array($var, $this->allowVars)) {
            return;
        }

		$words = $this->getWordsByCapitalLetter($var);

        $fBit = array_shift($words);
        if (!$this->checkLowWord($fBit)) {
            $error = "Variable name \"$var\" is not in camel caps format";
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.5.1') . $error, $stackPtr);

        }

        if (!$this->checkNoun($fBit)) {
            $error = "Variable name \"$var\" is not in noun form";
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.5.2') . $error, $stackPtr);
        }

        foreach ($words as $w) {
	        $res = $this->checkCamelWord($w);
            if ($res == -2) {
    	        $error = "Часть '" . $w. "' из слова '" .$var . "' не валидна и возможно является аббревиатурой, о которой валидатор незнает. Аббревиатура должна быть зарегестрирована в массиве abbrs.";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.2.1') . $error, $stackPtr);

            } elseif ($res < 0) {
                $error = "Variable name \"$var\" is not in camel caps format";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.5.1') . $error, $stackPtr);

            }
        }

    }//end processVariable()


    /**
     * Processes variables in double quoted strings.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {

		// TODO
        return;

    }//end processVariableInString()


}//end class

?>

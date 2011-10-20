<?php
/**
 * XLite_Sniffs_PHP_NamingConventions_ValidFunctionNameSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found');
}

/**
 * XLite_Sniffs_PHP_NamingConventions_ValidFunctionNameSniff.
 *
 * Ensures method names are correct depending on whether they are public
 * or private, and that functions are named correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.2.0RC1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_PHP_NamingConventions_ValidFunctionNameSniff extends XLite_AbstractScopeSniff
{

    /**
     * A list of all PHP magic methods.
     *
     * @var array
     */
    private $_magicMethods = array(
                              'construct',
                              'destruct',
                              'call',
                              'callStatic',
                              'get',
                              'set',
                              'isset',
                              'unset',
                              'sleep',
                              'wakeup',
                              'toString',
                              'set_state',
                              'clone',
							  'invoke'
                             );

    /**
     * A list of all PHP magic functions.
     *
     * @var array
     */
    private $_magicFunctions = array(
                                'autoload',
                               );


    /**
     * Constructs a XLite_Sniffs_PHP_NamingConventions_ValidFunctionNameSniff.
     */
    public function __construct()
    {
        parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION), true);

    }//end __construct()


    /**
     * Processes the tokens within the scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param int                  $stackPtr  The position where this token was
     *                                        found.
     * @param int                  $currScope The position of the current scope.
     *
     * @return void
     */
    protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $className  = $phpcsFile->getDeclarationName($currScope);
        $methodName = $phpcsFile->getDeclarationName($stackPtr);

        $tokens = $phpcsFile->getTokens();

		if ($tokens[$stackPtr + 1]['code'] == T_WHITESPACE && $tokens[$stackPtr + 2]['code'] == T_OPEN_PARENTHESIS) {
			// \Closure instance
			return;
		}

        // Is this a magic method. IE. is prefixed with "__".
        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = substr($methodName, 2);
            if (in_array($magicPart, $this->_magicMethods) === false) {
                 $error = "Method name \"$className::$methodName\" is invalid; only PHP magic methods should be prefixed with a double underscore";
                 $phpcsFile->addError($this->getReqPrefix('?') . $error, $stackPtr);
            }

            return;
        }

        $methodProps    = $phpcsFile->getMethodProperties($stackPtr);
        $isPublic       = ($methodProps['scope'] === 'private') ? false : true;
        $scope          = $methodProps['scope'];
        $scopeSpecified = $methodProps['scope_specified'];

		if ($this->isReserverMethodName($methodName)) {
			return;
		}

		$words = $this->getWordsByCapitalLetter($methodName);
		$fBit = array_shift($words);
		if (!$this->checkLowWord($fBit)) {
			$error = ucfirst($scope)." method name \"$className::$methodName\" is not in camel caps format";
			$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.1') . $error, $stackPtr);

		}

		if (!$this->checkVerb($fBit)) {

        $tokens = $phpcsFile->getTokens();

			$error = ucfirst($scope)." method name \"$className::$methodName\" is not in verb form";
			$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.2') . $error, $stackPtr);
		}

		foreach ($words as $wk => $w) {
			$res = $this->checkCamelWord($w);
			if ($res == -2) {
				$error = "Часть '" . $w. "' из слова '" .$methodName . "' не валидна и возможно является аббревиатурой, о которой валидатор не знает. Аббревиатура должна быть зарегестрирована в массиве abbrs.";
				$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.2.1') . $error, $stackPtr);
				
			} elseif ($res < 0 && (!isset($words[$wk + 1]) || !in_array($w . $words[$wk + 1], $this->twoWordsAbbrs))) {

				$error = ucfirst($scope)." method name \"$className::$methodName\" is not in camel caps format";
				$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.1') . $error, $stackPtr);

			}
		}

    }//end processTokenWithinScope()


    /**
     * Processes the tokens outside the scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param int                  $stackPtr  The position where this token was
     *                                        found.
     *
     * @return void
     */
    protected function processTokenOutsideScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $functionName = $phpcsFile->getDeclarationName($stackPtr);

        // Is this a magic function. IE. is prefixed with "__".
        if (preg_match('|^__|', $functionName) !== 0) {
            $magicPart = substr($functionName, 2);
            if (in_array($magicPart, $this->_magicFunctions) === false) {
                 $error = "Function name \"$functionName\" is invalid; only PHP magic methods should be prefixed with a double underscore";
                 $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.1') . $error, $stackPtr);
            }

            return;
        }

        $words = $this->getWordsByCapitalLetter($functionName);
 		$fBit = array_shift($words);
		if (!$this->checkLowWord($fBit)) {
			$error = "Function name \"$functionName\" is not in camel caps format";
			$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.1') . $error, $stackPtr);

		}

		if (!$this->checkVerb($fBit)) {

			$error = "Function name \"$functionName\" is not in verb form";
			$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.2') . $error, $stackPtr);
		}

		foreach ($words as $w) {
            $res = $this->checkCamelWord($w);
            if ($res == -2) {
                $error = "Часть '" . $w. "' из слова '" .$functionName . "' не валидна и возможно является аббревиатурой, о которой валидатор не знает. Аббревиатура должна быть зарегестрирована в массиве abbrs.";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.2.1') . $error, $stackPtr);

            } elseif ($res < 0) {
                $error = "Function name \"$functionName\" is not in camel caps format";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.4.1') . $error, $stackPtr);

            }
        }

    }//end processTokenOutsideScope()


}//end class

?>

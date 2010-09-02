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

        // Is this a magic method. IE. is prefixed with "__".
        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = substr($methodName, 2);
            if (in_array($magicPart, $this->_magicMethods) === false) {
                 $error = "Method name \"$className::$methodName\" is invalid; only PHP magic methods should be prefixed with a double underscore";
                 $phpcsFile->addError($this->getReqPrefix('?') . $error, $stackPtr);
            }

            return;
        }

        // PHP4 constructors are allowed to break our rules.
        if ($methodName === $className) {
            return;
        }

        // PHP4 destructors are allowed to break our rules.
        if ($methodName === '_'.$className) {
            return;
        }

        $methodProps    = $phpcsFile->getMethodProperties($stackPtr);
        $isPublic       = ($methodProps['scope'] === 'private') ? false : true;
        $scope          = $methodProps['scope'];
        $scopeSpecified = $methodProps['scope_specified'];

/* FIXME - incompatible with the "camel case" notation

        // If it's a private method, it must have an underscore on the front.
        if ($isPublic === false && $methodName{0} !== '_') {
            $error = "Private method name \"$className::$methodName\" must be prefixed with an underscore";
            $phpcsFile->addError($this->getReqPrefix('?') . $error, $stackPtr);
            return;
        }

        // If it's not a private method, it must not have an underscore on the front.
        if ($isPublic === true && $scopeSpecified === true && $methodName{0} === '_') {
            $error = ucfirst($scope)." method name \"$className::$methodName\" must not be prefixed with an underscore";
            $phpcsFile->addError($this->getReqPrefix('?') . $error, $stackPtr);
            return;
        }

*/

        // If the scope was specified on the method, then the method must be
        // camel caps and an underscore should be checked for. If it wasn't
        // specified, treat it like a public method and remove the underscore
        // prefix if there is one because we cant determine if it is private or
        // public.
        $testMethodName = $methodName;
        if ($scopeSpecified === false && $methodName{0} === '_') {
            $testMethodName = substr($methodName, 1);
        }

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

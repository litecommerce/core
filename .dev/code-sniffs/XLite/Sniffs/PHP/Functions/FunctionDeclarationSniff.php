<?php
/**
 * XLite_Sniffs_PHP_Functions_FunctionDeclarationSniff.
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
 * XLite_Sniffs_PHP_Functions_FunctionDeclarationSniff.
 *
 * Ensure single and multi-line function declarations are defined correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.2.0RC1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class XLite_Sniffs_PHP_Functions_FunctionDeclarationSniff extends XLite_ReqCodesSniff
{
	/**
	 * All action handler functions are prefixed with this value
	 */
	const ACTION_PREFIX = 'action';

	/**
	 * Only this function can be located in global scope
	 */
	const AUTOLOAD_FUNC = '__autoload';

	/**
	 * It's not needed to return value for these functions
	 * 
	 * @var    array
	 * @access private
	 * @see    ____var_see____
	 * @since  1.0.0
	 */
	private $_magicFunctions = array(
		self::AUTOLOAD_FUNC,
		'__construct',
		'__destruct',
		'__clone',
		'__sleep',
		'__wakeup',
		'throwException',
		'assert',
	);


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
		$methodName = $phpcsFile->getDeclarationName($stackPtr);

		end($tokens[$stackPtr]['conditions']);
		$key = key($tokens[$stackPtr]['conditions']);
		if ($key && in_array($tokens[$key]['code'], array(T_CLASS, T_INTERFACE))) {
			// Is method

			$prevEOL = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, false, "\n");
			$methodType = $phpcsFile->findNext(array(T_PRIVATE, T_PROTECTED, T_PUBLIC), $prevEOL + 1, $stackPtr - 1);
			if ($methodType === false) {
    	        $phpcsFile->addError(
					$this->getReqPrefix('REQ.PHP.3.5.1') . 'Методы класса должны всегда определять свою область видимости',
					$stackPtr
				);
			}

		} else {

			if (self::AUTOLOAD_FUNC !== $methodName) {

				$isInternal = false;
				foreach ($tokens[$stackPtr]['conditions'] as $k => $v) {
					if ($tokens[$k]['code'] == T_FUNCTION) {
						$isInternal = true;
					}
				}

				if (!$isInternal) {
		            $phpcsFile->addWarning(
    		            $this->getReqPrefix('WRN.PHP.3.5.1') . 'Функции в глобальной области видимости крайне не приветствуются',
        		        $stackPtr
            		);
				}
			}
		}

        // Check if this is a single line or multi-line declaration.
        $openBracket  = $tokens[$stackPtr]['parenthesis_opener'];
        $closeBracket = $tokens[$stackPtr]['parenthesis_closer'];

		$this->checkDefaultArguments($phpcsFile, $stackPtr, $tokens);
		$this->checkFuncBody($phpcsFile, $stackPtr, $tokens);

		if (!in_array($methodName, $this->_magicFunctions) && 0 !== strpos($methodName, self::ACTION_PREFIX)) {
			$this->checkReturn($phpcsFile, $stackPtr, $tokens);
		}

        if ($tokens[$openBracket]['line'] === $tokens[$closeBracket]['line']) {
            $this->processSingleLineDeclaration($phpcsFile, $stackPtr, $tokens);
        } else {
            $this->processMultiLineDeclaration($phpcsFile, $stackPtr, $tokens);
        }

    }//end process()

	function checkDefaultArguments($phpcsFile, $stackPtr, $tokens) {
		$lastPos = $tokens[$stackPtr]['parenthesis_opener'];
		$pos = $tokens[$stackPtr]['parenthesis_opener'];

		$defaultFound = false;
		do {
			$pos = $phpcsFile->findNext(T_COMMA, $pos + 1, $tokens[$stackPtr]['parenthesis_closer']);
			if ($pos !== false) {
				$isDefault = $phpcsFile->findNext(T_EQUAL, $lastPos + 1, $pos - 1);
				if ($isDefault !== false) {
					$defaultFound = true;

					if (
						$tokens[$isDefault - 1]['code'] !== T_WHITESPACE || $tokens[$isDefault - 1]['content'] !== ' '
						|| $tokens[$isDefault + 1]['code'] !== T_WHITESPACE || $tokens[$isDefault + 1]['content'] !== ' '
					) {
    	                $phpcsFile->addError(
        	                $this->getReqPrefix('REQ.PHP.3.5.14') . 'Аргументы со значениями по-умолчанию должны отделяться от значений по-умолчанию комбинацией знаков пробел + равенство + пробел',
            	            $isDefault
                	    );
					}

				} elseif ($defaultFound) {
	                $phpcsFile->addError(
    	                $this->getReqPrefix('REQ.PHP.3.5.3') . 'Аргументы функций со значениями по умолчанию должны находиться в конце списка аргументов',
        	            $lastPos + 2
            	    );
				}

				$lastPos = $pos;
			}

		} while ($pos !== false);

	}

	function checkReturn($phpcsFile, $stackPtr, $tokens) {
		if (!isset($tokens[$stackPtr]['scope_opener']) || !isset($tokens[$stackPtr]['scope_closer']))
			return;

		$return = $phpcsFile->findNext(T_RETURN, $tokens[$stackPtr]['scope_opener'] + 1, $tokens[$stackPtr]['scope_closer'] - 1);
		$str = $phpcsFile->findNext(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT), $tokens[$stackPtr]['scope_opener'] + 1, $tokens[$stackPtr]['scope_closer'] - 1, true);
		$isClass = $phpcsFile->findPrevious(array(T_CLASS, T_INTERFACE), $tokens[$stackPtr]['scope_opener']);
        if ($return === false && $str && !$isClass) {
	        $phpcsFile->addError(
    	        $this->getReqPrefix('REQ.PHP.3.5.4') . 'Функции всегда должны возвращать значение, если это возможно в принципе',
        	    $stackPtr
        	);
        }
	}

	function checkFuncBody($phpcsFile, $stackPtr, $tokens) {
		if (!isset($tokens[$stackPtr]['scope_opener']))
			return;

		$nextNWS = $phpcsFile->findNext(T_WHITESPACE, $tokens[$stackPtr]['scope_opener'] + 1, null, true);
		if (
			$nextNWS !== false
			&& isset($tokens[$stackPtr]['scope_opener'])
			&& $tokens[$nextNWS]['line'] > $tokens[$tokens[$stackPtr]['scope_opener']]['line'] + 2
		) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.3.5.6') . 'Тело функции начинается на следующей строке после открывающей фигурной скобки или через 1 пустую строку',
                $stackPtr
            );
		}
	}

    /**
     * Processes single-line declarations.
     *
     * Just uses the Generic BSD-Allman brace sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processSingleLineDeclaration(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        $openingBrace = $tokens[$stackPtr]['scope_opener'];

        // The end of the function occurs at the end of the argument list. Its
        // like this because some people like to break long function declarations
        // over multiple lines.
        $functionLine = $tokens[$tokens[$stackPtr]['parenthesis_closer']]['line'];
        $braceLine    = $tokens[$openingBrace]['line'];

        $lineDifference = ($braceLine - $functionLine);

        if ($lineDifference === 0) {
            $error = 'Opening brace should be on a new line';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.5.5') . $error, $openingBrace);
            return;
        }

        if ($lineDifference > 1) {
            $ender = 'line';
            if (($lineDifference - 1) !== 1) {
                $ender .= 's';
            }

            $error = 'Opening brace should be on the line after the declaration; found '.($lineDifference - 1).' blank '.$ender;
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.5.5') . $error, $openingBrace);
            return;
        }

        // We need to actually find the first piece of content on this line,
        // as if this is a method with tokens before it (public, static etc)
        // or an if with an else before it, then we need to start the scope
        // checking from there, rather than the current token.
        $lineStart = $stackPtr;
        while (($lineStart = $phpcsFile->findPrevious(array(T_WHITESPACE), ($lineStart - 1), null, false)) !== false) {
            if (strpos($tokens[$lineStart]['content'], $phpcsFile->eolChar) !== false) {
                break;
            }
        }

        // We found a new line, now go forward and find the first non-whitespace
        // token.
        $lineStart = $phpcsFile->findNext(array(T_WHITESPACE), $lineStart, null, true);

        // The opening brace is on the correct line, now it needs to be
        // checked to be correctly indented.
        $startColumn = $tokens[$lineStart]['column'];
        $braceIndent = $tokens[$openingBrace]['column'];

        if ($braceIndent !== $startColumn) {
            $error = 'Opening brace indented incorrectly; expected '.($startColumn - 1).' spaces, found '.($braceIndent - 1);
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.5.7') . $error, $openingBrace);
        }

    }//end processSingleLineDeclaration()


    /**
     * Processes mutli-line declarations.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processMultiLineDeclaration(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {

		$ws = $tokens[$tokens[$stackPtr]['parenthesis_opener'] + 1];
		if ($ws['code'] !== T_WHITESPACE && $ws['content'] !== "\n") {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.3.5.8') . 'Строка с именем функции должна обрываться после открывающей скобки',
                $stackPtr
            );
		}

		$prevCode = $phpcsFile->findPrevious(T_WHITESPACE, $tokens[$stackPtr]['parenthesis_closer'] - 1, null, true);
		if ($tokens[$prevCode]['line'] === $tokens[$tokens[$stackPtr]['parenthesis_closer']]['line']) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.3.5.9') . 'Закрывающая скобка должна быть на новой строке',
                $stackPtr
            );
		}

        // We need to work out how far indented the function
        // declaration itself is, so we can work out how far to
        // indent parameters.
        $functionIndent = 0;
        for ($i = ($stackPtr - 1); $i >= 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                $i++;
                break;
            }
        }

        if ($tokens[$i]['code'] === T_WHITESPACE) {
            $functionIndent = strlen($tokens[$i]['content']);
        }

        // Each line between the parenthesis should be indented 4 spaces.
        $openBracket  = $tokens[$stackPtr]['parenthesis_opener'];
        $closeBracket = $tokens[$stackPtr]['parenthesis_closer'];
        $lastLine     = $tokens[$openBracket]['line'];
        for ($i = ($openBracket + 1); $i < $closeBracket; $i++) {
            if ($tokens[$i]['line'] !== $lastLine) {
                if ($tokens[$i]['line'] === $tokens[$closeBracket]['line']) {
                    // Closing brace needs to be indented to the same level
                    // as the function.
                    $expectedIndent = $functionIndent;
                } else {
                    $expectedIndent = ($functionIndent + 4);
                }

                // We changed lines, so this should be a whitespace indent token.
                if ($tokens[$i]['code'] !== T_WHITESPACE) {
                    $foundIndent = 0;
                } else {
                    $foundIndent = strlen($tokens[$i]['content']);
                }

                if ($expectedIndent !== $foundIndent) {
					$rc = $i == $closeBracket - 1 ? 'REQ.PHP.3.5.10' : 'REQ.PHP.3.5.11';
                    $error = "Multi-line function declaration not indented correctly; expected $expectedIndent spaces but found $foundIndent";
                    $phpcsFile->addError($this->getReqPrefix($rc) . $error, $i);
                }

                $lastLine = $tokens[$i]['line'];
            }
        }

        if (isset($tokens[$stackPtr]['scope_opener']) === true) {
            // The openning brace needs to be one space away
            // from the closing parenthesis.
            $next = $tokens[($closeBracket + 1)];
            if ($next['code'] !== T_WHITESPACE) {
                $length = 0;
            } else if ($next['content'] === $phpcsFile->eolChar) {
                $length = -1;
            } else {
                $length = strlen($next['content']);
            }

            if ($length !== 1) {
                $error = 'There must be a single space between the closing parenthesis and the opening brace of a multi-line function declaration; found ';
                if ($length === -1) {
                    $error .= 'newline';
                } else {
                    $error .= "$length spaces";
                }

                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.5.12') . $error, ($closeBracket + 1));
                return;
            }

            // And just in case they do something funny before the brace...
            $next = $phpcsFile->findNext(
                T_WHITESPACE,
                ($closeBracket + 1),
                null,
                true
            );

            if ($next !== false && $tokens[$next]['code'] !== T_OPEN_CURLY_BRACKET) {
                $error = 'There must be a single space between the closing parenthesis and the opening brace of a multi-line function declaration';
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.5.12') . $error, $next);
            }
        }

    }//end processMultiLineDeclaration()

}//end class

?>

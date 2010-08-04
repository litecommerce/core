<?php
/**
 * Class Declaration Test.
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

/**
 * Class Declaration Test.
 *
 * Checks the declaration of the class is correct.
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
class XLite_Sniffs_PHP_Classes_ClassDeclarationSniff extends XLite_ReqCodesSniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CLASS,
                T_INTERFACE,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
		static $cache = array();

        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            $error  = 'Possible parse error: ';
            $error .= $tokens[$stackPtr]['content'];
            $error .= ' missing opening or closing brace';
            $phpcsFile->addWarning($this->getReqPrefix('?') . $error, $stackPtr);
            return;
        }

        $curlyBrace  = $tokens[$stackPtr]['scope_opener'];
        $lastContent = $phpcsFile->findPrevious(T_WHITESPACE, ($curlyBrace - 1), $stackPtr, true);
        $classLine   = $tokens[$lastContent]['line'];
        $braceLine   = $tokens[$curlyBrace]['line'];
        if ($braceLine === $classLine) {
            $error  = 'Opening brace of a ';
            $error .= $tokens[$stackPtr]['content'];
            $error .= ' must be on the line after the definition';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.4.1') . $error, $curlyBrace);
            return;

        } else if ($braceLine > ($classLine + 1)) {
            $difference  = ($braceLine - $classLine - 1);
            $difference .= ($difference === 1) ? ' line' : ' lines';
            $error       = 'Opening brace of a ';
            $error      .= $tokens[$stackPtr]['content'];
            $error      .= ' must be on the line following the ';
            $error      .= $tokens[$stackPtr]['content'];
            $error      .= ' declaration; found '.$difference;
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.4.1') . $error, $curlyBrace);
            return;
        }

        if ($tokens[($curlyBrace + 1)]['content'] !== $phpcsFile->eolChar) {
            $type  = strtolower($tokens[$stackPtr]['content']);
            $error = "Opening $type brace must be on a line by itself";
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.4.1') . $error, $curlyBrace);
        }

        if ($tokens[($curlyBrace - 1)]['code'] === T_WHITESPACE) {
            $prevContent = $tokens[($curlyBrace - 1)]['content'];
            if ($prevContent !== $phpcsFile->eolChar) {
                $blankSpace = substr($prevContent, strpos($prevContent, $phpcsFile->eolChar));
                $spaces     = strlen($blankSpace);
                if ($spaces !== 0) {
                    $error = "Expected 0 spaces before opening brace; $spaces found";
                    $phpcsFile->addError($this->getReqPrefix('REQ.PHP.3.4.1') . $error, $curlyBrace);
                }
            }
        }

		if ($tokens[$stackPtr]['code'] === T_CLASS) {
			$pos = 0;
			$classes = array();
			do {
				$pos = $phpcsFile->findNext(T_CLASS, $pos + 1);
				if ($pos !== false) {
					$classes[] = $pos;
				}

			} while ($pos !== false);

			array_shift($classes);
			if (count($classes) > 0 && !isset($cache[$phpcsFile->getFilename()])) {
				$cache[$phpcsFile->getFilename()] = true;

				$error = 'Один файл может содержать объявление только одного класса';
				foreach ($classes as $c) {
					$phpcsFile->addError(
						$this->getReqPrefix('REQ.PHP.3.4.4') . $error,
						$c
					);
				}
			}

		}

		$prevEOL = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, false, "\n");
		$posPrev = $phpcsFile->findPrevious(
			array(T_WHITESPACE, T_COMMENT, T_OPEN_TAG, T_DOC_COMMENT, T_NAMESPACE, T_SEMICOLON, T_STRING, T_NS_SEPARATOR, T_COMMA, T_AS, T_USE),
			$prevEOL - 1,
			null,
			true
		);
		$posNext = $phpcsFile->findNext(
			array(T_WHITESPACE, T_COMMENT, T_CLOSE_TAG, T_DOC_COMMENT),
			$tokens[$stackPtr]['scope_closer'] + 1,
			null,
			true
		);

		$error = 'Размещение дополнительно кода в файле с классом не рекомендуется';

		if ($posPrev !== false) {
            $phpcsFile->addWarning(
                $this->getReqPrefix('WRN.PHP.3.4.1') . $error,
                $posPrev
            );
		}

        if ($posNext !== false) {
            $phpcsFile->addWarning(
                $this->getReqPrefix('WRN.PHP.3.4.1') . $error,
                $posNext
            );
        }

        $pos = $curlyBrace;
        do {
            $pos = $phpcsFile->findNext(T_VARIABLE, $pos + 1, $tokens[$stackPtr]['scope_closer'] - 1);
            if ($pos !== false) {
				end($tokens[$pos]['conditions']);
				$varParent = key($tokens[$pos]['conditions']);

				$prevEOL = $phpcsFile->findPrevious(T_WHITESPACE, $pos - 1, null, false, "\n");
				$isClassVar = $phpcsFile->findNext(array(T_VAR, T_PROTECTED, T_PRIVATE, T_PUBLIC), $prevEOL + 1, $pos - 1);

				if ($varParent === $stackPtr && $isClassVar !== false && !isset($tokens[$pos]['nested_parenthesis'])) {
					$funcPos = $phpcsFile->findNext(T_FUNCTION, $stackPtr + 1, $pos - 1);
					if ($funcPos !== false) {
			            $phpcsFile->addError(
            			    $this->getReqPrefix('REQ.PHP.3.4.5') . 'Переменные класса должны быть определены до определения методов',
			                $pos
            			);
					}

					$prevEOL = $phpcsFile->findPrevious(T_WHITESPACE, $pos - 1, null, false, "\n");
					$posPrivate = $phpcsFile->findNext(T_PRIVATE, $prevEOL + 1, $pos - 1);
					$posPublic = $phpcsFile->findNext(T_PUBLIC, $prevEOL + 1, $pos - 1);
					$posProtected = $phpcsFile->findNext(T_PROTECTED, $prevEOL + 1, $pos - 1);
					if ($posPrivate === false && $posPublic === false && $posProtected === false) {
                        $phpcsFile->addError(
                            $this->getReqPrefix('REQ.PHP.3.4.6') . 'Использование определения области видимости обязательно',
                            $pos
                        );
 					}

					if ($posPublic !== false) {
                        $phpcsFile->addWarning(
                            $this->getReqPrefix('WRN.PHP.3.4.2') . 'Не рекомендуется использовать public переменные класса',
                            $pos
                        );
					}
				}
            }

        } while ($pos !== false);


    }//end process()


}//end class

?>

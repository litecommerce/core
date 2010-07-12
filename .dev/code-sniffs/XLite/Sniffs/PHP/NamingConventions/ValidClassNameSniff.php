<?php
/**
 * XLite_Sniffs_PHP_NamingConventions_ValidClassNameSniff.
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
 * XLite_Sniffs_PHP_NamingConventions_ValidClassNameSniff.
 *
 * Ensures class and interface names start with a capital letter
 * and use _ separators.
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
class XLite_Sniffs_PHP_NamingConventions_ValidClassNameSniff extends XLite_NameSniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The current file being processed.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

		$ns = $phpcsFile->findPrevious(T_NAMESPACE, $stackPtr);

        $className = $phpcsFile->findNext(T_STRING, $stackPtr);
        $name      = trim($tokens[$className]['content']);
		if ($ns) {
			$ns1 = $phpcsFile->findNext(T_STRING, $ns);
			$ns2 = $phpcsFile->findNext(T_SEMICOLON, $ns);
			$ns = $phpcsFile->getTokensAsString($ns1, $ns2 - $ns1);
			$name = $ns . '\\' . $name;
		}

        // Make sure the first letter is a capital.
        if (preg_match('|^[A-Z]|', $name) === 0) {
            $error = ucfirst($tokens[$stackPtr]['content']).' name must begin with a capital letter';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.3.1') . $error, $stackPtr);
        }

        // Check that each new word starts with a capital as well, but don't
        // check the first word, as it is checked above.
        $nameBits  = $this->getWordsByUnderline($name);
		foreach ($nameBits as $bit) {
			$res = $this->checkCamelWord($bit);
			if ($res == -2) {
				$error = "Часть '" . $bit. "' из слова '" .$name . "' не валидна и возможно является аббревиатурой, о которой валидатор не знает. Аббревиатура должна быть зарегестрирована в массиве abbrs.";
				$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.2.1') . $error, $stackPtr);

			} elseif ($res < 0) {
				$error = "The '" . $bit. "' part of the name '" .$name . "' is not valid.";
				$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.3.1') . $error, $stackPtr);
			}
		}

		list($res, $paths) = $this->checkClassPath($nameBits);
		if (!$res) {
			$error = "Пути до файла с объявлением класса '" .$name . "' не существуют (" .implode('; ', $paths). ")";
			$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.3.3') . $error, $stackPtr);
		}

    }//end process()


}//end class


?>

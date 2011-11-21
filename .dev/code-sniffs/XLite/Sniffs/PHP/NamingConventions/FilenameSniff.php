<?php
/**
 * XLite_Sniffs_PHP_Files_FilenameSniff.
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
 * XLite_Sniffs_PHP_Files_FilenameSniff.
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
class XLite_Sniffs_PHP_NamingConventions_FilenameSniff extends XLite_NameSniff
{

	private $endpointFiles = array(
		'admin.php',
		'api.php',
		'callback.php',
		'index.php',
		'payment.php',
		'top.inc.php',
		'config.ini.php',
		'restrictions.ini.php',
		'cron.php',
		'pin.php',
	);

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_OPEN_TAG
               );

    }


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
		$fn = basename($phpcsFile->getFilename());

		if (in_array($fn, $this->endpointFiles)) {
			return;
		}

		$nameBits  = $this->getWordsByUnderline(substr($fn, 0, -4));
        foreach ($nameBits as $bit) {
            $res = $this->checkCamelWord($bit);
            if ($res == -2) {
                $error = "Часть '" . $bit. "' из слова '" .$fn . "' не валидна и возможно является аббревиатурой, о которой валидатор не знает. Аббревиатура должна быть зарегестрирована в массиве abbrs.";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.2.1') . $error, $stackPtr);

            } elseif ($res < 0) {
                $error = "The '" . $bit. "' part of the name '" .$fn . "' is not valid.";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.8.1') . $error, $stackPtr);
            }
        }

		if (substr($fn, -4) != '.php') {
			$error = "Файл '" . $fn. "' имеет расширение, отличное от .php";
			$phpcsFile->addError($this->getReqPrefix('REQ.PHP.1.8.2') . $error, $stackPtr);
		}
	}

}//end class

?>

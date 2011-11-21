<?php
/**
 * XLite_Sniffs_PHP_ControlStructures_SwitchControlStructureSniff.
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
 * XLite_Sniffs_PHP_ControlStructures_SwitchControlStructureSniff.
 *
 * Verifies that inline control statements are not present.
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
class XLite_Sniffs_PHP_ControlStructures_SwitchCaseControlStructureSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CASE,
				T_DEFAULT
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
        $tokens = $phpcsFile->getTokens();

		if ($tokens[$stackPtr]['code'] === T_CASE && isset($tokens[$stackPtr]['scope_closer'])) {
//			$internalCase = $phpcsFile->findNext(T_CASE, $stackPtr + 1, $tokens[$stackPtr]['scope_closer']);
//			if ($internalCase !== false) {
//				$comment = $phpcsFile->findNext(T_COMMENT, $stackPtr + 1, $internalCase - 1);
//				if ($comment === false) {
//					$phpcsFile->addError($this->getReqPrefix('REQ.PHP.2.5.12') . '"case" has not break and has not any comment', $stackPtr);
//				}
//			}

			$switch = $phpcsFile->findPrevious(T_SWITCH, $stackPtr - 1);
			if ($switch !== false) {
				$nextCase = $phpcsFile->findNext(array(T_CASE, T_DEFAULT), $stackPtr + 1, $tokens[$switch]['scope_closer']);
				if ($nextCase !== false) {
					$prevBreak = $phpcsFile->findPrevious(T_BREAK, $nextCase - 1, $stackPtr);
					if ($prevBreak !== false) {
						$breakWS = $phpcsFile->findNext(T_WHITESPACE, $prevBreak + 1, $nextCase - 1);
						if ($breakWS !== false) {
							$str = $phpcsFile->getTokensAsString($breakWS, $nextCase - $breakWS - 1);
							if (!preg_match("/^\n\n[ ]*$/Ss", $str)) {
								$breakWS = false;
							}
						}

						if ($breakWS === false) {
							$phpcsFile->addError($this->getReqPrefix('REQ.PHP.2.5.14') . '"case" must has empty line between current "case" and previous "break"', $stackPtr);
						}
					}
				}
			}

		} elseif ($tokens[$stackPtr]['code'] === T_DEFAULT) {

		}

    }//end process()


}//end class

?>

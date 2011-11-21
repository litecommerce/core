<?php
/**
 * XLite_Sniffs_PHP_Commenting_DocCommentSniff.
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
 * XLite_Sniffs_PHP_Commenting_DocCommentSniff.
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
class XLite_Sniffs_PHP_Commenting_DocCommentSniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_DOC_COMMENT);

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

		if ($tokens[$stackPtr]['content'] !== "/**\n") {
			return;
		}

		$end = $phpcsFile->findNext(T_DOC_COMMENT, $stackPtr + 1, null, true) - 1;

        $column = $tokens[$stackPtr]['column'];

        if ($tokens[$stackPtr]['content'] !== "/**\n") {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.4.1.3') . '������ ������ ����������� ��������� ������ ������� "/**"',
                $stackPtr
            );
        }

        for ($i = $stackPtr + 1; $i < $end - 2; $i++) {
            if (preg_match('/^([ ]*) \*\s/Ss', $tokens[$i]['content'], $match)) {
                if (strlen($match[1]) + 1 !== $column) {
                   $phpcsFile->addError(
						$this->getReqPrefix('REQ.PHP.4.1.28') . '��� ������ ����������� ������� ����� ���������� ������� �������',
                        $i
                    );
                }

            } else {

               $phpcsFile->addError(
					$this->getReqPrefix('REQ.PHP.4.1.4') . '��� ������, ����� ������ � ���������, ���������� � " * "',
                    $i
                );
            }
        }

        if (!preg_match('/^([ ]*) \*\/$/Ss', $tokens[$end]['content'], $match)) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.4.1.5') . '����������� ������������� �������� " */"',
                $end
            );

        } elseif (strlen($match[1]) + 1 !== $column) {
           $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.4.1.28') . '��� ������ ����������� ������� ����� ���������� ������� �������',
                $end
            );
		}
    }

}//end class

?>

<?php
/**
 * XLite_Sniffs_CSS_Commenting_DocCommentSniff.
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
class XLite_Sniffs_CSS_Commenting_DocCommentSniff extends XLite_ReqCodesSniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('CSS');

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

        $column = $tokens[$stackPtr+1]['column'];

        if ($tokens[$stackPtr]['content'] !== "/**\n") {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.CSS.4.0.3') . 'Первая строка комментария содерждит только символы "/**"',
                $stackPtr
            );
        }

        for ($i = $stackPtr + 1; $i < $end; $i++) {
            if (preg_match('/^([\s]*)\*.*$/Ss', $tokens[$i]['content'], $match)) {
                if (strlen($match[1]) !== $column) {
                   $phpcsFile->addError(
						$this->getReqPrefix('REQ.CSS.4.1.7') . 'Все строки комментария должены иметь одинаковый уровень отступа',
                        $i
                    );
                }

            } else {

               $phpcsFile->addError(
					$this->getReqPrefix('REQ.CSS.4.0.4') . 'Все строки, кроме первой и последней, начинаются с " * "',
                    $i
                );
            }
        }

        if (!preg_match('/^([ ]*) \*\/$/Ss', $tokens[$end]['content'], $match)) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.CSS.4.0.5') . 'Комментарий заканчивается строчкой " */"',
                $end
            );

        } 

		if (trim(str_replace("*", $tokens[$end-1]['content'], "")) != '') {
           $phpcsFile->addError(
                $this->getReqPrefix('REQ.CSS.4.1.7') . 'Комментарий к файлу должен иметь строку с пустым комментарием после себя',
                $end
            );
		}
    }

}//end class

?>

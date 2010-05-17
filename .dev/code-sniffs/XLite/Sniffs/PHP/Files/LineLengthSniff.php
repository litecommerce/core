<?php
/**
 * XLite_Sniffs_PHP_Files_LineLengthSniff.
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

if (class_exists('Generic_Sniffs_Files_LineLengthSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_Files_LineLengthSniff not found');
}

/**
 * XLite_Sniffs_PHP_Files_LineLengthSniff.
 *
 * Checks all lines in the file, and throws warnings if they are over 85
 * characters in length.
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
class XLite_Sniffs_PHP_Files_LineLengthSniff extends XLite_ReqCodesSniff
{

    /**
     * The limit that the length of a line should not exceed.
     *
     * @var int
     */
    protected $lineLimit = 80;

    /**
     * The limit that the length of a line must not exceed.
     *
     * Set to zero (0) to disable.
     *
     * @var int
     */
    protected $absoluteLineLimit = 120;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);

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

        // Make sure this is the first open tag.
        $previousOpenTag = $phpcsFile->findPrevious(array(T_OPEN_TAG), ($stackPtr - 1));
        if ($previousOpenTag !== false) {
            return;
        }

        $tokenCount         = 0;
        $currentLineContent = '';
        $currentLine        = 1;

        for (; $tokenCount < $phpcsFile->numTokens; $tokenCount++) {
            if ($tokens[$tokenCount]['line'] === $currentLine) {
                $currentLineContent .= $tokens[$tokenCount]['content'];
            } else {
                $currentLineContent = trim($currentLineContent, $phpcsFile->eolChar);
                $this->checkLineLength($phpcsFile, ($tokenCount - 1), $currentLineContent);
                $currentLineContent = $tokens[$tokenCount]['content'];
                $currentLine++;
            }
        }

        $this->checkLineLength($phpcsFile, ($tokenCount - 1), $currentLineContent);

    }//end process()


    /**
     * Checks if a line is too long.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The token at the end of the line.
     * @param string               $lineContent The content of the line.
     *
     * @return void
     */
    protected function checkLineLength(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $lineContent)
    {
        // If the content is a CVS or SVN id in a version tag, or it is
        // a license tag with a name and URL, there is nothing the
        // developer can do to shorten the line, so don't throw errors.
        if (
			!preg_match('|@version[^\$]+\$Id|', $lineContent)
			&& !preg_match('|@license|', $lineContent)
			&& !preg_match('/^\s*const\s+\S+\s+=\s+.+;\s*$/Ss', $lineContent)
		) {
			$tokens = $phpcsFile->getTokens();
			if (isset($tokens[$stackPtr]['nested_parenthesis'])) {
				$p = key($tokens[$stackPtr]['nested_parenthesis']);
				if (isset($tokens[$p]['parenthesis_owner']) && in_array($tokens[$tokens[$p]['parenthesis_owner']]['code'], array(T_ARRAY))) {
					return;
				}
			} elseif ($tokens[$stackPtr]['code'] == T_HEREDOC) {
				return;
			}

            // Check - is property ?
            if (
                isset($tokens[$stackPtr]['conditions'])
                && $tokens[$stackPtr]['conditions']
                && 1 == count($tokens[$stackPtr]['conditions'])
            ) {
                reset($tokens[$stackPtr]['conditions']);
                $key = key($tokens[$stackPtr]['conditions']);
                if ($tokens[$key]['code'] == T_CLASS) {
					return;
				}
            }

            $lineLength = strlen($lineContent);
            if ($this->absoluteLineLimit > 0 && $lineLength > $this->absoluteLineLimit) {
				$pos = $phpcsFile->findPrevious(T_STATIC, $stackPtr);
				if (!$pos || $tokens[$stackPtr]['line'] != $tokens[$pos]['line']) {
	                $error = 'Line exceeds maximum limit of ' . $this->absoluteLineLimit . " characters; contains $lineLength characters";
    	            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.2.2.1') . $error, $stackPtr);
				}

            } else if ($lineLength > $this->lineLimit) {
				// FIXME - this warning is temporary commented
                // $warning = 'Line exceeds '.$this->lineLimit." characters; contains $lineLength characters";
                // $phpcsFile->addWarning($this->getReqPrefix('WRN.PHP.2.2.1') . $warning, $stackPtr);
            }
        }

    }//end checkLineLength()


}//end class

?>

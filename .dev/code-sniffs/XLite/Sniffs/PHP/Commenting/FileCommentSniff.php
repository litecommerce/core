<?php
/**
 * Parses and verifies the doc comments for files.
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

if (class_exists('PHP_CodeSniffer_CommentParser_ClassCommentParser', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_CommentParser_ClassCommentParser not found');
}

/**
 * Parses and verifies the doc comments for files.
 *
 * Verifies that :
 * <ul>
 *  <li>A doc comment exists.</li>
 *  <li>There is a blank newline after the short description.</li>
 *  <li>There is a blank newline between the long and short description.</li>
 *  <li>There is a blank newline between the long description and tags.</li>
 *  <li>A PHP version is specified.</li>
 *  <li>Check the order of the tags.</li>
 *  <li>Check the indentation of each tag.</li>
 *  <li>Check required and optional tags and the format of their content.</li>
 * </ul>
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

class XLite_Sniffs_PHP_Commenting_FileCommentSniff extends XLite_TagsSniff
{

    /**
     * Tags in correct order and related info.
     *
     * @var array
     */
    protected $tags = array(
                       'category'   => array(
                                        'required'       => true,
                                        'allow_multiple' => false,
                                        'order_text'     => 'precedes @package',
                                       ),
                       'author'     => array(
                                        'required'       => true,
                                        'allow_multiple' => true,
                                        'order_text'     => 'follows @category',
										'internal'       => array(
											'allow_multiple' => false,
										),
                                       ),
                       'copyright'  => array(
                                        'required'       => true,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @author',
                                       ),
                       'license'    => array(
                                        'required'       => true,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @copyright (if used) or @author',
                                       ),
                       /* 'version'    => array(
                                        'required'       => true,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @license',
                                       ),*/
                       'link'       => array(
                                        'required'       => true,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @version',
                                       ),
                       'see'        => array(
                                        'required'       => false,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @link',
                                       ),
                       'since'      => array(
                                        'required'       => false,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @see (if used) or @link',
                                       ),
                       'deprecated' => array(
                                        'required'       => false,
                                        'allow_multiple' => false,
                                        'order_text'     => 'follows @since (if used) or @see (if used) or @link',
                                       ),
                );


	// protected $reqCodeRequire = 'REQ.PHP.4.3.4';
	protected $reqCodePHPVersion = 'REQ.PHP.4.3.3';
	protected $reqCodeForbidden = 'REQ.PHP.4.3.9';
	protected $reqCodeOnlyOne = 'REQ.PHP.4.3.8';

	protected $docBlock = 'file';

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
        $this->currentFile = $phpcsFile;

        // We are only interested if this is the first open tag.
        if ($stackPtr !== 0) {
            if ($phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1)) !== false) {
                return;
            }
        }

        $tokens = $phpcsFile->getTokens();

        // Find the next non whitespace token.
        $commentStart
            = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

		if ($tokens[$commentStart]['code'] !== T_COMMENT) {
			$phpcsFile->addError(
				$this->getReqPrefix('REQ.PHP.4.3.5') . 'Файл должен начинаться с однострочного комментария',
				$commentStart
			);

		} elseif (!preg_match('/vim: set ts=\d sw=\d sts=\d et:/', $tokens[$commentStart]['content'])) {
            $phpcsFile->addError(
                $this->getReqPrefix('REQ.PHP.4.3.5') . 'Файл должен начинаться с однострочного комментария с директивой для vim',
                $commentStart
            );
		}

        $commentStart = $phpcsFile->findNext(
            T_WHITESPACE,
            ($commentStart + 1),
            null,
            true
        );

        $errorToken = ($stackPtr + 1);
        if (isset($tokens[$errorToken]) === false) {
            $errorToken--;
        }

        if ($tokens[$commentStart]['code'] === T_CLOSE_TAG) {
            // We are only interested if this is the first open tag.
            return;

        } else if ($tokens[$commentStart]['code'] === T_COMMENT) {
            $error = 'You must use "/**" style comments for a file comment';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.3.7') . $error, $errorToken);
            return;
        } else if ($commentStart === false
            || $tokens[$commentStart]['code'] !== T_DOC_COMMENT
        ) {
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.3.1') . 'Missing file doc comment', $errorToken);
            return;
        } else {

            // Extract the header comment docblock.
            $commentEnd = $phpcsFile->findNext(
                T_DOC_COMMENT,
                ($commentStart + 1),
                null,
                true
            );

            $commentEnd--;

            // Check if there is only 1 doc comment between the
            // open tag and class token.
            $nextToken   = array(
                            T_ABSTRACT,
                            T_CLASS,
                            T_FUNCTION,
                            T_DOC_COMMENT,
                           );

            $commentNext = $phpcsFile->findNext($nextToken, ($commentEnd + 1));
            if ($commentNext !== false
                && $tokens[$commentNext]['code'] !== T_DOC_COMMENT
            ) {
                // Found a class token right after comment doc block.
                $newlineToken = $phpcsFile->findNext(
                    T_WHITESPACE,
                    ($commentEnd + 1),
                    $commentNext,
                    false,
                    $phpcsFile->eolChar
                );

                if ($newlineToken !== false) {
                    $newlineToken = $phpcsFile->findNext(
                        T_WHITESPACE,
                        ($newlineToken + 1),
                        $commentNext,
                        false,
                        $phpcsFile->eolChar
                    );

                    if ($newlineToken === false) {
                        // No blank line between the class token and the doc block.
                        // The doc block is most likely a class comment.
                        $error = 'Missing file doc comment';
                        $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.6') . $error, $errorToken);
                        return;
                    }
                }
            }//end if

            $comment = $phpcsFile->getTokensAsString(
                $commentStart,
                ($commentEnd - $commentStart + 1)
            );

            // Parse the header comment docblock.
            try {
                $this->commentParser = new PHP_CodeSniffer_CommentParser_ClassCommentParser($comment, $phpcsFile);
                $this->commentParser->parse();
            } catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
                $line = ($e->getLineWithinComment() + $commentStart);
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.1') . $e->getMessage(), $line);
                return;
            }

            $comment = $this->commentParser->getComment();
            if (is_null($comment) === true) {
                $error = 'File doc comment is empty';
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.3.2') . $error, $commentStart);
                return;
            }

            // No extra newline before short description.
            $short        = $comment->getShortComment();
            $newlineCount = 0;
            $newlineSpan  = strspn($short, $phpcsFile->eolChar);
            if ($short !== '' && $newlineSpan > 0) {
                $line  = ($newlineSpan > 1) ? 'newlines' : 'newline';
                $error = "Extra $line found before file comment short description";
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.7') . $error, ($commentStart + 1));
            }

            $newlineCount = (substr_count($short, $phpcsFile->eolChar) + 1);

            // Exactly one blank line between short and long description.
            $long = $comment->getLongComment();
            if (empty($long) === false) {
                $between        = $comment->getWhiteSpaceBetween();
                $newlineBetween = substr_count($between, $phpcsFile->eolChar);
                if ($newlineBetween !== 2) {
                    $error = 'There must be exactly one blank line between descriptions in file comment';
                    $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.19') . $error, ($commentStart + $newlineCount + 1));
                }

                $newlineCount += $newlineBetween;
            }

            // Exactly one blank line before tags.
            $tags = $this->commentParser->getTagOrders();
            if (count($tags) > 1) {
                $newlineSpan = $comment->getNewlineAfter();
                if ($newlineSpan !== 2) {
                    $error = 'There must be exactly one blank line before the tags in file comment';
                    if ($long !== '') {
                        $newlineCount += (substr_count($long, $phpcsFile->eolChar) - $newlineSpan + 1);
                    }

                    $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.18') . $error, ($commentStart + $newlineCount));
                    $short = rtrim($short, $phpcsFile->eolChar.' ');
                }
            }

            // Check the PHP Version.
            $this->processPHPVersion($commentStart, $commentEnd, $long);

            // Check each tag.
            $this->processTags($commentStart, $commentEnd);
        }//end if

    }//end process()

    /**
     * check category tag
     *
     * @param   string    $content Tag content
     * @access  protected
     * @return  array
     * @since   1.0.0
     */
    protected function checkCategory($content)
    {
        $result = parent::checkCategory($content);

		if ($result[0] && $result[1] != 'LiteCommerce') {
			$result = array(false, 'LiteCommerce');
		
		}

        return $result;
    }

}//end class

?>

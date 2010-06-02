<?php
/**
 * Parses and verifies the doc comments for functions.
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

if (class_exists('PHP_CodeSniffer_CommentParser_FunctionCommentParser', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_CommentParser_FunctionCommentParser not found');
}

/**
 * Parses and verifies the doc comments for functions.
 *
 * Verifies that :
 * <ul>
 *  <li>A comment exists</li>
 *  <li>There is a blank newline after the short description.</li>
 *  <li>There is a blank newline between the long and short description.</li>
 *  <li>There is a blank newline between the long description and tags.</li>
 *  <li>Parameter names represent those in the method.</li>
 *  <li>Parameter comments are in the correct order</li>
 *  <li>Parameter comments are complete</li>
 *  <li>A space is present before the first and after the last parameter</li>
 *  <li>A return type exists</li>
 *  <li>There must be one blank line between body and headline comments.</li>
 *  <li>Any throw tag must have an exception class.</li>
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
class XLite_Sniffs_PHP_Commenting_ClassVarCommentSniff extends XLite_TagsSniff
{

    /**
     * The function comment parser for the current method.
     *
     * @var PHP_CodeSniffer_Comment_Parser_FunctionCommentParser
     */
    protected $commentParser = null;

    /**
     * The current PHP_CodeSniffer_File object we are processing.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $currentFile = null;

	protected $tags = array(
    	'var'    => array(
        	'required'       => true,
            'allow_multiple' => false,
            'order_text'     => 'precedes @access',
        ),
        'access' => array(
            'required'       => true,
            'allow_multiple' => false,
            'order_text'     => 'follows @var',
        ),
        'see'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @since',
        ),
        'since'      => array(
            'required'       => true,
            'allow_multiple' => false,
            'order_text'     => 'follows @access',
        ),
        'Column'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @since',
        ),
        'OneToMany'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @Column',
        ),
        'OneToOne'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @Column',
        ),
        'ManyToMany'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @Column',
        ),
        'Id'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @Column',
        ),
        'GeneratedValue'      => array(
            'required'       => false,
            'allow_multiple' => false,
            'order_text'     => 'follows @Column',
        ),


	);

    protected $reqCodeRequire = array('REQ.PHP.4.6.3');
    protected $reqCodePHPVersion = false;
    protected $reqCodeForbidden = 'REQ.PHP.4.6.3';
    protected $reqCodeOnlyOne = 'REQ.PHP.4.6.5';

    protected $docBlock = 'variable';

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VARIABLE);

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
        $find = array(
                 T_COMMENT,
                 T_DOC_COMMENT,
                 T_CLASS,
                 T_FUNCTION,
                 T_OPEN_TAG,
                );

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));

        if ($commentEnd === false) {
            return;
        }

        $this->currentFile = $phpcsFile;
        $tokens            = $phpcsFile->getTokens();

		if (isset($tokens[$stackPtr]['nested_parenthesis'])) {
			return;
		}

		if (!isset($tokens[$stackPtr]['conditions'])) {
			return;
		}

		end($tokens[$stackPtr]['conditions']);
		$ckey = key($tokens[$stackPtr]['conditions']);
		if (!isset($tokens[$ckey]) || ($tokens[$ckey]['code'] !== T_CLASS && $tokens[$ckey]['code'] !== T_INTERFACE)) {
			return;
		}

        // If the token that we found was a class or a function, then this
        // function has no doc comment.
        $code = $tokens[$commentEnd]['code'];

        if ($code === T_COMMENT) {
            $error = 'You must use "/**" style comments for a variable comment';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.6.4') . $error, $stackPtr);
            return;

        } else if ($code !== T_DOC_COMMENT) {
			$error = 'Missing variable doc comment';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.6.1') . $error, $stackPtr);
            return;
        }

        // If there is any code between the function keyword and the doc block
        // then the doc block is not for us.
        $ignore    = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $ignore[]  = T_STATIC;
        $ignore[]  = T_WHITESPACE;
        $ignore[]  = T_ABSTRACT;
        $ignore[]  = T_FINAL;
        $prevToken = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($prevToken !== $commentEnd) {
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.6.1') . 'Missing function doc comment', $stackPtr);
            return;
        }

        // If the first T_OPEN_TAG is right before the comment, it is probably
        // a file comment.
        $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
        $prevToken    = $phpcsFile->findPrevious(T_WHITESPACE, ($commentStart - 1), null, true);
        if ($tokens[$prevToken]['code'] === T_OPEN_TAG) {
            // Is this the first open tag?
            if ($stackPtr === 0 || $phpcsFile->findPrevious(T_OPEN_TAG, ($prevToken - 1)) === false) {
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.6.1') . 'Missing function doc comment', $stackPtr);
                return;
            }
        }

        $comment = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

        try {
            $this->commentParser = new XLite_FunctionCommentParser($comment, $phpcsFile);
            $this->commentParser->parse();
        } catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
            $line = ($e->getLineWithinComment() + $commentStart);
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.1') . $e->getMessage(), $line);
            return;
        }

        $comment = $this->commentParser->getComment();
        if (is_null($comment) === true) {
            $error = 'Variable doc comment is empty';
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.6.2') . $error, $commentStart);
            return;
        }

		$this->checkAccess($stackPtr, $commentStart, $commentEnd);

        $this->processTags($commentStart, $commentEnd);

        // No extra newline before short description.
        $short        = $comment->getShortComment();
        $newlineCount = 0;
        $newlineSpan  = strspn($short, $phpcsFile->eolChar);
        if ($short !== '' && $newlineSpan > 0) {
            $line  = ($newlineSpan > 1) ? 'newlines' : 'newline';
            $error = "Extra $line found before variable comment short description";
            $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.7') . $error, ($commentStart + 1));
        }

        $newlineCount = (substr_count($short, $phpcsFile->eolChar) + 1);

        // Exactly one blank line between short and long description.
        $long = $comment->getLongComment();
        if (empty($long) === false) {
            $between        = $comment->getWhiteSpaceBetween();
            $newlineBetween = substr_count($between, $phpcsFile->eolChar);
            if ($newlineBetween !== 2) {
                $error = 'There must be exactly one blank line between descriptions in variable comment';
                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.19') . $error, ($commentStart + $newlineCount + 1));
            }

            $newlineCount += $newlineBetween;
        }

        // Exactly one blank line before tags.
        $params = $this->commentParser->getTagOrders();
        if (count($params) > 1) {
            $newlineSpan = $comment->getNewlineAfter();
            if ($newlineSpan !== 2) {
                $error = 'There must be exactly one blank line before the tags in function comment';
                if ($long !== '') {
                    $newlineCount += (substr_count($long, $phpcsFile->eolChar) - $newlineSpan + 1);
                }

                $phpcsFile->addError($this->getReqPrefix('REQ.PHP.4.1.18') . $error, ($commentStart + $newlineCount));
                $short = rtrim($short, $phpcsFile->eolChar.' ');
            }
        }

    }//end process()

}//end class

?>

<?php
/**
 * @version $Id$
 */

class XLite_TagsSniff extends XLite_ReqCodesSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * The name of the method that we are currently processing.
     *
     * @var string
     */
    protected $_methodName = '';

    /**
     * The position in the stack where the fucntion token was found.
     *
     * @var int
     */
    protected $_functionToken = null;

    /**
     * The position in the stack where the class token was found.
     *
     * @var int
     */
    protected $_classToken = null;

    /**
     * The header comment parser for the current file.
     *
     * @var PHP_CodeSniffer_Comment_Parser_ClassCommentParser
     */
    protected $commentParser = null;

    /**
     * The current PHP_CodeSniffer_File object we are processing.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $currentFile = null;

    /**
     * Tags in correct order and related info.
     *
     * @var array
     */
    protected $tags = array();

	protected $reqCodesWrongFormat = array(
		'category'		=> array(
			'code'		=> 'REQ.PHP.4.1.11',
			'function'	=> 'getCategory',
			'type'		=> 'single',
		),
		'package'		=> array(
			'code'		=> 'REQ.PHP.4.1.11',
			'function'	=> 'getPackage',
			'type'		=> 'single',
		),
		'subpackage'	=> array(
			'code'		=> 'REQ.PHP.4.1.11',
			'function'	=> 'getSubpackage',
			'type'		=> 'single',
		),
		'author'		=> array(
			'code'		=> 'REQ.PHP.4.1.13',
			'function'	=> 'getAuthors',
			'type'		=> 'array',
		),
		'copyright'		=> array(
			'code'		=> 'REQ.PHP.4.1.14',
			'function'	=> 'getCopyrights',
			'type'		=> 'array',
		),
		'license'		=> array(
			'code'		=> 'REQ.PHP.4.1.15',
			'function'	=> 'getLicense',
			'type'		=> 'single',
		),
		'version'		=> array(
			'code'		=> 'REQ.PHP.4.1.16',
			'function'	=> 'getVersion',
			'type'		=> 'single',
		),
		'param'			=> array(
			'code'		=> 'REQ.PHP.4.1.26',
			'function'	=> '',
			'type'		=> '',
		),
	);

	protected $reqCodePHPVersion = false;
	protected $reqCodeRequire = false;
	protected $reqCodeForbidden = false;
	protected $reqCodeOnlyOne = false;
	protected $reqCodeWrongOrder = 'REQ.PHP.4.1.9';
	protected $reqCodeUngroup = 'REQ.PHP.4.1.10';
	protected $reqCodeIndent = 'REQ.PHP.4.1.8';
	protected $reqCodeEmpty = 'REQ.PHP.4.1.12';
	protected $reqCodeDefault = 'REQ.PHP.4.1.20';

	protected $docBlock = 'unknown';

    protected $allowedParamTypes = array(
        'integer', 'float', 'string', 'array', 'mixed', 'boolean', 'null', 'object', 'resource',
    );

    protected $allowedReturnTypes = array(
        'integer', 'float', 'string', 'array', 'mixed', 'boolean', 'void', 'object', 'resource',
    );

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
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
	}

    /**
     * Check that the PHP version is specified.
     *
     * @param int    $commentStart Position in the stack where the comment started.
     * @param int    $commentEnd   Position in the stack where the comment ended.
     * @param string $comment      The text of the function comment.
     *
     * @return void
     */
    protected function processPHPVersion($commentStart, $commentEnd, $commentText)
    {
        if ($this->reqCodePHPVersion && preg_match('/PHP version \d+\.\d+\.\d+$/Sm', $commentText) === false) {
            $error = 'PHP version not specified';
            $this->currentFile->addError($this->getReqPrefix($this->reqCodePHPVersion) . $error, $commentEnd);
        }

    }//end processPHPVersion()

    /**
     * Processes each required or optional tag.
     *
     * @param int $commentStart Position in the stack where the comment started.
     * @param int $commentEnd   Position in the stack where the comment ended.
     *
     * @return void
     */
    protected function processTags($commentStart, $commentEnd)
    {
        $foundTags   = $this->commentParser->getTagOrders();
        $orderIndex  = 0;
        $indentation = array();
        $longestTag  = 0;
        $errorPos    = 0;

		$this->checkGotchas($commentStart, $commentEnd);

        $diff = array_diff($foundTags, array_keys($this->tags), array('comment'));
        if (count($diff) > 0 && $this->reqCodeForbidden) {
            foreach ($diff as $tag) {
                $error = "Forbidden @$tag tag in " . $this->docBlock ." comment";
				
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeForbidden) . $error, $commentEnd);
            }
        }

        foreach ($this->tags as $tag => $info) {

            // Required tag missing.
            if ($info['required'] === true && in_array($tag, $foundTags) === false && $this->reqCodeRequire) {
                $error = "Missing @$tag tag in " .$this->docBlock . " comment";
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeRequire) . $error, $commentEnd);
                continue;
            }

             // Get the line number for current tag.
            $tagName = ucfirst($tag);
            if ($info['allow_multiple'] === true) {
                $tagName .= 's';
            }

            $getMethod  = 'get'.$tagName;
			if (!method_exists($this->commentParser, $getMethod) && $info['allow_multiple'] !== true) {
				$getMethod .= 's';
			}

			if (!method_exists($this->commentParser, $getMethod))
				continue;

	        $tagElement = $this->commentParser->$getMethod();
    	    if (is_null($tagElement) === true || empty($tagElement) === true) {
                continue;
           	}

			$tagElements = is_array($tagElement) ? $tagElement : array($tagElement);

            $errorPos = $commentStart;
            if (is_array($tagElement) === false) {
                $errorPos = ($commentStart + $tagElement->getLine());
            }

            // Get the tag order.
            $foundIndexes = array_keys($foundTags, $tag);

            if (count($foundIndexes) > 1) {
                // Multiple occurance not allowed.
                if ($info['allow_multiple'] === false) {
					if ($this->reqCodeOnlyOne) {
	                    $error = "Only 1 @$tag tag is allowed in a " . $this->docBlock ." comment";
    	                $this->currentFile->addError($this->getReqPrefix($this->reqCodeOnlyOne) . $error, $errorPos);
					}

                } else {
                    // Make sure same tags are grouped together.
                    $i     = 0;
                    $count = $foundIndexes[0];
                    foreach ($foundIndexes as $index) {
                        if ($index !== $count) {
                            $errorPosIndex = ($errorPos + $tagElement[$i]->getLine());
                            $error = "@$tag tags must be grouped together";
                            $this->currentFile->addError($this->getReqPrefix($this->reqCodeUngroup) . $error, $errorPosIndex);
                        }

                        $i++;
                        $count++;
                    }
                }
            }//end if

            // Check tag order.
            if ($foundIndexes[0] > $orderIndex) {
                $orderIndex = $foundIndexes[0];
            } else {
                if (is_array($tagElement) === true && empty($tagElement) === false) {
                    $errorPos += $tagElement[0]->getLine();
                }

                $orderText = $info['order_text'];
                $error = "The @$tag tag is in the wrong order; the tag $orderText";
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeWrongOrder) . $error, $errorPos);
            }

            // Store the indentation for checking.
            $len = strlen($tag);
            if ($len > $longestTag) {
                $longestTag = $len;
            }

            foreach ($tagElements as $key => $element) {
            	$indentation[] = array(
                	'tag'   => $tag,
                    'space' => $this->getIndentation($tag, $element),
                    'line'  => $element->getLine(),
                    'value' => $this->getTagValue($element),
                );
            }

            $method = 'process' . $tagName;
            if (method_exists($this, $method) === true) {
                // Process each tag if a method is defined.
                call_user_func(array($this, $method), $errorPos, $commentEnd, $tagElements);

            } else {
                foreach ($tagElements as $key => $element) {
					if (method_exists($element, 'process')) {
                        $element->process(
                            $this->currentFile,
                            $commentStart,
                            $this->docBlock
                        );
                    }
                }
            }
        }//end foreach

        foreach ($indentation as $indentInfo) {

			$this->checkForDefaultValue($indentInfo['value'], $indentInfo['tag'], $commentStart + $indentInfo['line']);

            if ($indentInfo['space'] !== 0
                && $indentInfo['space'] !== ($longestTag + 1)
            ) {
                $expected = (($longestTag - strlen($indentInfo['tag'])) + 1);
                $space    = ($indentInfo['space'] - strlen($indentInfo['tag']));
                $error    = "@$indentInfo[tag] tag comment indented incorrectly. ";
                $error   .= "Expected $expected spaces but found $space.";

                $getTagMethod = isset($this->reqCodesWrongFormat[$indentInfo['tag']]) ? $this->reqCodesWrongFormat[$indentInfo['tag']]['function'] : false;

				$line = $indentInfo['line'];
                if ($this->tags[$indentInfo['tag']]['allow_multiple'] === true) {
                    $line = $indentInfo['line'];

                } elseif ($getTagMethod && method_exists($this->commentParser, $getTagMethod)) {
                    $tagElem = $this->commentParser->$getTagMethod();
					if ('array' === $this->reqCodesWrongFormat[$indentInfo['tag']]['type']) {
						$tagElem = array_pop($tagElem);
					}
                    $line = $tagElem->getLine();
                }

                $this->currentFile->addError($this->getReqPrefix($this->reqCodeIndent) . $error, ($commentStart + $line));
            }
        }

    }//end processTags()

	protected function checkForDefaultValue($value, $tag, $line) {

		// REMOVE THIS LATER
		return;

		if (preg_match('/____\w+____/', $value)) {
			$error = 'Тег @' . $tag  . ' имеет дефолтное значение. Его необходимо сменить';
			$this->currentFile->addError($this->getReqPrefix($this->reqCodeDefault) . $error, $line);
			return true;
		}

		return false;
	}

	protected function checkGotchas($commentStart, $commentEnd) {
		$gotchas = $this->getGotchas($commentStart, $commentEnd);

		$lastPos = $commentStart;
		foreach ($gotchas as $g) {
			if (!in_array($g['name'], array('TODO', 'FIXME', 'KLUDGE', 'TRICKY', 'WARNING', 'PARSER'))) {
				$this->currentFile->addError(
					$this->getReqPrefix('REQ.PHP.4.2.1') . 'При использовании gotchas необходимо использовать зарезервированные слова',
					$g['begin']
				);
			}

			if ($g['begin'] != $lastPos + 1) {
                $this->currentFile->addError(
                    $this->getReqPrefix('REQ.PHP.4.2.2') . 'Ключевое слово gotcha должно ставиться в самом начале комментария',
                    $g['begin']
                );
			}

			if ($g['link']['type'] && !in_array($g['link']['type'], array('M', 'C', 'T'))) {
                $this->currentFile->addError(
                    $this->getReqPrefix('REQ.PHP.4.2.4', 'REQ.PHP.4.2.5', 'REQ.PHP.4.2.6') . 'Тип ссыли должен быть M или C или T',
                    $g['begin']
                );
			}

			$lastPos = $g['end'];
		}
	}

	protected function getGotchas($commentStart, $commentEnd) {

		$tokens = $this->currentFile->getTokens();

		$gotchas = array();

		$idx = false;
		for ($i = $commentStart + 1; $i < $commentEnd - 2; $i++) {
			if (!preg_match('/\s+:([\w\d\_]+): (.+)$/S', $tokens[$i]['content'], $match)) {
				if ($idx !== false) {
					if (preg_match('/^[ ]* \*\s*$/S', $tokens[$i]['content'])) {
						$gotchas[$idx]['end'] = $i - 1;
						$idx = false;

					} elseif (preg_match('/^[ ]* \*(.+)$/S', $tokens[$i]['content'], $match)) {
						$gotchas[$idx]['text'] .= ' ' . trim($match[1]);
					}
				}
				
				continue;
			}

			$gotcha = array(
				'name' => $match[1],
				'text' => trim($match[2]),
				'begin' => $i,
				'end' => $i,
				'link' => array(
					'type' => false,
					'id' => false
				)
			);

			if (preg_match('/^([\w]):([\d]+) /S', $gotcha['text'], $match)) {
				$gotcha['link']['type'] = $match[1];
				$gotcha['link']['id'] = $match[2];
				$gotcha['text'] = trim(substr($gotcha['text'], strlen($match[0])));
			}

			$idx = count($gotchas);
			$gotchas[$idx] = $gotcha;
		}

		return $gotchas;

	}

	/**
	 * getTagValue 
	 * 
	 * @param string $tagElement The doc comment element
	 *  
	 * @return void
	 * @access protected
	 */
	protected function getTagValue($tagElement, &$type = '')
	{
		if ($tagElement instanceof PHP_CodeSniffer_CommentParser_SingleElement) {
			$type = 'single';
			return $tagElement->getContent();
		} elseif ($tagElement instanceof PHP_CodeSniffer_CommentParser_PairElement) {
			$type = 'pair';
			return $tagElement->getValue() . ' ' . $tagElement->getComment();
		}

		return '';
	}

    /**
     * Get the indentation information of each tag.
     *
     * @param string                                   $tagName    The name of the
     *                                                             doc comment
     *                                                             element.
     * @param PHP_CodeSniffer_CommentParser_DocElement $tagElement The doc comment
     *                                                             element.
     *
     * @return void
     */
    protected function getIndentation($tagName, $tagElement)
    {
		$elementType = '';

		if ('' !== $this->getTagValue($tagElement, $elementType)) {
			$funcName = '';

			if ($elementType == 'single') {
				$funcName = 'getWhitespaceBeforeContent';
			} elseif ($elementType == 'pair') {
				$funcName = 'getWhitespaceBeforeValue';
			}

			if (!empty($funcName)) {
				return (strlen($tagName) + substr_count($tagElement->$funcName(), ' '));
			}
		}
		
		return 0;
    }//end getIndentation()

    /**
     * Process the category tag.
     *
     * @param int $errorPos The line number where the error occurs.
     *
     * @return void
     */
    protected function processCategory($errorPos)
    {
        $tag = $this->commentParser->getCategory();
        if ($tag !== null) {
            $content = $tag->getContent();
            if ($content !== '') {
				list($isValid, $validName) = $this->checkCategory($content);
   	            if (!$isValid) {
       	            $error = "Category name \"$content\" is not valid; consider \"$validName\" instead";
           	        $this->currentFile->addError($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'category')) . $error, $errorPos);
               	}
             } else {
                $error = '@category tag must contain a name';
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $errorPos);
            }
        }

    }

	/**
	 * check category tag
	 * 
	 * @param   string	  $content Tag content
	 * @access  protected
	 * @return  array
	 * @since   1.0.0
	 */
	protected function checkCategory($content)
	{
		$result = array(true, $content);

		if (PHP_CodeSniffer::isUnderscoreName($content) !== true) {
			$result = array(false, $this->sanitazeUnderscoreName($content));
		}

		return $result;
	}

    /**
     * Process the package tag.
     *
     * @param int $errorPos The line number where the error occurs.
     *
     * @return void
     */
    protected function processPackage($errorPos)
    {
        $tag = $this->commentParser->getPackage();
        if ($tag !== null) {
            $content = $tag->getContent();
            if ($content !== '') {
				list($isValid, $validName) = $this->checkPackage($content);
                if (!$isValid) {
                    $error = "Package name \"$content\" is not valid; consider \"$validName\" instead";
                    $this->currentFile->addError($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'package')) . $error, $errorPos);
                }
             } else {
                $error = '@package tag must contain a name';
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $errorPos);
            }
        }

    }

	/**
	 * check package tag
	 * 
	 * @param   string	  $content Tag content
	 * @access  protected
	 * @return  array
	 * @since   1.0.0
	 */
	protected function checkPackage($content)
	{
		$result = array(true, $content);

		if (PHP_CodeSniffer::isUnderscoreName($content) !== true) {
			$result = array(false, $this->sanitazeUnderscoreName($content));
		}

		return $result;
	}

    /**
     * Process the subpackage tag.
     *
     * @param int $errorPos The line number where the error occurs.
     *
     * @return void
     */
    protected function processSubpackage($errorPos)
    {
        $tag = $this->commentParser->getSubpackage();
        if ($tag !== null) {
            $content = $tag->getContent();
            if ($content !== '') {
				list($isValid, $validName) = $this->checkSubpackage($content);
                if (!$isValid) {
                    $error = "Subpackage name \"$content\" is not valid; consider \"$validName\" instead";
                    $this->currentFile->addError($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'subpackage')) . $error, $errorPos);
                }
             } else {
                $error = '@subpackage tag must contain a name';
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $errorPos);
            }
        }

    }

	/**
	 * check subpackage tag
	 * 
	 * @param   string	  $content Tag content
	 * @access  protected
	 * @return  array
	 * @since   1.0.0
	 */
	protected function checkSubpackage($content)
	{
		$result = array(true, $content);

		if (PHP_CodeSniffer::isUnderscoreName($content) !== true) {
			$result = array(false, $this->sanitazeUnderscoreName($content));
		}

		return $result;
	}

    protected function processAuthors($commentStart)
    {
         $authors = $this->commentParser->getAuthors();
        // Report missing return.
        if (empty($authors) === false) {
            foreach ($authors as $author) {
                $errorPos = ($commentStart + $author->getLine());
                $content  = $author->getContent();
                if ($content !== 'Ruslan R. Fazliev <rrf@x-cart.com>') {
                    $error = 'Content of the @author tag must be in the form "Ruslan R. Fazliev <rrf@x-cart.com>"';
                    $this->currentFile->addError($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'author')) . $error, $errorPos);

                } else {
                    $error = "Content missing for @author tag in " . $this->docBlock ." comment";
                    $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . ' ' . $error, $errorPos);
                }
            }
        }

    }//end processAuthors()

    protected function processCopyrights($commentStart)
    {
        $copyrights = $this->commentParser->getCopyrights();
        foreach ($copyrights as $copyright) {
            $errorPos = ($commentStart + $copyright->getLine());
            $content  = $copyright->getContent();
			if (empty($content)) {
	            $bYear = '2009';
    	        $eYear = date('Y');
        	    $text = 'Copyright (c) ' . (($bYear == $eYear) ? $bYear : $bYear . '-' . $eYear) . ' Ruslan R. Fazliev <rrf@x-cart.com>';
            	if ($content !== $text) {
                	$error = 'Content of the @copyright tag must be in the form "' . $text . '"';
	                $this->currentFile->addError($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'copyright')) . $error, $errorPos);
    	        }//end if
			} else {
                $error = "Content missing for @copyright tag in " . $this->docBlock ." comment";
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . ' ' . $error, $errorPos);
			}
        }//end if

    }//end processCopyrights()

    protected function processLicense($errorPos)
    {
        $license = $this->commentParser->getLicense();
        if ($license !== null) {
            $value   = $license->getValue();
            $comment = $license->getComment();
			$content = $value . ' ' . $comment;
			if (empty($content)) {
                $error = "Content missing for @license tag in " . $this->docBlock ." comment";
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . ' ' . $error, $errorPos);

            } elseif ($content !== 'http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)') {
                $error = 'Content of the @license tag must be in the form "http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement"';
                $this->currentFile->addError($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'license')) . $error, $errorPos);
			}
        }

    }//end processLicense()

    protected function processVersion($errorPos)
    {
        $version = $this->commentParser->getVersion();
        if ($version !== null) {
            $content = $version->getContent();
            $matches = array();
            if (empty($content) === true) {
                $error = 'Content missing for @version tag in file comment';
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $errorPos);

            } else if (!preg_match('/^SVN: \$' . 'Id: [\w\d_\.]+ \d+ \d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}Z [\w\d_]+ \$$/Ss', $content)) {
                $error = "Invalid version \"$content\" in file comment; consider \"SVN: <svn_id>\" instead";
                $this->currentFile->addWarning($this->getReqPrefix($this->getReqCode($this->reqCodesWrongFormat, 'version')) . $error, $errorPos);
            }
        }

    }//end processVersion()

    protected function processThrows($errorPos)
    {

        if (count($this->commentParser->getThrows()) === 0) {
            return;
        }

        foreach ($this->commentParser->getThrows() as $throw) {

            $exception = $throw->getValue();
            $errorPos  = ($commentStart + $throw->getLine());

            if ($exception === '') {
                $error = '@throws tag must contain the exception class name';
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $errorPos);
            }
        }

    }

    /**
     * Process the return comment of this function comment.
     *
     * @param int $commentStart The position in the stack where the comment started.
     * @param int $commentEnd   The position in the stack where the comment ended.
     *
     * @return void
     */
    protected function processReturn($commentStart, $commentEnd)
    {
        // Skip constructor and destructor.
        $className = '';
        if ($this->_classToken !== null) {
            $className = $this->currentFile->getDeclarationName($this->_classToken);
            $className = strtolower(ltrim($className, '_'));
        }

        $methodName      = strtolower(ltrim($this->_methodName, '_'));
        $isSpecialMethod = ($this->_methodName === '__construct' || $this->_methodName === '__destruct');

        // Check type
		if ($this->commentParser->getReturn()) {
            $r = $this->checkType($this->commentParser->getReturn()->getValue(), $this->allowedReturnTypes, 'return');
            if (true !== $r) {
                $this->currentFile->addError($this->getReqPrefix('?') . $r, $commentStart + $this->commentParser->getReturn()->getLine());
            }
		}

		// Check comment case
        if (
			$this->commentParser->getReturn()->getComment()
			&& preg_match('/^[a-z]/Ss', trim($this->commentParser->getReturn()->getComment()))
		) {
        	$error = 'Комментарий аннотации возврата метода начинается с маленькой буквы';
            $this->currentFile->addError($this->getReqPrefix('?') . $error, $commentStart + $this->commentParser->getReturn()->getLine());
		}

        if ($isSpecialMethod === false && $methodName !== $className) {
            // Report missing return tag.
            if ($this->commentParser->getReturn() === null) {
                $error = 'Missing @return tag in function comment';
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $commentEnd);

            } else if (trim($this->commentParser->getReturn()->getRawContent()) === '') {
                $error    = '@return tag is empty in function comment';
                $errorPos = ($commentStart + $this->commentParser->getReturn()->getLine());
                $this->currentFile->addError($this->getReqPrefix($this->reqCodeEmpty) . $error, $errorPos);
            }
        }

    }//end processReturn()

    /**
     * Process the function parameter comments.
     *
     * @param int $commentStart The position in the stack where
     *                          the comment started.
     *
     * @return void
     */
    protected function processParams($commentStart, $commentEnd, $tagElements)
    {
        $realParams = $this->currentFile->getMethodParameters($this->_functionToken);

        $params      = $this->commentParser->getParams();
        $foundParams = array();

        if (empty($params) === false) {

            $lastParm = (count($params) - 1);
            if (substr_count($params[$lastParm]->getWhitespaceAfter(), $this->currentFile->eolChar) !== 2) {
                $error    = 'Last parameter comment requires a blank newline after it';
                $errorPos = ($params[$lastParm]->getLine() + $commentStart);
                $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.20') . $error, $errorPos);
            }

            // Parameters must appear immediately after the comment.
            if ($params[0]->getOrder() !== 2) {
                $error    = 'Parameters must appear immediately after the comment';
                $errorPos = ($params[0]->getLine() + $commentStart);
                $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.21') . $error, $errorPos);
            }

            $previousParam      = null;
            $spaceBeforeVar     = 10000;
            $spaceBeforeComment = 10000;
            $longestType        = 0;
            $longestVar         = 0;

            foreach ($params as $param) {

                $paramComment = trim($param->getComment());
                $errorPos     = ($param->getLine() + $commentStart);

				// Check type
				$r = $this->checkType($param->getType(), $this->allowedParamTypes, 'param');
				if (true !== $r) {
	            	$this->currentFile->addError($this->getReqPrefix('?') . $r, $errorPos);
				}

                // Make sure that there is only one space before the var type.
                if ($param->getWhitespaceBeforeType() !== ' ') {
                    $error = 'Expected 1 space before variable type';
                    $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.22') . $error, $errorPos);
                }

                $spaceCount = substr_count($param->getWhitespaceBeforeVarName(), ' ');
                if ($spaceCount < $spaceBeforeVar) {
                    $spaceBeforeVar = $spaceCount;
                    $longestType    = $errorPos;
                }

                $spaceCount = substr_count($param->getWhitespaceBeforeComment(), ' ');

                if ($spaceCount < $spaceBeforeComment && $paramComment !== '') {
                    $spaceBeforeComment = $spaceCount;
                    $longestVar         = $errorPos;
                }

                // Make sure they are in the correct order,
                // and have the correct name.
                $pos = $param->getPosition();

                $paramName = ($param->getVarName() !== '') ? $param->getVarName() : '[ UNKNOWN ]';

                if ($previousParam !== null) {
                    $previousName = ($previousParam->getVarName() !== '') ? $previousParam->getVarName() : 'UNKNOWN';

                    // Check to see if the parameters align properly.
                    if ($param->alignsVariableWith($previousParam) === false) {
                        $error = 'The variable names for parameters '.$previousName.' ('.($pos - 1).') and '.$paramName.' ('.$pos.') do not align';
                        $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.23') . $error, $errorPos);
                    }

                    if ($param->alignsCommentWith($previousParam) === false) {
                        $error = 'The comments for parameters '.$previousName.' ('.($pos - 1).') and '.$paramName.' ('.$pos.') do not align';
                        $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.24') . $error, $errorPos);
                    }
                }//end if

                // Make sure the names of the parameter comment matches the
                // actual parameter.
                if (isset($realParams[($pos - 1)]) === true) {
                    $realName      = $realParams[($pos - 1)]['name'];
                    $foundParams[] = $realName;
                    // Append ampersand to name if passing by reference.
                    if ($realParams[($pos - 1)]['pass_by_reference'] === true) {
                        $realName = '&'.$realName;
                    }

                    if ($realName !== $param->getVarName()) {
                        $error  = 'Doc comment var "'.$paramName;
                        $error .= '" does not match actual variable name "'.$realName;
                        $error .= '" at position '.$pos;

                        $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.25') . $error, $errorPos);
                    }
                } else {
                    // We must have an extra parameter comment.
                    $error = 'Superfluous doc comment at position '.$pos;
                    $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.27') . $error, $errorPos);
                }

                if ($param->getVarName() === '') {
                    $error = 'Missing parameter name at position '.$pos;
                     $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.26') . $error, $errorPos);
                }

                if ($param->getType() === '') {
                    $error = 'Missing type at position '.$pos;
                    $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.26') . $error, $errorPos);
                }

                if ($paramComment === '') {
                    $error = 'Missing comment for param "'.$paramName.'" at position '.$pos;
                    $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.26') . $error, $errorPos);

				} elseif (preg_match('/^[a-z]/Ss', trim($paramComment))) {
                    $error = 'Комментарий параметра "' . $paramName . '" начинается с маленькой буквы';
                    $this->currentFile->addError($this->getReqPrefix('?') . $error, $errorPos);

				}

				$this->checkForDefaultValue($paramName, 'param', $errorPos);
				$this->checkForDefaultValue($paramComment, 'param', $errorPos);

                $previousParam = $param;

            }//end foreach

            if ($spaceBeforeVar !== 1 && $spaceBeforeVar !== 10000 && $spaceBeforeComment !== 10000) {
                $error = 'Expected 1 space after the longest type';
                $this->currentFile->addError($this->getReqPrefix('?') . $error, $longestType);
            }

            if ($spaceBeforeComment !== 1 && $spaceBeforeComment !== 10000) {
                $error = 'Expected 1 space after the longest variable name';
                $this->currentFile->addError($this->getReqPrefix('?') . $error, $longestVar);
            }

        }//end if

        $realNames = array();
        foreach ($realParams as $realParam) {
            $realNames[] = $realParam['name'];

        }

        // Report and missing comments.
        $diff = array_diff($realNames, $foundParams);
        foreach ($diff as $neededParam) {
            if (count($params) !== 0) {
                $errorPos = ($params[(count($params) - 1)]->getLine() + $commentStart);
            } else {
                $errorPos = $commentStart;
            }

            $error = 'Doc comment for "'.$neededParam.'" missing';
            $this->currentFile->addError($this->getReqPrefix('REQ.PHP.4.1.27') . $error, $errorPos);
        }

    }//end processParams()

	protected function checkType($rawType, array $allowedTypes, $tag)
	{
        $types = array_map('trim', explode('|', $rawType));
        if (4 < count($types)) {
            $this->currentFile->addError($this->getReqPrefix('?') . 'Число вариантов типов @' . $tag . ' больше 4', $errorPos);
        }

		$result = true;

		foreach ($types as $type) {
			if ('\\' == substr($type, 0, 1) || in_array($type, $allowedTypes)) {
    	    	// Class or simple type
				continue;

        	} elseif (preg_match('/^array\((.+)\)$/Ss', $type, $m)) {

				// Array
				$r = $this->checkType($m[1], $allowedTypes, $tag);
				if (true === $r) {
					continue;
				}

				$result = $r;

			} else {
				$result = 'Тип "' . $type . '" запрещен для использования в @' . $tag;
			}

			break;
		}

		return $result;
	}

	function checkAccess($stackPtr, $commentStart, $commentEnd) {
		$tokens = $this->currentFile->getTokens();
		$access = $this->commentParser->getAccess();
        $prevWS = $this->currentFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, false, "\n");
        $type = $this->currentFile->findNext(array(T_PRIVATE, T_PUBLIC, T_PROTECTED), $prevWS + 1, $stackPtr - 1);
		$code = $tokens[$type]['code'];

        if (
            !is_null($access)
            && (
                ($code === T_PUBLIC && $access->getValue() !== 'public')
                || ($code === T_PRIVATE && $access->getValue() !== 'private')
                || (($code === T_PROTECTED && $access->getValue() !== 'protected'))
            )
        ) {
            $cnt = substr_count(
                preg_replace('/@access.+$/Ss', '', $this->currentFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1))),
                "\n"
            );
            $this->currentFile->addError(
                $this->getReqPrefix('REQ.PHP.4.1.25') . 'Значение тэга @access не совпадает с декларацией (декларированно как ' . $tokens[$type]['content']. ', а @access равен ' . $access->getValue() . ')',
                $commentStart + $cnt
            );
        }
	}

    /**
     * Service function
     */

    /**
     * sanitaze underscore name (Pascal Case + underscore as word delimiter)
     *
     * @param   string  $content
     * @access  private
     * @return  string
     * @since   1.0.0
     */
    private function sanitazeUnderscoreName($content)
    {
        $newContent = str_replace(' ', '_', $content);
        $nameBits   = explode('_', $newContent);
        $firstBit   = array_shift($nameBits);
        $newName    = ucfirst($firstBit).'_';
        foreach ($nameBits as $bit) {
            $newName .= ucfirst($bit).'_';
        }

        return trim($newName, '_');
    }

}


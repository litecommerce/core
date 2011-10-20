<?php
/**
 * XLite_Sniffs_PHP_Formatting_ArraySniff.
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
 * XLite_Sniffs_PHP_Formatting_ArraySniff.
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
class XLite_Sniffs_PHP_Formatting_ArraySniff extends XLite_ReqCodesSniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
   {
        return array(T_ARRAY);

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

		$pos = $tokens[$stackPtr]['parenthesis_opener'];
		do {
			$pos = $phpcsFile->findNext(T_DOUBLE_ARROW, $pos + 1, $tokens[$stackPtr]['parenthesis_closer']);
			if ($pos !== false) {
				$notWS = $phpcsFile->findPrevious(T_WHITESPACE, $pos - 1, null, true);
				if ($tokens[$notWS]['code'] === T_LNUMBER && $tokens[$notWS - 1]['code'] === T_MINUS) {
		            $phpcsFile->addError(
        		        $this->getReqPrefix('REQ.PHP.3.3.1')
                		. 'Ключ массива не может быть отрицательным числом',
		                $notWS
        		    );
				}
			}

		} while ($pos !== false);

		$posP = $stackPtr - 1;
		do {
			$pos = $phpcsFile->findPrevious(T_WHITESPACE, $posP);
			$posP = $pos - 1;
		} while (substr($tokens[$pos]['content'], -1) != "\n");

		$column = $tokens[$pos + 2]['column'] - 1;
		$nextColumn = $column + 4;
		$pos = $tokens[$stackPtr]['parenthesis_opener'];
		do {
			$prevPos = $pos + 1;
            $pos = $phpcsFile->findNext(T_WHITESPACE, $prevPos, $tokens[$stackPtr]['parenthesis_closer'], false, "\n");
			$arrPos = $phpcsFile->findNext(T_ARRAY, $prevPos, $pos);
            $newPos = $phpcsFile->findNext(T_NEW, $prevPos, $pos);
			if ($arrPos) {
				$pos = $tokens[$arrPos]['parenthesis_closer'];

			} elseif ($newPos) {
				$pos = array_keys($tokens[$newPos]['nested_parenthesis']);
				$pos = $tokens[$pos[0]]['parenthesis_closer'];

			} elseif (
				$pos !== false
				&& $tokens[$pos + 1]['code'] === T_WHITESPACE && $tokens[$pos + 1]['content'] !== "\n"
				&& $tokens[$pos + 2]['code'] !== T_WHITESPACE && ($tokens[$pos + 2]['column'] - 1) !== $nextColumn
				&& $pos + 2 < $tokens[$stackPtr]['parenthesis_closer']
			) {
                $phpcsFile->addError(
                    $this->getReqPrefix('REQ.PHP.3.3.3')
                    . 'Объявление многострочного массива должно иметь следующий уровень отступа; найдено ' . ($tokens[$pos + 2]['column'] - 1) . ' пробелов вместо ' . $nextColumn,
                    $pos + 2
                );
			}

		} while ($pos !== false);

		if ($tokens[$tokens[$stackPtr]['parenthesis_opener']]['line'] !== $tokens[$tokens[$stackPtr]['parenthesis_closer']]['line']) {

	        $pos = $tokens[$stackPtr]['parenthesis_opener'];
    	    do {
				do {
	        	    $posN = $phpcsFile->findNext(T_DOUBLE_ARROW, $pos + 1, $tokens[$stackPtr]['parenthesis_closer']);
					$arrayCodePos = $phpcsFile->findNext(T_ARRAY, $pos + 1, $posN - 1);
					if ($arrayCodePos) {
                        $posN = $tokens[$arrayCodePos]['parenthesis_closer'];

					}

					$pos = $posN;
				} while ($arrayCodePos !== false);

            	if ($pos !== false) {
                	$notWS = $phpcsFile->findPrevious(T_WHITESPACE, $pos - 1, null, true);
	                if ($tokens[$notWS]['code'] === T_CONSTANT_ENCAPSED_STRING) {
						$posNext = $phpcsFile->findNext(T_DOUBLE_ARROW, $pos + 1, $tokens[$stackPtr]['parenthesis_closer']);
						if ($posNext !== false && $tokens[$posNext]['line'] === $tokens[$pos]['line']) {
							$arrayCodePos = $phpcsFile->findNext(T_ARRAY, $pos + 1, $posNext - 1);
							if ($arrayCodePos === false) {
		    	                $phpcsFile->addError(
    		    	                $this->getReqPrefix('REQ.PHP.3.3.4')
        		    	            . 'Для многостраничного хэш-массива каждая пара ключа-значение должно определятся на отдельной строке',
            		    	        $posNext
                		    	);

							} else {
								$pos = $tokens[$arrayCodePos]['parenthesis_closer'];
							}
						}
	                }
    	        }

        	} while ($pos !== false);

/*
	TODO: WRN.PHP.-3-.3.2
			$commas = $this->findNextAll(T_COMMA, $tokens[$stackPtr]['parenthesis_opener'] + 1, $tokens[$stackPtr]['parenthesis_closer']);
			$clines = array();
			$firstLine= false;
			foreach ($commas as $c) {
				$line = $tokens[$c]['line'];
				if ($firstLine === false)
					$firstLine = $line;
				if (!isset($clines[$line]))
					$clines[$line] = array();

				$clines[$line][] = $c;
			}

			$avgCount = $clines[$firstLine];
			foreach ($clines as $commasPerLine) {
				if (count($commasPerLine) != $avgCount && count($commasPerLine) > 0) {
					$phpcsFile->addWarning(
						$this->getReqPrefix('WRN.PHP.-3-.3.2') . 'При определении многострочного массива рекомендуется на каждой строке иметь одинаковое количество элементов, за исключением последней строки',
						$commasPerLine[0]
					);
				}
			}
*/
		}

    }

}//end class

?>

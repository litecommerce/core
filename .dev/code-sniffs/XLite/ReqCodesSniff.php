<?php
/**
 * @version $Id$
 */
class XLite_ReqCodesSniff implements PHP_CodeSniffer_Sniff
{

    public function register()
    {
		return array();
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
    }

    /**
     * get requirement code by tag
     *
     * @param   array   $reqCodes Requirements list
     * @param   string  $tag      Tag name
     * @access  private
     * @return  string
     * @since   1.0.0
     */
    protected function getReqCode($reqCodes, $tag) {

        if (isset($reqCodes[$tag])) {
            return $reqCodes[$tag]['code'];
		}

        if (isset($reqCodes['*'])) {
            return $reqCodes['*']['code'];
		}

        return '?';
    }

    /**
     * get requirement prefix based on requirement code
     *
     * @access  private
     * @return  string
     * @since   1.0.0
     */
    protected function getReqPrefix() {
		$codes = array();
		foreach (func_get_args() as $v) {
			if (is_string($v))
				$codes[] = $v;
			elseif (is_array($v))
				$codes = array_merge($codes, $v);
		}

		return count($codes) ? '[' . implode('; ', $codes) . '] ' : '';
    }

	protected function findNextAll(PHP_CodeSniffer_File $phpcsFile, $types, $start, $end = null, $exclude = false, $value = null, $local = false) {
		$return = array();
		$pos = $start - 1;
		do {

			$pos = $phpcsFile->findNext($types, $pos + 1, $end, $exclude, $value, $local);
			if ($pos !== false) {
				$return[] = $pos;
			}
		} while ($pos !== false);

		return $return;
	}

    protected function findPreviousAll(PHP_CodeSniffer_File $phpcsFile, $types, $start, $end = null, $exclude = false, $value = null, $local = false) {
        $return = array();
        $pos = $start + 1;
        do {

            $pos = $phpcsFile->findNext($types, $pos - 1, $end, $exclude, $value, $local);
            if ($pos !== false) {
                $return[] = $pos;
            }
        } while ($pos !== false);

        return $return;
    }


}

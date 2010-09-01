<?php
/**
 * @version $Id$
 */

class XLite_NameSniff extends XLite_ReqCodesSniff
{

	protected $abbrs = array(
		'HTML', 'XML', 'XHTML', 'CSS', 'SQL', 'PDO', 'URL', 'GET', 'POST', 'DOM',
		'AES', 'RSA', 'PGP', 'XSLT', 'IV', 'DN', 'URL', 'IP', 'MIME', 'CRC', 'CRC32', 'MD4', 'MD5', 'API',
		'NVP', 'PHP', 'CURL', 'VS', 'PC', 'UTF8', 'TTL', 'SMTP', 'IP4', 'CC', 'CVV2', 'UK', 'FMF', 'CSSURL',
		'HMACMD5', 'HMAC', 'URI', 'ID', 'JS', 'SSL', 'AVS', 'CVV', 'DB', 'HSBC', 'SOAP', 'GMT', 'HTTPS', 'CLI',
		'CMS', 'GC', 'AJAX', 'URLAJAX', 'USPS', 'GD', 'PM', 'XPC', 'DSN', 'EM', 'QB',
	);

	protected $twoWordsAbbrs = array('ECard', 'ECards');

	protected $nouns = array(
	);

	protected $verbs = array(
		'do', 'get', 'set', 'insert', 'append', 'detect', 'assign', 'register', 'unregister', 'unset',
		'is', 'are', 'isset', 'remove', 'move', 'add', 'handle', 'display', 'view', 'prepare', 'check',
		'run', 'dispatch', 'compose', 'test', 'connect', 'parse', 'throw', 'fetch', 'execute',
		'key', 'current', 'next', 'rewind', 'valid', 'action', 'schema',
		'redirect', 'convert', 'select', 'update', 'replace', 'delete', 'generate', 'start', 'crypt',
		'begin', 'end', 'write', 'save', 'query', 'process', 'open', 'header', 'clear', 'clean', 'assert',
		'send', 'validate', 'init', 'output', 'call', 'find', 'reset', 'destroy', 'echo', 'format',
		'encrypt', 'decrypt', 'serialize', 'unserialize', 'renew', 'define', 'collect', 'return', 'calculate',
		'lock', 'unlock', 'can', 'walk', 'pack', 'unpack', 'initialize', 'finish', 'create', 'mark', 'unmark',
		'recrypt', 'revalidate', 'close' , 'change' , 'confirm', 'rewrite', 'store', 'log', 'finalize', 'encode', 'decode',
		'substitute', 'implode', 'has', 'expand', 'load', 'assemble', 'activate', 'restore', 'show', 'install', 'emulate',
		'perform', 'notify', 'cancel', 'sort', 'sanitize', 'use', 'accept', 'regenerate', 'inner', 'make', 'count', 'build',
		'refresh', 'postprocess', 'preprocess', 'include', 'truncate', 'inc', 'map', 'strip', 'calc', 'compile', 'request', 'modify',
		'normalize', 'filter', 'sanitize', 'fill', 'import', 'export', 'stop', 'start', 'perform', 'correct', 'rebuild', 'merge',
        'apply', 'translate', 'enable', 'disable', 'detach', 'attach', 'read',

		// FIXME - rename later
		'processed', 'checked', 'declined', 'queued', 'unchecked', 'checkout',
	);

	protected $cssPseudoClasses = array(
		'link', 'active', 'hover', 'visited', 'first-line', 'first-letter', 'first-child', 'last-child', 'last-line', 'last-letter',
		'disabled'
	);

	protected $reservedMethodNames = array(
		'postUpdate', 'postRemove', 'postPersist', 't', 'trigger',
		'_doFetch', '_doContains', '_doSave', '_doDelete', // for classes/XLite/Core/FileCache.php
		'lbl',
	);

    public function register()
    {
		return array();
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
    }

	/**
	 * Get words from name (delimiter - underline symbol)
	 * 
	 * @param   string	$name 
	 * @access  protected
	 * @return  array
	 * @since   1.0.0
	 */
	protected function getWordsByUnderline($name) {
		return explode('\\', $name);
	}

	/**
	 * Get words from name (delimiter - capital letter)
	 * 
	 * @param   string	$name 
	 * @access  protected
	 * @return  array
	 * @since   1.0.0
	 */
	protected function getWordsByCapitalLetter($name) {
		$return = array();
		$words = preg_split('/([A-Z])/Ss', $name, -1, PREG_SPLIT_DELIM_CAPTURE);
		if (empty($words[0]))
			array_shift($words);
		else
			$return[] = array_shift($words);

		while (count($words) > 0) {
			$return[] = array_shift($words) . array_shift($words);
		}

		$return2 = array();
		$store = '';
		foreach ($return as $w) {
			if (strlen($w) == 1 || preg_match('/^[A-Z]\d+$/Ss', $w)) {
				$store .= $w;

			} else {
				if (strlen($store) > 0) {
					$return2[] = $store;
					$store = '';
				}
				$return2[] = $w;
			}
		}

		if (strlen($store) > 0) {
			$return2[] = $store;
		}

		return $return2;
	}

	/**
	 * Check word in Camel case style
	 * 
	 * @param   string	$name 
	 * @access  protected
	 * @return  integer
	 * @since   1.0.0
	 */
	protected function checkCamelWord($name) {
		if (!preg_match('/^[A-Z]/Ss', $name))
			return -1;

		if (preg_match('/^[A-Z][A-Z\d]+$/Ss', $name)) {
			if (in_array($name, $this->abbrs))
				return 2;

			return -2;
		}

		

		if (preg_match('/^[A-Z][a-zA-Z\d]+$/Ss', $name))
			return 1;

		return -3;
	}

	/**
	 * Check word in low case style
	 * 
	 * @param   string	$name 
	 * @access  protected
	 * @return  integer
	 * @since   1.0.0
	 */
	protected function checkLowWord($name) {
		return preg_match('/^[a-z\d]+$/Ss', $name);
	}

	/**
	 * Check class path by class name 
	 * 
	 * @param   array	$words 
	 * @access  protected
	 * @return  array
	 * @since   1.0.0
	 */
	protected function checkClassPath($words) {
		$paths = explode(PATH_SEPARATOR, XP_CLASSES_ROOT);
		
		$fn = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $words) . '.php';
		$avail_paths = array();
		$res = false;
		foreach ($paths as $p) {
			$p .= $fn;
			$res = $res || file_exists($p);
			$avail_paths[] = $p;
		}

		return array($res, $avail_paths);
	}

	/**
	 * check verb or not 
	 * 
	 * @param   string	$word 
	 * @access  protected
	 * @return  boolean
	 * @since   1.0.0
	 */
	protected function checkVerb($word) {
		return in_array($word, $this->verbs);
	}

	/**
	 * check noun or not 
	 * 
     * @param   string  $word
	 * @access  protected
	 * @return  boolean
	 * @since   1.0.0
	 */
	protected function checkNoun($word) {
		// TODO
		return true;
	}

    /**
     * Check if the input property is a pseudo-class
     * of a HTML element
     *
     * @param   string $word
     * @access  protected
     * @return  boolean
     * @since   1.0.0
     */
    public function isCSSPseudoClass($property) {
        return in_array($property, $this->cssPseudoClasses);
    }

	/**
	 * Check - is reserver method name or not
	 * 
	 * @param string $methodName Method name
	 *  
	 * @return boolean
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function isReserverMethodName($methodName)
	{
		return in_array($methodName, $this->reservedMethodNames);
	}
}

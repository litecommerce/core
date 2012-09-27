<?php
/**
 * @version $Id: 98ff7a2a4b235f32754655a011cb32edbd79035d $
 */

/**
 * XLite_NameSniff 
 *
 * @see   ____class_see____
 * @since 1.0.24
 */
class XLite_NameSniff extends XLite_ReqCodesSniff
{
    /**
     * abbrs
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
	protected $abbrs = array(
		'HTML', 'XML', 'XHTML', 'CSS', 'SQL', 'PDO', 'URL', 'GET', 'POST', 'DOM',
		'AES', 'RSA', 'PGP', 'XSLT', 'IV', 'DN', 'URL', 'IP', 'MIME', 'CRC', 'CRC32', 'MD4', 'MD5', 'API',
		'NVP', 'PHP', 'CURL', 'VS', 'PC', 'UTF8', 'TTL', 'SMTP', 'IP4', 'CC', 'CVV2', 'UK', 'FMF', 'CSSURL',
		'HMACMD5', 'HMAC', 'URI', 'ID', 'JS', 'SSL', 'AVS', 'CVV', 'DB', 'HSBC', 'SOAP', 'GMT', 'HTTPS', 'CLI',
		'CMS', 'GC', 'AJAX', 'URLAJAX', 'USPS', 'GD', 'PM', 'XPC', 'DSN', 'EM', 'QB', 'SKU', 'REST', 'FS', 'IREST',
        'YAML', 'GZ', 'HTTP', 'SPL', 'PHAR', 'JSON', 'LC', 'APC', 'VAT', 'IPN', 'ACL',
	);

    /**
     * twoWordsAbbrs
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.24
     */
	protected $twoWordsAbbrs = array('ECard', 'ECards');

    /**
     * nouns
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
	protected $nouns = array(
	);

    /**
     * verbs
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
	protected $verbs = array(
		'do', 'get', 'set', 'insert', 'append', 'detect', 'assign', 'register', 'unregister', 'unset',
		'is', 'are', 'isset', 'remove', 'move', 'add', 'handle', 'display', 'view', 'prepare', 'check',
		'run', 'dispatch', 'compose', 'test', 'connect', 'parse', 'throw', 'fetch', 'execute',
		'key', 'current', 'next', 'rewind', 'valid', 'action', 'schema',
		'redirect', 'convert', 'select', 'update', 'replace', 'delete', 'generate', 'start', 'crypt',
		'begin', 'end', 'write', 'save', 'query', 'process', 'open', 'header', 'clear', 'clean', 'cleanup', 'assert',
		'send', 'validate', 'init', 'output', 'call', 'find', 'reset', 'destroy', 'echo', 'format',
		'encrypt', 'decrypt', 'serialize', 'unserialize', 'renew', 'define', 'collect', 'return', 'calculate',
		'lock', 'unlock', 'can', 'walk', 'pack', 'unpack', 'initialize', 'finish', 'create', 'mark', 'unmark',
		'recrypt', 'revalidate', 'close' , 'change' , 'confirm', 'rewrite', 'store', 'log', 'finalize', 'encode', 'decode',
		'substitute', 'implode', 'has', 'expand', 'load', 'assemble', 'activate', 'restore', 'show', 'install', 'emulate',
		'perform', 'notify', 'cancel', 'sort', 'sanitize', 'use', 'accept', 'regenerate', 'inner', 'make', 'count', 'build',
		'refresh', 'postprocess', 'preprocess', 'include', 'truncate', 'inc', 'map', 'strip', 'calc', 'compile', 'request', 'modify',
		'normalize', 'filter', 'sanitize', 'fill', 'import', 'export', 'stop', 'start', 'perform', 'correct', 'rebuild', 'merge',
        'apply', 'translate', 'enable', 'disable', 'detach', 'attach', 'read', 'resize', 'search', 'uninstall', 'flush', 'compare',
		'mask', 'pay', 'clone', 'login', 'logoff', 'exclude', 'restart', 'invalidate',
		'remember', 'remind', 'link', 'concat','split', 'round', 'depack', 'upload', 'hydrate', 'unload',
		'download', 'deploy', 'construct', 'retrieve', 'print', 'increase', 'decrease', 'sum',
		'drop', 'list', 'reverse', 'rand', 'extract', 'wake', 'sleep', 'mkdir', 'unlink', 'copy', 'chmod',
		'complete', 'manage', 'upgrade', 'measure', 'draw', 'replant', 'switch', 'deduct',

		// FIXME - rename later
		'processed', 'checked', 'declined', 'queued', 'unchecked', 'checkout', 'display404',
		// Method offsetSet() defined in Doctrine
        'offset',
        'forbid', 'alter', 'submit', 'invoke', 'optimize', 'escape',

        // Add new verbs
        'seek',
	);

    /**
     * cssPseudoClasses
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
	protected $cssPseudoClasses = array(
		'link', 'active', 'hover', 'visited', 'first-line', 'first-letter', 'first-child', 'last-child', 'last-line', 'last-letter',
		'disabled'
	);

    /**
     * reservedMethodNames
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
	protected $reservedMethodNames = array(
		'postUpdate', 'postRemove', 'postPersist', 't', 'trigger',
		'_doFetch', '_doContains', '_doSave', '_doDelete', // for classes/XLite/Core/FileCache.php
		'lbl', 'repo', 'em',
	);

    /**
     * register
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function register()
    {
		return array();
    }

    /**
     * process
     *
     * @param PHP_CodeSniffer_File $phpcsFile ____param_comment____
     * @param mixed                $stackPtr  ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
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
		return explode('_', $name);
	}

    /**
     * Get words from name (delimiter - underline symbol)
     *
     * @param   string  $name
     * @access  protected
     * @return  array
     * @since   1.0.0
     */
    protected function getWordsBySlash($name) {
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
	protected function checkClassPath(array $words, $namespace) {
		$paths = defined('XP_CLASSES_ROOT') ? explode(PATH_SEPARATOR, constant('XP_CLASSES_ROOT')) : array();
        $paths[] = __DIR__ . '/../../../src/classes';
        $paths[] = __DIR__ . '/../../../src';
		
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
	 * @since  1.0.0
	 */
	protected function isReserverMethodName($methodName)
	{
		return in_array($methodName, $this->reservedMethodNames);
	}
}

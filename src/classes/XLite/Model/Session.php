<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

define('SESSION_DEFAULT_ID', md5(uniqid(rand(), true)));

/**
 * Session
 * TODO[SINGLETON]
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Session extends \XLite\Base
{
    /**
     * Currently used form ID 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected static $xliteFormId = null;

    /**
     * Language (cache)
     * 
     * @var    \XLite\Model\Language
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $language = null;

    /**
     * Generate new form ID
     * TODO - to revise
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function generateXliteFormID()
    {
        $form = new \XLite\Model\XliteForm();

        $formId = md5(uniqid(rand(0,time())));
        $sessId = $this->getID();

        $form->set('form_id', $formId);
        $form->set('session_id', $sessId);
        $form->set('date', time());
        $form->create();

        $form->collectGarbage($sessId);

        return $formId;
    }

    /**
     * It's not possible to instantiate this class using the 'new' operator
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function __construct()
    {
        parent::__construct();

        $this->options = array_merge($this->options, \XLite::getInstance()->getOptions('host_details'));
    }

    /**
     * Get language
     * 
     * @return \XLite\Model\Language
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLanguage()
    {
        if (is_null($this->language)) {
            $this->language = \XLite\Core\Database::getRepo('\XLite\Model\Language')
                ->findOneByCode($this->getCurrentLanguage());
        }

        return $this->language;
    }

    /**
     * Set language 
     * 
     * @param string $language Language code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setLanguage($language)
    {
        $code = $this->get('language');
        $zone = \XLite::isAdminZone() ? 'admin' : 'customer';

        if (!is_array($code)) {
            $code = array();
        }

        if (!isset($code[$zone]) || $code[$zone] != $language) {
            $code[$zone] = $language;
            $this->set('language', $code);
            $this->language = null;
        }
    }

    /**
     * Get current language 
     * 
     * @return string Language code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentLanguage()
    {
        $code = $this->get('language');
        $zone = \XLite::isAdminZone() ? 'admin' : 'customer';

        if (!is_array($code)) {
            $code = array();
        }

        if (!isset($code[$zone]) || !$code[$zone]) {
            $this->setLanguage($this->defineCurrentLanguage());
            $code = $this->get('language');
        }

        return $code[$zone];
    }

    /**
     * Define current language 
     * 
     * @return string Language code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCurrentLanguage()
    {
        $languages = array();

        if (\XLite\Model\Auth::getInstance()->isLogged()) {
            $languages[] = \XLite\Model\Auth::getInstance()->getProfile()->get('language');
        }

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $tmp = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $languages = array_merge($languages, preg_replace('/^([a-z]{2}).+$/Ss', '$1', $tmp));
        }

        $languages[] = \XLite\Core\Config::getInstance()->General->defaultLanguage->code;

        // Process query
        $idx = 999999;
        $found = false;
        $first = false;
        foreach (\XLite\Core\Database::getRepo('\XLite\Model\Language')->findActiveLanguages() as $lng) {
            if (!$first) {
                $first = $lng->code;
            }
            $key = array_search($lng->code, $languages);
            if (false !== $key && $key < $idx) {
                $idx = $key;
                $found = $lng->code;
            }
        }

        return $found
            ? $found
            : ($first ? $first : 'en');
    }

    /**
     * Destroys the concrete session object. Abstract method, should be overridden
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    abstract public function destroy();

    /**
     * Saves session data 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    abstract public function writeClose();


    /**
     * Method to access a singleton
     *
     * @return \XLite\Base
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        $className = '\XLite\Model\Session\\' . LC_SESSION_TYPE;

        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className();
        }

        return self::$instances[$className];
    }

    /**
     * Returns the value for the specified session variable
     * FIXME - rename and make abstract
     *
     * @param string $name variable name
     *
     * @return mixed variable value
     * @access public
     * @since  3.0.0
     */
    public function get($name)
    {
        $this->doDie('\XLite\Model\Session::get(): Trying to call the abstract method');
    }

    /**
     * Sets the variable with specified name and value (add it to the data container)
     * FIXME - rename and make abstract
     *
     * @param string $name  variable name
     * @param mixed  $value variable value
     *
     * @return mixed the concrete Session object SetVar method result or singleton result on error
     * @access public
     * @since  3.0.0
     */
    public function set($name, $value)
    {
        $this->doDie('\XLite\Model\Session::set(): Trying to call the abstract method');
    }

    /**
     * Close session
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __destruct()
    {
        $this->writeClose();
    }

    /**
     * restart 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function restart()
    {
        if (\XLite\Core\Request::getInstance()->__get(self::SESSION_DEFAULT_NAME)) {

            \XLite\Core\Request::getInstance()->__set(self::SESSION_DEFAULT_NAME, null);
            $this->set('_' . self::SESSION_DEFAULT_NAME, self::SESSION_DEFAULT_NAME . '=' . $this->getID());

            $this->destroy();
            $this->setID(SESSION_DEFAULT_ID);
            $this->_initialize();
        }
    }

    /**
     * Return current form ID 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getXliteFormID()
    {
        if (!isset(self::$xliteFormId)) {
            self::$xliteFormId = $this->generateXliteFormID();
        }

        return self::$xliteFormId;
    }




    const SESSION_DEFAULT_TYPE = 'Sql';
    const SESSION_DEFAULT_NAME = 'xid';
    const SESSION_DEFAULT_PATH = '/';
    const SESSION_DEFAULT_TTL  = 7200;

    /**
    * Session data containter.
    * @var array $_data
    * @access private
    */    
    public $_data = array();

    /**
    * Session options.
    *
    * @var array $options
    * @access private
    */
    protected $options = array(
        'type' => self::SESSION_DEFAULT_TYPE,
        'name' => self::SESSION_DEFAULT_NAME,
        'id'   => SESSION_DEFAULT_ID,
        'path' => self::SESSION_DEFAULT_PATH,
        'ttl'  => self::SESSION_DEFAULT_TTL
    );


    /**
    * Checks whether the variable has been registered to session. FIXME
    */
    function isRegistered($name)
    {
    }

    /**
    * Abstract method for concrete Session object initialization. FIXME
    */
    function _initialize()
    {
    }

    /**
    * Abstract method for fetching the concrete Session object data
    * from the database. FIXME
    */
    function _fetchData()
    {
    }
    
    /**
    * Sets the session type.
    *
    * @param string $type The session type. Default is self::SESSION_DEFAULT_TYPE
    * @access public
    */
    function setType($type = self::SESSION_DEFAULT_TYPE)
    {
        $this->options['type'] = $type;
    }
    
    /**
    * Returns the session type.
    *
    * @access public
    * @return string Session type.
    */
    function getType()
    {
        return $this->options['type'];
    }

    /**
    * Sets the session name.
    *
    * @access public
    * @param string $name The session name, Default is self::SESSION_DEFAULT_NAME
    */
    function setName($name = self::SESSION_DEFAULT_NAME)
    {
        $this->options['name'] = $name;
    }
    
    function getName()
    {
        return $this->options['name'];
    }

    function setID($id)
    {
        if (!preg_match('/^[0-9a-f]{31,32}$/Sis', $id)) {
            $this->doDie('Session::setID(): Incorrect session ID has been detected: ' . $id);
        }
        $this->options['id'] = $id;
    }
    
    function getID()
    {
        return $this->options['id'];
    }

    /**
     * Return path for cookies
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPath()
    {
        return \XLite::getInstance()->getOptions(array('host_details', 'web_dir'));
    }

    function setTtl($ttl = self::SESSION_DEFAULT_TTL)
    {
        $this->options['ttl'] = $ttl;
    }
    
    function getTtl()
    {
        return $this->options['ttl'];
    }

    function getHttpHost()
    {
        return $this->options['http_host'];
    }
    
    function getHttpsHost()
    {
        return $this->options['https_host'];
    }

    function getShopURL($secure = false)
    {
        $proto   = $secure ? 'https://' : 'http://';
        $host    = $secure ? $this->options['https_host'] :
                             $this->options['http_host'];
        $web_dir = $this->options['web_dir'];
        $last    = strlen($web_dir) - 1;
        $web_dir.= ($web_dir{$last} == '/') ? '' : '/';
    
        return $proto . $host . $web_dir;
    }
 
    function getData()
    {
        return addslashes(serialize($this->_data));
    }

    function setData($data = array())
    {
        $this->_data = unserialize(stripslashes($data));
    }
}

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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core;

/**
 * Current session
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Session extends \XLite\Base\Singleton
{
    /**
     * Public session id argument name 
     */
    const ARGUMENT_NAME = 'xid';

    /**
     * Session 
     * 
     * @var    \XLite\Model\Session
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $session;

    /**
     * Currently used form ID
     *
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected static $xliteFormId;

    /**
     * Language (cache)
     *
     * @var    \XLite\Model\Language
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $language;

    /**
     * Last form id 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lastFormId;

    /**
     * Constructor
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function __construct()
    {
        $this->clearGardage();
        if (!$this->restoreSession()) {
            $this->createSession();
        }

        $this->setCookie();
    }

    /**
     * Getter
     * 
     * @param string $name Session cell name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        return $this->session->$name;
    }

    /**
     * Setter
     * 
     * @param string $name  Session cell name
     * @param mixed  $value Value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __set($name, $value)
    {
        $this->session->$name = $value;
    }

    /**
     * Check session cell availability
     * 
     * @param string $name Session cell name
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __isset($name)
    {
        return isset($this->session->$name);
    }

    /**
     * Remove session cell
     * 
     * @param string $name Session cell name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __unset($name)
    {
        unset($this->session->$name);
    }

    /**
     * Getter
     * DEPRECATE
     * 
     * @param string $name Session cell name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        return $this->__get($name);
    }

    /**
     * Setter
     * DEPRECATE
     * 
     * @param string $name  Session cell name
     * @param mixed  $value Value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($name, $value)
    {
        $this->__set($name, $value);
    }

    /**
     * Clear expired sessions and other obsolete data
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearGardage()
    {
        \XLite\Core\Database::getRepo('XLite\Model\Session')->removeExpired();
    }

    /**
     * Restart session
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function restart()
    {
        $this->lastFormId = null;

        if (!\XLite\Core\Database::getEM()->contains($this->session)) {
            try {
                $this->session = \XLite\Core\Database::getEM()->merge($this->session);
            } catch (\Doctrine\ORM\EntityNotFoundException $exception) {
                $this->session = null;
            }
        }

        $old = null;
        if ($this->session) {
            $old = $this->session;
            $oldId = $this->session->getId();
        }

        $this->createSession();

        if ($old) {
            foreach (\XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findById($oldId) as $cell) {
                $cell->setId($this->session->getId());
            }

            \XLite\Core\Database::getEM()->remove($old);
            \XLite\Core\Database::getEM()->flush();
        }

        $this->setCookie();
    }

    /**
     * Restore session 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function restoreSession()
    {
        $this->session = null;

        list($sid, $source) = $this->detectPublicSessionId();

        if ($sid) {
            $this->session = \XLite\Core\Database::getRepo('XLite\Model\Session')
                ->findOneBySid($sid);

            if ($this->session) {
                $this->session->updateExpiry();
                \XLite\Core\Database::getEM()->flush();
            }
        }

        return isset($this->session);
    }

    /**
     * Detect public session id 
     * 
     * @return array (public session id and source)
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function detectPublicSessionId()
    {
        $sid = null;
        $source = null;

        $arg = $this->getName();

        foreach (array('POST', 'GET', 'COOKIE') as $key) {
            
            if (isset($GLOBALS['_' . $key][$arg])) {
                $sid = $GLOBALS['_' . $key][$arg];
                $source = $key;
                break;
            }
        }

        if ($sid && !\XLite\Core\Database::getRepo('XLite\Model\Session')->isPublicSessionIdValid($sid)) {
            $sid = null;
        }

        return array($sid, $source);
    }

    /**
     * Create session 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createSession()
    {
        $this->session = new \XLite\Model\Session();

        $this->session->setSid(\XLite\Core\Database::getRepo('XLite\Model\Session')->generatePublicSessionId());
        $this->session->updateExpiry();

        \XLite\Core\Database::getEM()->persist($this->session);
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Set cookie 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setCookie()
    {
        if (!headers_sent() && 'cli' != PHP_SAPI) {

            $arg = $this->getName();

            $httpDomain = $this->getCookieDomain();
            $httpsDomain = $this->getCookieDomain(true);

            setcookie(
                $arg,
                $this->session->getSid(),
                0,
                $this->getCookiePath(),
                $httpDomain,
                false,
                true
            );

            if ($httpDomain != $httpsDomain) {
                setcookie(
                    $arg,
                    $this->session->getSid(),
                    0,
                    $this->getCookiePath(true),
                    $httpsDomain,
                    false,
                    true
                );
            }
        }
    }

    /**
     * Get public session id argument name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getName()
    {
        return self::ARGUMENT_NAME;
    }

    /**
     * Get public session id
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getID()
    {
        return $this->session->getSid();
    }

    /**
     * Load session by public session id 
     * 
     * @param string $sid Public session id
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function loadBySid($sid)
    {
        $session = \XLite\Core\Database::getRepo('XLite\Model\Session')->findOneBy(
            array(
                'sid' => $sid,
            )
        );

        $result = false;

        if ($session) {
            $result = true;
            \XLite\Core\Database::getEM()->remove($this->session);
            \XLite\Core\Database::getEM()->flush();

            $this->session = $session;
            $this->lastFormId = null;

            $this->setCookie();
        }

        return $result;
    }

    /**
     * Get parsed URL for Set-Cookie
     * 
     * @param boolean $secure Secure protocol or not
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCookieURL($secure = false)
    {
        $url = $secure
            ? 'http://' .  \XLite::getInstance()->getOptions(array('host_details', 'http_host'))
            : 'https://' . \XLite::getInstance()->getOptions(array('host_details', 'https_host'));

        $url .= \XLite::getInstance()->getOptions(array('host_details', 'web_dir'));

        return parse_url($url);
    }

    /**
     * Get host / domain for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCookieDomain($secure = false)
    {
        $url = $this->getCookieURL($secure);

        return false === strstr($url['host'], '.') ? false : $url['host'];
    }

    /**
     * Get URL path for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCookiePath($secure = false)
    {
        $url = $this->getCookieURL($secure);

        return $url['path'];
    }

    /**
     * Create form id
     *
     * @return string Form id
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createFormId()
    {
        if (!isset($this->lastFormId)) {
            $formId = new \XLite\Model\FormId;
            $formId->setSessionId($this->session->getId());
            \Xlite\Core\Database::getEM()->persist($formId);

            $this->lastFormId = $formId->getFormId();
        }

        return $this->lastFormId;
    }

    /**
     * Get model 
     * 
     * @return \XLite\Model\Session
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModel()
    {
        return $this->session;
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
        if (!isset($this->language)) {
            $this->language = \XLite\Core\Database::getRepo('XLite\Model\Language')
                ->findOneByCode($this->getCurrentLanguage());

            if ($this->language) {
                $this->language->detach();
            }
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
        $code = $this->session->language;
        $zone = \XLite::isAdminZone() ? 'admin' : 'customer';

        if (!is_array($code)) {
            $code = array();
        }

        if (!isset($code[$zone]) || $code[$zone] != $language) {
            $code[$zone] = $language;
            $this->session->language = $code;
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
        $code = $this->session->language;
        $zone = \XLite::isAdminZone() ? 'admin' : 'customer';

        if (!is_array($code)) {
            $code = array();
        }

        if (!isset($code[$zone]) || !$code[$zone]) {
            $this->setLanguage($this->defineCurrentLanguage());
            $code = $this->session->language;
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

        if (\XLite\Core\Auth::getInstance()->isLogged()) {
            $languages[] = \XLite\Core\Auth::getInstance()->getProfile()->getLanguage();
        }

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $tmp = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $languages = array_merge($languages, preg_replace('/^([a-z]{2}).+$/Ss', '$1', $tmp));
        }

        $languages[] = \XLite\Core\Config::getInstance()->General->defaultLanguage->code;

        // Process query
        $idx = 999999;
        $found = false;
        foreach (\XLite\Core\Database::getRepo('XLite\Model\Language')->findActiveLanguages() as $lng) {
            if (!$found) {
                $found = $lng->getCode();
            }

            $key = array_search($lng->getCode(), $languages);
            if (false !== $key && $key < $idx) {
                $idx = $key;
                $found = $lng->getCode();
            }
        }

        return $found ?: 'en';
    }

}

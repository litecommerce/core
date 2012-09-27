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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core;

/**
 * Current session
 *
 */
class Session extends \XLite\Base\Singleton
{
    /**
     * Public session id argument name
     */
    const ARGUMENT_NAME = 'xid';

    /**
     * Referer cookie name
     */
    const LC_REFERER_COOKIE_NAME = 'LCRefererCookie';

    /**
     * Session
     *
     * @var \XLite\Model\Session
     */
    protected $session;

    /**
     * Currently used form ID
     *
     * @var string
     */
    protected static $xliteFormId;

    /**
     * Language (cache)
     *
     * @var \XLite\Model\Language
     */
    protected $language;

    /**
     * Last form id
     *
     * @var string
     */
    protected $lastFormId;


    /**
     * Get session TTL (seconds)
     *
     * @return integer
     */
    public static function getTTL()
    {
        return 0;
    }

    /**
     * Getter
     *
     * @param string $name Session cell name
     *
     * @return mixed
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
     */
    public function set($name, $value)
    {
        $this->__set($name, $value);
    }

    /**
     * Restart session
     *
     * @return void
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
     * Get public session id argument name
     *
     * @return string
     */
    public function getName()
    {
        return self::ARGUMENT_NAME;
    }

    /**
     * Get public session id
     *
     * @return string
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
     * Create form id
     *
     * @return string Form id
     */
    public function createFormId()
    {
        if (!isset($this->lastFormId)) {

            $formId = new \XLite\Model\FormId;

            $formId->setSessionId($this->session->getId());

            \XLite\Core\Database::getEM()->persist($formId);

            $this->lastFormId = $formId->getFormId();
        }

        return $this->lastFormId;
    }

    /**
     * Get model
     *
     * @return \XLite\Model\Session
     */
    public function getModel()
    {
        return $this->session;
    }

    /**
     * Get language
     *
     * @return \XLite\Model\Language
     */
    public function getLanguage()
    {
        if (!isset($this->language)) {
            $this->language = \XLite\Core\Database::getRepo('XLite\Model\Language')
                ->findOneByCode($this->getCurrentLanguage());
        }

        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language Language code
     * @param string $zone     Admin/customer zone OPTIONAL
     *
     * @return void
     */
    public function setLanguage($language, $zone = null)
    {
        $code = $this->session->language;

        if (!isset($zone)) {
            $zone = \XLite::isAdminZone() ? 'admin' : 'customer';
        }

        if (!is_array($code)) {
            $code = array();
        }

        if (!isset($code[$zone]) || $code[$zone] !== $language) {
            $code[$zone] = $language;

            $this->session->language = $code;
            $this->language = null;
        }
    }

    /**
     * Update language in customer sessions
     *
     * @return void
     */
    public function updateSessionLanguage()
    {
        $list = array();

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\SessionCell')->findByName('language') as $cell) {
            $data = $cell->getValue() ?: array();

            if (isset($data['customer'])) {
                $data['customer'] = \XLite\Core\Config::getInstance()->General->default_language;
                $cell->setValue($data);

                $list[] = $cell;
            }
        }

        \XLite\Core\Database::getRepo('\XLite\Model\SessionCell')->updateInBatch($list);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function __construct()
    {
        $this->clearGarbage();

        if (!$this->restoreSession()) {
            $this->createSession();
        }

        $this->setCookie();
    }

    /**
     * Clear expired sessions and other obsolete data
     *
     * @return void
     */
    protected function clearGarbage()
    {
        \XLite\Core\Database::getRepo('XLite\Model\Session')->removeExpired();
    }

    /**
     * Restore session
     *
     * @return boolean
     */
    protected function restoreSession()
    {
        $this->session = null;

        list($sid, $source) = $this->detectPublicSessionId();

        if ($sid) {
            $this->session = \XLite\Core\Database::getRepo('XLite\Model\Session')->findOneBySid($sid);

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

        if (
            $sid
            && !\XLite\Core\Database::getRepo('XLite\Model\Session')->isPublicSessionIdValid($sid)
        ) {
            $sid = null;
        }

        return array($sid, $source);
    }

    /**
     * Create session
     *
     * @return void
     */
    protected function createSession()
    {
        $this->session = new \XLite\Model\Session();

        $this->session->updateExpiry();

        $this->session->setSid(\XLite\Core\Database::getRepo('XLite\Model\Session')->generatePublicSessionId());

        \XLite\Core\Database::getEM()->persist($this->session);

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Set cookie
     *
     * @return void
     */
    protected function setCookie()
    {
        if (
            !headers_sent()
            && 'cli' != PHP_SAPI
        ) {
            $arg = $this->getName();

            $httpDomain = $this->getCookieDomain();
            $httpsDomain = $this->getCookieDomain(true);
            $ttl = static::getTTL();

            setcookie(
                $arg,
                $this->session->getSid(),
                $ttl,
                $this->getCookiePath(),
                $httpDomain,
                false,
                true
            );

            if ($httpDomain != $httpsDomain) {
                setcookie(
                    $arg,
                    $this->session->getSid(),
                    $ttl,
                    $this->getCookiePath(true),
                    $httpsDomain,
                    false,
                    true
                );
            }

            $this->setLCRefererCookie();
        }
    }

    /**
     * Set referer cookie (this is stored when user register new profile)
     *
     * @return void
     */
    protected function setLCRefererCookie()
    {
        if (!isset($_COOKIE[self::LC_REFERER_COOKIE_NAME]) && isset($_SERVER['HTTP_REFERER'])) {

            $referer = parse_url($_SERVER['HTTP_REFERER']);

            if (isset($referer['host']) && $referer['host'] != $_SERVER['HTTP_HOST']) {
                setcookie(
                    self::LC_REFERER_COOKIE_NAME,
                    $_SERVER['HTTP_REFERER'],
                    $this->getLCRefererCookieTTL(),
                    $this->getCookiePath(),
                    $this->getCookieDomain(),
                    false,
                    true
                );
            }
        }
    }

    /**
     * Get parsed URL for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not OPTIONAL
     *
     * @return array
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
     * @param boolean $secure Secure protocol or not OPTIONAL
     *
     * @return string
     */
    protected function getCookieDomain($secure = false)
    {
        $url = $this->getCookieURL($secure);

        return false === strstr($url['host'], '.') ? false : $url['host'];
    }

    /**
     * Get URL path for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not OPTIONAL
     *
     * @return string
     */
    protected function getCookiePath($secure = false)
    {
        $url = $this->getCookieURL($secure);

        return $url['path'];
    }

    /**
     * Get referer cookie TTL (seconds)
     *
     * @return integer
     */
    protected function getLCRefererCookieTTL()
    {
        return time() + 3600 * 24 * 180; // TTL is 180 days
    }

    /**
     * Get current language
     *
     * @return string Language code
     */
    protected function getCurrentLanguage()
    {
        $code = $this->session->language;
        $zone = \XLite::isAdminZone() ? 'admin' : 'customer';

        if (!is_array($code)) {
            $code = array();
        }

        if (!empty($code[$zone])) {
            $language = \XLite\Core\Database::getRepo('XLite\Model\Language')->findOneByCode($code[$zone]);

            if (!isset($language) || $language::ENABLED != $language->getStatus()) {
                unset($code[$zone]);
            }
        }

        if (empty($code[$zone])) {
            $this->setLanguage($this->defineCurrentLanguage());
            $code = $this->session->language;
        }

        return $code[$zone];
    }

    /**
     * Define current language
     *
     * @return string Language code
     */
    protected function defineCurrentLanguage()
    {
        if (!\XLite::isAdminZone()) {
            $result = \Includes\Utils\ArrayManager::searchInObjectsArray(
                \XLite\Core\Database::getRepo('XLite\Model\Language')->findActiveLanguages(),
                'getCode',
                \XLite\Core\Config::getInstance()->General->default_language
            );
        }

        return isset($result) ? $result->getCode() : static::getDefaultLanguage();
    }
}

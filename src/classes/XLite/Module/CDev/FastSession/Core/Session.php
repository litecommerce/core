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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\FastSession\Core;

/**
 * Current session
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Session extends \XLite\Core\Session implements \XLite\Base\IDecorator
{
    const STORAGE_PREFIX = 'session';

    const SESSION_START_KEY = '__session_start__';

    /**
     * Storage 
     * 
     * @var   \XLite\Module\CDev\FastSession\Core\Storage
     */
    protected $storage;

    /**
     * Public session id characters list
     *
     * @var   array
     */
    protected $sessionIdChars = array(
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
        'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z',
    );

    /**
     * Form id characters list
     *
     * @var   array
     */
    protected $formIdChars = array(
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
        'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z',
    );

    /**
     * Get session TTL (seconds)
     *
     * @return integer
     */
    public static function getTTLLength()
    {
        $ttl = static::getTTL();
        $ttl = 0 < $ttl ? $ttl - time() : 0;

        return 0 >= $ttl ? null : $ttl;
    }

    /**
     * Restart session
     *
     * @return void
     */
    public function restart()
    {
        $this->lastFormId = null;

        $data = array();
        if ($this->getID()) {
            $data = $this->getStorage()->getArray();
            $this->getStorage()->remove();
        }

        $this->createSession();

        $this->setCookie();

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->__set($key, $value);
            }
        }
    }

    /**
     * Get public session id
     *
     * @return string
     */
    public function getID()
    {
        return $this->getStorage()->getID();
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
        $result = false;

        if ($sid) {
            $oldSid = $this->getID();
            $this->getStorage()->setID($sid);
            if ($this->getStorage()->isExists()) {
                $this->lastFormId = null;
                $result = true;

            } else {
                $this->getStorage()->setID($oldSid);
                
            }
        }

        return $result;
    }

    /**
     * Update language in customer sessions
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function updateSessionLanguage()
    {
    }

    /**
     * Create session
     *
     * @return void
     */
    protected function createSession()
    {
        $this->getStorage()->setID($this->generatePublicSessionId());
    }

    /**
     * Clear expired sessions and other obsolete data
     *
     * @return void
     */
    protected function clearGarbage()
    {
        if (0 == rand(0, 10)) {
            $this->getStorage()->clearGarbage();
        }
    }

    /**
     * Restore session
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function restoreSession()
    {
        $result = false;

        list($sid, $source) = $this->detectPublicSessionId();

        if ($sid) {
            $this->getStorage()->setID($sid);
            $result = $this->getStorage()->isExists();
        }

        return $result;
    }

    /**
     * Generate public session ID 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function generatePublicSessionId()
    {
        $iterationLimit = 10;
        $limit = count($this->sessionIdChars) - 1;

        do {
            $x = explode('.', uniqid('', true));
            mt_srand(microtime(true) + intval(hexdec($x[0])) + $x[1]);
            $sid = '';
            for ($i = 0; \XLite\Model\Repo\Session::PUBLIC_SESSION_ID_LENGTH > $i; $i++) {
                $sid .= $this->sessionIdChars[mt_rand(0, $limit)];
            }
            $iterationLimit--;

        } while (!is_null($this->__get(static::SESSION_START_KEY)) && 0 < $iterationLimit);

        if (0 == $iterationLimit) {
            // TODO - add throw exception
        }

        return $sid;
    }

    /**
     * Get storage 
     * 
     * @return \XLite\Module\CDev\FastSession\Core\Storage
     */
    protected function getStorage()
    {
        if (!isset($this->storage)) {
            $this->storage = new \XLite\Module\CDev\FastSession\Core\Storage(static::STORAGE_PREFIX, static::getTTL());
        }

        return $this->storage;
    }

    // {{{ Cell interface 

    /**
     * Getter
     *
     * @param string $name Session cell name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getID() ? $this->getStorage()->$name : null;
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
        if ($this->getID()) {
            $this->getStorage()->$name = $value;
        }
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
        return $this->getID() ? isset($this->getStorage()->$name) : false;
    }

    /**
     * Remove session cell
     *
     * @param string $name Session cell name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __unset($name)
    {
        if ($this->getID()) {
            unset($this->getStorage()->$name);
        }
    }

    // }}}

    // {{{ Form ID

    /**
     * Create form id
     *
     * @return string Form id
     */
    public function createFormId()
    {
        if (!isset($this->lastFormId)) {
            $formIds = $this->__get('formIds');
            if (!is_array($formIds)) {
                $formIds = array();
            }
            $this->lastFormId = $this->generateFormId($formIds);
            $formIds[$this->lastFormId] = time();
            $this->__set('formIds', $formIds);
        }

        return $this->lastFormId;
    }

    /**
     * Generate form id 
     *
     * @param array $formIds Form IDs list OPTIONAL
     * 
     * @return string
     */
    protected function generateFormId($formIds = null)
    {
        $formIds = $formIds ?: $this->__get('formIds');
        if (!is_array($formIds)) {
            $formIds = array();
        }

        $iterationLimit = 10;
        $limit = count($this->formIdChars) - 1;

        do {
            mt_srand(microtime(true) * 1000);
            $id = '';
            for ($i = 0; \XLite\Model\Repo\FormId::FORM_ID_LENGTH > $i; $i++) {
                $id .= $this->formIdChars[mt_rand(0, $limit)];
            }
            $iterationLimit--;

        } while (isset($formIds[$id]) && 0 < $iterationLimit);

        return $id;
    }

    // }}}

}

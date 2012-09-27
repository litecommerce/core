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

namespace XLite\Model;

/**
 * Session
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Session")
 * @Table  (name="sessions",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="sid", columns={"sid"})
 *      },
 *      indexes={
 *          @Index (name="expiry", columns={"expiry"})
 *      }
 * )
 * @HasLifecycleCallbacks
 */
class Session extends \XLite\Model\AEntity
{
    /**
     * Maximum TTL (1 year) 
     */
    const MAX_TTL = 31536000;

    /**
     * Session increment id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $id;

    /**
     * Public session id
     *
     * @var string
     *
     * @Column (type="fixedstring", length=32)
     */
    protected $sid;

    /**
     * Session expiration time
     *
     * @var integer
     *
     * @Column (type="uinteger")
     */
    protected $expiry;

    /**
     * Cells cache
     *
     * @var array
     */
    protected $cache;

    /**
     * Return instance of the session cell repository
     *
     * @return \XLite\Model\Repo\SessionCell
     */
    protected static function getSessionCellRepo()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\SessionCell');
    }

    /**
     * Set session id
     *
     * @param string $value Session id
     *
     * @return void
     */
    public function setSid($value)
    {
        $this->sid = $value;
    }

    /**
     * Update expiration time
     *
     * @return void
     */
    public function updateExpiry()
    {
        $ttl = \XLite\Core\Session::getTTL();
        $this->setExpiry(0 < $ttl ? $ttl : time() + self::MAX_TTL);
    }

    /**
     * Session cell getter
     *
     * @param string $name Cell name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $cell = $this->getCellByName($name);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * Session cell setter
     *
     * @param string $name  Cell name
     * @param mixed  $value Value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setCellValue($name, $value);
    }

    /**
     * Check - set session cell with specified name or not
     *
     * @param string $name Cell name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        return !is_null($this->getCellByName($name));
    }

    /**
     * Remove session cell
     *
     * @param string $name Cell name
     *
     * @return void
     */
    public function __unset($name)
    {
        $this->setCellValue($name, null);
    }

    /**
     * Get session cell by name
     *
     * @param string $name Cell name
     *
     * @return \XLite\Model\SessionCell|void
     */
    protected function getCellByName($name)
    {
        if (!isset($this->cache)) {
            $this->cache = array();

            foreach ((array) static::getSessionCellRepo()->findById($this->getId()) as $cell) {
                $this->cache[$cell->getName()] = $cell;
            }
        }

        return \Includes\Utils\ArrayManager::getIndex($this->cache, $name, true);
    }

    /**
     * Set session cell value
     *
     * @param string $name  Cell name
     * @param mixed  $value Value to set
     *
     * @return void
     */
    protected function setCellValue($name, $value)
    {
        // Check if cell exists (need to perform update or delete)
        $cell = $this->getCellByName($name);

        if (!$cell) {

            // Cell not found - create new
            if (isset($value)) {
                $this->cache[$name] = static::getSessionCellRepo()->insertCell($this->getId(), $name, $value);
            }

        } elseif (isset($value)) {

            // Only perform SQL query if cell value is changed
            if ($cell->getValue() !== $value) {
                static::getSessionCellRepo()->updateCell($cell, $value);
            }

        } else {

            // Set the "null" value to delete current cell
            static::getSessionCellRepo()->removeCell($cell);
            unset($this->cache[$name]);
        }
    }
}

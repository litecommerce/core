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

namespace XLite\Model;

/**
 * Session
 *
 * @see   ____class_see____
 * @since 1.0.0
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
     * Session increment id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $id;

    /**
     * Public session id
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="32")
     */
    protected $sid;

    /**
     * Session expiration time
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="uinteger")
     */
    protected $expiry;

    /**
     * Cells cache
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $cache = array();


    /**
     * Return instance of the session cell repository
     *
     * @return \XLite\Model\Repo\SessionCell
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setSid($value)
    {
        $this->sid = $value;
    }

    /**
     * Update expiration time
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function updateExpiry()
    {
        $this->setExpiry(\XLite\Core\Session::getTTL());
    }

    /**
     * Session cell getter
     *
     * @param string $name Cell name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCellByName($name)
    {
        if (!isset($this->cache[$name])) {

            $this->cache[$name] = static::getSessionCellRepo()->findOneBy(
                array('id' => $this->getId(), 'name' => $name)
            );
        }

        return $this->cache[$name];
    }

    /**
     * Invalidate cached entity
     *
     * @param string $name Cell name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function invalidateCellCache($name)
    {
        if (isset($this->cache[$name])) {
            $this->cache[$name]->detach();
            unset($this->cache[$name]);
        }
    }

    /**
     * Set session cell value
     *
     * @param string $name  Cell name
     * @param mixed  $value Value to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setCellValue($name, $value)
    {
        // Check if cell exists (need to perform update or delete)
        $cell = $this->getCellByName($name);
        if (!$cell) {

            // Cell not found - create new
            if (isset($value)) {
                static::getSessionCellRepo()->insertCell($this->getId(), $name, $value);
            }

        } elseif (isset($value)) {

            // Only perform SQL query if cell value is changed
            if ($cell->getValue() !== $value) {
                static::getSessionCellRepo()->updateCell($cell, $value);
                $this->invalidateCellCache($name);
            }

        } else {

            // Set the "null" value to delete current cell
            static::getSessionCellRepo()->removeCell($cell);
            $this->invalidateCellCache($name);
        }
    }
}

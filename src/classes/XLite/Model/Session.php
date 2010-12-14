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

/**
 * Session
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * Session TTL (in seconds)
     */
    const TTL = 7200;


    /**
     * Session increment id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $id;

    /**
     * Public session id
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="fixedstring", length="32")
     */
    protected $sid;

    /**
     * Session expiration time
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="uinteger")
     */
    protected $expiry;

    /**
     * Cells cache 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cache = array();

    /**
     * Set session id 
     * 
     * @param string $value Session id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSid($value)
    {
        $this->sid = $value;
    }

    /**
     * Return instance of the session cell repository
     * 
     * @return \XLite\Model\Repo\SessionCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getSessionCellRepo()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\SessionCell');
    }


    /**
     * Get session cell by name
     *
     * @param string $name Cell name
     *
     * @return \XLite\Model\SessionCell|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

    /**
     * Update expiration time
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function updateExpiry()
    {
        $this->setExpiry(time() + self::TTL);
    }

    /**
     * Session cell getter
     * 
     * @param string $name Cell name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __unset($name)
    {
        $this->setCellValue($name, null);
    }
}

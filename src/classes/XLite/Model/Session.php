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
 * @Table  (name="sessions")
 * @HasLifecycleCallbacks
 */
class Session extends \XLite\Model\AEntity
{
    /**
     * Session TTL (in seconds)
     */
    const TTL = 7200;


    /**
     * Session cell repository (cache)
     *
     * @var    \XLite\Model\Repo\SessionCell
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $sessionCellRepository;


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
     * @Column (type="string", length="32")
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
     * @Column (type="integer")
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
     * Return instance of the session cell repository
     * 
     * @return \XLite\Model\Repo\SessionCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getSessionCellRepo()
    {
        if (!isset(static::$sessionCellRepository)) {
            static::$sessionCellRepository = \XLite\Core\Database::getRepo('XLite\Model\SessionCell');
        }

        return static::$sessionCellRepository;
    }


    /**
     * Get session cell by name
     *
     * @param string $name cell name
     *
     * @return \XLite\Model\SessionCell|null
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
     * @param string $name cell name
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
        }

        unset($this->cache[$name]);
    }

    /**
     * Set session cell value
     *
     * @param string $name  cell name
     * @param mixed  $value value to set
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setCellValue($name, $value)
    {
        // Check if cell exists (need to perform update or delete)
        if ($cell = $this->getCellByName($name)) {

            // Value is not null - update
            if (isset($value)) {

                // Only perform SQL query if cell value is changed
                if ($cell->getValue() !== $value) {
                    static::getSessionCellRepo()->updateCell($cell, $value);
                    $this->invalidateCellCache($name);
                }

            } else {

                // Set the "null" value to delete current cell
                static::getSessionCellRepo()->deleteCell($cell);
                $this->invalidateCellCache($name);
            }

        } else {

            // Cell not found - create new
            static::getSessionCellRepo()->insertCell($this->getId(), $name, $value);
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
        return ($cell = $this->getCellByName($name)) ? $cell->getValue() : null;
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

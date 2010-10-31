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
     * Session cell repository (cache)
     * 
     * @var    \XLite\Model\Repo\SessionCell
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $sessionCellRepository;

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
     * Get session cell by name 
     * 
     * @param string $name Name
     *  
     * @return \XLite\Model\SessionCell or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCellByName($name)
    {
        if (!isset($this->sessionCellRepository)) {
            $this->sessionCellRepository = \XLite\Core\Database::getRepo('XLite\Model\SessionCell');
        }

        return $this->sessionCellRepository->findOneByIdAndName($this->getId(), $name);
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
        if ($cell) {
            $cell->detach();
        }

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
        $cell = $this->getCellByName($name);

        if ($cell && !isset($value)) {
            \XLite\Core\Database::getEM()->remove($cell);

        } else {

            if (!$cell) {
                $cell = new \XLite\Model\SessionCell;
                $cell->setId($this->getId());
                $cell->setName($name);
                \XLite\Core\Database::getEM()->persist($cell);
            }

            $cell->setValue($value);

        }

        \XLite\Core\Database::getEM()->flush();

        $cell->detach();
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
        $cell = $this->getCellByName($name);
        if ($cell) {
            $cell->detach();
        }

        return isset($cell);
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
        $cell = $this->getCellByName($name);

        if ($cell) {
            \XLite\Core\Database::getEM()->remove($cell);
            \XLite\Core\Database::getEM()->flush();
        }
    }
}

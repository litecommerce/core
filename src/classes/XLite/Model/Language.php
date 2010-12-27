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
 * Language
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity
 * @Table (name="languages",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="code3", columns={"code3"}),
 *          @UniqueConstraint (name="code2", columns={"code"})
 *      },
 *      indexes={
 *          @Index (name="status", columns={"status"})
 *      }
 * )
 */
class Language extends \XLite\Model\Base\I18n
{
    /**
     * Language statuses
     */
    const INACTIVE = 0;
    const ADDED = 1;
    const ENABLED = 2;


    /**
     * Unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", unique=true)
     */
    protected $lng_id;

    /**
     * Language alpha-2 code (ISO 639-2)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="fixedstring", length="2", unique=true)
     */
    protected $code;

    /**
     * Language alpha-3 code (ISO 639-3)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="fixedstring", length="3", unique=true)
     */
    protected $code3 = '';

    /**
     * Right-to-left flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean")
     */
    protected $r2l = false;

    /**
     * Status
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $status = self::INACTIVE;

    /**
     * Get added status
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAdded()
    {
        return 0 < $this->status;
    }

    /**
     * Set added status
     * 
     * @param boolean $status Added status
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setAdded($status)
    {
        if (
            $status != $this->getAdded()
            && (!$status || !$this->getEnabled())
        ) {
            $this->status = $status ? self::ADDED : self::INACTIVE;
        }
    }

    /**
     * Get enabled status
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEnabled()
    {
        return self::ENABLED == $this->status;
    }

    /**
     * Set enabled status
     * 
     * @param boolean $status Enabled status
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setEnabled($status)
    {
        if ($status != $this->getEnabled()) {
            $this->status = $status ? self::ENABLED : self::ADDED;
        }
    }

    /**
     * Get flag URL 
     * 
     * @return string|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFlagURL()
    {
        $path = \XLite\Model\Layout::getInstance()->getSkinURL('images/flags/' . $this->getCode() . '.png');

        if (!file_exists(LC_ROOT_DIR . $path)) {
            $path = \XLite\Model\Layout::getInstance()->getSkinURL('images/flags/__.png');
        }

        return file_exists(LC_ROOT_DIR . $path)
            ? \XLite::getInstance()->getShopUrl($path)
            : null;
    }
}


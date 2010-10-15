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
 * @Entity (repositoryClass="\XLite\Model\Repo\SessionCell")
 * @Table  (name="session_cells")
 */
class SessionCell extends \XLite\Model\AEntity
{
    /**
     * Cell unique id 
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
    protected $cell_id;

    /**
     * Session id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $id;

    /**
     * Name 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $name;

    /**
     * Value 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="text")
     */
    protected $value = '';

    /**
     * Value type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="16")
     */
    protected $type = '';

    /**
     * Get value 
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getValue()
    {
        switch ($this->getType()) {
            case 'boolean':
                $result = (bool)$this->value;
                break;

            case 'integer':
                $result = intval($this->value);
                break;

            case 'double':
                $result = doubleval($this->value);
                break;

            case 'string':
                $result = $this->value;
                break;

            case 'array':
            case 'object':
                $result = unserialize($this->value);
                break;

            default:
                $result = null;
        }

        return $result;
    }

    /**
     * Set value 
     * 
     * @param mixed $value Value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setValue($value)
    {
        $type = gettype($value);

        switch ($type) {
            case 'boolean':
                $result = $value ? 1 : 0;
                break;

            case 'integer':
            case 'double':
            case 'string':
                $result = $value;
                break;

            case 'array':
            case 'object':
                $result = serialize($value);
                break;

            default:
                $result = null;
                $type = '';
        }

        $this->value = $result;
        $this->type = $type;
    }

    /**
     * Dump setter for 'type' property 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setType()
    {
    }

}


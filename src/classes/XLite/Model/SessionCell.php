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
 * @Entity (repositoryClass="\XLite\Model\Repo\SessionCell")
 * @Table  (name="session_cells",
 *      indexes={
 *          @UniqueConstraint (name="iname", columns={"id", "name"}),
 *          @Index (name="id", columns={"id"})
 *      }
 * )
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
     * @Column         (type="integer", nullable=false)
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
     * @Column (type="integer", nullable=false)
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
     * @Column (type="string", length="255", nullable=false)
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
    protected $type;

    /**
     * Automatically get variable type
     *
     * @param mixed $value Variable to check
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getTypeByValue($value)
    {
        $type = gettype($value);

        return in_array($type, array('NULL', 'unknown type')) ? null : $type;
    }

    /**
     * Common getter
     *
     * NOTE: this function is designed as "static public" to use in repository
     * NOTE: customize this method instead of the "getValue()" one
     * 
     * @param mixed  $value Value to prepare
     * @param string $type  Field type OPTIONAL
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function prepareValueForGet($value, $type = null)
    {
        $type = $type ?: static::getTypeByValue($value);

        switch ($type) {

            case 'boolean':
                $value = (bool) $value;
                break;

            case 'integer':
                $value = intval($value);
                break;

            case 'double':
                $value = doubleval($value);
                break;

            case 'string':
                $value = $value;
                break;

            case 'array':
            case 'object':
                $value = unserialize($value);
                break;

            default:
                $value = null;
        }

        return $value;
    }

    /**
     * Common setter
     *
     * NOTE: this function is designed as "static public" to use in repository
     * NOTE: customize this method instead of the "getValue()" one
     *
     * @param mixed  $value Value to prepare
     * @param string $type  Field type OPTIONAL
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function prepareValueForSet($value, $type = null)
    {
        $type = $type ?: static::getTypeByValue($value);

        switch ($type) {

            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
                break;

            case 'array':
            case 'object':
                $value = serialize($value);
                break;

            default:
                $value = null;
        }

        return $value;
    }


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
        return static::prepareValueForGet($this->value, $this->getType());
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
        $this->type  = static::getTypeByValue($value);
        $this->value = static::prepareValueForSet($value, $this->type);
    }

    /**
     * Disallowed method
     * 
     * @param string $type Type to set
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setType($type)
    {
        throw new \Exception('It\'s not possible to change value type for the existsing cell.');
    }
}

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

namespace XLite\Model\WidgetParam;

/**
 * Abstract Object id widget parameter
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ObjectId extends \XLite\Model\WidgetParam\Int
{
    /**
     * Return object class name 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getClassName();


    /**
     * getIdValidCondition 
     * 
     * @param mixed $value value to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getIdValidCondition($value)
    {
        return array(
            self::ATTR_CONDITION => 0 >= $value,
            self::ATTR_MESSAGE   => ' is a non-positive number',
        );
    }

    /**
     * getObjectExistsCondition 
     * 
     * @param mixed $value value to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getObjectExistsCondition($value)
    {
        return array(
            self::ATTR_CONDITION => !$this->getObject($value)->isExists(),
            self::ATTR_MESSAGE   => ' record with such ID is not found',
        );
    }

    /**
     * Return object ID
     * 
     * @param int $id object ID
     *  
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getId($id = null)
    {
        return isset($id) ? $id : $this->value;
    }

    /**
     * Return list of conditions to check
     *
     * @param mixed $value value to validate
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getValidaionSchema($value)
    {
        $schema = parent::getValidaionSchema($value);
        $schema[] = $this->getIdValidCondition($value);
        $schema[] = $this->getObjectExistsCondition($value);

        return $schema;
    }


    /**
     * Return object with passed/predefined ID
     *
     * @param int $id object ID
     *
     * @return \XLite\Model\AEntity
     * @access public
     * @since  3.0.0
     */
    public function getObject($id = null)
    {
        return \XLite\Core\Database::getRepo($this->getClassName())->find($this->getId($id));
    }
}

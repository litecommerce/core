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

namespace XLite\Model\Repo;

/**
 * Session cell repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SessionCell extends \XLite\Model\Repo\ARepo
{
    /**
     * Return data to indentify cell in SQL queries
     *
     * @param \XLite\Model\SessionCell $cell Cell to use
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCellIdentifier(\XLite\Model\SessionCell $cell)
    {
        return array(
            'cell_id' => $cell->getCellId(),
        );
    }

    /**
     * Prepare data for cell insert 
     * 
     * @param integer $id    Session ID
     * @param string  $name  Cell name
     * @param mixed   $value Data to store
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareDataForNewCell($id, $name, $value)
    {
        return array(
            'id'    => $id,
            'name'  => $name,
        ) + $this->prepareDataForExistingCell($value);
    }

    /**
     * Prepare data for cell update
     *
     * @param mixed                    $value Data to store
     * @param \XLite\Model\SessionCell $cell  Cell to update
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareDataForExistingCell($value, \XLite\Model\SessionCell $cell = null)
    {
        return array(
            'value' => \XLite\Model\SessionCell::prepareValueForSet($value),
            'type'  => \XLite\Model\SessionCell::getTypeByValue($value),
        );
    }

    /**
     * Insert new cell
     *
     * @param integer $id    Session ID
     * @param string  $name  Cell name
     * @param mixed   $value Data to store
     *
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function insertCell($id, $name, $value)
    {
        $this->getEntityManager()->getConnection()->insert(
            $this->_class->getTableName(),
            $this->prepareDataForNewCell($id, $name, $value)
        );

        return $this->getEntityManager()->getConnection()->lastInsertId();
    }

    /**
     * Update existsing cell
     *
     * @param \XLite\Model\SessionCell $cell  Cell to update
     * @param mixed                    $value Value to set
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function updateCell(\XLite\Model\SessionCell $cell, $value)
    {
        $this->getEntityManager()->getConnection()->update(
            $this->_class->getTableName(),
            $this->prepareDataForExistingCell($value, $cell),
            $this->getCellIdentifier($cell)
        );
    }

    /**
     * Remove cell
     *
     * @param \XLite\Model\SessionCell $cell Cell to delete
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeCell(\XLite\Model\SessionCell $cell)
    {
        $this->getEntityManager()->getConnection()->delete(
            $this->_class->getTableName(),
            $this->getCellIdentifier($cell)
        );
    }
}

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

namespace XLite\Model\Repo;

/**
 * Session cell repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class SessionCell extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $type = self::TYPE_SERVICE;

    /**
     * Insert new cell
     *
     * @param integer $id    Session ID
     * @param string  $name  Cell name
     * @param mixed   $value Data to store
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function insertCell($id, $name, $value)
    {
        return $this->insert($this->prepareDataForNewCell($id, $name, $value));
    }

    /**
     * Update existsing cell
     *
     * @param \XLite\Model\SessionCell $cell  Cell to update
     * @param mixed                    $value Value to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function updateCell(\XLite\Model\SessionCell $cell, $value)
    {
        $cell->setValue($value);

        return $this->update($cell);
    }

    /**
     * Remove cell
     *
     * @param \XLite\Model\SessionCell $cell Cell to delete
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function removeCell(\XLite\Model\SessionCell $cell)
    {
        return $this->delete($cell);
    }

    /**
     * Process DB schema
     *
     * @param array  $schema Schema
     * @param string $type   Schema type
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processSchema(array $schema, $type)
    {
        $schema = parent::processSchema($schema, $type);

        if (\XLite\Core\Database::SCHEMA_CREATE == $type) {
            $schema[] = 'ALTER TABLE `' . $this->getClassMetadata()->getTableName() . '`'
                . ' ADD CONSTRAINT `session_cell_to_session` FOREIGN KEY `id` (`id`)'
                . ' REFERENCES `' . $this->_em->getClassMetadata('XLite\Model\Session')->getTableName() . '` (`id`)'
                . ' ON DELETE CASCADE ON UPDATE CASCADE';

        } elseif (\XLite\Core\Database::SCHEMA_UPDATE == $type) {
            $schema = preg_grep('/DROP FOREIGN KEY `?session_cell_to_session`?/Ss', $schema, PREG_GREP_INVERT);
        }

        return $schema;
    }

    /**
     * Prepare data for cell insert
     *
     * @param integer $id    Session ID
     * @param string  $name  Cell name
     * @param mixed   $value Data to store
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \XLite\Model\SessionCell $cell  Cell to update OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareDataForExistingCell($value, \XLite\Model\SessionCell $cell = null)
    {
        return array(
            'value' => \XLite\Model\SessionCell::prepareValueForSet($value),
            'type'  => \XLite\Model\SessionCell::getTypeByValue($value),
        );
    }
}

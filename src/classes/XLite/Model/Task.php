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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model;

/**
 * Task
 *
 *
 * @Entity
 * @Table (name="tasks")
 */
class Task extends \XLite\Model\AEntity
{
    /**
     * Unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Owner class
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $owner;

    /**
     * Trigger time
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $triggerTime = 0;

    /**
     * Task abstract data
     *
     * @var array
     *
     * @Column (type="array")
     */
    protected $data = array();

    /**
     * Owner instance
     *
     * @var \XLite\Core\Task\ATask
     */
    protected $ownerInstance;

    /**
     * Get owner instance
     *
     * @return \XLite\Core\Task\ATask
     */
    public function getOwnerInstance()
    {
        if (!isset($this->ownerInstance)) {
            $class = $this->getOwner();
            $this->ownerInstance = new $class($this);

            if (!($this->ownerInstance instanceof \XLite\Core\Task\ATask)) {
                $this->ownerInstance = false;
            }
        }

        return $this->ownerInstance;
    }
}

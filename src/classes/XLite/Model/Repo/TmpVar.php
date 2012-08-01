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

namespace XLite\Model\Repo;

/**
 * Temporary variables repository
 * 
 */
class TmpVar extends \XLite\Model\Repo\ARepo
{
    /**
     * Event task state prefix
     */
    const EVENT_TASK_STATE_PREFIX = 'eventTaskState.';

    /**
     * Set variable 
     * 
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *  
     * @return void
     */
    public function setVar($name, $value)
    {
        $entity = $this->findOneBy(array('name' => $name));

		if (isset($value)) {
	        if (!$entity) {
		        $entity = new \XLite\Model\TmpVar;
			    $entity->setName($name);
				\XLite\Core\Database::getEM()->persist($entity);
	        }

		    if (!is_scalar($value)) {
			    $value = serialize($value);
	        }

		    $entity->setValue($value);
			\XLite\Core\Database::getEM()->flush();

		} elseif ($entity) {
			\XLite\Core\Database::getEM()->remove($entity);
			\XLite\Core\Database::getEM()->flush();
		}
    }

    /**
     * Get variable 
     * 
     * @param string $name Variable name
     *  
     * @return mixed
     */
    public function getVar($name)
    {
        $entity = $this->findOneBy(array('name' => $name));

        $value = $entity ? $entity->getValue() : null;

        if (!empty($value)) {
            $tmp = @unserialize($value);
            if (false !== $tmp) {
                $value = $tmp;
            }
        }

        return $value;
    }

    // {{{ Event tasks-based temporary variable operations

    /**
     * Initialize event task state
     *
     * @param string $name Event task name
     *
     * @return array
     */
    public function initializeEventState($name)
    {
        $this->setEventState(
            $name,
            array(
                'position' => 0,
                'length'   => 0,
                'state'    => \XLite\Core\EventTask::STATE_STANDBY,
            )
        );
    }

    /**
     * Get event task state 
     * 
     * @param string $name Event task name
     *  
     * @return array
     */
    public function getEventState($name)
    {
        return $this->getVar(static::EVENT_TASK_STATE_PREFIX . $name);
    }

    /**
     * Set event state 
     * 
     * @param string $name Event task name
     * @param array  $rec  Event task state
     *  
     * @return void
     */
    public function setEventState($name, array $rec)
    {
        $this->setVar(static::EVENT_TASK_STATE_PREFIX . $name, $rec);
    }

    /**
     * Set event state
     *
     * @param string $name Event task name
     *
     * @return void
     */
    public function removeEventState($name)
    {
        $this->setVar(static::EVENT_TASK_STATE_PREFIX . $name, null);
    }

    /**
     * Check event state - finished or not
     *
     * @param string $name Event task name
     *
     * @return boolean
     */
    public function isFinishedEventState($name)
    {
        $record = $this->getEventState($name);

        return $record
            && ($record['state'] == \XLite\Core\EventTask::STATE_FINISHED || $record['state'] == \XLite\Core\EventTask::STATE_ABORTED);
    }

    /**
     * Check event state - finished or not
     *
     * @param string $name Event task name
     *
     * @return boolean
     */
    public function getEventStatePercent($name)
    {
        $record = $this->getEventState($name);

        $percent = 0;

        if ($record) {
            if ($this->isFinishedEventState($name)) {
                $percent = 100;

            } elseif (0 < $record['length']) {
                $percent = min(100, round($record['position'] / $record['length'] * 100));
            }
        }

        return $percent;
    }


    // }}}
}


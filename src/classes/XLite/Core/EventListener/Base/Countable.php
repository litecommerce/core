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

namespace XLite\Core\EventListener\Base;

/**
 * Abstract countable task
 * 
 */
abstract class Countable extends \XLite\Core\EventListener\AEventListener
{
    /**
     * Event record 
     * 
     * @var array
     */
    protected $record;

    /**
     * Get event name 
     * 
     * @return string
     */
    abstract protected function getEventName();

    /**
     * Get length 
     * 
     * @return integer
     */
    abstract protected function getLength();

    /**
     * Get items 
     * 
     * @return array
     */
    abstract protected function getItems();

    /**
     * Process item 
     * 
     * @param mixed $item Item
     *  
     * @return boolean
     */
    abstract protected function processItem($item);

    /**
     * Handle event (internal, after checking)
     *
     * @param string $name      Event name
     * @param array  $arguments Event arguments OPTIONAL
     *
     * @return boolean
     */
    public function handleEvent($name, array $arguments)
    {
        parent::handleEvent($name, $arguments);

        $this->errors = array();

        $result = false;

        $this->initializeStep();

        if ($this->isStepValid()) {

            $this->startStep();
            $this->runCurrentStep();

            if ($this->record['length'] <= $this->record['position']) {
                $this->finishTask();

            } else {
                $this->finishStep();
            }

            $result = true;
        }

        return $result;
    }

    /**
     * Initialize step 
     * 
     * @return void
     */
    protected function initializeStep()
    {
        $this->record = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState($this->getEventName());
        $this->record['state'] = \XLite\Core\EventTask::STATE_IN_PROGRESS;
        \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->setEventState($this->getEventName(), $this->record);
    }

    /**
     * Check step valid state
     * 
     * @return boolean
     */
    protected function isStepValid()
    {
        return !empty($this->record);
    }

    /**
     * Start step 
     * 
     * @return void
     */
    protected function startStep()
    {
        if (0 == $this->record['length']) {
            $this->record['length'] = $this->getLength();
        }
    }

    /**
     * Run current step 
     * 
     * @return void
     */
    protected function runCurrentStep()
    {
        $repo = \XLite\Core\Database::getRepo('XLite\Model\TmpVar');
        foreach ($this->getItems() as $item) {
            if ($this->processItem($item)) {
                $this->record['position']++;
                $repo->setEventState($this->getEventName(), $this->record);
            }
        }
    }

    /**
     * Finish step 
     * 
     * @return void
     */
    protected function finishStep()
    {
        $this->record['state'] = \XLite\Core\EventTask::STATE_STANDBY;
        \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->setEventState($this->getEventName(), $this->record);

        $event = $this->getEventName();
        \XLite\Core\EventTask::$event($this->arguments);
    }

    /**
     * Finish task 
     * 
     * @return void
     */
    protected function finishTask()
    {
        $this->record['state'] = \XLite\Core\EventTask::STATE_FINISHED;
        \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->setEventState($this->getEventName(), $this->record);
    }    
}


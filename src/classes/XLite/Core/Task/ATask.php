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

namespace XLite\Core\Task;

/**
 * Abstract task
 *
 */
abstract class ATask extends \XLite\Base
{
    /**
     * Model
     *
     * @var \XLite\Model\Task
     */
    protected $model;

    /**
     * Last step flag
     *
     * @var boolean
     */
    protected $lastStep = false;

    /**
     * Result operation message
     *
     * @var string
     */
    protected $message = 'done';

    /**
     * Get title
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * Run step
     *
     * @return void
     */
    abstract protected function runStep();

    /**
     * Constructor
     *
     * @param \XLite\Model\Task $model Model
     *
     * @return void
     */
    public function __construct(\XLite\Model\Task $model)
    {
        $this->model = $model;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Check - task ready or not
     *
     * @return boolean
     */
    public function isReady()
    {
        return true;
    }

    /**
     * Run task
     *
     * @return boolean
     */
    public function run()
    {
        $result = null;

        if ($this->isValid()) {

            $this->prepareStep();

            $this->runStep();

            if ($this->isLastStep()) {
                $this->finalizeTask();

            } else {
                $this->finalizeStep();
            }

        } elseif (!$this->message) {
            $this->message = 'invalid';
        }
    }

    /**
     * Prepare step
     *
     * @return void
     */
    protected function prepareStep()
    {
    }

    /**
     * Check - current step is last or not
     * 
     * @return boolean
     */
    protected function isLastStep()
    {
        return $this->lastStep;
    }

    /**
     * Finalize task (last step)
     *
     * @return void
     */
    protected function finalizeTask()
    {
        $this->close();
    }

    /**
     * Finalize step
     *
     * @return void
     */
    protected function finalizeStep()
    {
    }

    /**
     * Check availability
     *
     * @return boolean
     */
    protected function isValid()
    {
        return true;
    }

    /**
     * Close task
     *
     * @return void
     */
    protected function close()
    {
        \XLite\Core\Database::getEM()->remove($this->model);
    }
}

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

namespace XLite\View;

/**
 * Event task progress bar
 * 
 * @see   ____class_see____
 * @since 1.0.22
 */
class EventTaskProgress extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */
    const PARAM_EVENT             = 'event';
    const PARAM_TITLE             = 'title';
    const PARAM_BLOCKING_NOTE     = 'blockingNote';
    const PARAM_NON_BLOCKING_NOTE = 'nonBlockingNote';

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'event_task_progress/controller.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'event_task_progress/style.css';

        return $list;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_EVENT             => new \XLite\Model\WidgetParam\String('Event name', null),
            static::PARAM_TITLE             => new \XLite\Model\WidgetParam\String('Progress bar title', null),
            static::PARAM_BLOCKING_NOTE     => new \XLite\Model\WidgetParam\String('Blocking note', null),
            static::PARAM_NON_BLOCKING_NOTE => new \XLite\Model\WidgetParam\String('Non-blocking note', null),
        );
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {   
        return 'event_task_progress/body.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getTmpVar();
    }

    /**
     * Get temporary variable data
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getTmpVar()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState($this->getParam(static::PARAM_EVENT));
    }

    // {{{ Content helpers

    /**
     * Get event title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getEventTitle()
    {
        return $this->getParam(static::PARAM_TITLE);
    }

    /**
     * Get event name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getEvent()
    {
        return $this->getParam(static::PARAM_EVENT);
    }

    /**
     * Get percent
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getPercent()
    {
        $rec = $this->getTmpVar();

        return 0 < $rec['position'] ? min(100, round($rec['position'] / $rec['length'] * 100)) : 0;
    }

    /**
     * Check - current event driver is blocking or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function isBlockingDriver()
    {
        return \XLite\Core\EventTask::getInstance()->getDriver()->isBlocking();
    }

    /**
     * Get blocking note 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getBlockingNote()
    {
        return $this->getParam(static::PARAM_BLOCKING_NOTE);
    }

    /**
     * Get non-blocking note
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getNonBlockingNote()
    {
        return $this->getParam(static::PARAM_NON_BLOCKING_NOTE);
    }

    // }}}

}


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
 * @since     1.0.22
 */

namespace XLite\Controller\Admin;

/**
 * Event task controller
 * 
 * @see   ____class_see____
 * @since 1.0.22
 */
class EventTask extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAccess()
    {
        return parent::checkAccess() && $this->isAJAX();
    }

    /**
     * Check ACL permissions
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function checkACL()
    {
        return true;
    }

    /**
     * Run task
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function doActionRun()
    {
        $event = \XLite\Core\Request::getInstance()->event;
        $result = false;
        $errors = array();

        $task = \XLite\Core\Database::getRepo('XLite\Model\EventTask')->findOneBy(array('name' => $event));
        if ($task) {
            if (\XLite\Core\EventListener::getInstance()->handle($task->getName(), $task->getArguments())) {
                \XLite\Core\Database::getEM()->remove($task);
                $result = true;
            }
            $errors = \XLite\Core\EventListener::getInstance()->getErrors();

        } else {
            \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->removeEventState($event);
        }

        \XLite\Core\Database::getEM()->flush();

        $state = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState($event);

        $this->setPureAction(true);
        if ($result && $state) {
            \XLite\Core\Event::eventTaskRun(
                array(
                    'percent' => \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventStatePercent($event),
                    'error'   => !empty($errors)
                )
            );

        } else {
            $result = false;
        }

        if ($errors) {
            foreach ($errors as $message) {
                \Xlite\Core\TopMessage::addError($message);
            }
            $result = false;
        }

        $this->valid = $result;
    }

    /**
     * Run task
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function doActionTouch()
    {
        $event = \XLite\Core\Request::getInstance()->event;
        $state = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->getEventState($event);

        $this->setPureAction(true);
        
        $data = array(
            'percent' => $state && 0 < $state['position'] ? min(100, round($state['position'] / $state['length'] * 100)) : 0,
            'error'   => false,
        );

        print json_encode($data);
    }

    /**
     * Process request
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processRequest()
    {
    }
}

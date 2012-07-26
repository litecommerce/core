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

namespace XLite\Core;

/**
 * Event listener (common) 
 * 
 */
class EventListener extends \XLite\Base\Singleton
{
    /**
     * Errors 
     * 
     * @var array
     */
    protected $errors = array();

    /**
     * Handle event
     * 
     * @param string $name      Event name
     * @param array  $arguments Event arguments OPTIONAL
     *  
     * @return boolean
     */
    public function handle($name, array $arguments = array())
    {
        $result = false;
        $this->errors = array();

        $list = $this->getListeners();

        if (isset($list[$name])) {
            $list = is_array($list[$name]) ? $list[$name] : array($list[$name]);
            foreach ($list as $class) {
                if ($class::handle($name, $arguments)) {
                    $result = true;
                }
                if ($class::getInstance()->getErrors()) {
                    $this->errors = $class::getInstance()->getErrors();
                }

            }
        }

        return $result;
    }

    /**
     * Get errors 
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get events 
     * 
     * @return array
     */
    public function getEvents()
    {
        return array_keys($this->getListeners());
    }

    /**
     * Get listeners 
     * 
     * @return array
     */
    protected function getListeners()
    {
        return array(
            'probe' => array('\XLite\Core\EventListener\Probe'),
        );
    }
}

